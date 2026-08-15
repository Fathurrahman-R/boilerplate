<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    // Dibutuhkan modul yang berotorisasi lewat policy (mis. PostController)
    // sehingga $this->authorize() tersedia.
    use AuthorizesRequests;
}
