<?php

namespace App\Models\Acl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class AdminUserRoleModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_admin_user_roles';
    protected $primaryKey = 'ID';

    public function user()
    {
        return $this->belongsTo('App\Models\Acl\AdminUserModel', 'admin_ID', 'ID');
    }
 
    public function role()
    {
        return $this->belongsTo('App\Models\Acl\RoleModel', 'role_ID', 'ID');
    }
}
