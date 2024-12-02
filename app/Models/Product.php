<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'supplier_id', 'code', 'name', 'cost', 'price', 'currency', 'quantity', 'image', 'description'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function saleProducts()
    {
        return $this->hasMany(SaleProduct::class);
    }

    public function returnProducts()
    {
        return $this->hasMany(ReturnProduct::class);
    }
}
