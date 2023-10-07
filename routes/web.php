<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('company', function () {
    return view('IndexCompany');

// Route::redirect('/', '/admin/company');

// Route::prefix('admin')->name('admin.')->group(function() {

//     Route::get('company', [CompanyController::class, 'company'])->name('company.listar');
//     Route::get('company/cadastrar', [CompanyController::class, 'formAdicionar'])->name('company.form');
//     Route::post('company/cadastrar', [CompanyController::class, 'adicionar'])->name('company.adicionar');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
