<?php

use Illuminate\Routing\Router;

// 接口
Route::group([
    'prefix' => config('qihu.role_prefix', 'role'),
    'namespace' => 'Qihucms\Role\Controllers\Api',
    'middleware' => ['api'],
    'as' => 'api.role.'
], function (Router $router) {
    $router->get('roles', 'RoleController@index')->name('roles');
    $router->post('roles', 'RoleController@store');
    $router->get('roles/{id}', 'RoleController@show')->name('roles.show');
});

// 后台管理
Route::group([
    'prefix' => config('admin.route.prefix') . '/role',
    'namespace' => 'Qihucms\Role\Controllers\Admin',
    'middleware' => config('admin.route.middleware'),
    'as' => 'admin.'
], function (Router $router) {
    $router->resource('roles', 'RoleController');
    $router->resource('permissions', 'PermissionController');
});