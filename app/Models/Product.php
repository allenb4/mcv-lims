<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    public $guarded=[];
    public $appends=[
        'initial_quantity',
        'in_quantity',
        'out_quantity',
        'purchase_quantity',
        'consumption_quantity',
        'stock_quantity'
    ];

    /**
     * Relationships
     */

    public function purchases()
    {
        return $this->hasMany(PurchaseProduct::class,'product_id','id')->orderBy('id','asc');
    }

    public function adjustments()
    {
        return $this->hasMany(AdjustmentProduct::class,'product_id','id')->orderBy('id','asc');
    }

    public function transfers()
    {
        return $this->hasMany(TransferProduct::class,'product_id','id')->orderBy('id','asc');
    }

    public function consumptions()
    {
        return $this->hasMany(ProductConsumption::class,'product_id','id')->orderBy('id','asc');
    }

    public function branches()
    {
        return $this->hasMany(BranchProduct::class,'product_id','id')->orderBy('id','asc');
    }

    /**
     * Accessories
     */

    public function getInitialQuantityAttribute()
    {
        return $this->branches()->sum('initial_quantity');
    }

    public function getInQuantityAttribute()
    {
        return $this->adjustments()->where('type',1)->sum('quantity');
    }

    public function getOutQuantityAttribute()
    {
        return $this->adjustments()->where('type',2)->sum('quantity');
    }

    public function getPurchaseQuantityAttribute()
    {
        return $this->purchases()->sum('quantity');
    }

    public function getConsumptionQuantityAttribute()
    {
        return $this->consumptions()->sum('quantity');
    }

    public function getStockQuantityAttribute()
    {
        return $this->initial_quantity+$this->in_quantity+$this->purchase_quantity-$this->out_quantity-$this->consumption_quantity;
    }
}
