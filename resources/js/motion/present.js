import { animate } from 'motion';
import { springOptions } from './springs';

/**
 * Nilai tampil, bukan nilai target.
 *
 * Prinsip terpenting dari seluruh berkas ini: animasi baru selalu berangkat
 * dari posisi yang sedang terlihat di layar, bukan dari nilai logis yang
 * "seharusnya". Kalau berangkat dari nilai target, elemen yang ditangkap di
 * tengah terbang akan melompat dulu sebelum bergerak.
 *
 * Caranya: setiap nilai yang pernah kita gerakkan disimpan di elemen, dan
 * kitalah yang menulisnya ke DOM tiap frame. Jadi "nilai tampil" bukan
 * tebakan — ia memang angka yang barusan kita tulis. Untuk elemen yang belum
 * pernah kita sentuh, nilainya dibaca dari transform terkomputasi.
 */

const PROPS = ['x', 'y', 'scale', 'scaleX', 'scaleY', 'rotate', 'opacity', 'blur'];

const DEFAULTS = {
    x: 0,
    y: 0,
    scale: 1,
    scaleX: 1,
    scaleY: 1,
    rotate: 0,
    opacity: 1,
    blur: 0,
};

function readFromDom(el) {
    const computed = getComputedStyle(el);
    const values = { ...DEFAULTS };

    values.opacity = parseFloat(computed.opacity);

    if (Number.isNaN(values.opacity)) {
        values.opacity = 1;
    }

    const transform = computed.transform;

    if (transform && transform !== 'none' && typeof DOMMatrixReadOnly !== 'undefined') {
        try {
            const matrix = new DOMMatrixReadOnly(transform);
            values.x = matrix.m41;
            values.y = matrix.m42;
            values.scaleX = Math.hypot(matrix.m11, matrix.m12) || 1;
            values.scaleY = Math.hypot(matrix.m21, matrix.m22) || 1;
            values.scale = values.scaleX;
        } catch {
            // Transform yang tidak bisa diurai bukan alasan untuk gagal;
            // nilai bawaan sudah benar untuk elemen yang belum digerakkan.
        }
    }

    const filter = computed.filter;
    const blurMatch = filter && filter !== 'none' ? filter.match(/blur\(([\d.]+)px\)/) : null;

    if (blurMatch) {
        values.blur = parseFloat(blurMatch[1]);
    }

    return values;
}

export function state(el) {
    if (! el.__mo) {
        el.__mo = { values: readFromDom(el), anims: {}, running: 0 };
    }

    return el.__mo;
}

/**
 * Nilai yang sedang terlihat. Dipakai directive x-spring dan lapisan gesture.
 */
export function presentation(el) {
    return { ...state(el).values };
}

function compose(values) {
    const parts = [];

    if (values.x !== 0 || values.y !== 0) {
        parts.push(`translate3d(${values.x}px, ${values.y}px, 0)`);
    }

    if (values.rotate !== 0) {
        parts.push(`rotate(${values.rotate}deg)`);
    }

    if (values.scaleX !== values.scaleY) {
        parts.push(`scale(${values.scaleX}, ${values.scaleY})`);
    } else if (values.scaleX !== 1) {
        parts.push(`scale(${values.scaleX})`);
    }

    return parts.length > 0 ? parts.join(' ') : '';
}

export function write(el) {
    const values = state(el).values;
    const transform = compose(values);

    // Hanya transform, opacity, dan filter — tiga properti yang bisa
    // digerakkan compositor tanpa memicu layout atau paint ulang.
    el.style.transform = transform;
    el.style.opacity = values.opacity === 1 ? '' : String(values.opacity);
    el.style.filter = values.blur > 0.01 ? `blur(${values.blur}px)` : '';
}

function normalise(props) {
    const next = { ...props };

    // `scale` adalah jalan pintas untuk kedua sumbu.
    if (next.scale !== undefined) {
        next.scaleX = next.scale;
        next.scaleY = next.scale;
        delete next.scale;
    }

    return next;
}

/**
 * Memasang nilai seketika tanpa animasi. Dipakai selama jari masih menempel:
 * di situ posisi ditentukan jari, bukan spring.
 */
