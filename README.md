**会员权限**

##安装扩展包

`composer require jncinet/laravel-role`

## 后台菜单链接
- 规则 `role/roles`
- 权限 `role/permissions`

## 使用
在user model中添加 `use HasPermissions`（注意命令空间是 Qihucms\Role\Models\HasPermissions）

## API接口路由
```php
// 所有可以获取的权限「根据控制器代码设置读取条件」
$router->get('role/roles', 'RoleController@index')->name('api.role.roles');
// 功能开通
$router->post('role/roles', 'RoleController@store');
// 权限详细说明
$router->get('role/roles/{id}', 'RoleController@show')->name('api.role.roles.show');
```

## 删除过期权限
```shell
php artisan role:checkExpires
```