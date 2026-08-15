# Laravel Boilerplate — Auth + RBAC Resource Key + Flowbite

Titik awal untuk project Laravel baru: autentikasi lengkap, kontrol akses berbasis peran yang bisa diatur dari UI, dan pustaka komponen Flowbite siap pakai.

Yang membedakannya dari boilerplate RBAC biasa: kode tidak pernah menyebut nama permission. Kode memakai **resource key**, dan permission di baliknya ditentukan lewat tabel pemetaan di database yang bisa diubah dari panel admin.

**Stack:** Laravel 13 · PHP 8.3+ · MySQL 8 · Fortify · spatie/laravel-permission 8 · Tailwind CSS 4 · Flowbite 4 · Alpine 3 · Pest 5

---

## Menjalankan pertama kali

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan storage:link

composer run dev      # server + queue + vite sekaligus
```

Buka `http://127.0.0.1:8000`. Akun bawaan seeder (kata sandi semuanya `password`):

| Email | Role | Bisa apa |
|---|---|---|
| `super@example.com` | super-admin | Semuanya, melewati seluruh pengecekan |
| `admin@example.com` | admin | Kelola pengguna dan artikel |
| `user@example.com` | user | Baca artikel saja |

**Database.** MySQL 8. Buat dua database sebelum menjalankan migration — satu untuk aplikasi, satu untuk test:

```sql
CREATE DATABASE boilerplate      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE boilerplate_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Nama database test dibaca dari `phpunit.xml`; ganti di sana kalau memakai nama lain. Test memakai `RefreshDatabase`, jadi isi `boilerplate_test` dihapus setiap kali dijalankan — jangan arahkan ke database yang berisi data sungguhan.

---

## Resource key

Satu resource key berbentuk `{resource}.{aksi}` — misalnya `posts.update`. Key inilah yang dipakai kode.

Permission Spatie adalah entitas terpisah. Hubungan keduanya disimpan di tabel `resource_permissions`:

```
resource key            pemetaan (DB)          permission
"posts.update"    ─────────────────────►      "posts.update"     (dibuat otomatis)
"laporan.export"  ─────────────────────►      "akses-laporan"    (diarahkan ulang lewat UI)
"posts.publish"   ─────────────────────►      "content-manage"   (banyak key, satu permission)
```

Konsekuensinya: menggabungkan dua permission, mengganti namanya, atau memindahkan sebuah key ke permission lain sama sekali tidak menyentuh kode. Cukup ubah pemetaannya di menu **Pemetaan Key**.

### Empat cara memakainya

Keempatnya memakai key yang sama dan memberi jawaban yang sama.

```php
// 1. Menjaga route
Route::get('/laporan', ...)->middleware('resource:laporan.view');

Route::get('/laporan/ekspor', ...)->middleware('resource:laporan.export|laporan.print'); // salah satu (ATAU)
Route::post('/laporan', ...)->middleware('resource:laporan.view,laporan.create');        // keduanya (DAN)
```

```blade
{{-- 2. Menyembunyikan bagian tampilan --}}
@resource('laporan.export')
    <x-ui.button>Ekspor</x-ui.button>
@endresource

{{-- 3. Komponen, untuk potongan UI kecil --}}
<x-can resource="laporan.export">
    <x-ui.button>Ekspor</x-ui.button>
</x-can>
```

```php
// 4. Policy — $this->authorize() dan @can tetap idiomatis
class LaporanPolicy extends BaseResourcePolicy
{
    protected function resource(): string
    {
        return 'laporan';
    }
}
```

Menu sidebar menyaring dirinya sendiri: cukup cantumkan `'resource' => rk('laporan', ResourceAction::View)` di `config/navigation.php`.

### Aksi hanya boleh dari enum

`app/Enums/ResourceAction.php` adalah satu-satunya sumber nama aksi. Admin memilihnya lewat centang di UI, developer memakai case enum-nya:

```php
rk('laporan', ResourceAction::Export);   // "laporan.export"
rk('laporan', 'ekspor');                 // InvalidResourceKey — gagal saat itu juga
```

Aksi yang tersedia: `view` `create` `update` `delete` `restore` `force_delete` `export` `import` `approve` `reject` `publish` `assign` `print` `manage`. Tambah case baru di enum kalau butuh.

Untuk autocomplete IDE, jalankan `php artisan resource:keys`. Perintah itu membaca database lalu menulis ulang `app/Support/Resources/ResourceKeys.php` berisi konstanta seperti `ResourceKeys::LAPORAN_EXPORT`.

### Aturan yang berlaku

- Key tidak dikenal atau belum dipetakan **selalu ditolak** — tidak ada celah diam-diam. Key tak dikenal juga dicatat di log.
- Super admin (`config/resources.php`) melewati semuanya lewat `Gate::before`, tanpa perlu satu centangan pun.
- Menghapus permission **tidak** menghapus resource key-nya; key-nya berubah jadi "tak terpetakan" dan aksesnya tertutup sampai dipetakan ulang.
- Menghapus resource **tidak** menghapus permission-nya — bisa jadi masih dipakai key lain.
- Resource dan permission inti ditandai terkunci dan tidak bisa dihapus dari UI.

---

## Menambah modul baru

Contohnya modul Laporan. Modul `posts` di repo ini adalah cetakan lengkapnya — salin saja strukturnya.

**1. Buat resource lewat panel.** Menu Resource → Tambah. Isi nama `laporan`, centang aksi yang dibutuhkan. Permission-nya terbuat dan terpetakan otomatis.

**2. Buat model, migration, dan controller.**

```bash
php artisan make:model Laporan -mfc
php artisan make:request Admin/StoreLaporanRequest
```

**3. Buat policy** di `app/Policies/LaporanPolicy.php`, turunkan dari `BaseResourcePolicy`, sebutkan `return 'laporan';`.

**4. Daftarkan route** di `routes/web.php`, di dalam grup admin, dengan `->middleware('resource:'.rk('laporan', ResourceAction::View))` — atau panggil `$this->authorize()` di controller kalau memakai policy.

**5. Tambahkan menu** di `config/navigation.php` dengan `'resource' => rk('laporan', ResourceAction::View)`.

Lalu bagikan permission-nya ke role lewat menu Role.

---

## Tabel: pencarian, urutan, filter, ekspor

`TableBuilder` mengurus query-nya, komponen `<x-ui.table>` mengurus tampilannya.

```php
$table = TableBuilder::for(Laporan::query()->with('penulis'))
    ->searchable(['judul', 'penulis.name'])                                  // titik = lewat relasi
    ->sortable(['judul', 'created_at'], default: 'created_at', direction: 'desc')
    ->filter('status', fn ($query, $value) => $query->where('status', $value))
    ->perPage(15);

