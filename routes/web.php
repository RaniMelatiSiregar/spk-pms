<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\SPKController;
use App\Http\Controllers\PeriodeController;

Route::get('/', fn()=> redirect()->route('login'));

Route::get('/login', [AuthController::class,'showLogin'])->name('login');
Route::post('/login', [AuthController::class,'login'])->name('login.post');
Route::get('/logout', [AuthController::class,'logout'])->name('logout');

Route::middleware(['admin.auth'])->group(function(){

    // DASHBOARD
    Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');

    // SUPPLIER / ALTERNATIF
    Route::get('/alternatif',[SupplierController::class,'index'])->name('supplier.index');
    Route::get('/alternatif/create',[SupplierController::class,'create'])->name('supplier.create');
    Route::post('/alternatif',[SupplierController::class,'store'])->name('supplier.store');
    Route::get('/alternatif/{supplier}/edit',[SupplierController::class,'edit'])->name('supplier.edit');
    Route::put('/alternatif/{supplier}',[SupplierController::class,'update'])->name('supplier.update');
    Route::delete('/alternatif/{supplier}',[SupplierController::class,'destroy'])->name('supplier.destroy');

    // KRITERIA
    Route::get('/kriteria',[CriteriaController::class,'index'])->name('kriteria.index');
    Route::get('/kriteria/create',[CriteriaController::class,'create'])->name('kriteria.create');
    Route::post('/kriteria',[CriteriaController::class,'store'])->name('kriteria.store');
    Route::get('/kriteria/{kriteria}/edit',[CriteriaController::class,'edit'])->name('kriteria.edit');
    Route::put('/kriteria/{kriteria}',[CriteriaController::class,'update'])->name('kriteria.update');
    Route::delete('/kriteria/{kriteria}',[CriteriaController::class,'destroy'])->name('kriteria.destroy');

    // PARAMETER KRITERIA
    Route::get('kriteria/{kriteria}/parameter', [ParameterController::class, 'index'])->name('parameter.index');
    Route::get('kriteria/{kriteria}/parameter/create', [ParameterController::class, 'create'])->name('parameter.create');
    Route::post('kriteria/{kriteria}/parameter', [ParameterController::class, 'store'])->name('parameter.store');
    Route::get('kriteria/{kriteria}/parameter/{parameter}/edit', [ParameterController::class, 'edit'])->name('parameter.edit');
    Route::put('kriteria/{kriteria}/parameter/{parameter}', [ParameterController::class, 'update'])->name('parameter.update');
    Route::delete('kriteria/{kriteria}/parameter/{parameter}', [ParameterController::class, 'destroy'])->name('parameter.destroy');

    // SMART
    Route::get('/perhitungan', [SPKController::class, 'compute'])->name('spk.compute');
    Route::get('/hasil', [SPKController::class, 'result'])->name('spk.result');
    Route::get('/export/excel', [SPKController::class, 'exportExcel'])->name('spk.export.excel');
    Route::get('/export/pdf', [SPKController::class, 'exportPDF'])->name('spk.export.pdf');

    // HISTORY PERHITUNGAN — FIX
    Route::get('/spk/history', [SPKController::class, 'history'])->name('spk.history');

    // PERIODE
    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode.index');
    Route::get('/periode/create', [PeriodeController::class, 'create'])->name('periode.create');
    Route::post('/periode', [PeriodeController::class, 'store'])->name('periode.store');
    Route::get('/periode/{periode}/edit', [PeriodeController::class, 'edit'])->name('periode.edit');
    Route::put('/periode/{periode}', [PeriodeController::class, 'update'])->name('periode.update');
    Route::delete('/periode/{periode}', [PeriodeController::class, 'destroy'])->name('periode.destroy');
    Route::put('/periode/{periode}/activate', [PeriodeController::class, 'setActive'])->name('periode.setActive');
    Route::get('/periode/generate-next-month', [PeriodeController::class, 'generateNextMonth'])->name('periode.generateNextMonth');

    Route::get('/supplier/{supplier}/scores', [SupplierScoreController::class, 'index'])
    ->name('scores.index');
    Route::post('/supplier/{supplier}/scores', [SupplierScoreController::class, 'store'])
    ->name('scores.store');
});
