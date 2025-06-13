<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceUnitMasterModel extends Model
{
    use HasFactory;
    protected $table = 'price_unit_master';
    protected $primaryKey = 'id';
}
