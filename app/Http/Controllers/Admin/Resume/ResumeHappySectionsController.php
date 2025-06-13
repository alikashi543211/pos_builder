<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeHappySectionsModel;
use App\Rules\LinkedInValidationRule;
use App\Traits\ResumeHeaderSectionsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeHappySectionsController extends Controller
{

    public function index()
    {
        if (!validatePermissions('resume/happy-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumeHappySectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->first();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Happy Section',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => $headerSection
        ];

        return view('admin.resume.happy_sections.index')->with($data);
    }

    public function store(Request $request)
    {
        if (!validatePermissions('resume/happy-sections/store')) {
            return response()->json(['responseCode' => 0, 'msg' => 'Permission Denied']);
        }

        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        // dd($sanitizedInputs);
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'one_count' => 'required|integer',
            'one_heading' => 'required|string|max:255',
            'one_subheading' => 'required|string|max:255',
            'one_icon' => 'required|string|max:255',
            'two_count' => 'required|integer',
            'two_heading' => 'required|string|max:255',
            'two_subheading' => 'required|string|max:255',
            'two_icon' => 'required|string|max:255',
            'three_count' => 'required|integer',
            'three_heading' => 'required|string|max:255',
            'three_subheading' => 'required|string|max:255',
            'three_icon' => 'required|string|max:255',
            'four_count' => 'required|integer',
            'four_heading' => 'required|string|max:255',
            'four_subheading' => 'required|string|max:255',
            'four_icon' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return json_encode(['responseCode' => 0, 'msg' => $validator->errors()->first()]);
        }

        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;

            $header_section = ResumeHappySectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('is_active', 1)
                ->first() ?? new ResumeHappySectionsModel();

            // Assign form values
            $header_section->job_position_id = $activeJobPositionId;
            $header_section->one_count = $sanitizedInputs['one_count'] ?? null;
            $header_section->one_heading = $sanitizedInputs['one_heading'] ?? null;
            $header_section->one_subheading = $sanitizedInputs['one_subheading'] ?? null;
            $header_section->one_icon = $sanitizedInputs['one_icon'] ?? null;
            $header_section->two_count = $sanitizedInputs['two_count'] ?? null;
            $header_section->two_heading = $sanitizedInputs['two_heading'] ?? null;
            $header_section->two_subheading = $sanitizedInputs['two_subheading'] ?? null;
            $header_section->two_icon = $sanitizedInputs['two_icon'] ?? null;
            $header_section->three_count = $sanitizedInputs['three_count'] ?? null;
            $header_section->three_heading = $sanitizedInputs['three_heading'] ?? null;
            $header_section->three_subheading = $sanitizedInputs['three_subheading'] ?? null;
            $header_section->three_icon = $sanitizedInputs['three_icon'] ?? null;
            $header_section->four_count = $sanitizedInputs['four_count'] ?? null;
            $header_section->four_heading = $sanitizedInputs['four_heading'] ?? null;
            $header_section->four_subheading = $sanitizedInputs['four_subheading'] ?? null;
            $header_section->four_icon = $sanitizedInputs['four_icon'] ?? null;
            $header_section->created_by = $userId;
            $header_section->save();

            return json_encode(['responseCode' => 1, 'msg' => 'Happy section saved successfully.']);
        } catch (QueryException $e) {
            Log::error('Header Section Saving Query Exception Error : ' . $e->getMessage());
            return json_encode(['responseCode' => 0, 'msg' => 'An error occurred while saving the resume header section.']);
        } catch (Exception $e) {
            Log::error('Header Section Saving Exception Error : ' . $e->getMessage());
            return json_encode(['responseCode' => 0, 'msg' => 'An error occurred while saving the resume header section.']);
        }
    }
}
