<?php

use App\Http\Controllers\AssetManagemenController;
use App\Http\Controllers\CssdItemsetController;
use App\Http\Controllers\CssdMasterItemController;
use App\Http\Controllers\CssdMasterSatuanController;
use App\Http\Controllers\CssdMasterSupplierController;
use App\Http\Controllers\CssdMasterTypeController;
use App\Http\Controllers\CssdMerkController;
use App\Http\Controllers\CssdPengajuanItemController;
use App\Http\Controllers\DataInventarisController;
use App\Http\Controllers\FileTemplateController;
use App\Http\Controllers\FlipbookController;
use App\Http\Controllers\FormulirPembersihanController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KalibrasiController;
use App\Http\Controllers\KsoController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MaintananceController;
use App\Http\Controllers\MasalahController;
use App\Http\Controllers\MasterDepartemenController;
use App\Http\Controllers\MasterIPController;
use App\Http\Controllers\MasterItemGroupController;
use App\Http\Controllers\MasterMerkController;
use App\Http\Controllers\MasterNamaSetController;
use App\Http\Controllers\MasterPenggunaController;
use App\Http\Controllers\MasterRsController;
use App\Http\Controllers\MasterUnitController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenghapusanAsetController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ReportMaintenanceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register web routes for your application. These
 * | routes are loaded by the RouteServiceProvider within a group which
 * | contains the "web" middleware group. Now create something great!
 * |
 */

