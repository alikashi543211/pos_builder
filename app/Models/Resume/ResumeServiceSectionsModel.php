<?php

namespace App\Models\Resume;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class ResumeServiceSectionsModel extends Model
{
    use HasFactory, SoftDeletes;
    // use \OwenIt\Auditing\Auditable;

    protected $table = 'resume_service_sections';


    public function getServiceThumbnailAttribute($value)
    {
        if (isset($value)) {
            if (File::exists(env('BASE_MEDIA_PATH') . $value)) {
                return env('BASE_MEDIA_URL') . $value;
            }
        }
        return gallery_photo('empty.png', 'avatars');
    }
}
