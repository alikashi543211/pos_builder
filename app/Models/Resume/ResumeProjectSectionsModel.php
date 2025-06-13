<?php

namespace App\Models\Resume;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;

class ResumeProjectSectionsModel extends Model
{
    use HasFactory, SoftDeletes;
    // use \OwenIt\Auditing\Auditable;

    protected $table = 'resume_project_sections';

    /**
     * Get the companyExperience that owns the ResumeProjectSectionsModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function projectCategory(): BelongsTo
    {
        return $this->belongsTo(ResumeProjectCategoriesModel::class, 'resume_project_category_id', 'id');
    }

    public function getProjectThumbnailAttribute($value)
    {
        if (isset($value)) {
            if (File::exists(env('BASE_MEDIA_PATH') . $value)) {
                return env('BASE_MEDIA_URL') . $value;
            }
        }
        return gallery_photo('empty.png', 'avatars');
    }
}
