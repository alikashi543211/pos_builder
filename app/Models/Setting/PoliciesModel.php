<?php

namespace App\Models\Setting;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PoliciesModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_policies';
    protected $primaryKey = 'id';

    protected $fillable = [
        'policy_title',
        'long_description',
        'short_description',
        'order_number',
    ];
}
