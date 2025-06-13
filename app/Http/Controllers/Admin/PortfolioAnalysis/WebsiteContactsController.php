<?php

namespace App\Http\Controllers\Admin\PortfolioAnalysis;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeWebsiteContactsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsiteContactsController extends Controller
{
    public function index()
    {
        if (!validatePermissions('portfolio-analysis/website-contacts')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $websiteContacts = ResumeWebsiteContactsModel::where('created_by', $userId)
            ->orderBy('created_at', 'DESC')
            ->get();
        $data = [
            'pageTitle' => 'Website Contacts',
            'websiteContacts' => $websiteContacts
        ];

        return view('admin.portfolio_analysis.website_contacts.index')->with($data);
    }

    /** Edit Testimonial  */
    public function detail($id)
    {
        if (!validatePermissions('portfolio-analysis/website-contacts/detail/{id}')) {
            abort(403);
        }
        $data = [];

        $data['row'] = ResumeWebsiteContactsModel::where('id', $id)->first();

        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Testimonial record not found.'];
            return json_encode($response);
        }

        $html = view('admin.portfolio_analysis.website_contacts.detail')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }
}
