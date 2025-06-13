<?php

namespace App\Models\Resume;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeWebsiteContactServicesModel extends Model
{
    use SoftDeletes;
    protected $table = "resume_website_contact_services";

    public function contactMessages(): HasMany
    {
        return $this->hasMany(ResumeWebsiteContactsModel::class, 'resume_website_contact_service_id', 'id');
    }
}
