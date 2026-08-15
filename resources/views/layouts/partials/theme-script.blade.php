{{--
    Dijalankan sebelum CSS dimuat supaya halaman tidak berkedip terang lebih
    dulu saat pengguna memilih tema gelap. Sengaja inline dan sesingkat mungkin.
--}}
<script>
    (function () {
        var stored = localStorage.getItem('theme');
        var dark = stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches);

        document.documentElement.classList.toggle('dark', dark);
    })();
</script>
