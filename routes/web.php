<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenAiApiController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [OpenAiApiController::class, 'show'])->name('home');

Route::post('/recaptcha', [OpenAiApiController::class, 'validateCaptcha']);

Route::post('/ai', [OpenAiApiController::class, 'index']);

Route::post('/export', [ExportController::class, 'exportChatResponses']);
