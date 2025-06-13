<?php

namespace App\Models\Resume;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeInterestSectionsModel extends Model
{
    use HasFactory, SoftDeletes;
    // use \OwenIt\Auditing\Auditable;

    protected $table = 'resume_interest_sections';
}
