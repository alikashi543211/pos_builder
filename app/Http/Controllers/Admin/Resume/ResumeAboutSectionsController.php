<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeAboutSectionsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeAboutSectionsController extends Controller
{

    public function index()
    {
        if (!validatePermissions('resume/about-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumeAboutSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->first();
        $data = [
            'pageTitle' => 'About Section',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => $headerSection
        ];


        return view('admin.resume.about_sections.index')->with($data);
    }

    public function store(Request $request)
    {
        if (!validatePermissions('resume/about-sections/store')) {
            return response()->json(['responseCode' => 0, 'msg' => 'Permission Denied']);
        }

        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());

        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'display_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'display_position_title' => 'required|string|max:250',
            'heading_short_description' => 'required|string',
            'birthday' => 'required|date',
            'age' => 'required|integer|min:1',
            'website' => 'required|url|max:250',
            'degree' => 'required|string|max:250',
            'phone_no' => 'required|string|max:250',
            'email_address' => 'required|email|max:250',
            'city' => 'required|string|max:250',
            'freelance' => 'required|string|max:250',
            'footer_short_description' => 'required|string',

            'job_display_position_title' => 'nullable|string|max:250',
            'job_heading_short_description' => 'nullable|string',
            'job_birthday' => 'nullable|date',
            'job_age' => 'nullable|integer|min:1',
            'job_website' => 'nullable|url|max:250',
            'job_degree' => 'nullable|string|max:250',
            'job_phone_no' => 'nullable|string|max:250',
            'job_email_address' => 'nullable|email|max:250',
            'job_city' => 'nullable|string|max:250',
            'job_freelance' => 'nullable|string|max:250',
            'job_footer_short_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return json_encode(['responseCode' => 0, 'msg' => $validator->errors()->first()]);
        }

        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPosition = getActiveResumeJobPosition();
            $activeJobPositionId = $activeJobPosition->id ?? null;

            $header_section = ResumeAboutSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('is_active', 1)
                ->first() ?? new ResumeAboutSectionsModel();

            if ($request->hasFile('display_picture')) {
                $header_section->display_picture = $this->resizeAndSaveImage(
                    $sanitizedInputs['display_picture'],
                    'media/' . Auth::guard('admin')->user()->user_name . '/sidebar_profile_images',
                    $request->file('display_picture')->getClientOriginalName(),
                    400,
                    400
                );
            }

            // Assign attributes
            $header_section->job_position_id = $activeJobPositionId;
            $header_section->display_position_title = $sanitizedInputs['display_position_title'] ?? null;
            $header_section->heading_short_description = $sanitizedInputs['heading_short_description'] ?? null;
            $header_section->birthday = $sanitizedInputs['birthday'] ?? null;
            $header_section->age = $sanitizedInputs['age'] ?? null;
            $header_section->website = $sanitizedInputs['website'] ?? null;
            $header_section->degree = $sanitizedInputs['degree'] ?? null;
            $header_section->phone_no = $sanitizedInputs['phone_no'] ?? null;
            $header_section->email_address = $sanitizedInputs['email_address'] ?? null;
            $header_section->city = $sanitizedInputs['city'] ?? null;
            $header_section->freelance = $sanitizedInputs['freelance'] ?? null;
            $header_section->footer_short_description = $sanitizedInputs['footer_short_description'] ?? null;
            /** Start - JOB Fields */
            $header_section->job_display_position_title = $sanitizedInputs['job_display_position_title'] ?? null;
            $header_section->job_heading_short_description = $sanitizedInputs['job_heading_short_description'] ?? null;
            $header_section->job_birthday = $sanitizedInputs['job_birthday'] ?? null;
            $header_section->job_age = $sanitizedInputs['job_age'] ?? null;
            $header_section->job_website = $sanitizedInputs['job_website'] ?? null;
            $header_section->job_degree = $sanitizedInputs['job_degree'] ?? null;
            $header_section->job_phone_no = $sanitizedInputs['job_phone_no'] ?? null;
            $header_section->job_email_address = $sanitizedInputs['job_email_address'] ?? null;
            $header_section->job_city = $sanitizedInputs['job_city'] ?? null;
            $header_section->job_freelance = $sanitizedInputs['job_freelance'] ?? null;
            $header_section->job_footer_short_description = $sanitizedInputs['job_footer_short_description'] ?? null;
            /** End - JOB Fields */
            $header_section->is_active = 1;
            $header_section->created_by = $userId;

            $header_section->save();

            return json_encode(['responseCode' => 1, 'msg' => 'Resume about section saved successfully.']);
        } catch (QueryException $e) {
            Log::error('Header Section Saving Query Exception Error : ' . $e->getMessage());
            return json_encode(['responseCode' => 0, 'msg' => 'An error occurred while saving the resume header section.']);
        } catch (Exception $e) {
            Log::error('Header Section Saving Exception Error : ' . $e->getMessage());
            return json_encode(['responseCode' => 0, 'msg' => 'An error occurred while saving the resume header section.']);
        }
    }
}
