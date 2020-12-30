<h1 align="center">会员权限</h1>

## 安装

```shell
$ composer require jncinet/qihucms-role
```

## 使用

### 数据迁移
```shell
$ php artisan migrate
```

### 发布资源
```shell
$ php artisan vendor:publish --provider="Qihucms\Role\RoleServiceProvider"
```

### 添加到会员模型
```php
...
use Qihucms\Role\Models\HasPermissions
...

class User extends Authenticatable
{
    use HasPermissions;
    ...
}
```

### 删除过期权限，可以宝塔定时任务中添加运行此命令
```shell
$ php artisan role:checkExpires
```

## 后台菜单
+ 规则 `role/roles`
+ 权限 `role/permissions`

## 接口
### 所有可开通的功能
+ 请求方式：GET
+ 请求地址：role/roles
+ 请求参数：
```
{
    "name": "名称", // 可选
    "slug": "标识", // 可选
    "currency_type_id": 1, // 支付货币类型 可选
    "times": 1, // 有效时长 可选
    "unit": "days", // 有效时长单位 可选
    "is_pa": 1, // 是否需要完成个人认证 可选
    "is_co": 0, // 是否需要完成企业认证 可选
}
```
+ 返回值：
```
{
    "data": [
        {
            'id': 1,
            'name': "名称",
            'slug': "标识",
            'desc': "介绍",
            'times': 3,
            'unit': "days",
            'is_qualification_pa': 0,
            'is_qualification_co': 1,
            'price': 1.00,
            'currency_type': {货币详细信息},
        },
        ...
    ],
    "meta": {},
    "links": {},
}
```

### 开通功能
+ 请求方式：POST
+ 请求地址：role/roles
+ 请求参数：{'role_id': 1, // 功能ID}
+ 返回值：
```
{
    "status": "SUCCESS",
    "result" : {
        'user_id': 1, // 会员ID号
        'role_id': 1, // 已开通的功能ID
    }
}
```

### 权限详细说明
+ 请求方式：POST
+ 请求地址：role/roles/{id=功能ID}
+ 返回值：
```
{
    'id': 1,
    'name': "名称",
    'slug': "标识",
    'desc': "介绍",
    'times': 3,
    'unit': "days",
    'is_qualification_pa': 0,
    'is_qualification_co': 1,
    'price': 1.00,
    'currency_type': {货币详细信息},
}
```

## 数据库
### 签约项目表：roles
| Field             | Type      | Length    | AllowNull | Default   | Comment   |
| :----             | :----     | :----     | :----     | :----     | :----     |
| id                | bigint    |           |           |           |           |
| name              | varchar   | 255       |           |           | 签约名称   |
| slug              | varchar   | 255       |           |           | 标识      |
| desc              | varchar   | 255       | Y         | NULL      | 简介      |
| times             | mediumint |           |           | 0         | 有效时长   |
| unit | enum | 'days','weeks','months','year' |        | days      | 单位      |
| is_qualification_pa | tinyint |           |           | 0         | 个人认证？ |
| is_qualification_co | tinyint |           |           | 0         | 企业认证？ |
| currency_type_id  | bigint    |           |           | 0         | 货币类型   |
| price             | decimal   |           |           | 0.00      | 价格      |
| created_at        | timestamp |           | Y         | NULL      | 创建时间   |
| updated_at        | timestamp |           | Y         | NULL      | 更新时间   |

### 功能权限表：permissions
| Field             | Type      | Length    | AllowNull | Default   | Comment   |
| :----             | :----     | :----     | :----     | :----     | :----     |
| id                | bigint    |           |           |           |           |
| name              | varchar   | 255       |           |           | 功能名称   |
| slug              | varchar   | 255       | Y         | NULL      | 标识      |
| amount            | mediumint |           |           | 0         | 数量限制   |
| created_at        | timestamp |           | Y         | NULL      | 创建时间   |
| updated_at        | timestamp |           | Y         | NULL      | 更新时间   |

### 签约项目后拥有功能权限表：role_permissions
| Field             | Type      | Length    | AllowNull | Default   | Comment   |
| :----             | :----     | :----     | :----     | :----     | :----     |
| id                | bigint    |           |           |           |           |
| permission_id     | bigint    |           |           |           | 功能权限ID |
| role_id           | bigint    |           |           |           | 签约项目ID |
| created_at        | timestamp |           | Y         | NULL      | 创建时间   |
| updated_at        | timestamp |           | Y         | NULL      | 更新时间   |

### 会员己经签约的项目表：role_users
| Field             | Type      | Length    | AllowNull | Default   | Comment   |
| :----             | :----     | :----     | :----     | :----     | :----     |
| id                | bigint    |           |           |           |           |
| user_id           | bigint    |           |           |           | 会员ID    |
| role_id           | bigint    |           |           |           | 签约项目ID |
| expires           | timestamp |           | Y         | NULL      | 到期时间   |
| created_at        | timestamp |           | Y         | NULL      | 创建时间   |
| updated_at        | timestamp |           | Y         | NULL      | 更新时间   |

### 会员已经拥有的功能权限表：user_permissions
| Field             | Type      | Length    | AllowNull | Default   | Comment   |
| :----             | :----     | :----     | :----     | :----     | :----     |
| id                | bigint    |           |           |           |           |
| user_id           | bigint    |           |           |           | 会员ID    |
| permission_id     | bigint    |           |           |           | 功能权限ID |
| expires           | timestamp |           | Y         | NULL      | 到期时间   |
| created_at        | timestamp |           | Y         | NULL      | 创建时间   |
| updated_at        | timestamp |           | Y         | NULL      | 更新时间   |
