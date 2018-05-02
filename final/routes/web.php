<?php

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

/* PAGINA PRINCIPAL */
Route::get('/', 'IndexController@index');

/* LOGIN PARA INICIAR SESION */
Route::get('/iniciarSesion', 'InicioSesionController@index');


/* RUTAS PARA LOGUEAR/REGISTRAR USUARIO */
Auth::routes();

#Route::get('/home', 'HomeController@index')->middleware('auth');
/* PAGINA PRINCIPAL DEL USUARIO LOGUEADO */
Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/paginaPrincipal','UsuariosController@index')->middleware('auth');

/* MANEJO DE USUARIOS */
Route::get('/usuarios/create', 'UsuariosController@create')->middleware('auth','permiso:usuario_new');
Route::post('/usuarios', 'UsuariosController@store')->middleware('auth','permiso:usuario_new');
Route::put('/usuarios/{id}', 'UsuariosController@update')->middleware('auth','permiso:usuario_update');
Route::get('/usuarios/{id}/edit', 'UsuariosController@edit')->middleware('auth','permiso:usuario_update');
Route::get('/usuarios/{id}', 'UsuariosController@show')->middleware('auth','permiso:usuario_show');
Route::get('/usuarios','UsuariosController@index')->middleware('auth','permiso:usuario_index');
#Route::resource('/usuarios','UsuariosController');

/* MANEJO DE LA CONFIGURACION DEL SITIO */
Route::get('/configuracion','ConfiguracionController@index')->middleware('auth','permiso:configuracion');