<?php

/*
|--------------------------------------------------------------------------
| Backpack\MenuCRUD Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are
| handled by the Backpack\MenuCRUD package.
|
*/

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => ['web', config('backpack.base.middleware_key', 'admin')],
    'namespace' => 'Backpack\MenuCRUD\app\Http\Controllers\Admin',
], function () {
    Route::crud('menu-item/{menu_id}', 'MenuItemCrudController');
    Route::crud('menu', 'MenuCrudController');
});
