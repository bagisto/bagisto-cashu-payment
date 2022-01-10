<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('/cashu')->group(function () {

        Route::get('/redirect', 'Webkul\CashU\Http\Controllers\CashUController@redirect')->name('cashu.payement.redirect');

        Route::post('/callback', 'Webkul\CashU\Http\Controllers\CashUController@success')->name('cashu.payement.success');

        Route::post('/cancel', 'Webkul\CashU\Http\Controllers\CashUController@cancel')->name('cashu.payement.cancel');
    });
});