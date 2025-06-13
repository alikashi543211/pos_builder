<?php

namespace App\Models\Suppliers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SupplierAssignedUsersModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_supplier_assigned_users';

    public function member()
    {
        return $this->belongsTo('App\Models\Acl\AdminUserModel', 'user_id', 'id');
    }
    public function assignedBy()
    {
        return $this->belongsTo('App\Models\Acl\AdminUserModel', 'assigned_by', 'id');
    }

}

