# Backpack\MenuCRUD

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

An admin panel for menu items on Laravel 5 or Laravel 6, using [Backpack\CRUD](https://github.com/Laravel-Backpack/crud). Add, edit, reorder, nest, rename menu items and link them to [Backpack\PageManager](https://github.com/Laravel-Backpack/pagemanager) pages, external link or custom internal link. 

Usually used for front-end menus, not back-end. Adding a menu item in MenuCRUD will not add an item in the admin sidebar, though you can easily customize Backpack\Base's ```sidebar.blade.php``` to show all menu items in the db, if you'd like.


> ### Security updates and breaking changes
> Please **[subscribe to the Backpack Newsletter](http://backpackforlaravel.com/newsletter)** so you can find out about any security updates, breaking changes or major features. We send an email every 1-2 months.


## Install

This needs [Backpack\PageManager](https://github.com/Laravel-Backpack/pagemanager) to be installed first. If you haven't already, please do that first.

Since MenuCRUD is just a Backpack\CRUD example, you can choose to install it one of two ways.

**(A) Download and place files in your application** (recommended)

or

**(B) As a package**

The only PRO of installing it as a package is that you may benefit from updates. But the reality is there is very little (if any) bug fixing to do, so you probably won't need to update it, ever.



#### Installation type (A) - download


1) [Download the latest build](https://github.com/Laravel-Backpack/MenuCRUD/archive/master.zip).

2) Paste the 'app', 'config', and 'database' folders over your projects (merge them). No file overwrite warnings should come up.

3) Replace all mentions of 'Backpack\MenuCRUD\app' in the pasted files with your application's namespace ('App' if you haven't changed it):
- app/Http/Controllers/Admin/MenuItemCrudController.php
- app/Http/Controllers/Admin/MenuCrudController.php
- app/Http/Requests/Request/MenuRequest.php
- app/Models/MenuItem.php
- app/Models/Menu.php

4) Run the migration to have the database table we need:
```
php artisan migrate
```

5) Add MenuCRUD to your routes file:

```
Route::group(['prefix' => config('backpack.base.route_prefix', 'admin'), 'middleware' => ['web', 'auth'], 'namespace' => 'Admin'], function () {
    // Backpack\MenuCRUD
    Route::crud('menu-item/{menu_id}', 'MenuItemCrudController');
    Route::crud('menu', 'MenuCrudController');
});
```

6) [optional] Add a menu item for it in resources/views/vendor/backpack/base/inc/sidebar.blade.php or menu.blade.php:

```html
<li><a href="{{ backpack_url('menu') }}"><i class="la la-list"></i> <span>Menu</span></a></li>
```



#### Installation type (B) - package

1) In your terminal, run:

``` bash
composer require backpack/menucrud
```

2) Publish the migration:

```
php artisan vendor:publish --provider="Backpack\MenuCRUD\MenuCRUDServiceProvider"
```

3) Run the migration to have the database table we need:

```
php artisan migrate
```

4) [optional] Add a menu item for it in resources/views/vendor/backpack/base/inc/sidebar_content.blade.php or menu.blade.php:

```
php artisan backpack:add-sidebar-content "<li class='nav-item'><a class='nav-link' href='{{ backpack_url('menu') }}'><i class='nav-icon la la-list'></i> <span>Menu</span></a></li>"
```

#### Usage in your template

Here's a simple example to use in your frontend:

Create a recursive single menu item renderer in blade:

`menu_renderer.blade.php`

```
<li>
    <a href="{{ $menu['menu_data']->url() }}">{{ $menu['menu_data']->name }}@if ($menu['menu_data']->is_current) (current) @endif</a>
    @isset($menu['submenus'])
        @foreach ($menu['submenus'] as $item)
            <ul>
                @include('frontend.components.menu_renderer', ['menu' => $item])
            </ul>
        @endforeach
    @endisset
</li>
```

Create a menu wrapper which will call the first single menu renderer:

`menu_wrapper.blade.php`

```
<ul>
    @foreach ($menus as $item)
        @include('frontend.components.menu_renderer', ['menu' => $item])
    @endforeach
</ul>
```

Call the view*, for example, from Controller:

```
public function rendermenu()
    {
        return view('frontend.components.menu_wrapper', ['menus' => \App\Models\Menu::getTree('mobile_menu')]);
    }
```

`getTree()` method above needs a string parameter named `placement`. It is used to find / get the menu and its items that's set to that placement. You can set the placement options in the `config/backpack/menu.php` file, for example:

```
<?php

return [
    'placement' => [
        "header_menu" => "Header Menu",
        "mobile_menu" => "Mobile Menu",
        "footer_menu" => "Footer Menu",
    ]
];
```

So basically those array keys should be constant throughout the system development and deployment.

*For Installation type (B), change the namespace to `Backpack\MenuCRUD\app\Models`.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
composer test
```

## Overwriting functionality

If you've used installation type A and need to modify how this works in a project: 
- create a ```routes/backpack/menucrud.php``` file; the package will see that, and load _your_ routes file, instead of the one in the package; 
- create controllers/models that extend the ones in the package, and use those in your new routes file;
- modify anything you'd like in the new controllers/models;

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hello@tabacitu.ro instead of using the issue tracker.

Please **[subscribe to the Backpack Newsletter](http://backpackforlaravel.com/newsletter)** so you can find out about any security updates, breaking changes or major features. We send an email every 1-2 months.

## Credits

- [Cristian Tabacitu][link-author]
- [All Contributors][link-contributors]

## License

Backpack is free for non-commercial use and 49 EUR/project for commercial use. Please see [License File](LICENSE.md) and [backpackforlaravel.com](https://backpackforlaravel.com/#pricing) for more information.

## Hire us

We've spend more than 10.000 hours creating, polishing and maintaining administration panels on Laravel. We've developed e-Commerce, e-Learning, ERPs, social networks, payment gateways and much more. We've worked on admin panels _so much_, that we've created one of the most popular software in its niche - just from making public what was repetitive in our projects.

If you are looking for a developer/team to help you build an admin panel on Laravel, look no further. You'll have a difficult time finding someone with more experience & enthusiasm for this. This is _what we do_. [Contact us - let's see if we can work together](https://backpackforlaravel.com/need-freelancer-or-development-team).


[ico-version]: https://img.shields.io/packagist/v/backpack/MenuCRUD.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/Laravel-Backpack/MenuCRUD/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/Laravel-Backpack/MenuCRUD.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/Laravel-Backpack/MenuCRUD.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/backpack/MenuCRUD.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/backpack/MenuCRUD
[link-travis]: https://travis-ci.org/Laravel-Backpack/MenuCRUD
[link-scrutinizer]: https://scrutinizer-ci.com/g/Laravel-Backpack/MenuCRUD/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/Laravel-Backpack/MenuCRUD
[link-downloads]: https://packagist.org/packages/backpack/MenuCRUD
[link-author]: https://github.com/tabacitu
[link-contributors]: ../../contributors
