<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'code', 'company_name', 'contact_person', 'email', 'phone',
        'address', 'city', 'province', 'npwp', 'status'
    ];

    public $timestamps = true;

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'supplier_id', 'id');
    }
}