Route::get('/', function () {
    return view('auth.login');
});
Route::middleware(['auth'])->group(function () {
    Route::get('asset-managemen/get-ip', [AssetManagemenController::class, 'getIP'])->name('get-ip');
    Route::get('asset-managemen/get-departemen', [AssetManagemenController::class, 'getDepartemen'])->name('get-departemen');
    Route::get('asset-managemen/label/{id}', [AssetManagemenController::class, 'label'])->name('label');
    Route::resource('asset-managemen', AssetManagemenController::class);

    Route::get('masalah/get-asset', [MasalahController::class, 'getAsset'])->name('get-asset');
    Route::get('masalah/get-alat', [MasalahController::class, 'getAlat'])->name('get-alat');
    Route::get('/get-item', [MasalahController::class, 'getItem'])->name('masalah.get-item');

    Route::get('masalah/edit', [MasalahController::class, 'edit'])->name('Masalah.edit');
    Route::get('/export', [MasalahController::class, 'export'])->name('masalah.export');
    Route::get('/excel_masalah/', [MasalahController::class, 'excel_masalah'])->name('masalah.excel_masalah');

    Route::resource('masalah', MasalahController::class);

    Route::get('perbaikan/get-asset', [PerbaikanController::class, 'getAsset'])->name('get-asset-perbaikan');
    Route::get('perbaikan/label/{id}', [PerbaikanController::class, 'label'])->name('label-perbaikan');
    Route::put('perbaikan/status/{id}', [PerbaikanController::class, 'status'])->name('update-status');
    Route::resource('perbaikan', PerbaikanController::class);

    Route::group(['prefix' => 'managemenUser'], function () {
        Route::resource('Permission', PermissionController::class);
        Route::get('change-password', [UserController::class, 'changePassword'])->name('change-password');
        Route::post('save-password', [UserController::class, 'changePasswordSave'])->name('save-password');
        Route::get('/get-rs', [UserController::class, 'getrs'])->name('user.get-rs');
        Route::resource('User', UserController::class);
        Route::resource('Role', RoleController::class);
    });

    Route::resource('file-template', FileTemplateController::class);
    Route::get('file-template/download/{id}', [FileTemplateController::class, 'downloadFile'])->name('download-file-template');
});
// Auth::routes();
Route::group(['prefix' => 'master'], function () {
    Route::get('master-departemen/get-departemen', [AssetManagemenController::class, 'getDepartemen'])->name('master.get-departemen');
    Route::get('master-departemen/ambilDepartemen', [AssetManagemenController::class, 'AmbilDepartemen'])->name('master.AmbilDepartemen');
    Route::resource('master-departemen', MasterDepartemenController::class);
    Route::resource('master-unit', MasterUnitController::class);
    Route::resource('master-merk', MasterMerkController::class);


    Route::prefix('master-pengguna')->group(function () {
        Route::get('/', [MasterPenggunaController::class, 'index'])->name('master.master-pengguna.index');
        Route::get('/create', [MasterPenggunaController::class, 'create'])->name('master.master-pengguna.create');
        Route::post('/store', [MasterPenggunaController::class, 'store'])->name('master.master-pengguna.store');
    });
    Route::prefix('master-rs')->group(function () {
        Route::resource('master-rs', MasterRsController::class);
        Route::get('/', [MasterRsController::class, 'index'])->name('master.master-rs.index');
        Route::get('/create', [MasterRsController::class, 'create'])->name('master.master-rs.create');
        Route::post('/store', [MasterRsController::class, 'store'])->name('master.master-rs.store');
    });

});
Route::group(['prefix' => 'cssd'], function () {
    Route::resource('cssd-master-merk', CssdMerkController::class);
    Route::resource('cssd-master-tipe', CssdMasterTypeController::class);
    Route::resource('cssd-master-satuan', CssdMasterSatuanController::class);

    Route::prefix('cssd-master-item')->group(function () {
        // Route::resource('master-rs', CssdMasterItemController::class);
        Route::get('/', [CssdMasterItemController::class, 'index'])->name('master-cssd.cssd-master-item.index');
        Route::get('/create', [CssdMasterItemController::class, 'create'])->name('master-cssd.cssd-master-item.create');
        Route::get('/edit/{id}', [CssdMasterItemController::class, 'edit'])->name('master-cssd.cssd-master-item.edit');
        Route::put('/update/{id}', [CssdMasterItemController::class, 'update'])->name('master-cssd.cssd-master-item.update');
        Route::post('/store', [CssdMasterItemController::class, 'store'])->name('master-cssd.cssd-master-item.store');
        Route::delete('/destroy/{id}', [CssdMasterItemController::class, 'cssd-master-itemestroy'])->name('master-cssd.cssd-master-item.destroy');
    });
    Route::prefix('master-item-group')->group(function () {
        Route::get('/', [MasterItemGroupController::class, 'index'])->name('master-cssd.item-group.index');
        Route::get('/stok-item', [MasterItemGroupController::class, 'Stok'])->name('cssd-stok-item.index');
        Route::get('/create', [MasterItemGroupController::class, 'create'])->name('master-cssd.item-group.create');
        Route::get('/edit/{id}', [MasterItemGroupController::class, 'edit'])->name('master-cssd.item-group.edit');
        Route::put('/update/{id}', [MasterItemGroupController::class, 'update'])->name('master-cssd.item-group.update');
        Route::post('/store', [MasterItemGroupController::class, 'store'])->name('master-cssd.item-group.store');
        Route::delete('/destroy/{id}', [MasterItemGroupController::class, 'destroy'])->name('master-cssd.item-group.destroy');

    });
    Route::prefix('master-nama-set')->group(function () {
        Route::get('/', [MasterNamaSetController::class, 'index'])->name('master-cssd.master-set-item.index');
        Route::get('/create', [MasterNamaSetController::class, 'create'])->name('master-cssd.master-set-item.create');
        Route::get('/edit/{id}', [MasterNamaSetController::class, 'edit'])->name('master-cssd.master-set-item.edit');
        Route::put('/update/{id}', [MasterNamaSetController::class, 'update'])->name('master-cssd.master-set-item.update');
        Route::post('/store', [MasterNamaSetController::class, 'store'])->name('master-cssd.master-set-item.store');
        Route::delete('/destroy/{id}', [MasterNamaSetController::class, 'destroy'])->name('master-cssd.master-set-item.destroy');
    });
    Route::prefix('pengajuan-nama-item-cssd')->group(function () {
        Route::get('/', [CssdPengajuanItemController::class, 'index'])->name('pengajuan-nama-item-cssd.index');
        Route::get('/create', [CssdPengajuanItemController::class, 'create'])->name('pengajuan-nama-item-cssd.create');
        Route::get('/edit/{id}', [CssdPengajuanItemController::class, 'edit'])->name('pengajuan-nama-item-cssd.edit');
        Route::get('/detail/{id}', [CssdPengajuanItemController::class, 'show'])->name('pengajuan-nama-item-cssd.show');
        Route::put('/update/{id}', [CssdPengajuanItemController::class, 'update'])->name('pengajuan-nama-item-cssd.update');
        Route::post('/store', [CssdPengajuanItemController::class, 'store'])->name('pengajuan-nama-item-cssd.store');
        Route::delete('/destroy/{id}', [CssdPengajuanItemController::class, 'destroy'])->name('pengajuan-nama-item-cssd.destroy');
        Route::post('/setujui/{id}', [CssdPengajuanItemController::class, 'AccPengajuan'])->name('pengajuan-nama-item-cssd.setujui');
        Route::post('/tolak/{id}', [CssdPengajuanItemController::class, 'TolakPengajuan'])->name('pengajuan-nama-item-cssd.tolak');
        Route::get('/cetak-pengajuan-item/{id}', [CssdPengajuanItemController::class, 'Print'])->name('pengajuan-nama-item-cssd.cetak');
    });
    Route::prefix('master-supplier')->group(function () {
        Route::get('/', [CssdMasterSupplierController::class, 'index'])->name('cssd-master-supplier.index');
        Route::get('/create', [CssdMasterSupplierController::class, 'create'])->name('cssd-master-supplier.create');
        Route::get('/edit/{id}', [CssdMasterSupplierController::class, 'edit'])->name('cssd-master-supplier.edit');
        Route::put('/update/{id}', [CssdMasterSupplierController::class, 'update'])->name('cssd-master-supplier.update');
        Route::post('/store', [CssdMasterSupplierController::class, 'store'])->name('cssd-master-supplier.store');
        Route::delete('/destroy/{id}', [CssdMasterSupplierController::class, 'destroy'])->name('cssd-master-supplier.destroy');
    });
    Route::prefix('cssd-item-set')->group(function () {
        Route::get('/', [CssdItemsetController::class, 'index'])->name('cssd-item-set.index');
        Route::get('/create', [CssdItemsetController::class, 'create'])->name('cssd-item-set.create');
        Route::get('/get-item-details', [CssdItemsetController::class, 'getItemDetail'])->name('cssd-item-set.getItem');
        Route::get('/edit/{id}', [CssdItemsetController::class, 'edit'])->name('cssd-item-set.edit');
        Route::put('/update/{id}', [CssdItemsetController::class, 'update'])->name('cssd-item-set.update');
        Route::post('/store', [CssdItemsetController::class, 'store'])->name('cssd-item-set.store');
        Route::delete('/destroy/{id}', [CssdItemsetController::class, 'destroy'])->name('cssd-item-set.destroy');

    });
});

