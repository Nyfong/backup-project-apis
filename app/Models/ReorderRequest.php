<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReorderRequest extends Model
{
    protected $fillable = ['product_id', 'quantity', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}