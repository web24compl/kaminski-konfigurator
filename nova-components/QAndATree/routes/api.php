<?php

declare(strict_types=1);

use Configurator\QAndATree\QAndAController;
use Illuminate\Support\Facades\Route;

Route::get('/tree', [QAndAController::class, 'tree'])->name('q-and-a-tree.tree');

