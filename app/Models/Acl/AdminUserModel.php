<?php

namespace App\Models\Acl;

use App\Models\AdminDetail;
use App\Models\EmployeesModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminUserModel extends Authenticatable implements Auditable
{
    use SoftDeletes;
    use HasFactory;
    use \OwenIt\Auditing\Auditable;


    protected $table = 'tbl_admin';
    protected $primaryKey = 'id';

    protected $hidden = ['password', 'remember_token'];


    public function employee(){
        return $this->hasOne(EmployeesModel::class, 'user_name', 'user_name');
    }

    public function roles(){
        return $this->hasOne('App\Models\Acl\RoleModel', 'tbl_admin_user_roles');
    }

    public function userroles(){
        return $this->hasMany('App\Models\Acl\AdminUserRoleModel', 'admin_ID', 'id');
    }



}
