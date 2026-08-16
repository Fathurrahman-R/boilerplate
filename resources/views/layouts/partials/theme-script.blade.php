{{--
    Dijalankan sebelum CSS dimuat supaya halaman tidak berkedip terang lebih
    dulu saat pengguna memilih tema gelap, dan supaya sidebar tidak melompat
    dari lebar penuh ke rail begitu Alpine termuat. Sengaja inline dan
    sesingkat mungkin.
--}}
<script>
    (function () {
        var root = document.documentElement;

        var stored = localStorage.getItem('theme');
        var dark = stored === 'dark' || (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches);
        root.setAttribute('data-theme', dark ? 'dark' : 'light');

        root.setAttribute('data-sidebar', localStorage.getItem('sidebar-collapsed') === '1' ? 'collapsed' : 'expanded');
    })();
</script>
