<?php

use Illuminate\Http\Request;

Route::get('testMail', 'TestMailController@index');
Route::get('testPdf', 'TestPdfController@index');
Route::get('testFacturados', 'FacturadoController@getFacturadosTest');


Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

// Route::get('caja', 'FacturadoController@getCaja');
Route::get('comprobantes/{cierre_id}', 'FacturadoController@getComprobantes');
Route::get('modificaciones/{cierre_id}', 'FacturadoController@getModificaciones');
Route::get('tarjetas', 'FacturadoController@getTarjetas');
Route::get('planilla', 'ArticuloController@getPdf');

Route::group(['middleware' => ['jwt']], function () {
	Route::delete('cierre/{period}', 'CierreController@remove');

    Route::post('articulo/orden','ArticuloController@saveOrden');
    Route::post('articulo', 'ArticuloController@store');
    Route::delete('articulo/{id}','ArticuloController@delete');

    Route::post('aforador/orden','AforadorController@saveOrden');
    Route::post('aforador', 'AforadorController@store');
    Route::delete('aforador/{id}','AforadorController@delete');
});

Route::get('aforador', 'AforadorController@index');

Route::get('playero', 'PlayeroController@index');
Route::get('usuarios', 'PlayeroController@usuariosCaja');
Route::get('articulo', 'ArticuloController@index');

Route::get('cio/periodos/{month}/{year}', 'CioController@getPeriodos');
Route::get('cio/cierre/{period}', 'CioController@getCierre');
Route::get('cio/ypfenruta/{period}', 'CioController@getYPFenRuta');

Route::get('articulo/combustibles', 'ArticuloController@getArtCombBit');
Route::get('articulo/relacion_multiple', 'ArticuloController@getArtRelacionMultiple');

Route::get('cierre/close/{period}', 'CierreController@close');
Route::post('cierre/obs', 'CierreController@setObs');
Route::get('cierre', 'CierreController@index');
Route::get('cierre/{period}', 'CierreController@show');
Route::post('cierre/modificaciones', 'CierreController@modificaciones');
Route::post('cierre', 'CierreController@store');


Route::get('arqueo/{cierre_id}', 'ArqueoController@index');
Route::post('arqueo', 'ArqueoController@store');


Route::get('cheque/{cierre_id}', 'ChequeController@index');
Route::post('cheque', 'ChequeController@store');
Route::delete('cheque/{id}', 'ChequeController@delete');

Route::get('promo/{cierre_id}', 'PromoController@index');
Route::post('promo', 'PromoController@store');
Route::delete('promo/{id}', 'PromoController@delete');


Route::get('incidencia/{cierre_id}', 'IncidenciaController@index');
Route::post('incidencia/delete', 'IncidenciaController@remove');
Route::post('incidencia', 'IncidenciaController@store');

Route::get('control/aforadores/{cierre_id}', 'ControlAforadoresController@index');
Route::get('control/articulos/{cierre_id}', 'ControlArticulosController@index');
Route::get('facturado/cajas/{cierre_id}', 'FacturadoController@getCajasPorFecha');
Route::get('facturado/{cierre_id}', 'FacturadoController@getFacturados');
Route::get('facturado/yer/{cierre_id}', 'FacturadoController@getFacturadosYER');

Route::post('control/articulos', 'ControlArticulosController@store');
Route::post('control/aforadores', 'ControlAforadoresController@store');

/* Guardo lo del cio en una tabla mysql*/
Route::get('cierrescio/cierre/{period}', 'CierreCioController@getCierreCio');
Route::get('cierrescio/yer/{period}', 'CierreCioController@getYERCio');
Route::post('cierrescio/cierre', 'CierreCioController@saveCierreCio');
Route::post('cierrescio/yer', 'CierreCioController@saveYERCio');
