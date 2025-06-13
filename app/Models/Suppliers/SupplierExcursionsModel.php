<?php

namespace App\Models\Suppliers;

use App\Models\Acl\AdminUserModel;
use App\Models\EmployeesModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SupplierExcursionsModel extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_supplier_excursions';

    public function assignedBy()
    {
        return $this->belongsTo(EmployeesModel::class, 'assigned_by', 'user_name');
    }
    public function SupplierType()
    {
        return $this->belongsTo(SupplierTypeModel::class, 'excursion_type', 'supplier_type_id');
    }

}

