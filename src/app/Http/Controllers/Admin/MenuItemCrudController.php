<?php

namespace Backpack\MenuCRUD\app\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

class MenuItemCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    private $menu_id;
    private $menu;

    public function setup()
    {
        $this->menu_id = \Route::current()->parameter('menu_id');
        $this->menu = \Backpack\MenuCRUD\app\Models\Menu::find($this->menu_id);
        if ($this->menu == null) {
            abort(404);
        }

        $this->crud->setModel("Backpack\MenuCRUD\app\Models\MenuItem");
        $this->crud->setRoute(config('backpack.base.route_prefix').'/menu-item');
        $this->crud->setEntityNameStrings('menu item', 'menu items');
        $this->crud->addClause('orderBy', 'lft', 'asc');
        $this->crud->addClause('where', 'menu_id', $this->menu_id);

        $this->crud->setHeading('menu items'." - <a href='".backpack_url('menu/'.$this->menu_id.'/show')."'>".$this->menu->name.'</a>', false);

        $this->crud->enableReorder('name', 30); // Basically infinite

        $this->crud->operation('list', function () {
            $this->crud->addColumn([
                'name' => 'name',
                'label' => 'Label',
            ]);
            $this->crud->addColumn([
                'label' => 'Parent',
                'type' => 'select',
                'name' => 'parent_id',
                'entity' => 'parent',
                'attribute' => 'name',
                'model' => "\Backpack\MenuCRUD\app\Models\MenuItem",
            ]);
        });

        $this->crud->operation(['create', 'update'], function () {
            $this->crud->addField([
                'name' => 'name',
                'label' => 'Label',
            ]);
            $this->crud->addField([
                'name'  => 'menu_id',
                'type'  => 'hidden',
                'value' => $this->menu_id,
            ]);
            $this->crud->addField([
                'label' => 'Parent',
                'type' => 'select',
                'name' => 'parent_id',
                'entity' => 'parent',
                'attribute' => 'name',
                'model' => "\Backpack\MenuCRUD\app\Models\MenuItem",
            ]);
            $this->crud->addField([
                'name' => ['type', 'link', 'page_id'],
                'label' => 'Type',
                'type' => 'page_or_link',
                'page_model' => '\Backpack\PageManager\app\Models\Page',
            ]);
        });
    }
}
