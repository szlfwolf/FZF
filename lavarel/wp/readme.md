# Laravel PHP Framework

[![Build Status](https://travis-ci.org/laravel/framework.svg)](https://travis-ci.org/laravel/framework)
[![Total Downloads](https://poser.pugx.org/laravel/framework/d/total.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/framework/v/stable.svg)](https://packagist.org/packages/laravel/framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/framework/v/unstable.svg)](https://packagist.org/packages/laravel/framework)
[![License](https://poser.pugx.org/laravel/framework/license.svg)](https://packagist.org/packages/laravel/framework)

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).


# soft 
wampserver 2.5 + (安装到c:\wamp目录)
# enviroment
1.系统环境变量添加php目录(c:\wamp\bin\php\php5.5.12\)
    1.1 修改php.exe的php.ini [c:\wamp\bin\php\php5.5.12\php.ini]
    1.2 修改apache的php.ini [c:\wamp\bin\apache\apache2.4.9\bin\php.ini]
        开启ssl：
            extension=php_openssl.dll
        开启XDebug：
            xdebug.remote_enable = on
            xdebug.remote_autostart = on

2.安装composer
    <code>
    copy tools\composer.phar c:\wamp\bin\php\php5.5.12\ 
    cd c:\wamp\bin\php\php5.5.12\ 
    echo @echo off > composer.bat
    echo. >> composer.bat
    echo "php "%~dp0composer.phar" %*" >> composer.bat
    composer -V
    </code>
3. 更改composer的国内镜像
    <code> composer config -g repo.packagist composer https://packagist.phpcomposer.com </code>
4. 更新依赖
    <code> composer update</code>
# run

1.导入数据库
<code>mysql -u root < xiaoxi.sql</code>
2.运行
<code>php artisan serve</code>

## template

http://crm.jftrust.cn[286/123456abc]

http://abc.hzhuainuo.com/administrator
http://abc.hzhuainuo.com/operationcenter
http://abc.hzhuainuo.com/memberunitcenter/signin
http://abc.hzhuainuo.com/staff

http://abc.hzhuainuo.com/yuangongstaff[xiaoxiao@qq.com/123456]

