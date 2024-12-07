<?php

use App\Http\Controllers\ExecutionController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return to_route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/operation', [OperationController::class, 'index'])->name('operation.index');
    Route::post('/operation', [OperationController::class, 'store'])->name('operation.store');
    Route::delete('/operation/{operation}', [OperationController::class, 'destroy'])->name('operation.destroy');
    Route::get('/operation/{operation}', [OperationController::class, 'show'])->name('operation.show');

    Route::get('/finish/{execution}', [ExecutionController::class, 'finish'])->name('finish.execution');
    Route::get('/execution/{execution}', [ExecutionController::class, 'show'])->name('execution.show');
    Route::put('/finish', [ExecutionController::class, 'finish'])->name('execution.finish');
    Route::delete('/execution/{execution}', [ExecutionController::class, 'destroy'])->name('execution.destroy');

    Route::post('/finish/operation/{operation}', [OperationController::class, 'finish'])->name('finish.operation');
});

require __DIR__.'/auth.php';
