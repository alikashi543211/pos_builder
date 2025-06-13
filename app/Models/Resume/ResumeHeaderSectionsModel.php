<?php

namespace App\Models\Resume;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use OwenIt\Auditing\Contracts\Auditable;

class ResumeHeaderSectionsModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $appends = [
        'age',
        'experience'
    ];
    // use \OwenIt\Auditing\Auditable;

    protected $table = 'resume_header_sections';

    public function getDisplayPictureAttribute($value)
    {
        if (isset($value)) {
            if (File::exists(env('BASE_MEDIA_PATH') . $value)) {
                return env('BASE_MEDIA_URL') . $value;
            }
        }
        return url('assets/images/default_user.png');
    }

    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            $dateOfBirth = $this->date_of_birth;

            // Convert the date of birth into a DateTime object
            $dob = new DateTime($dateOfBirth);

            // Get the current date
            $today = new DateTime();

            // Calculate the age using the diff method
            $age = $today->diff($dob)->y;

            return $age . " years age";
        }
        return '';
    }

    public function getExperienceAttribute()
    {
        if ($this->date_of_joining) {
            $dateOfJoining = $this->date_of_joining;

            // Convert the date of birth into a DateTime object
            $dob = new DateTime($dateOfJoining);

            // Get the current date
            $today = new DateTime();

            // Calculate the age using the diff method
            $exp = $today->diff($dob)->y;

            return $exp . "+ years";
        }
        return '';
    }

    public function getBackgroundImageAttribute($value)
    {
        if (isset($value)) {
            if (File::exists(env('BASE_MEDIA_PATH') . $value)) {
                return env('BASE_MEDIA_URL') . $value;
            }
        }
        return gallery_photo('empty.png', 'avatars');
    }
}
