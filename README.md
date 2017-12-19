# lky_vendor/laravel_command

## 安装

使用 Composer

``` bash
$ composer require lky_vendor/laravel_command
```

## 使用前的配置
在config/app.php中找到providers,并添加:
``` php
lky_vendor\laravel_command\laravel_commandServiceProvider::class,
```

## 配置
安装后请务必执行以下命令:
``` php
php artisan vendor:publish
```
## 使用

``` bash
$ php artisan yu:model #对应数据库生成表
```

