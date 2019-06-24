<?php
//use App\Client;
use Illuminate\Http\Request;
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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/* Clientes */
Route::get('clients/restore/{id}', 'Administration\ClientController@restore')->name('clients.restore');
Route::resource('clients', 'Administration\ClientController');

/* Proveedores */
Route::get('providers/restore/{id}', 'Administration\ProviderController@restore')->name('providers.restore');
Route::resource('providers', 'Administration\ProviderController');

/* Choferes */
Route::get('drivers/restore/{id}', 'Administration\DriverController@restore')->name('drivers.restore');
Route::resource('drivers', 'Administration\DriverController');

/* Unidades */
Route::get('units/restore/{id}', 'Administration\UnityController@restore')->name('units.restore');
Route::resource('units', 'Administration\UnityController');

/* Duenos */
Route::get('owners/restore/{id}', 'Administration\OwnerUnityControlller@restore')->name('owners.restore');
Route::resource('owners', 'Administration\OwnerUnityControlller');

/* Lugares */
Route::get('places/restore/{id}', 'Administration\PlacesController@restore')->name('places.restore');
Route::resource('places', 'Administration\PlacesController');

/* Cartera */
Route::get('wallet/restore/{id}', 'Administration\WalletController@restore')->name('wallet.restore');
Route::resource('wallet', 'Administration\WalletController');

/* Carta de porte */
Route::get('waybills/restore/{id}', 'WaybillsController@restore')->name('waybills.restore');
Route::resource('waybills', 'WaybillsController');

/* Adelantos */
Route::resource('orders/advancements', 'Orders\AdvancementsController');
Route::resource('orders/advancementsout', 'Orders\AdvancementsOutController');

/* Compras */
Route::get('orders/purchases/restore/{id}', 'Orders\PurchasesController@restore')->name('purchases.restore');
Route::post('orders/purchases/confirmations/{id}', 'Orders\PurchasesController@confirmations')->name('purchases.confirmations');
Route::resource('orders/purchases', 'Orders\PurchasesController');

//Nota de credito y debito
Route::resource('orders/credito', 'Orders\NotaCreditoController');
Route::resource('orders/debito', 'Orders\NotaDebitoController');

/* Liquidaciones */
Route::get('liquidaciones/dueno', 'Liquidacion\DuenoController@index')->name('dueno.index');
Route::get('liquidaciones/dueno/guardado/{id}', 'Liquidacion\DuenoController@guardado')->name('dueno.guardado');

Route::get('liquidaciones/clientes', 'Liquidacion\ClientesController@index')->name('clientes.index');
Route::get('liquidaciones/clientes/guardado/{id}', 'Liquidacion\ClientesController@guardado')->name('clientes.guardado');

/* Cobranzas / Pagos */
Route::get('cobranzas-pagos/duenos', 'CobranzasPagos\DuenosController@index')->name('duenosP.index');
Route::post('cobranzas-pagos/duenos/guardado/{id}', 'CobranzasPagos\DuenosController@guardado')->name('duenosP.guardado');
Route::post('cobranzas-pagos/duenos/baja/{id}', 'CobranzasPagos\DuenosController@baja')->name('duenosP.baja');

Route::get('cobranzas-pagos/clientes', 'CobranzasPagos\ClientesController@index')->name('clientesC.index');
Route::post('cobranzas-pagos/clientes/guardado/{id}', 'CobranzasPagos\ClientesController@guardado')->name('clientesC.guardado');
Route::post('cobranzas-pagos/clientes/baja/{id}', 'CobranzasPagos\ClientesController@baja')->name('clientesC.baja');

/* Cuentas Corrientes */
Route::get('cuentas-corriente/duenos', 'CuentasCorriente\DuenosController@index')->name('duenosCC.index');
Route::get('cuentas-corriente/clientes', 'CuentasCorriente\ClientesController@index')->name('clientesCC.index');