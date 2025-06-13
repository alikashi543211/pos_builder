<?php

namespace App\Http\Controllers\Admin\PortfolioAnalysis;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeWebsiteContactEmailsModel;
use Illuminate\Support\Facades\Auth;

class WebsiteContactEmailsController extends Controller
{
    public function index()
    {
        if (!validatePermissions('portfolio-analysis/website-contact-emails')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $contactEmails = ResumeWebsiteContactEmailsModel::where('created_by', $userId)
            ->orderBy('created_at', 'DESC')
            ->get();
        $data = [
            'pageTitle' => 'Contact Emails',
            'contactEmails' => $contactEmails
        ];

        return view('admin.portfolio_analysis.website_contact_emails.index')->with($data);
    }
}
