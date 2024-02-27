<?php

declare(strict_types=1);

use Configurator\QAndATree\Http\Controllers\QAndAController;
use Illuminate\Support\Facades\Route;

Route::get('/tree', [QAndAController::class, 'tree'])->name('q-and-a-tree.tree');
Route::post('/tree/', [QAndAController::class, 'create'])->name('q-and-a-tree.tree-item.create');
Route::post('/tree/{item}', [QAndAController::class, 'update'])->name('q-and-a-tree.tree-item.update');
Route::delete('/tree/{item}', [QAndAController::class, 'delete'])->name('q-and-a-tree.tree-item.delete');

