<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CodigoPostalController;

Route::get('codigos-postales/{cp}', [CodigoPostalController::class, 'buscar']);
