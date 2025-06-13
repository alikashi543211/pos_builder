<?php

namespace App\Models\Resume;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeServiceSectionDescriptionsModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resume_service_section_descriptions';
}
