@include('errors.partials.layout', [
    'code' => '403',
    'title' => 'Akses ditolak',
    'message' => $exception?->getMessage() ?: 'Anda tidak punya izin untuk membuka halaman ini.',
    'hint' => 'Kalau menurut Anda ini keliru, minta administrator memeriksa role dan pemetaan resource key Anda.',
])