return view('admin.laporan.index', ['laporan' => $table->paginate(), 'table' => $table]);
```

```blade
<x-ui.table.toolbar :table="$table" placeholder="Cari laporan…" />

<x-ui.table :table="$table" :headers="['judul' => 'Judul', 'created_at' => 'Dibuat', 0 => '']">
    @foreach ($laporan as $item)
        <x-ui.table.row>
            <x-ui.table.cell header>{{ $item->judul }}</x-ui.table.cell>
            ...
        </x-ui.table.row>
    @endforeach
</x-ui.table>
```

Kolom yang boleh diurutkan wajib didaftarkan di `sortable()`. Nilai `?sort=` di luar daftar itu diabaikan, bukan diteruskan ke query.

Ekspor CSV:

```php
return $table->download(fn (Laporan $item): array => [
    'Judul' => $item->judul,
    'Dibuat' => $item->created_at->format('Y-m-d'),
], 'laporan.csv');
```

---

## Komponen UI

Semua di `resources/views/components/ui/`, memakai kelas Flowbite dan mendukung mode gelap.

`button` `input` `textarea` `select` `checkbox` `radio` `toggle` `file-upload` `datepicker` `alert` `badge` `card` `modal` `dropdown` `dropdown-item` `tabs` `toast` `breadcrumb` `avatar` `empty-state` `spinner` `icon` `table` `table.row` `table.cell` `table.toolbar`

Komponen form membaca `$errors` sendiri — cukup sebut `name`, pesan validasinya muncul otomatis:

```blade
<x-ui.input name="judul" label="Judul" required />
```

Layout: `<x-layouts.admin>` (sidebar + topbar + breadcrumb) dan `<x-layouts.guest>` (kartu terpusat untuk halaman auth).

**Satu hal soal Flowbite JS.** Flowbite memasang listener-nya sekali saat halaman dimuat. Markup yang baru masuk ke DOM belakangan tidak akan hidup sampai `initFlowbite()` dipanggil lagi. Pancarkan event ini setelah menyisipkan markup baru:

```js
document.dispatchEvent(new CustomEvent('content:updated'));
```

---

## Perintah artisan

| Perintah | Gunanya |
|---|---|
| `php artisan resource:list` | Daftar resource key beserta permission dan jumlah role pemakainya |
| `php artisan resource:list --unmapped` | Hanya key yang belum dipetakan |
| `php artisan resource:keys` | Menulis ulang `ResourceKeys.php` untuk autocomplete |
| `php artisan resource:keys --check` | Gagal kalau berkas itu tidak mutakhir — cocok untuk CI |
| `php artisan resource:sync` | Membuatkan permission untuk key yang masih kosong |
| `php artisan resource:doctor` | Audit: key tanpa permission, permission tanpa key, permission tanpa role |

---

## Auth

Ditangani Fortify; seluruh tampilannya ada di `resources/views/auth/` dan bebas diubah.

Aktif: login, registrasi, reset password, verifikasi email, konfirmasi password, dan verifikasi dua langkah (TOTP + kode pemulihan). Passkey tersedia di Fortify tapi sengaja dimatikan — butuh alur JavaScript sendiri.

Matikan registrasi mandiri lewat `.env`:

```
REGISTRATION_ENABLED=false
```

Akun yang dinonaktifkan (`is_active = false`) ditolak saat login dan sesinya langsung diakhiri oleh middleware `EnsureUserIsActive`.

---

## Struktur

```
app/
  Enums/ResourceAction.php              ← sumber kebenaran nama aksi
  Support/Resources/
    ResourceKey.php                     ← nilai key + validasi format
    ResourceMap.php                     ← peta key → permission, ter-cache
    ResourceGate.php                    ← satu-satunya tempat keputusan akses
    ResourceManager.php                 ← semua penulisan + pembersihan cache
    ResourceKeys.php                    ← hasil generate, jangan diubah manual
  Support/Table/TableBuilder.php
  Support/Navigation/NavigationBuilder.php
  Http/Middleware/EnsureResourceAccess.php
  Policies/BaseResourcePolicy.php
config/
  resources.php                         ← role super admin, cache, log
  navigation.php                        ← menu sidebar
resources/views/
  components/ui/                        ← pustaka Flowbite
  components/layouts/                   ← layout admin & tamu
  admin/                                ← modul panel
```

---

## Test

```bash
php artisan test
./vendor/bin/pint          # format kode
```

Yang sudah tercakup: format resource key, penciptaan permission dari resource, pengarahan ulang pemetaan, key tak terpetakan, penghapusan permission, middleware, directive Blade, penyaringan menu, dan seluruh halaman panel admin.
