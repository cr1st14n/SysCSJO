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

Route::get('/', function () {
    return redirect ('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/time','HomeController@time');
Route::get('print',function()
{
	return view('true');
});

Route::get('1234',function ()
    {	
    	 Session::flash('flash_message', 'Mensaje de prueba');
        return 'jejeje';
    })->middleware('administracion');
   Route::post('searchredirect','PacienteController@buscar');
/* Route::get('home/searchredirect', function(){
     
     Nuevo: si el argumento search está vacío regresar a la página anterior 
    if (empty(Input::get('search'))) return redirect()->back();
    
    $search = urlencode(e(Input::get('search')));
    $route = "home/search/$search";
    return redirect($route);
});
Route::get("home/search/{search}", function($search){

return $search ;

});
*/
//----------vistas generales ---------//



//--------------administracion---------//


Route::group(['middleware' => ['administracion'],'prefix' =>'/adm'],function(){
		Route::get('/home','HomeController@admHome')->name('adm.Home');
		Route::get('/perfil','HomeController@store_perfil')->name('store_user_adm');
		Route::post('perfil_update_datos','HomeController@update_perfil_datos')->name('store_user_adm_update_date');
		Route::post('perfil_update_email','HomeController@update_perfil_email')->name('store_user_adm_update_email');
		
	Route::group(['prefix' => '/users'],function(){
		
		Route::get('indexA','UsersController@index')->name('formNewuserA');
		Route::get('store','UsersController@store')->name('storeUsers');
		Route::get('show/{id} ','UsersController@show')->name('showuser');
		Route::get('showAll','UsersController@showAll');
		Route::get('edit','UsersController@edit');
		Route::post('update_date','UsersController@update_date')->name('updateUser_date');
		Route::post('update_acceso','UsersController@update_acceso')->name('updateUser_acceso');
		Route::get('destroy/{id} ','UsersController@destroy')->name('destroy_users');
		Route::get('acceso/{id} ','UsersController@accesoOnOff')->name('acceso_user');
	});
	Route::group(['prefix' => '/personal_salud'],function(){
		Route::get('index','PersonalSaludController@index')->name('formPS');
		Route::post('create','PersonalSaludController@create')->name('registerPS');
		Route::get('edit/{ps_id} ','PersonalSaludController@edit')->name('showPS');
		Route::post('update','PersonalSaludController@update')->name('updatePS');
		Route::get('destroy/{id}','PersonalSaludController@destroy')->name('destroyPS');

	});
	Route::group(['prefix' => '/especialidad'],function(){
		
		Route::get('index','especialidadController@index')->name('formNewEspecialidad');
		Route::post('create','especialidadController@register')->name('createEspecialidad');
		Route::get('store','especialidadController@store');
		Route::get('show/{id} ','especialidadController@show')->name('form_edit_especialidad');
		Route::get('showAll','especialidadController@showAll');
		Route::get('edit','especialidadController@edit');
		Route::post('update','especialidadController@update')->name('update_especialidad');
		Route::get('delete/{id}','especialidadController@destroy')->name('destroy_especialidad');
	});
	Route::group(['prefix' => '/area'],function(){
		
		Route::get('index','areaController@index')->name('formNewArea');
		Route::post('create','areaController@create')->name('createArea');
		Route::get('store','areaController@store');
		Route::get('show','areaController@show');
		Route::get('showAll','areaController@showAll');
		Route::get('edit','areaController@edit');
		Route::get('update','areaController@update');
		Route::get('destroy','areaController@destroy');
	});
	Route::group(['prefix'=> '/reporte'],function(){
		Route::get('home','HomeController@admReportHome')->name('admReportHome');
		Route::any('reporteDiario_Imprimir','cajaController@reporteDiario')->name('reporteDiario_adm');
		Route::any('reporteMensual','cajaController@reporteMensual')->name('reporteMensual_adm');
	});

});

//--------------RECEPCION---------//
Route::group(['middleware'=>['recepcion'],'prefix' => '/Recepcion'],function(){
	Route::get('home','HomeController@recepHome')->name('recepcion.home');
	Route::get('/perfil','UsersController@store_perfil')->name('store_user_recepcion');
	Route::post('perfil_update_datos','HomeController@update_perfil_datos')->name('store_user_recepcion_update_date');
	Route::post('perfil_update_email','HomeController@update_perfil_email')->name('store_user_recepcion_update_email');
	//crud pacientes
	Route::group(['prefix' => '/paciente'],function(){
		Route::get('index','PacienteController@index')->name('index_paciente');
		Route::post('register','PacienteController@create')->name('register_paciente');
		Route::get('buscar','PacienteController@formBuscarPaciente')->name('form_buscar_paciente');
		Route::any('search','PacienteController@buscar')->name('buscar_paciente');
		Route::post('PrintHCL','PacienteController@print_HCl')->name('print_HCl');
		Route::get('PrintHCL1/{pa_hcl}','PacienteController@print_HCl_1')->name('printHcl');
		Route::get('edit/{pa_hcl}','PacienteController@edit')->name('edit_paciente');
		Route::post('update','PacienteController@update')->name('update_paciente');
		Route::get('delete/{pa_hcl}','PacienteController@destroy')->name('destroy_pa_hcl');
	});
	Route::group(['prefix'=> '/atencion'],function(){
		Route::get('index/{pa_hcl} ','AtencionController@index')->name('form_atencion');
		Route::post('register','AtencionController@create')->name('create_atencion');
		Route::any('edit_a/{ate_id}','AtencionController@edit')->name('edit_atencion');
		Route::post('update','AtencionController@update')->name('update_antencion');
		Route::get('showAll','AtencionController@showAll')->name('showAll_atencion');
		Route::post('show','AtencionController@show')->name('show');
		Route::get('delete/{id}','AtencionController@destroy')->name('delete_atencion');
		Route::get('pagar_ate/{id} ','AtencionController@pago')->name('recep_pago');

	});
	Route::group(['prefix'=>'/reporte'],function(){
		
		Route::get('inf_atencion','AtencionController@showPa_Ate1')->name('inf_atencion');
		Route::post('inf_atencion_list','AtencionController@showPa_Ate_list')->name('inf_atencion_list');
		Route::get('index','AtencionController@reporte_index')->name('reporte_index');
		Route::get('RD','AtencionController@reporte_diario')->name('reporte_diario_p');
	});
	Route::group(['prefix'=>'/Notas'],function(){
		Route::get('/','RecepNotasController@index')->name('notas-index');
		Route::get('/listNotas','RecepNotasController@show')->name('notas-showNotas');
        Route::post('/create','RecepNotasController@create')->name('notas-create');
        Route::post('/update','RecepNotasController@update')->name('notas-update');
        Route::get('/destroy/{id}','RecepNotasController@destroy')->name('notas-destroy');
        Route::post('/filtrarPrestamos/','RecepNotasController@filtrarPrestamos');


	});
    Route::group(['prefix'=>'/PresHCL'],function(){
        Route::post('/create','PrestHCLController@create');
        Route::any('/list','PrestHCLController@list');
        Route::post('/listFiltrado','PrestHCLController@listFiltrado');
        Route::get('/show/{id}','PrestHCLController@show');
        Route::post('/update/','PrestHCLController@update');
        Route::get('/cerrarPrestamo/{id}','PrestHCLController@cerrarPrestamo');

    });

});

//---------------CAJA------//
Route::group(['middleware'=>['caja'], 'prefix'=>'/caja'],function(){
	Route::get('/perfil','UsersController@store_perfil')->name('store_user_caja');
	Route::post('/perfil/update_datos','HomeController@update_perfil_datos')->name('store_user_caja_update_date');
	Route::post('/perfil/update_email','HomeController@update_perfil_email')->name('store_user_caja_update_email');
	Route::get('home','cajaController@index')->name('cajaHome');
	Route::get('cola_pacientes','cajaController@pacientes_cola')->name('pacientes_cola');
	Route::get('pagar/{id} ','cajaController@pago')->name('ate_pago');
	Route::get('fila_pacientes','cajaController@store_pagos')->name('store_pagos');
	Route::post('filter_pagos','cajaController@store_filter_pagos')->name('filter_pagos');
	Route::get('reportes','cajaController@reportes')->name('caja_reporte');
	Route::any('reporteDiario_Imprimir','cajaController@reporteDiario')->name('reporteDiario');
	Route::any('reporteMensual','cajaController@reporteMensual')->name('reporteMensual');
});

//---------------RRHH------//
Route::group([/*'middleware'=>['caja'],*/ 'prefix'=>'/RRHH'],function(){
    Route::get('/home','RecHumanController@index')->name('rrhh_home');
    Route::get('/1','RecHumanController@create');

    Route::group(['prefix'=>'personal'],function(){
        Route::get('/','empleadoController@index')->name('empleado_home');
        Route::get('showEmpTodos','empleadoController@showEmpTodos');
        Route::get('showDatosEmp/{id}','empleadoController@showDatosEmp');
        Route::get('22','empleadoController@segun');
        Route::post( 'createUser','empleadoController@createUser');
    });
    Route::group(['prefix'=>'Areas'],function(){
        Route::get('/','areaController@homeArea')->name('home_area');
    });

});

