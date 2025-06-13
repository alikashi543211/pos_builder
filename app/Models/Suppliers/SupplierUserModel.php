<?php

namespace App\Models\Suppliers;

use App\Models\DesignationsModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierUserModel extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_supplier_users';

    public function designation(){
        return $this->belongsTo(DesignationsModel::class, 'supplier_user_designation_id', 'id');
    }

    public function supplier(){
        return $this->belongsTo(SupplierModel::class, 'supplier_id', 'id');
    }

    public function SupplierUserRole(){
        return $this->belongsTo(SupplierUserRoleModel::class, 'supplier_user_role_id', 'supplier_role_id');
    }

}
