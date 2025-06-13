<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeHeaderSectionsModel;
use App\Rules\LinkedInValidationRule;
use App\Traits\ResumeHeaderSectionsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeHeaderSectionsController extends Controller
{

    public function index()
    {
        if (!validatePermissions('resume/header-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumeHeaderSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->first();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Header Section',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => $headerSection
        ];

        return view('admin.resume.header_sections.index')->with($data);
    }

    public function store(Request $request)
    {
        if (!validatePermissions('resume/header-sections/store')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return json_encode($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());

        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'display_picture'              => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image file validation
            'first_name'                   => 'required|string|min:3|max:50',
            'last_name'                    => 'required|string|min:3|max:50',
            'full_name'                    => 'required|string|min:3|max:50',
            'position_display_title_1'     => 'required|string|min:5|max:100',
            'position_display_title_2'     => 'required|string|min:5|max:100',
            'position_display_title_3'     => 'required|string|min:5|max:100',
            'position_display_title_4'     => 'required|string|min:5|max:100',

            'job_first_name'               => 'required|string|min:3|max:50',
            'job_last_name'                => 'required|string|min:3|max:50',
            'job_full_name'                => 'required|string|min:3|max:50',
            'job_position_display_title_1' => 'required|string|min:5|max:100',
            'job_position_display_title_2' => 'required|string|min:5|max:100',
            'job_position_display_title_3' => 'required|string|min:5|max:100',
            'job_position_display_title_4' => 'required|string|min:5|max:100',
        ], [
            'display_picture.image' => 'The display picture must be a valid image file.',
            'display_picture.mimes' => 'Only JPEG, PNG, JPG and GIF, WEBP formats are allowed for the display picture.',
            'display_picture.max' => 'The display picture size must not exceed 2MB.',
            'background_image.image' => 'The background image must be a valid image file.',
            'background_image.mimes' => 'Only JPEG, PNG, JPG, GIF, and WEBP formats are allowed for the background image.',
            'background_image.max' => 'The background image size must not exceed 2MB.',
            'first_name.required' => 'The first name is required.',
            'first_name.min' => 'The first name must be at least 3 characters long.',
            'first_name.max' => 'The first name must not exceed 50 characters.',
            'last_name.required' => 'The last name is required.',
            'last_name.min' => 'The last name must be at least 3 characters long.',
            'last_name.max' => 'The last name must not exceed 50 characters.',
            'position_display_title_1.required' => 'The first position display title is required.',
            'position_display_title_1.min' => 'The first position display title must be at least 5 characters long.',
            'position_display_title_1.max' => 'The first position display title must not exceed 100 characters.',
            'position_display_title_2.required' => 'The second position display title is required.',
            'position_display_title_2.min' => 'The second position display title must be at least 5 characters long.',
            'position_display_title_2.max' => 'The second position display title must not exceed 100 characters.',
            'position_display_title_3.required' => 'The third position display title is required.',
            'position_display_title_3.min' => 'The third position display title must be at least 5 characters long.',
            'position_display_title_3.max' => 'The third position display title must not exceed 100 characters.',
            'position_display_title_4.required' => 'The fourth position display title is required.',
            'position_display_title_4.min' => 'The fourth position display title must be at least 5 characters long.',
            'position_display_title_4.max' => 'The fourth position display title must not exceed 100 characters.',
            'phone_number.max' => 'Phone number must not be greater than 15 digits.',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }
        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            $header_section = null;
            if (!$header_section = ResumeHeaderSectionsModel::where('job_position_id', $activeJobPositionId)->where('created_by', $userId)->where('is_active', 1)->first()) {
                $header_section = new ResumeHeaderSectionsModel();
            }
            if ($request->hasFile('display_picture')) {
                if (isset($sanitizedInputs['display_picture']) && $request->hasFile('display_picture')) {
                    $header_section->display_picture = $this->resizeAndSaveImage($sanitizedInputs['display_picture'], 'media/' . Auth::guard('admin')->user()->user_name . '/profile_images', $request->file('display_picture')->getClientOriginalName(), 400, 400);
                }
            }
            if ($request->hasFile('background_image')) {
                if (isset($sanitizedInputs['background_image']) && $request->hasFile('background_image')) {
                    $header_section->background_image = $this->resizeAndSaveImage($sanitizedInputs['background_image'], 'media/' . Auth::guard('admin')->user()->user_name . '/header_background_images', $request->file('background_image')->getClientOriginalName(), 400, 400);
                }
            }
            $header_section->job_position_id = $activeJobPositionId;
            $header_section->first_name = $sanitizedInputs['first_name'] ?? null;
            $header_section->last_name = $sanitizedInputs['last_name'] ?? null;
            $header_section->full_name = $sanitizedInputs['full_name'] ?? null;
            $header_section->position_display_title_1 = $sanitizedInputs['position_display_title_1'] ?? null;
            $header_section->position_display_title_2 = $sanitizedInputs['position_display_title_2'] ?? null;
            $header_section->position_display_title_3 = $sanitizedInputs['position_display_title_3'] ?? null;
            $header_section->position_display_title_4 = $sanitizedInputs['position_display_title_4'] ?? null;
            $header_section->is_active = 1;
            $header_section->created_by = $userId;

            $header_section->job_first_name               = $sanitizedInputs['job_first_name'] ?? null;
            $header_section->job_last_name                = $sanitizedInputs['job_last_name'] ?? null;
            $header_section->job_full_name                = $sanitizedInputs['job_full_name'] ?? null;
            $header_section->job_position_display_title_1 = $sanitizedInputs['job_position_display_title_1'] ?? null;
            $header_section->job_position_display_title_2 = $sanitizedInputs['job_position_display_title_2'] ?? null;
            $header_section->job_position_display_title_3 = $sanitizedInputs['job_position_display_title_3'] ?? null;
            $header_section->job_position_display_title_4 = $sanitizedInputs['job_position_display_title_4'] ?? null;
            $header_section->is_active = 1;
            $header_section->created_by = $userId;
            $header_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Header section saved successfully.'];
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
