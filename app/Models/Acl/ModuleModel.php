<?php

namespace App\Models\Acl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class ModuleModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_modules';
    protected $primaryKey = 'ID';

    public function category()
    {
        return $this->hasOne('App\Models\Acl\ModuleCategoryModel', 'ID', 'module_category_ID');
    }
    public function roles()
    {
        return $this->hasMany('App\Models\Acl\RolePrivilgeModel','module_ID', 'ID');
    }
}