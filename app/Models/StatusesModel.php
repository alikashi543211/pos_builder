<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class StatusesModel extends Model implements Auditable
{
    use HasFactory,SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $table = 'statuses';
    protected $primaryKey = 'id';

    public function getStatusNameAttribute($value)
    {
        $class = 'light-info';
        $title = 'Status of supplier profile.';
        if($value == 'In Process'){
            $class = "light-info";
            $title = 'The product status is currently in process.';
        }elseif($value == 'In Review'){
            $class = "light-primary";
            $title = 'The product status is currently in review.';
        }elseif($value == 'In Modification'){
            $class = "light-danger";
            $title = 'The product status is currently in modification.';
        }elseif($value == 'Approved'){
            $class = "light-success";
            $title = 'The product status is currently in approved.';
        }elseif($value == 'Disabled'){
            $class = "light-secondary";
            $title = 'The product status is currently in disabled.';
        }

        return '<span data-bs-toggle="tooltip" title="'. $title .'" class="badge badge-pill badge-' . $class . '">' . $value . '</span>';
    }

}
