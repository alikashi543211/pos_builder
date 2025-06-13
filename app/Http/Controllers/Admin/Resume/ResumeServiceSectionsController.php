<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeExperienceSectionsModel;
use App\Models\Resume\ResumeProjectCategoriesModel;
use App\Models\Resume\ResumeServiceSectionDescriptionsModel;
use App\Models\Resume\ResumeServiceSectionImagesModel;
use App\Models\Resume\ResumeServiceSectionsModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeServiceSectionsController extends Controller
{
    public function index()
    {
        if (!validatePermissions('resume/service-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $projects = ResumeServiceSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();
        $data = [
            'pageTitle' => 'Service Section',
            'activeJobPosition' => $activeJobPosition,
            'projects' => $projects
        ];

        return view('admin.resume.service_sections.index')->with($data);
    }

    public function create()
    {
        if (!validatePermissions('resume/service-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $data = [];
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $html = view('admin.resume.service_sections.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Add Descriptions  */
    public function addDescriptions($id)
    {
        if (!validatePermissions('resume/service-sections/descriptions/add/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $data = [];
        $data['row'] = ResumeServiceSectionsModel::where('id', sanitizeInput($id, 'int'))->first();
        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Service record not found.'];
            return json_encode($response);
        }
        $data['projectDescriptions'] = ResumeServiceSectionDescriptionsModel::where('resume_service_section_id', sanitizeInput($id, 'int'))
            ->orderBy('sort_number', 'ASC')
            ->get();
        $html = view('admin.resume.service_sections.add_descriptions')->with($data)->render();
        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Add Images  */
    public function addImages($id)
    {
        if (!validatePermissions('resume/service-sections/images/add/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $data = [];
        $data['row'] = ResumeServiceSectionsModel::where('id', $id)->first();
        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Service record not found.'];
            return json_encode($response);
        }
        $data['projectImages'] = ResumeServiceSectionImagesModel::where('resume_service_section_id', $id)->get();
        $html = view('admin.resume.service_sections.add_images')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit Service  */
    public function edit($id)
    {
        if (!validatePermissions('resume/service-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $data = [];
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $data['row'] = ResumeServiceSectionsModel::where('id', sanitizeInput($id, 'int'))->first();
        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Service record not found.'];
            return json_encode($response);
        }
        $data['experiences'] = ResumeProjectCategoriesModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->whereNotNull('category_name')
            ->orderBy('sort_number', 'ASC')
            ->get();

        $html = view('admin.resume.service_sections.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }

    /** Save New Service */
    public function store(Request $request)
    {
        if (!validatePermissions('resume/service-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'service_name' => 'required|string|min:3|max:250',
            'service_slug' => 'required|string|min:3|max:250',
            'service_icon_class' => 'required|string|min:3|max:100',
            'service_thumbnail' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'required|string|min:20|max:500',
            'service_sidebar_title' => 'nullable|string|min:20|max:200',
            'service_sidebar_description' => 'nullable|string|min:20|max:500',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Service Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if (ResumeServiceSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('service_slug', $sanitizedInputs['service_slug'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Service slug already exists.'];
                return json_encode($response);
            }
            if ($project_section = ResumeServiceSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('service_name', $sanitizedInputs['service_name'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Service name already exists.'];
                return json_encode($response);
            }
            $project_section = new ResumeServiceSectionsModel();
            if ($request->hasFile('service_thumbnail')) {
                if (isset($sanitizedInputs['service_thumbnail']) && $request->hasFile('service_thumbnail')) {
                    $project_section->service_thumbnail = $this->resizeAndSaveImage($sanitizedInputs['service_thumbnail'], 'media/' . Auth::guard('admin')->user()->user_name . '/service_thumbnails', $request->file('service_thumbnail')->getClientOriginalName(), 500, 350);
                }
            }
            $project_section->job_position_id = $activeJobPositionId;
            $project_section->service_name = !empty($sanitizedInputs['service_name']) ? $sanitizedInputs['service_name'] : null;
            $project_section->service_slug = !empty($sanitizedInputs['service_slug']) ? $sanitizedInputs['service_slug'] : null;
            $project_section->service_icon_class = !empty($sanitizedInputs['service_icon_class']) ? $sanitizedInputs['service_icon_class'] : null;
            $project_section->description = !empty($sanitizedInputs['description']) ? $sanitizedInputs['description'] : null;
            $project_section->service_sidebar_title = !empty($sanitizedInputs['service_sidebar_title']) ? $sanitizedInputs['service_sidebar_title'] : null;
            $project_section->service_sidebar_description = !empty($sanitizedInputs['service_sidebar_description']) ? $sanitizedInputs['service_sidebar_description'] : null;
            $project_section->is_active = 1;
            $project_section->created_by = $userId;
            $project_section->save();

            /** Store Service 5 Nullable _ Service Descriptions _ */
            for ($j = 0; $j < 5; $j++) {
                $description = new ResumeServiceSectionDescriptionsModel();
                $description->description = null;
                $description->sort_number = ($j + 1);
                $description->resume_service_section_id = $project_section->id;
                $description->save();
            }
            $response = ['responseCode' => 1, 'msg' => 'Service saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Service Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the service.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Service Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the service.'];
            return json_encode($response);
        }
    }

    /** Save Multiple Service Descriptions */
    public function storeDescriptions(Request $request, $id)
    {
        if (!validatePermissions('resume/service-sections/descriptions/add/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());

        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'description_points' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        $data['row'] = ResumeServiceSectionsModel::where('id', sanitizeInput($id, 'int'))->first();
        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Service record not found.'];
            return json_encode($response);
        }
        try {
            // dd($sanitizedInputs['description_points']);
            if (isset($sanitizedInputs['description_points']) && is_array($sanitizedInputs['description_points'])) {
                foreach ($sanitizedInputs['description_points'] as $descriptionItem) {
                    $descriptionItem = (object) $descriptionItem;
                    if (isset($descriptionItem)) {
                        if (isset($descriptionItem->id) && !empty($descriptionItem->id)) {
                            $description_point = ResumeServiceSectionDescriptionsModel::whereId($descriptionItem->id)->first();
                        } else {
                            $description_point = new ResumeServiceSectionDescriptionsModel();
                        }

                        $description_point->resume_service_section_id = sanitizeInput($id, 'int');
                        $description_point->description = !empty($descriptionItem->description) ? $descriptionItem->description : null;
                        $description_point->sort_number = $descriptionItem->sort_number ?? null;
                        $description_point->save();
                    }
                }
            }
            $response = ['responseCode' => 1, 'msg' => 'Service descriptions saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Service Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the service.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Service Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the service.'];
            return json_encode($response);
        }
    }

    /** Save Multiple Service Images */
    public function storeImages(Request $request, $id)
    {
        if (!validatePermissions('resume/service-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $project_section = ResumeServiceSectionsModel::where('id', sanitizeInput($id, 'int'))->first();
        if (!$project_section) {
            $response = ['responseCode' => 0, 'msg' => 'Service record not found.'];
            return json_encode($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make(
            $sanitizedInputs,
            [
                'project_images' => 'required|array', // Ensure project_images is an array if provided
                'project_images.*' => 'file|image|mimes:jpg,jpeg,png|max:2048', // Validate each file
            ],
            [
                'project_images.array' => 'Select Service images to upload.',
                'project_images.array' => 'The Service images must be an array.',
                'project_images.*.file' => 'Each file must be a valid file.',
                'project_images.*.image' => 'Each file must be an image.',
                'project_images.*.mimes' => 'Each image must be of type: jpg, jpeg, or png.',
                'project_images.*.max' => 'Each image must not exceed 2MB in size.',
            ]
        );

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Service Section */
            // if(ResumeServiceSectionImagesModel::where('resume_service_section_id', $id)->count() > 0){
            //     ResumeServiceSectionImagesModel::where('resume_service_section_id', $id)->delete();
            // }
            // dd($sanitizedInputs['project_images']);
            foreach ($sanitizedInputs['project_images'] as $key => $imageFile) {
                // Check if the file exists and is valid
                if ($request->hasFile("project_images.$key")) {
                    $uploadedFile = $request->file("project_images.$key");

                    // Ensure the uploaded file is set and valid
                    if ($uploadedFile) {
                        $newImage = new ResumeServiceSectionImagesModel();
                        $newImage->resume_service_section_id = $id;

                        // Save the resized image and set the image path
                        $imagePath = $this->resizeAndSaveImage(
                            $uploadedFile,
                            'media/' . Auth::guard('admin')->user()->user_name . '/project_images/' . $project_section->project_name,
                            $uploadedFile->getClientOriginalName(),
                            1000,
                            700
                        );
                        $newImage->image_path = $imagePath;

                        // Save the model
                        $newImage->save();
                    }
                }
            }
            $projectImages = ResumeServiceSectionImagesModel::where('resume_service_section_id', $id)->get();
            $response = ['responseCode' => 1, 'msg' => 'Service images uploaded successfully.', 'data' => $projectImages];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Service Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the service.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Service Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the service.'];
            return json_encode($response);
        }
    }

    /** Delete Service Image */
    public function deleteProjectImage(Request $request, $id)
    {
        if (!validatePermissions('resume/service-sections/images/delete/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        try {
            $project_section_image = ResumeServiceSectionImagesModel::where('id', sanitizeInput($id, 'int'))->first();
            if (!$project_section_image) {
                $response = ['responseCode' => 0, 'msg' => 'Service record not found.'];
                return json_encode($response);
            }
            $project_section_image->delete();
            // return 'Service Image Deleted Successfully';
            $response = ['responseCode' => 1, 'msg' => 'Service image deleted successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Service Section image Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while deleting the Service image.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Service Section image Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while deleting the Service image.'];
            return json_encode($response);
        }
    }



    /** Update Existing Service */
    public function update(Request $request, $id)
    {
        if (!validatePermissions('resume/service-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'service_name' => 'required|string|min:3|max:250',
            'service_slug' => 'required|string|min:3|max:250',
            'service_icon_class' => 'required|string|min:3|max:100',
            'service_thumbnail' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'required|string|min:20|max:500',
            'service_sidebar_title' => 'nullable|string|min:20|max:200',
            'service_sidebar_description' => 'nullable|string|min:20|max:500',
        ]);


        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            // Validate id exists or not
            $project_section = ResumeServiceSectionsModel::whereId(sanitizeInput($id, 'int'))->first();
            if (!$project_section) {
                $response = ['responseCode' => 0, 'msg' => 'Service record not found.'];
                return json_encode($response);
            }

            /** Create or Update Resume Service Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if (ResumeServiceSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('service_slug', $sanitizedInputs['service_slug'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Service slug already exists.'];
                return json_encode($response);
            }
            // Check Name already exist or not
            if (ResumeServiceSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('service_name', $sanitizedInputs['service_name'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Service name already exists.'];
                return json_encode($response);
            }
            // Update data
            if ($request->hasFile('service_thumbnail')) {
                if (isset($sanitizedInputs['service_thumbnail']) && $request->hasFile('service_thumbnail')) {
                    $project_section->service_thumbnail = $this->resizeAndSaveImage($sanitizedInputs['service_thumbnail'], 'media/' . Auth::guard('admin')->user()->user_name . '/service_thumbnails', $request->file('service_thumbnail')->getClientOriginalName(), 500, 350);
                }
            }
            $project_section->job_position_id = $activeJobPositionId;
            $project_section->service_name = !empty($sanitizedInputs['service_name']) ? $sanitizedInputs['service_name'] : null;
            $project_section->service_slug = !empty($sanitizedInputs['service_slug']) ? $sanitizedInputs['service_slug'] : null;
            $project_section->service_icon_class = !empty($sanitizedInputs['service_icon_class']) ? $sanitizedInputs['service_icon_class'] : null;
            $project_section->description = !empty($sanitizedInputs['description']) ? $sanitizedInputs['description'] : null;
            $project_section->service_sidebar_title = !empty($sanitizedInputs['service_sidebar_title']) ? $sanitizedInputs['service_sidebar_title'] : null;
            $project_section->service_sidebar_description = !empty($sanitizedInputs['service_sidebar_description']) ? $sanitizedInputs['service_sidebar_description'] : null;
            $project_section->is_active = 1;
            $project_section->created_by = $userId;
            $project_section->save();
            $response = ['responseCode' => 1, 'msg' => 'Service saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Service Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the service.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Service Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the service.'];
            return json_encode($response);
        }
    }

    public function change($id)
    {
        if (!validatePermissions('resume/service-sections/status-change/{id}')) {
            abort(403);
        }

        $service = ResumeServiceSectionsModel::where('id', sanitizeInput($id, 'int'))->first();

        if (!$service) {
            $response = ['responseCode' => 0, 'msg' => 'Service record not found.'];
            return json_encode($response);
        }
        if ($service) {
            $status = ($service->is_active == 0) ? 1 : 0;
            $service->is_active  = $status;
            $service->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'Service status changed successfully'];
        return json_encode($response);
    }

    /** Sorting Service  */
    public function sorting($id, $sort_number)
    {
        if (!validatePermissions('resume/service-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeServiceSectionsModel::where('id', $id)->first();

        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Service record not found.'];
            return json_encode($response);
        }
        $row->sort_number = $sort_number;
        $row->save();

        return 'Service sorted successfully.';
        // $response = ['responseCode' => 1, 'msg' => 'Service has been deleted'];
        // return json_encode($response);
    }

    /** Delete Service  */
    public function destroy($id)
    {
        if (!validatePermissions('resume/service-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeServiceSectionsModel::where('id', $id)->first();
        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Service record not found.'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Service has been deleted'];
        return json_encode($response);
    }
}