export function setNow(el, props) {
    const current = state(el);
    const next = normalise(props);

    for (const key of Object.keys(next)) {
        if (PROPS.includes(key)) {
            current.values[key] = next[key];

            if (key === 'scaleX' || key === 'scaleY') {
                current.values.scale = current.values.scaleX;
            }
        }
    }

    write(el);
}

export function stop(el, prop = null) {
    const current = el.__mo;

    if (! current) {
        return;
    }

    if (prop) {
        current.anims[prop]?.stop();
        delete current.anims[prop];

        return;
    }

    for (const key of Object.keys(current.anims)) {
        current.anims[key].stop();
    }

    current.anims = {};
    current.running = 0;
    el.style.willChange = '';
}

/**
 * Menggerakkan elemen ke nilai baru.
 *
 * Tiap properti mendapat spring-nya sendiri. Itu disengaja: satu spring atas
 * jarak dua dimensi akan kehilangan sinkron begitu kecepatan sumbu X dan Y
 * berbeda, dan gerakannya melengkung ke arah yang tidak diminta siapa pun.
 *
 * Animasi yang sedang berjalan untuk properti yang sama dihentikan lebih
 * dulu, lalu yang baru berangkat dari nilai tampil — bukan dari awal.
 */
export function springTo(el, props, token = 'move', { velocity = 0, onComplete = null } = {}) {
    const current = state(el);
    const next = normalise(props);
    const keys = Object.keys(next).filter((key) => PROPS.includes(key));

    if (keys.length === 0) {
        onComplete?.();

        return;
    }

    el.style.willChange = 'transform, opacity, filter';

    let pending = keys.length;

    const settle = (key, value) => {
        current.values[key] = value;

        if (key === 'scaleX' || key === 'scaleY') {
            current.values.scale = current.values.scaleX;
        }

        write(el);
        delete current.anims[key];
        pending -= 1;

        if (pending === 0) {
            if (Object.keys(current.anims).length === 0) {
                el.style.willChange = '';
            }

            onComplete?.();
        }
    };

    for (const key of keys) {
        current.anims[key]?.stop();
        delete current.anims[key];

        const from = current.values[key];
        const to = next[key];

        // Nilai yang sudah berada di tujuannya tidak dianimasikan. Spring
        // dengan selisih nol tidak pernah melapor selesai, dan entri yang
        // menggantung di daftar animasi membuat gerakan berikutnya mengira
        // masih ada yang berjalan — lalu berhenti menyemai nilai awalnya.
        if (Math.abs(to - from) < 0.0005) {
            settle(key, to);

            continue;
        }

        // Kecepatan lepasan hanya berlaku untuk sumbu yang memang diseret.
        const perProp = key === 'x' || key === 'y' ? velocity : 0;

        const controls = animate(from, to, {
            ...springOptions(token, { velocity: perProp }),
            onUpdate: (value) => {
                current.values[key] = value;

                if (key === 'scaleX' || key === 'scaleY') {
                    current.values.scale = current.values.scaleX;
                }

                write(el);
            },
        });

        current.anims[key] = controls;

        // Selesainya dibaca dari promise `finished`, bukan dari opsi
        // onComplete: pada bentuk animate(nilai, nilai, …) opsi itu tidak
        // dipanggil, dan tanpa penanda selesai lapisan di atasnya tidak
        // pernah tahu kapan boleh menyembunyikan elemennya.
        controls.finished
            .then(() => {
                // Animasi yang sudah digantikan animasi lain untuk properti
                // yang sama tidak boleh ikut menutup hitungan.
                if (current.anims[key] === controls) {
                    settle(key, to);
                }
            })
            .catch(() => {});
    }
}

/**
 * Satu sumbu saja, dengan kecepatannya sendiri.
 */
export function springAxis(el, axis, to, token = 'move', options = {}) {
    springTo(el, { [axis]: to }, token, options);
}

export function isAnimating(el) {
    return Boolean(el.__mo) && Object.keys(el.__mo.anims).length > 0;
}
