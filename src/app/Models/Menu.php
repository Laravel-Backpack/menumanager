<?php

namespace Backpack\MenuCRUD\app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'menus';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    public static function boot()
    {
        parent::boot();

        static::updated(function ($menu) {
            if ($menu->wasChanged('placement')) {
                \Backpack\MenuCRUD\app\Models\Menu::where('placement', $menu->placement)
                    ->where('id', '!=', $menu->id)
                    ->update(['placement' => null]);
            }
        });
    }

    /**
     * Get all menu items, in a hierarchical collection.
     * Supports infinite levels of indentation.
     */
    public static function getTree($placement)
    {
        $menu = self::where('placement', $placement)->first();
        if ($menu == null) {
            return [];
        }

        $menus = \Backpack\MenuCRUD\app\Models\MenuItem::where('menu_id', $menu->id)->orderBy('lft', 'asc')->get()->keyBy('id');

        // Build the tree using reference method
        $flat = [];
        $tree = [];

        foreach ($menus as $primaryKey => $menu) {
            if (! isset($flat[$primaryKey])) {
                $flat[$primaryKey]['menu_data'] = $menu;
            }

            if ($menu->parent_id != null) {
                $flat[$menu->parent_id]['submenus'][$primaryKey] = &$flat[$primaryKey];
            } else {
                $tree[$primaryKey] = &$flat[$primaryKey];
            }
        }

        return $tree;
    }

    public function customActions($crud = false)
    {
        return '
        <a href="'.backpack_url('menu-item', $this->id).'" class="btn btn-sm btn-link"><i class="la la-list"></i> Menu Items</a>';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
