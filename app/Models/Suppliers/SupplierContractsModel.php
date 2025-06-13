<?php

namespace App\Models\Suppliers;

use App\Models\EmployeesModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SupplierContractsModel extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_supplier_contracts';

    public function addedBy()
    {
        return $this->belongsTo(EmployeesModel::class, 'added_by', 'user_name');
    }

}

