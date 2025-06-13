<?php

namespace App\Http\Controllers\Admin\PortfolioAnalysis;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeWebsiteContactServicesModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WebsiteContactServicesController extends Controller
{
    public function index()
    {
        if (!validatePermissions('portfolio-analysis/website-contact-services')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $contactServices = ResumeWebsiteContactServicesModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();
        $data = [
            'pageTitle' => 'Contact Services',
            'activeJobPosition' => $activeJobPosition,
            'contactServices' => $contactServices
        ];

        return view('admin.portfolio_analysis.website_contact_services.index')->with($data);
    }

    public function create()
    {
        if (!validatePermissions('portfolio-analysis/website-contact-services/add')) {
            abort(403);
        }
        $data = [];
        $html = view('admin.portfolio_analysis.website_contact_services.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit Contact Service  */
    public function edit($id)
    {
        if (!validatePermissions('portfolio-analysis/website-contact-services/edit/{id}')) {
            abort(403);
        }
        $data = [];
        $data = [
            'pageTitle' => 'Contact Service Edit'
        ];

        $data['row'] = ResumeWebsiteContactServicesModel::where('id', $id)->first();

        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Contact Service record not found.'];
            return json_encode($response);
        }

        $html = view('admin.portfolio_analysis.website_contact_services.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }

    /** Save New live_link */
    public function store(Request $request)
    {
        if (!validatePermissions('portfolio-analysis/website-contact-services/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'service_name' => 'required|string|min:3|max:255',
        ], [
            'service_name.required' => 'The service name field is required.',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }
        try {
            /** Create or Update Resume live_link Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if (ResumeWebsiteContactServicesModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('service_name', $sanitizedInputs['service_name'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Service name already exists.'];
                return json_encode($response);
            }
            $live_link_section = new ResumeWebsiteContactServicesModel();
            $live_link_section->job_position_id = $activeJobPositionId;
            $live_link_section->service_name = $sanitizedInputs['service_name'] ?? null;
            $live_link_section->is_active = 1;
            $live_link_section->created_by = $userId;
            $live_link_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Contact Service saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Contact Service Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the tesimonial.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Contact Service Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the tesimonial.'];
            return json_encode($response);
        }
    }

    /** Update Existing live_link */
    public function update(Request $request, $id)
    {
        if (!validatePermissions('portfolio-analysis/website-contact-services/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'service_name' => 'required|string|min:3|max:255',
        ], [
            'service_name.required' => 'The service name field is required.',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            // Validate id exists or not
            $live_link_section = ResumeWebsiteContactServicesModel::whereId(sanitizeInput($id, 'int'))->first();
            if (!$live_link_section) {
                $response = ['responseCode' => 0, 'msg' => 'Contact Service record not found.'];
                return json_encode($response);
            }

            /** Create or Update Resume live_link Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;

            // Check Name already exist or not
            if (ResumeWebsiteContactServicesModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('service_name', $sanitizedInputs['service_name'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Service name already exists.'];
                return json_encode($response);
            }
            // Update data
            $live_link_section->job_position_id = $activeJobPositionId;
            $live_link_section->service_name = $sanitizedInputs['service_name'] ?? null;
            $live_link_section->is_active = 1;
            $live_link_section->created_by = $userId;
            $live_link_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Contact Service saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Contact Service Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the Testimonial.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Contact Service Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the Testimonial.'];
            return json_encode($response);
        }
    }

    public function change($id)
    {
        if (!validatePermissions('portfolio-analysis/website-contact-services/status-change/{id}')) {
            abort(403);
        }

        $live_link = ResumeWebsiteContactServicesModel::where('id', sanitizeInput($id, 'int'))->first();

        if (!$live_link) {
            $response = ['responseCode' => 0, 'msg' => 'Contact Service record not found.'];
            return json_encode($response);
        }
        if ($live_link) {
            $status = ($live_link->is_active == 0) ? 1 : 0;
            $live_link->is_active  = $status;
            $live_link->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'Contact Service status changed successfully'];
        return json_encode($response);
    }

    /** Sorting live_link  */
    public function sorting($id, $sort_number)
    {
        if (!validatePermissions('portfolio-analysis/website-contact-services/delete/{id}')) {
            abort(403);
        }
        $row = ResumeWebsiteContactServicesModel::where('id', $id)->first();

        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Contact Service record not found.'];
            return json_encode($response);
        }
        $row->sort_number = $sort_number;
        $row->save();

        return 'live_link sorted successfully.';
        // $response = ['responseCode' => 1, 'msg' => 'live_link has been deleted'];
        // return json_encode($response);
    }

    /** Delete live_link  */
    public function destroy($id)
    {
        if (!validatePermissions('portfolio-analysis/website-contact-services/delete/{id}')) {
            abort(403);
        }
        $row = ResumeWebsiteContactServicesModel::where('id', $id)->first();
        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Contact Service record not found.'];
            return json_encode($response);
        }
        if (isset($row->contactMessages) && count($row->contactMessages) > 0) {
            $response = ['responseCode' => 0, 'msg' => 'Contact Service contains messages'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Contact Service has been deleted'];
        return json_encode($response);
    }
}
