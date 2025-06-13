<?php

namespace App\Models\Acl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
class ModuleCategoryModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_module_categories';
    protected $primaryKey = 'ID';

    public function modules()
    {
        return $this->hasMany('App\Models\Acl\ModuleModel', 'module_category_ID', 'ID');
    }
}