Route::prefix('inventaris')->group(function () {
    Route::get('/', [DataInventarisController::class, 'index'])->name('inventaris.index');
    Route::get('/create', [DataInventarisController::class, 'create'])->name('inventaris.create');
    Route::post('/store', [DataInventarisController::class, 'store'])->name('inventaris.store');
    Route::post('/storeNoro', [DataInventarisController::class, 'storeNoro'])->name('inventaris.storeNoro');
    Route::get('/get-item', [DataInventarisController::class, 'getItem'])->name('inventaris.get-item');
    Route::get('/get-unit-his', [DataInventarisController::class, 'getUnitHis'])->name('inventaris.get-unit-his');
    Route::get('/get-departemen-his', [DataInventarisController::class, 'getDepartemenHis'])->name('inventaris.getDeptHis');
    Route::get('/get-unit', [DataInventarisController::class, 'getUnit'])->name('inventaris.get-unit');
    Route::get('/label/{id}', [DataInventarisController::class, 'label'])->name('inventaris.label');
    Route::get('/tesprint', [DataInventarisController::class, 'tesprint'])->name('inventaris.tesprint');
    Route::get('/masteritem', [DataInventarisController::class, 'masteritem'])->name('inventaris.masteritem');
    Route::resource('inventaris', DataInventarisController::class);
    Route::get('getkategori', [DataInventarisController::class, 'getkategori'])->name('inventaris.getkategori');
    Route::get('/get-ro-item', [DataInventarisController::class, 'getRoItem'])->name('inventaris.getroitem');
    Route::get('getMerk', [DataInventarisController::class, 'getMerk'])->name('inventaris.get-merk');
    Route::get('tanpa-ro', [DataInventarisController::class, 'CreateBc'])->name('inventaris.tanparo');
    Route::get('/get-master-item', [DataInventarisController::class, 'getMasterItem'])->name('inventaris.get-master-item');
});
Route::prefix('flipbook')->group(function () {
    Route::get('/', [FlipbookController::class, 'index'])->name('flipbook.index');
    Route::get('/create', [FlipbookController::class, 'create'])->name('flipbook.create');
    Route::post('/store', [FlipbookController::class, 'store'])->name('flipbook.store');
    Route::get('/destroy', [FlipbookController::class, 'destroy'])->name('flipbook.destroy');
    Route::get('/show/{id}', [FlipbookController::class, 'show'])->name('flipbook.show');

});
Route::prefix('penghapusan-aset')->group(function () {
    Route::get('/', [PenghapusanAsetController::class, 'index'])->name('pa.index');
    Route::get('/create', [PenghapusanAsetController::class, 'create'])->name('pa.create');
    Route::post('/store', [PenghapusanAsetController::class, 'store'])->name('pa.store');
    Route::post('/setujui/{id}', [PenghapusanAsetController::class, 'AccPengajuan'])->name('pa.setujui');
    Route::post('/tolak/{id}', [PenghapusanAsetController::class, 'TolakPengajuan'])->name('pa.tolak');
    Route::post('/setujui-smi/{id}', [PenghapusanAsetController::class, 'AccPengajuanSmi'])->name('pa.setujuiSmi');
    Route::post('/tolak-smi/{id}', [PenghapusanAsetController::class, 'TolakPengajuanSmi'])->name('pa.tolakSmi');
    Route::get('/destroy', [PenghapusanAsetController::class, 'destroy'])->name('pa.destroy');
    Route::get('/show/{id}', [PenghapusanAsetController::class, 'show'])->name('pa.show');
    Route::get('/cetak-pengajuan/{id}', [PenghapusanAsetController::class, 'Print'])->name('pa.cetak');
    Route::get('/edit/{id}', [PenghapusanAsetController::class, 'edit'])->name('pa.edit');
    Route::put('/update/{id}', [PenghapusanAsetController::class, 'update'])->name('pa.update');


});
Route::prefix('maintanance')->group(function () {
    Route::get('/', [MaintananceController::class, 'index'])->name('maintanance.index');
    Route::get('/pm', [MaintananceController::class, 'pm'])->name('maintanance.pm');
    Route::post('/store', [MaintananceController::class, 'store'])->name('maintanance.store');
    Route::post('/AddPm', [MaintananceController::class, 'AddPm'])->name('maintanance.AddPm');
    Route::get('/get-item', [MaintananceController::class, 'getItem'])->name('maintanance.get-item');
    Route::get('/destroy', [MaintananceController::class, 'destroy'])->name('maintanance.destroy');
});

