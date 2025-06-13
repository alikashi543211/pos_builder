<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeTestimonialSectionsModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeTestimonialSectionsController extends Controller
{
    public function index()
    {
        if (!validatePermissions('resume/testimonial-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $testimonials = ResumeTestimonialSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();
        $data = [
            'pageTitle' => 'Testimonials',
            'activeJobPosition' => $activeJobPosition,
            'testimonials' => $testimonials
        ];

        return view('admin.resume.testimonial_sections.index')->with($data);
    }

    public function create()
    {
        if (!validatePermissions('resume/testimonial-sections/add')) {
            abort(403);
        }
        $data = [];
        $html = view('admin.resume.testimonial_sections.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit Testimonial  */
    public function edit($id)
    {
        if (!validatePermissions('resume/testimonial-sections/edit/{id}')) {
            abort(403);
        }
        $data = [];
        $data = [
            'pageTitle' => 'Testimonial Edit'
        ];

        $data['row'] = ResumeTestimonialSectionsModel::where('id', $id)->first();

        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Testimonial record not found.'];
            return json_encode($response);
        }

        $html = view('admin.resume.testimonial_sections.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }

    /** Save New live_link */
    public function store(Request $request)
    {
        if (!validatePermissions('resume/testimonial-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'full_name' => 'required|string|min:3|max:255',
            'display_picture' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'designation_title' => 'required|string|min:2|max:150',
            'message' => 'required|string|min:5|max:5000',
        ], [
            'full_name.required' => 'The full name field is required.',
            'full_name.string' => 'The full name must be a valid string.',
            'full_name.min' => 'The full name must be at least 3 characters long.',
            'full_name.max' => 'The full name must not exceed 255 characters.',

            'display_picture.image' => 'The display picture must be a valid image file.',
            'display_picture.mimes' => 'The display picture must be a file of type: jpg, jpeg, png.',
            'display_picture.max' => 'The display picture must not be larger than 2MB.',

            'designation_title.string' => 'The designation title must be a valid string.',
            'designation_title.min' => 'The designation title must be at least 2 characters long.',
            'designation_title.max' => 'The designation title must not exceed 150 characters.',

            'message.string' => 'The message must be a valid string.',
            'message.min' => 'The message must be at least 5 characters long.',
            'message.max' => 'The message must not exceed 5000 characters.',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }
        try {
            /** Create or Update Resume live_link Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if (ResumeTestimonialSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('full_name', $sanitizedInputs['full_name'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'User name already exists.'];
                return json_encode($response);
            }
            $live_link_section = new ResumeTestimonialSectionsModel();
            if ($request->hasFile('display_picture')) {
                if (isset($sanitizedInputs['display_picture']) && $request->hasFile('display_picture')) {
                    $live_link_section->display_picture = $this->resizeAndSaveImage($sanitizedInputs['display_picture'], 'media/' . Auth::guard('admin')->user()->user_name . '/testimonial_user_images', $request->file('display_picture')->getClientOriginalName(), 150, 50);
                }
            }
            $live_link_section->job_position_id = $activeJobPositionId;
            $live_link_section->full_name = $sanitizedInputs['full_name'] ?? null;
            $live_link_section->designation_title = $sanitizedInputs['designation_title'] ?? null;
            $live_link_section->message = $sanitizedInputs['message'] ?? null;
            $live_link_section->is_active = 1;
            $live_link_section->created_by = $userId;
            $live_link_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Testimonial saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Testimonial Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the tesimonial.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Testimonial Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the tesimonial.'];
            return json_encode($response);
        }
    }

    /** Update Existing live_link */
    public function update(Request $request, $id)
    {
        if (!validatePermissions('resume/testimonial-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'full_name' => 'required|string|min:3|max:255',
            'display_picture' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'designation_title' => 'required|string|min:2|max:150',
            'message' => 'required|string|min:5|max:5000',
        ], [
            'full_name.required' => 'The full name field is required.',
            'full_name.string' => 'The full name must be a valid string.',
            'full_name.min' => 'The full name must be at least 3 characters long.',
            'full_name.max' => 'The full name must not exceed 255 characters.',

            'display_picture.image' => 'The display picture must be a valid image file.',
            'display_picture.mimes' => 'The display picture must be a file of type: jpg, jpeg, png.',
            'display_picture.max' => 'The display picture must not be larger than 2MB.',

            'designation_title.string' => 'The designation title must be a valid string.',
            'designation_title.min' => 'The designation title must be at least 2 characters long.',
            'designation_title.max' => 'The designation title must not exceed 150 characters.',

            'message.string' => 'The message must be a valid string.',
            'message.min' => 'The message must be at least 5 characters long.',
            'message.max' => 'The message must not exceed 5000 characters.',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            // Validate id exists or not
            $live_link_section = ResumeTestimonialSectionsModel::whereId(sanitizeInput($id, 'int'))->first();
            if (!$live_link_section) {
                $response = ['responseCode' => 0, 'msg' => 'Testimonial record not found.'];
                return json_encode($response);
            }

            /** Create or Update Resume live_link Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;

            // Check Name already exist or not
            if (ResumeTestimonialSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('full_name', $sanitizedInputs['full_name'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'User name already exists.'];
                return json_encode($response);
            }
            // Update data
            if ($request->hasFile('display_picture')) {
                if (isset($sanitizedInputs['display_picture']) && $request->hasFile('display_picture')) {
                    $live_link_section->display_picture = $this->resizeAndSaveImage($sanitizedInputs['display_picture'], 'media/' . Auth::guard('admin')->user()->user_name . '/testimonial_user_images', $request->file('display_picture')->getClientOriginalName(), 150, 50);
                }
            }
            $live_link_section->job_position_id = $activeJobPositionId;
            $live_link_section->full_name = $sanitizedInputs['full_name'] ?? null;
            $live_link_section->designation_title = $sanitizedInputs['designation_title'] ?? null;
            $live_link_section->message = $sanitizedInputs['message'] ?? null;
            $live_link_section->is_active = 1;
            $live_link_section->created_by = $userId;
            $live_link_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Testimonial saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Live Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the Testimonial.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('live_link Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the Testimonial.'];
            return json_encode($response);
        }
    }

    public function change($id)
    {
        if (!validatePermissions('resume/testimonial-sections/status-change/{id}')) {
            abort(403);
        }

        $live_link = ResumeTestimonialSectionsModel::where('id', sanitizeInput($id, 'int'))->first();

        if (!$live_link) {
            $response = ['responseCode' => 0, 'msg' => 'Testimonial record not found.'];
            return json_encode($response);
        }
        if ($live_link) {
            $status = ($live_link->is_active == 0) ? 1 : 0;
            $live_link->is_active  = $status;
            $live_link->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'Testimonial status changed successfully'];
        return json_encode($response);
    }

    /** Sorting live_link  */
    public function sorting($id, $sort_number)
    {
        if (!validatePermissions('resume/testimonial-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeTestimonialSectionsModel::where('id', $id)->first();

        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Testimonial record not found.'];
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
        if (!validatePermissions('resume/testimonial-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeTestimonialSectionsModel::where('id', $id)->first();
        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Testimonial record not found.'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Testimonial has been deleted'];
        return json_encode($response);
    }
}
