<?php

namespace Backpack\MenuCRUD\app\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use CrudTrait;

    protected $table = 'menu_items';
    protected $fillable = ['name', 'type', 'link', 'page_id', 'parent_id', 'menu_id'];

    public function parent()
    {
        return $this->belongsTo('Backpack\MenuCRUD\app\Models\MenuItem', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('Backpack\MenuCRUD\app\Models\MenuItem', 'parent_id');
    }

    public function page()
    {
        return $this->belongsTo('Backpack\PageManager\app\Models\Page', 'page_id');
    }

    public function menu()
    {
        return $this->belongsTo('Backpack\MenuCRUD\app\Models\Menu', 'menu_id');
    }

    public function url()
    {
        switch ($this->type) {
            case 'external_link':
                return $this->link;
                break;

            case 'internal_link':
                return is_null($this->link) ? '#' : url($this->link);
                break;

            default: //page_link
                if ($this->page) {
                    return url($this->page->slug);
                }
                break;
        }
    }

    public function getIsCurrentAttribute()
    {
        return $this->url() == url()->current();
    }
}
