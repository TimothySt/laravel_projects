<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\PublishersController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\CopyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MemberController;
use App\Http\Middleware\CheckRole;


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

Route::get('/', function () {
    return view('home');//welcome
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// books
Route::get('/books/list', [BookController::class, 'list'])->name('books.list');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create')->middleware(['checkRole:admin,employee']);
Route::post('/books/store', [BookController::class, 'store'])->name('books.store')->middleware(['checkRole:admin,employee']);
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit')->middleware(['checkRole:admin,employee']);
Route::patch('/books/{book}', [BookController::class, 'update'])->name('books.update')->middleware(['checkRole:admin,employee']);
Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update')->middleware(['checkRole:admin,employee']);
Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy')->middleware(['checkRole:admin,employee']);

// copies
Route::post('/books/{book}/copy/new', [CopyController::class, 'store'])->name('copies.store');

// authors
Route::get('/authors/list', [AuthorsController::class, 'list'])->name('authors.list');
Route::get('/authors/create', [AuthorsController::class, 'create'])->name('authors.create')->middleware(['checkRole:admin,employee']);
Route::post('/authors/store', [AuthorsController::class, 'store'])->name('authors.store')->middleware(['checkRole:admin,employee']);
Route::get('/authors/{author_id}', [AuthorsController::class, 'show'])->name('authors.show');
Route::get('/authors/{author_id}/edit', [AuthorsController::class, 'edit'])->name('authors.edit')->middleware(['checkRole:admin,employee']);
Route::patch('/authors/{author_id}', [AuthorsController::class, 'update'])->name('authors.update')->middleware(['checkRole:admin,employee']);
Route::put('/authors/{author_id}', [AuthorsController::class, 'update'])->name('authors.update')->middleware(['checkRole:admin,employee']);
Route::delete('/authors/{author_id}', [AuthorsController::class, 'destroy'])->name('authors.destroy')->middleware(['checkRole:admin,employee']);

//edit
// Route::get('/authors/{author_id}/edit', [AuthorsController::class, 'edit'])->name('authors.edit');
// Route::patch('/authors/{author_id}', [AuthorsController::class, 'update'])->name('authors.update');
// Route::delete('/authors/{author_id}', [AuthorsController::class, 'destroy'])->name('authors.destroy');

// publishers
Route::get('/publishers/list', [PublishersController::class, 'list'])->name('publishers.list');
Route::get('/publishers/create', [PublishersController::class, 'create'])->name('publishers.create')->middleware(['checkRole:admin,employee']);
Route::post('/publishers/create', [PublishersController::class, 'createpost'])->name('publishers.createpost')->middleware(['checkRole:admin,employee']);
Route::post('/publishers/store', [PublishersController::class, 'store'])->name('publishers.store')->middleware(['checkRole:admin,employee']);
Route::get('/publishers/search', [PublishersController::class, 'search'])->name('publishers.search');
Route::get('/publishers/{publisher_id}', [PublishersController::class, 'show'])->name('publishers.show');
Route::get('/publishers/{publisher_id}/edit', [PublishersController::class, 'edit'])->name('publishers.edit')->middleware(['checkRole:admin,employee']);
Route::patch('/publishers/{publisher_id}', [PublishersController::class, 'update'])->name('publishers.update')->middleware(['checkRole:admin']);
Route::PUT('/publishers/{publisher_id}', [PublishersController::class, 'update'])->name('publishers.update')->middleware(['checkRole:admin']);
Route::delete('/publishers/{publisher_id}', [PublishersController::class, 'destroy'])->name('publishers.destroy')->middleware(['checkRole:admin']);

//employees
Route::get('/employees', [EmployeeController::class, 'index'])->middleware(['checkRole:admin,employee']);;
Route::get('/workers', [EmployeeController::class, 'workersList'])->middleware(['checkRole:admin,employee']);
Route::get('/employees/serach', [EmployeeController::class, 'search'])->middleware(['checkRole:admin,employee']);
Route::post('/employees/hire', [EmployeeController::class, 'hire'])->middleware(['checkRole:admin']);
Route::post('/employees/fire', [EmployeeController::class, 'fire'])->middleware(['checkRole:admin']);
// Route::post('/employees/loan', [EmployeeController::class, 'loan'])->middleware(['checkRole:admin,employee']);

// loans
// employees/loan
Route::get('/loan', [LoanController::class, 'newLoan'])->middleware(['checkRole:admin,employee']);
Route::post('/loan/store', [LoanController::class, 'store'])->middleware(['checkRole:admin,employee']);
Route::get('/loan/returnes', [LoanController::class, 'returnLoan'])->middleware(['checkRole:admin,employee']);
Route::post('/loan/returne', [LoanController::class, 'return'])->middleware(['checkRole:admin,employee']);

// members loans
//loans extend
Route::post('/loan/extend', [LoanController::class, 'extendLoan'])->name('loan.extend');

// profile member
Route::get('/myprofile', [MemberController::class, 'showProfile'])->name('profil.show')->middleware(['auth']);

// Admin
Route::middleware(['web', 'auth', CheckRole::class . ':admin'])->group(function ()
{
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});



require __DIR__.'/auth.php';