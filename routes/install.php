<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallController;


//Route::prefix('install')->name('installer.')->middleware('installed')->group(function () {
Route::middleware('prevent-installed-access')->group(function () {

    Route::prefix('install')->name('install.')->group(function () {
        //function () { return "workd";}
        Route::get('/', 'InstallController@welcome')->name('index');
        Route::get('/welcome', 'InstallController@welcome')->name('welcome');
        Route::post('/welcome', 'InstallController@welcomePost')->name('welcome.post');

        Route::get('/requirements', 'InstallController@requirements')->name('requirements');
        Route::post('/requirements', 'InstallController@requirementsPost')->name('requirements.post');

        Route::get('/file-permissions', 'InstallController@filePermissions')->name('filePermissions');
        Route::post('/file-permissions', 'InstallController@filePermissionsPost')->name('filePermissions.post');

        Route::get('/license', 'InstallController@license')->name('license');
        Route::post('/license', 'InstallController@licensePost')->name('license.post');

        Route::get('/database-info', 'InstallController@databaseInfo')->name('databaseInfo');
        Route::post('/database-info', 'InstallController@databaseInfoPost')->name('databaseInfo.post');

        Route::get('/database-import', 'InstallController@databaseImport')->name('databaseImport');
        Route::post('/database-import', 'InstallController@databaseImportPost')->name('databaseImport.post');

        Route::get('/download', 'InstallController@download')->name('download');
        Route::get('/skip', 'InstallController@skip')->name('skip');

        Route::get('/site-info', [InstallController::class, 'siteInfo'])->name('install.site_info');
        Route::post('/site-info', [InstallController::class, 'storeSiteInfo'])->name('install.site_info.store');

        Route::get('/complete', 'InstallController@complete')->name('complete');
    });
});