<?php
    
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\MilesSupplierController;
use App\Http\Controllers\Api\MilesConversionController;
use App\Http\Controllers\Api\MilesAccountController;
use App\Http\Controllers\Api\MilesOperationController;
use App\Http\Controllers\Api\ClientPassportController;


Route::middleware(['client'])->group(function () {
//companies
Route::post('cadastro/companhia', [CompanyController::class, 'store']);
Route::get('buscar/companhia', [CompanyController::class, 'index']);
Route::get('token/companhia', [CompanyController::class, 'accessToken']);
Route::get('buscar/{id}/companhia', [CompanyController::class, 'getCompany']);
Route::patch('editar/{id}/companhia', [CompanyController::class, 'update']);
Route::delete('deletar/{id}/companhia', [CompanyController::class, 'destroy']);
//suppliers
Route::post('cadastro/fornecedor', [MilesSupplierController::class, 'store']);
Route::get('buscar/fornecedor', [MilesSupplierController::class, 'index']);
Route::get('token/fornecedor', [MilesSupplierController::class, 'accessToken']);
Route::get('buscar/{id}/fornecedor', [MilesSupplierController::class, 'getMilesSupplier']);
Route::patch('editar/{id}/fornecedor', [MilesSupplierController::class, 'update']);
Route::delete('deletar/{id}/fornecedor', [MilesSupplierController::class, 'destroy']);
//conversions
Route::post('cadastro/conversao', [MilesConversionController::class, 'store']);
Route::get('buscar/conversao', [MilesConversionController::class, 'index']);
Route::get('buscar/{id}/conversao', [MilesConversionController::class, 'getMilesQuotation']);
Route::patch('editar/{id}/conversao', [MilesConversionController::class, 'update']);
Route::delete('deletar/{id}/conversao', [MilesConversionController::class, 'destroy']);
//accounts
Route::post('cadastro/conta', [MilesAccountController::class, 'store']);
Route::get('buscar/conta', [MilesAccountController::class, 'index']);
Route::get('token/conta', [MilesAccountController::class, 'accessToken']);
Route::get('buscar/{id}/conta', [MilesAccountController::class, 'getMilesAccount']);
Route::patch('editar/{id}/conta', [MilesAccountController::class, 'update']);
Route::delete('deletar/{id}/conta', [MilesAccountController::class, 'destroy']);
//operations
Route::post('cadastro/operacao', [MilesOperationController::class, 'store']);
Route::get('buscar/operacao', [MilesOperationController::class, 'index']);
Route::get('token/operacao', [MilesOperationController::class, 'accessToken']);
Route::get('buscar/{id}/operacao', [MilesOperationController::class, 'getMilesOperation']);
Route::get('operacao', [MilesOperationController::class, 'milesOperation']);
Route::patch('editar/{id}/operacao', [MilesOperationController::class, 'update']);
Route::delete('deletar/{id}/operacao', [MilesOperationController::class, 'destroy']);

Route::group(['middleware' => 'client'], function () {
        //passport
        Route::post('oauth/clientscredential',[ClientPassportController::class, 'store']);
        //estimates
        // Route::post('estimates',[EstimateController::class, 'getAvailableService']); // "operation": "search",
        // Route::get('estimates/tokens',[EstimateController::class, 'getEstimateByToken']); // "operation": "recover",
        // //translates
        // Route::resource('services.translates', TranslateController::class, ['except' => ['edit', 'create']]);
        // //providers
        // Route::resource('providers', ProviderController::class, ['except' => ['edit', 'create']]);
        // //providers's branches and address
        // Route::resource('providers.branches', BranchController::class, ['except' => ['edit', 'create']]);
        // Route::get('branches/list', [BranchController::class, 'getAllBranches'])->name('branches.list');
        // //service's provider
        // Route::resource('branches.services', ServiceController::class, ['except' => ['edit', 'create']]);
        // Route::get('services/list', [ServiceController::class, 'getAllServices'])->name('services.list');
        // Route::get('branches/{branch}/services', [ServiceController::class, 'index'])->name('api.branches.services.index');
        // Route::post('branches/{branch}/services', [ServiceController::class, 'store'])->name('api.branches.services.store');
        // Route::get('branches/{branch}/services/{service}', [ServiceController::class, 'show'])->name('api.branches.services.show');
        // Route::post('branches/{branch}/services/{service}', [ServiceController::class, 'update'])->name('api.branches.services.update');
        // Route::delete('branches/{branch}/services/{service}', [ServiceController::class, 'destroy'])->name('api.branches.services.destroy');
        // //fare's services
        // Route::resource('services.fares', ServicesFareController::class, ['except' => ['edit', 'create']]);
        // Route::get('fares/list', [ServicesFareController::class, 'getAllFares'])->name('fares.list');
        // Route::post('services/{service}/fare/prices', [ServicesFareController::class, 'createFareWithPrice'])->name('fares.create.withPrices');
        // Route::patch('services/{service}/fare/{fare}/prices', [ServicesFareController::class, 'updateFareWithPrice'])->name('fares.update.withPrices');
       });
    });
