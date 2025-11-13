<?php

use App\Http\Controllers\dahargoController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [dahargoController::class, "index" ]);
