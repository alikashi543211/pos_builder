<?php

namespace App\Models\Resume;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class ResumeProjectSectionImagesModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'resume_project_section_images';

    public function getImagePathAttribute($value)
    {
        if (isset($value)) {
            if (File::exists(env('BASE_MEDIA_PATH') . $value)) {
                return env('BASE_MEDIA_URL') . $value;
            }
        }
        return gallery_photo('empty.png', 'avatars');
    }
}
