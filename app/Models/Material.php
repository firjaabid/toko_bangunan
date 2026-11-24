<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Material extends Model
{
    protected $table = 'materials';

    protected $fillable = [
        'supplier_id', 'sku', 'name', 'category', 'unit', 'price', 'stock'
    ];

    public $timestamps = true;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }
}
