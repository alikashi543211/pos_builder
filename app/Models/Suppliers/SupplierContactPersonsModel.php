<?php

namespace App\Models\Suppliers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SupplierContactPersonsModel extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_supplier_contact_persons';

    public function addedBy()
    {
        return $this->belongsTo('App\Models\Acl\AdminUserModel', 'added_by', 'id');
    }

}

