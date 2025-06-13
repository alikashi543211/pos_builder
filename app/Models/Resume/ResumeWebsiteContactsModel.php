<?php

namespace App\Models\Resume;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResumeWebsiteContactsModel extends Model
{
    use SoftDeletes;
    protected $table = "resume_website_contacts";

    public function contactService()
    {
        return $this->belongsTo(ResumeWebsiteContactServicesModel::class, 'resume_website_contact_service_id', 'id');
    }

    public function websiteContactEmail()
    {
        return $this->belongsTo(ResumeWebsiteContactEmailsModel::class, 'resume_website_contact_email_id', 'id');
    }
}
