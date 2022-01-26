<?php


Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/dash', 'HomeController@dash')->name('dash');


Route::group([
    //'as' => '.admin' ver php artisan r:l para ver problema admin.admin.
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'auth'],
    function (){
        Route::resource('users', 'UsersController', ['as' => 'admin']);
        Route::post('users/password/update', 'UsersController@updatePassword');
        Route::resource('roles', 'RolesController', ['as' => 'admin']);
        Route::resource('permissions', 'PermissionsController', ['except'=>'show', 'as' => 'admin']);

        Route::middleware('role:Root|Admin')
            ->put('users/{user}/roles','UsersRolesController@update');
        Route::middleware('role:Root|Admin')
            ->put('users/{user}/permissions','UsersPermissionsController@update');
        Route::middleware('role:Root|Admin')
            ->put('users/{user}/empresas','UsersEmpresasController@update');
        Route::put('users/{user}/empresa', 'UsersController@updateEmpresa');
        Route::put('users/{user}/reset', 'UsersController@reset');

        Route::post('users/{user}/avatar', 'AvatarsController@store');
        Route::delete('avatars/{user}/delete', 'AvatarsController@destroy');

        Route::middleware('role:Root|Admin')->group(function () {
            Route::resource('retenciones', 'RetencionesController', ['except'=>'show','as' => 'admin']);
            Route::resource('ivas', 'IvasController', ['except'=>'show','as' => 'admin']);
            Route::resource('archivos', 'ArchivosController', ['except'=>'show','as' => 'admin']);
            Route::resource('empresas', 'EmpresasController', ['except'=>'show','as' => 'admin']);
            Route::resource('fpagos', 'FpagosController', ['except'=>'show','as' => 'admin']);
            Route::resource('contadors', 'ContadorsController', ['except'=>'show','as' => 'admin']);
            Route::resource('cuentas', 'CuentasController', ['except'=>'show','as' => 'admin']);

            Route::get('sepa/index', 'SepaController@index');
            Route::post('sepa/transfer', 'SepaController@transfer');
            Route::post('sepa/recibos', 'SepaController@recibos');

            Route::get('adeudos', 'AdeudosController@index');
            Route::post('adeudos/remesar', 'AdeudosController@remesar');


        });



    }
);


Route::group([
    'prefix' => 'mto',
    'namespace' => 'Mto',
    'middleware' => 'auth'],
    function (){
        Route::resource('clientes', 'ClientesController', ['as' => 'mto']);
        Route::get('clientes/{cliente}/albaranes', 'ClientesController@albaranes');
        Route::post('clientes/filtrar', 'ClientesController@filtrar');
        Route::resource('productos', 'ProductosController', ['as' => 'mto']);
        Route::resource('vencimientos', 'VencimientosController', ['except'=>'show','as' => 'mto']);
        Route::resource('extractos', 'ExtractosController', ['only'=>['index','show','update'],'as' => 'mto']);
        Route::post('extractos/filtrar', 'ExtractosController@filtrar');
        Route::get('extractos/{id}/importar', 'ExtractosController@importar');
        Route::put('extractos/{extracto}/liberar', 'ExtractosController@liberar');
        Route::post('extractos/banco', 'ExtractosController@banco');
        Route::get('extractos/{ejercicio}/export', 'ExtractosController@export');

        Route::post('documentos/filtrar', 'DocumentosController@filtrar');
        Route::post('documentos/attach', 'DocumentosController@attach');
        Route::post('documentos/{documento}/detach', 'DocumentosController@detach');
        Route::post('documentos/zip', 'DocumentosController@zip');
        Route::resource('documentos', 'DocumentosController', ['as' => 'mto']);

         //Route::resource('filedocs', 'FiledocsController', ['only'=>['store','show','destroy'],'as' => 'mto']);
        Route::post('filedocs/{filedoc}', 'FiledocsController@store');
        Route::delete('filedocs/{filedoc}', 'FiledocsController@destroy');
        Route::get('filedocs/{filedoc}', 'FiledocsController@show');

        Route::resource('carpetas', 'CarpetasController', ['as' => 'mto']);

        Route::middleware('role:Root|Admin')->group(function () {
            Route::resource('remesa', 'RemesasController', ['as' => 'mto']);
            Route::post('remesa/filtrar', 'RemesasController@filtrar');
        });
    }
);


Route::group([
    'prefix' => 'ventas',
    'namespace' => 'Ventas',
    'middleware' => 'auth'],
    function (){
        //Route::resource('albacabs', 'AlbacabsController', ['except'=>'show','as' => 'ventas']);
        Route::resource('albacabs', 'AlbacabsController');
        Route::put('albacabs/{albacab}/facturar', 'AlbacabsController@facturar');
        Route::put('albacabs/{albacab}/desfacturar', 'AlbacabsController@desfacturar');
        Route::get('albacabs/{albacab}/print', 'AlbacabsController@print');
        Route::put('albacabs/{albacab}/clonar', 'AlbacabsController@clonar');
        Route::put('albacabs/{albacab}/mail', 'AlbacabsController@mail');
        Route::post('albacabs/filtrar', 'AlbacabsController@filtrar');

        Route::resource('albalins', 'AlbalinsController', ['except'=>'index','as' => 'ventas']);

        Route::middleware('role:Root|Admin')->group(function () {
            Route::get('facturas/{fecha}/print', 'PrintFacturasController@print');
        });


    }
);


Route::group([
    'prefix' => 'util',
    'namespace' => 'Util',
    'middleware' => 'auth'],
    function (){

        Route::get('mandato/{cliente}/print', 'MandatoController@print');

    }
);


Route::any('{all}', function () {
    return view('welcome');
})->where(['all' => '.*']);
