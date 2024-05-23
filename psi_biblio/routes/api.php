<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\PublisherController;
use App\Http\Controllers\Api\CopyController;
use App\Http\Controllers\Api\MembersController;
use App\Http\Controllers\Api\LoanController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// książki
Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::post('/new', [BookController::class, 'store']);
    Route::get('/find/{isbn}', [BookController::class, 'show']);
    Route::put('/edit/{isbn}', [BookController::class, 'update']);
    Route::delete('/delete/{isbn}', [BookController::class, 'destroy']);
    Route::get('/search', [BookController::class, 'search']);
});

// autorzy
Route::prefix('authors')->group(function () {
    Route::get('/', [AuthorController::class, 'index']);
    Route::get('/find/{id}', [AuthorController::class, 'show']);
    Route::post('/new', [AuthorController::class, 'store']);
    Route::put('/edit/{id}', [AuthorController::class, 'update']);
    Route::delete('/delete/{id}', [AuthorController::class, 'destroy']);
    Route::get('/find/{id}/books', [AuthorController::class, 'listBooksByAuthor']);
});

// Wydawcy
Route::prefix('publishers')->group(function () {
    Route::get('/', [PublisherController::class, 'index'])->name('publishers.index');
    Route::post('/new', [PublisherController::class, 'store'])->name('publishers.store');
    Route::get('/find/{id}', [PublisherController::class, 'show'])->name('publishers.show');
    Route::put('/edit/{id}', [PublisherController::class, 'update'])->name('publishers.update');
    Route::delete('/delete/{id}', [PublisherController::class, 'destroy'])->name('publishers.destroy');
});

// egzemplarze
Route::prefix('copies')->group(function () {
    Route::get('/', [CopyController::class, 'index']);
    Route::post('/new', [CopyController::class, 'store']);
    Route::get('/find/{id}', [CopyController::class, 'show']);
    Route::put('/edit/{id}', [CopyController::class, 'update']);
    Route::delete('/delete/{id}', [CopyController::class, 'destroy']);
});

// konta - members
Route::prefix('members')->group(function () {
    Route::get('/', [MembersController::class, 'index']);
    Route::post('/new', [MembersController::class, 'store']);
    Route::get('/find/workers', [MembersController::class, 'findWorkers']);
    Route::get('/find/{member_id}', [MembersController::class, 'show']);
    Route::put('/edit/{member_id}', [MembersController::class, 'update']);
    Route::post('/hire/{member_id}', [MembersController::class, 'employ']); // TODO did-ed?
    Route::post('/fire/{member_id}', [MembersController::class, 'discharge']); // TODO did-ed?
    Route::delete('/delete/{member_id}', [MembersController::class, 'destroy']);
    Route::get('/search', [MembersController::class, 'search']); // TODO
});

// egzemplaże - copies
Route::prefix('copies')->group(function () {
    Route::get('/', [CopyController::class, 'index']);
    Route::post('/new', [CopyController::class, 'store']);
    Route::get('/find/{copy_id}', [CopyController::class, 'show']);
    Route::get('/forbook/{title_id}', [CopyController::class, 'showForBook']);
});

// wypożyczenia - loan
Route::prefix('loans')->group(function () {
    Route::get('/', [LoanController::class, 'index']);
    Route::post('/store', [LoanController::class, 'store']);
    Route::post('/return/{copy_id}', [LoanController::class, 'returnBook']);
    Route::get('/find/{copy_id}', [LoanController::class, 'show']);
    Route::post('/extend', [LoanController::class, 'extendDueDate']);
    Route::get('/user/{member_id}', [LoanController::class, 'getUserLoans']);
});