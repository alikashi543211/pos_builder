<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class AMLModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'tbl_aml';

    public function employee(){
        return $this->hasOne('App\Models\EmployeesModel', 'employee_ad_id', 'created_by');
    }
    
}
