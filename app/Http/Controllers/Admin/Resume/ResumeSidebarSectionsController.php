<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeHeaderSectionsModel;
use App\Models\Resume\ResumeSidebarSectionsModel;
use App\Rules\LinkedInValidationRule;
use App\Traits\ResumeHeaderSectionsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeSidebarSectionsController extends Controller
{

    public function index()
    {
        if (!validatePermissions('resume/sidebar-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumeSidebarSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->first();
        $data = [
            'pageTitle' => 'Sidebar Section',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => $headerSection
        ];

        return view('admin.resume.sidebar_sections.index')->with($data);
    }

    public function store(Request $request)
    {
        if (!validatePermissions('resume/sidebar-sections/store')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());

        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'display_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image file validation
            'facebook_link' => 'nullable|string|url|max:255',
            'instagram_link' => 'nullable|string|url|max:255',
            'skype_link' => 'nullable|string|url|max:255',
            'youtube_link' => 'nullable|string|url|max:255',
            'linkedin_link' => 'nullable|string|url|max:255',
            'github_link' => 'nullable|string|url|max:255',
        ], [
            'display_picture.image' => 'The display picture must be a valid image file.',
            'display_picture.mimes' => 'Only JPEG, PNG, JPG and GIF, WEBP formats are allowed for the display picture.',
            'display_picture.max' => 'The display picture size must not exceed 2MB.',
            'facebook_link.url' => 'Please enter a valid Facebook URL.',
            'instagram_link.url' => 'Please enter a valid Instagram URL.',
            'skype_link.url' => 'Please enter a valid Skype URL.',
            'youtube_link.url' => 'Please enter a valid Youtube URL.',
            'linkedin_link.url' => 'Please enter a valid LinkedIn URL.',
            'github_link.url' => 'Please enter a valid GitHub URL.',

        ]);
        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }
        // $validationRules = [
        //     'facebook_link' => '/^.*github.*$/i',
        //     'instagram_link' => '/^(https?:\/\/)?(www\.)?instagram\.com\/[a-zA-Z0-9_.-]+\/?$/',
        //     'skype_link' => '/^(https?:\/\/)?(www\.)?join\.skype\.com\/[a-zA-Z0-9]+\/?$/',
        //     'linkedin_link' => '/^(https?:\/\/)?(www\.)?linkedin\.com\/(in|company)\/[a-zA-Z0-9_-]+\/?$/',
        //     'github_link' => '/^.*github.*$/i',
        //     'youtube_link' => '/^(https?:\/\/)?(www\.)?youtube\.com\/.+$/',

        //     'job_facebook_link' => '/^(https?:\/\/)?(www\.)?facebook\.com\/[a-zA-Z0-9_.-]+\/?$/',
        //     'job_instagram_link' => '/^(https?:\/\/)?(www\.)?instagram\.com\/[a-zA-Z0-9_.-]+\/?$/',
        //     'job_skype_link' => '/^(https?:\/\/)?(www\.)?join\.skype\.com\/[a-zA-Z0-9]+\/?$/',
        //     'job_linkedin_link' => '/^(https?:\/\/)?(www\.)?linkedin\.com\/(in|company)\/[a-zA-Z0-9_-]+\/?$/',
        //     'job_github_link' => '/^.*github.*$/i',
        //     'job_youtube_link' => '/^(https?:\/\/)?(www\.)?youtube\.com\/.+$/',
        // ];


        // foreach ($validationRules as $key => $pattern) {
        //     if (!empty($sanitizedInputs[$key]) && !preg_match($pattern, $sanitizedInputs[$key])) {
        //         $response = ['responseCode' => 0, 'msg' => ucfirst(str_replace('_', ' ', $key)) . " is invalid"];
        //         return json_encode($response);
        //     }
        // }
        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPosition = getActiveResumeJobPosition();
            $activeJobPositionId = $activeJobPosition->id ?? null;

            $header_section = ResumeSidebarSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('is_active', 1)
                ->first() ?? new ResumeSidebarSectionsModel();

            if ($request->hasFile('display_picture')) {
                $header_section->display_picture = $this->resizeAndSaveImage(
                    $sanitizedInputs['display_picture'],
                    'media/' . Auth::guard('admin')->user()->user_name . '/sidebar_profile_images',
                    $request->file('display_picture')->getClientOriginalName(),
                    400,
                    400
                );
            }

            // Assign attributes one by one
            $header_section->job_position_id = $activeJobPositionId;
            $header_section->facebook_link = $sanitizedInputs['facebook_link'] ?? null;
            $header_section->instagram_link = $sanitizedInputs['instagram_link'] ?? null;
            $header_section->skype_link = $sanitizedInputs['skype_link'] ?? null;
            $header_section->youtube_link = $sanitizedInputs['youtube_link'] ?? null;
            $header_section->linkedin_link = $sanitizedInputs['linkedin_link'] ?? null;
            $header_section->github_link = $sanitizedInputs['github_link'] ?? null;

            $header_section->job_facebook_link = $sanitizedInputs['job_facebook_link'] ?? null;
            $header_section->job_instagram_link = $sanitizedInputs['job_instagram_link'] ?? null;
            $header_section->job_skype_link = $sanitizedInputs['job_skype_link'] ?? null;
            $header_section->job_youtube_link = $sanitizedInputs['job_youtube_link'] ?? null;
            $header_section->job_linkedin_link = $sanitizedInputs['job_linkedin_link'] ?? null;
            $header_section->job_github_link = $sanitizedInputs['job_github_link'] ?? null;
            $header_section->is_active = 1;
            $header_section->created_by = $userId;

            $header_section->save();


            $response = ['responseCode' => 1, 'msg' => 'Resume sidebar section saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Header Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the resume header section.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Header Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the resume header section.'];
            return json_encode($response);
        }
    }
}
