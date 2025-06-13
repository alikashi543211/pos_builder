<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaysOfWeekModel extends Model
{
    use HasFactory;
    protected $table = 'days_of_week';
    protected $primaryKey = 'id';

}
