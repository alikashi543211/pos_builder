<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsPagesModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_cms_pages';

    public function cmslang()
    {
        
        return $this->belongsTo('App\Models\CmsPagesLangModel','id','page_id');
    }
    public function addedUser(){
        return $this->belongsTo('App\Models\EmployeesModel', 'added_by', 'user_name');
    }
    public function modifiedUser(){
        return $this->belongsTo('App\Models\EmployeesModel', 'last_modified_by', 'user_name');
    }
   
}
