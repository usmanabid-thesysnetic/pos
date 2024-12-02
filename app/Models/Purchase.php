<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['supplier_id', 'ref_no', 'total', 'paid', 'balance', 'date'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
