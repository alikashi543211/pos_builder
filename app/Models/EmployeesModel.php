<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeesModel extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_employees';
    protected $primaryKey = 'ID';

    public static function getAllEmployees($keyword = '')
    {

        return EmployeesModel::where('hide_employee_in_directory', '0')->when($keyword, function ($query, $keyword) {
            $len = strlen($keyword);
            $condition  =  ($len == 1) ? $keyword . '%' : '%' . $keyword . '%';
            return $query->where('full_name', 'LIKE', $condition);
        })->orderBy('employee_ad_id', 'ASC')->get();
    }

    public function department()
    {
        return $this->belongsTo(DepartmentsModel::class, 'department_id', 'id');
    }
    public function teams()
    {
        return $this->hasMany('App\Models\TeamsEmployeesModel', 'employee_ad_id', 'employee_ad_id');
    }
    public function designation()
    {
        return $this->belongsTo(DesignationsModel::class, 'designation_id', 'id');
    }
    public function UserCity()
    {
        return $this->belongsTo(CitiesModel::class, 'city_id', 'city_id');
    }
    public function UserCountry()
    {
        return $this->belongsTo(CountriesModel::class, 'country', 'country_id');
    }

    public function getFullNameAttribute()
    {
        if ($this->first_name || $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        }

        return null;
    }

    public function getCustomPhotoAttribute($value)
    {
        // return $value ? storage_url($value) : storage_url('blank.png');
        return $value ? url('uploads/' . DIRECTORY_SEPARATOR . $value) : url('assets/blank.png');
    }
}
