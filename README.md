# Permission

[![Coverage report](http://gitlab.awema.pl/awema-pl/module-permission/badges/master/coverage.svg)](https://www.awema.pl/)
[![Build status](http://gitlab.awema.pl/awema-pl/module-permission/badges/master/build.svg)](https://www.awema.pl/)
[![Composer Ready](https://www.awema.pl/awema-pl/module-permission/status.svg)](https://www.awema.pl/)
[![Downloads](https://www.awema.pl/awema-pl/module-permission/downloads.svg)](https://www.awema.pl/)
[![Last version](https://www.awema.pl/awema-pl/module-permission/version.svg)](https://www.awema.pl/) 


PHP Roles & Permissions package. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require awema-pl/module-permission
```

The package will automatically register itself.

You can publish migration with:

```bash
php artisan vendor:publish --provider="AwemaPL\Permission\PermissionServiceProvider" --tag="migrations"
```

After migration has been published you can create tables by running:

```bash
php artisan migrate
```

You can publish package views:

```bash
php artisan vendor:publish --provider="AwemaPL\Permission\PermissionServiceProvider" --tag="views"
```

Run seeder for roles and permissions tables:

```bash
php artisan db:seed --class="AwemaPL\Permission\Seeds\PermissionTablesSeeder"
```

## Configuration

You can set up routes path and naming prefixes. First publish config:

```bash
php artisan vendor:publish --provider="AwemaPL\Permission\PermissionServiceProvider" --tag="config"
```

```php
'routes' => [
    // roles routes prefixes (path & naming)
    'roles_prefix' => 'roles',
    'roles_name_prefix' => 'roles.',

    // permissions routes prefixes
    'permissions_prefix' => 'permissions',
    'permissions_name_prefix' => 'permissions.',
]
```

## Usage

Add to routes/web.php

```php
Permission::routes();
```

Package will register several routes:

```
+--------+----------+--------------------+--------------------+-----------------------------------------------------------+------------+
| Domain | Method   | URI                | Name               | Action                                                    | Middleware |
+--------+----------+--------------------+--------------------+-----------------------------------------------------------+------------+
|        | GET|HEAD | permissions        | permissions.index  | AwemaPL\Permission\Controllers\PermissionController@index  | web        |
|        | POST     | permissions        | permissions.store  | AwemaPL\Permission\Controllers\PermissionController@store  | web        |
|        | POST     | permissions/assign | permissions.assign | AwemaPL\Permission\Controllers\PermissionController@assign | web        |
|        | POST     | permissions/revoke | permissions.revoke | AwemaPL\Permission\Controllers\PermissionController@revoke | web        |
|        | GET|HEAD | roles              | roles.index        | AwemaPL\Permission\Controllers\RoleController@index        | web        |
|        | POST     | roles              | roles.store        | AwemaPL\Permission\Controllers\RoleController@store        | web        |
|        | POST     | roles/assign       | roles.assign       | AwemaPL\Permission\Controllers\RoleController@assign       | web        |
|        | POST     | roles/revoke       | roles.revoke       | AwemaPL\Permission\Controllers\RoleController@revoke       | web        |
+--------+----------+--------------------+--------------------+-----------------------------------------------------------+------------+
```

```php
# Routes for permissions management
'permissions.'

# Routes for roles management
'roles.'
```

Add `AwemaPL\Permission\Traits\HasRoles` trait to your `User` model(s):

```php
use AwemaPL\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

## Testing

You can run the tests with:

```bash
composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [:author_name][link-author]
- [All Contributors][link-contributors]

## License

GNU General Public License v3.0. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/awema-pl/module-permission.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/awema-pl/module-permission.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/awema-pl/module-permission/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/awema-pl/module-permission
[link-downloads]: https://packagist.org/packages/awema-pl/module-permission
[link-travis]: https://travis-ci.org/awema-pl/module-permission
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/awema-pl
[link-contributors]: ../../contributors]
