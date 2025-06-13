<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriesLangModel extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_categories_lang';
    protected $primaryKey = 'id';

    public function employee(){
        return $this->belongsTo(EmployeesModel::class, 'added_by', 'user_name');
    }

    public function category(){
        return $this->belongsTo(CategoriesModel::class, 'category_id', 'id');
    }

    public function ParentCategory(){
        return $this->belongsTo(CategoriesLangModel::class, 'parent_id', 'category_id');
    }

    public function ParentCategoryEnglish(){
        return $this->belongsTo(CategoriesLangModel::class, 'parent_id', 'category_id')->whereLangId(7);
    }

}

