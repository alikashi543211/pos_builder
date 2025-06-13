<?php

namespace App\Models\Acl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class RoleModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_roles';
    protected $primaryKey = 'ID';

    public function permissions()
    {
        return $this->hasMany('App\Models\Acl\RolePrivilgeModel', 'role_ID', 'ID');
    }

    public function modules()
    {
        return $this->hasMany('App\Models\Acl\RolePrivilgeModel','role_ID', 'ID');
    }
    public function users()
    {
        return $this->belongsToMany('App\Models\Acl\AdminUserModel', 'tbl_admin_user_roles');
    }

    public static function getRolesByCategoryId($categoryId)
    {
        //
    }
}

