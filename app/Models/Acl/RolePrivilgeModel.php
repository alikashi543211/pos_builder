<?php

namespace App\Models\Acl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Acl\AdminUserRoleModel;
use Auth;
use DB;
use OwenIt\Auditing\Contracts\Auditable;
class RolePrivilgeModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_role_privileges';
    protected $primaryKey = 'ID';
    
    public function role()
    {
        return $this->belongsTo('App\Models\Acl\RoleModel', 'role_ID', 'ID');
    }

    public function users() {

        return $this->belongsToMany('App\Models\Acl\AdminUserModel','tbl_admin_user_roles');
            
     }

     public function module()
    {
        return $this->hasOne('App\Models\Acl\ModuleModel', 'ID', 'module_ID');
    }

    public static function hasPermission($role_ID,$currentUri){

        
        $row = RolePrivilgeModel::join('tbl_roles', 'tbl_role_privileges.role_ID', '=', 'tbl_roles.ID')
        ->join('tbl_modules', 'tbl_role_privileges.module_ID', '=', 'tbl_modules.ID')
        ->where('role_ID', $role_ID)
        ->where('route', $currentUri)
        ->get()
        ->first();
        return $row;
    }

    public static function drawLeftMenu($roleIds,$moduleCatId){
        $roleIds = AdminUserRoleModel::where('admin_ID',Auth::guard('admin')->user()->id)->get()->pluck('role_ID')->toArray();
        return RolePrivilgeModel::select('tbl_modules.*')
        ->join('tbl_roles', 'tbl_role_privileges.role_ID', '=', 'tbl_roles.ID')
        ->join('tbl_modules', 'tbl_role_privileges.module_ID', '=', 'tbl_modules.ID')
        ->whereIn('role_ID', $roleIds)
        ->where('module_category_ID', $moduleCatId)
        ->where('show_in_menu', 1)
        ->orderBy('tbl_modules.display_order')
        ->distinct('module_ID')
        ->get();
    }
}