Route::prefix('gudang')->group(function () {
    Route::get('/', [GudangController::class, 'index'])->name('gudang.index');
});
Route::prefix('kalibrasi')->group(function () {
    Route::get('/', [KalibrasiController::class, 'index'])->name('kalibrasi.index');
    Route::post('/store', [KalibrasiController::class, 'store'])->name('kalibrasi.store');
    Route::get('/get-item', [KalibrasiController::class, 'getItem'])->name('kalibrasi.get-item');
    Route::get('/destroy', [KalibrasiController::class, 'destroy'])->name('kalibrasi.destroy');
    Route::get('getInv', [KalibrasiController::class, 'getInv'])->name('kalibrasi.getInv');
});

// Route::resource('kso', KsoController::class);

Route::group(['prefix' => 'laporan'], function () {
    Route::prefix('pembelian')->group(function () {
        Route::get('/', [PembelianController::class, 'index'])->name('pembelian.index');
        Route::get('/file-import', [PembelianController::class, 'importView'])->name('pembelian.import-view');
        Route::get('/export', [PembelianController::class, 'export'])->name('pembelian.export');
        Route::get('/export-users', [PembelianController::class, 'exportUsers'])->name('pembelian.export-users');
        // Route::get('student_export',[StudentController::class, 'get_student_data'])->name('student.export');
        Route::get('/get-item', [PembelianController::class, 'getItem'])->name('pembelian.get-item');
        Route::get('/excel_pembelian/', [PembelianController::class, 'excel_pembelian'])->name('pembelian.excel_pembelian');
        Route::get('/form-kso-pdf', [KsoController::class, 'KsoPdf'])->name('laporan.kso.index');
        Route::post('/laporan-kso-pdf', [KsoController::class, 'LaporanPDF'])->name('kso.LaporanPDF');
    });

    Route::prefix('mutasi')->group(function () {
        Route::get('/', [MutasiController::class, 'index'])->name('mutasi.index');
        Route::get('/file-import', [MutasiController::class, 'importView'])->name('mutasi.import-view');
        Route::get('/export', [MutasiController::class, 'export'])->name('mutasi.export');
        Route::get('/export-users', [MutasiController::class, 'exportUsers'])->name('mutasi.export-users');
        // Route::get('student_export',[StudentController::class, 'get_student_data'])->name('student.export');
        Route::get('/get-item', [MutasiController::class, 'getItem'])->name('mutasi.get-item');
        Route::get('/excel_mutasi/', [MutasiController::class, 'excel_mutasi'])->name('mutasi.excel_mutasi');
    });
    Route::prefix('pemakaian')->group(function () {
        Route::get('/', [PemakaianController::class, 'index'])->name('pemakaian.index');
        Route::get('/file-import', [PemakaianController::class, 'importView'])->name('pemakaian.import-view');
        Route::get('/export', [PemakaianController::class, 'export'])->name('pemakaian.export');
        Route::get('/export-users', [PemakaianController::class, 'exportUsers'])->name('pemakaian.export-users');
        // Route::get('student_export',[StudentController::class, 'get_student_data'])->name('student.export');
        Route::get('/excel_pemakaian/', [PemakaianController::class, 'excel_pemakaian'])->name('pemakaian.excel_pemakaian');
    });

    Route::prefix('maintenance')->group(function () {
        Route::get('/', [ReportMaintenanceController::class, 'index'])->name('laporan.maintenance.index');
        Route::get('/pm', [ReportMaintenanceController::class, 'pm'])->name('laporan.maintenance.pm');
        Route::get('/file-import', [ReportMaintenanceController::class, 'importView'])->name('laporan.maintenance.import-view');
        Route::get('/export', [ReportMaintenanceController::class, 'export'])->name('laporan.maintenance.export');
        // Route::get('student_export',[StudentController::class, 'get_student_data'])->name('student.export');
        Route::get('/excel_maintenance/', [ReportMaintenanceController::class, 'excel_maintenance'])->name('laporan.maintenance.excel_maintenance');
        Route::get('/excel_pm/', [ReportMaintenanceController::class, 'excel_pm'])->name('laporan.maintenance.excel_pm');
    });
    Route::prefix('item-ruangan')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('laporan.item-ruangan.index');
        Route::get('/file-import', [LaporanController::class, 'importView'])->name('laporan.item-ruangan.import-view');
        Route::get('/export', [LaporanController::class, 'export'])->name('laporan.item-ruangan.export');
        Route::get('/excel_item/', [LaporanController::class, 'excel_item'])->name('laporan.item-ruangan.excel_item');
        Route::get('/data-inventaris', [LaporanController::class, 'DataInventaris'])->name('laporan.data-inventaris');
    });
    Route::prefix('monitoring-alat')->group(function () {
        Route::get('/cetak-laporan', [FormulirPembersihanController::class, 'index'])->name('laporan.monitoring.index');
        Route::get('/file-import', [FormulirPembersihanController::class, 'Print'])->name('laporan.monitoring.import');
    });
});
//CSSD

Route::resource('formulir-pembersihan', FormulirPembersihanController::class);
Route::get('/history/{kode_item}', [MasalahController::class, 'history'])->name('masalah.history');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
