<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoriesModel extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_categories';
    protected $primaryKey = 'id';

    public function employee(){
        return $this->belongsTo(EmployeesModel::class, 'added_by', 'user_name');
    }

    public function categoriesLang()
    {
        return $this->belongsTo(CategoriesLangModel::class,'id','category_id');
    }

    public function EnglishCategoryName()
    {
        return $this->hasOne(CategoriesLangModel::class,'category_id','id')->whereLangId(7);
    }

    public function getCategoryIconAttribute($value)
    {
        return $value ? storage_url_for_banner($value) : url(env('STORAGEPATH') . '/blank-gallery.png');
    }

    public function getCategoryImageAttribute($value)
    {
        return $value ? storage_url_for_banner($value) : url(env('STORAGEPATH') . '/blank-gallery.png');
    }

    public function getCategoryBannerAttribute($value)
    {
        return $value ? storage_url_for_banner($value) : url(env('STORAGEPATH') . '/blank-gallery.png');
    }
}

