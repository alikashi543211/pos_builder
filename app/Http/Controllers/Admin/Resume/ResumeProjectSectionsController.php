<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeExperienceSectionsModel;
use App\Models\Resume\ResumeProjectCategoriesModel;
use App\Models\Resume\ResumeProjectSectionDescriptionsModel;
use App\Models\Resume\ResumeProjectSectionImagesModel;
use App\Models\Resume\ResumeProjectSectionsModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeProjectSectionsController extends Controller
{
    public function index()
    {
        if (!validatePermissions('resume/project-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $projects = ResumeProjectSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();
        $data = [
            'pageTitle' => 'Project Section',
            'activeJobPosition' => $activeJobPosition,
            'projects' => $projects
        ];

        return view('admin.resume.project_sections.index')->with($data);
    }

    public function create()
    {
        if (!validatePermissions('resume/project-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $data = [];
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $data['experiences'] = ResumeProjectCategoriesModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->whereNotNull('category_name')
            ->orderBy('sort_number', 'ASC')
            ->get();
        $html = view('admin.resume.project_sections.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Add Descriptions  */
    public function addDescriptions($id)
    {
        if (!validatePermissions('resume/project-sections/descriptions/add/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $data = [];
        $data['row'] = ResumeProjectSectionsModel::where('id', sanitizeInput($id, 'int'))->first();
        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Project record not found.'];
            return json_encode($response);
        }
        $data['projectDescriptions'] = ResumeProjectSectionDescriptionsModel::where('resume_project_section_id', sanitizeInput($id, 'int'))
            ->orderBy('sort_number', 'ASC')
            ->get();
        $html = view('admin.resume.project_sections.add_descriptions')->with($data)->render();
        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Add Images  */
    public function addImages($id)
    {
        if (!validatePermissions('resume/project-sections/images/add/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $data = [];
        $data['row'] = ResumeProjectSectionsModel::where('id', $id)->first();
        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Project record not found.'];
            return json_encode($response);
        }
        $data['projectImages'] = ResumeProjectSectionImagesModel::where('resume_project_section_id', $id)->get();
        $html = view('admin.resume.project_sections.add_images')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit project  */
    public function edit($id)
    {
        if (!validatePermissions('resume/project-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $data = [];
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $data['row'] = ResumeProjectSectionsModel::where('id', sanitizeInput($id, 'int'))->first();
        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Project record not found.'];
            return json_encode($response);
        }
        $data['experiences'] = ResumeProjectCategoriesModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->whereNotNull('category_name')
            ->orderBy('sort_number', 'ASC')
            ->get();

        $html = view('admin.resume.project_sections.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }

    /** Save New project */
    public function store(Request $request)
    {
        if (!validatePermissions('resume/project-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'resume_project_category_id' => 'nullable|integer|exists:resume_project_categories,id',
            'project_name' => 'required|string|min:3|max:250',
            'project_slug' => 'required|string|min:3|max:250',
            'country' => 'nullable|string|min:3|max:100',
            'website_url' => 'nullable|string|min:3',
            'client_name' => 'nullable|string|min:3|max:250',
            'city' => 'nullable|string|min:3|max:100',
            'start_date' => 'nullable|date|max:100',
            'end_date' => 'nullable|date|max:100',
            'project_thumbnail' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string|min:20|max:500',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume project Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if ($project_section = ResumeProjectSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('project_slug', $sanitizedInputs['project_slug'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Project slug already exists.'];
                return json_encode($response);
            }
            if ($project_section = ResumeProjectSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('project_name', $sanitizedInputs['project_name'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Project name already exists.'];
                return json_encode($response);
            }
            $project_section = new ResumeProjectSectionsModel();
            if ($request->hasFile('project_thumbnail')) {
                if (isset($sanitizedInputs['project_thumbnail']) && $request->hasFile('project_thumbnail')) {
                    $project_section->project_thumbnail = $this->resizeAndSaveImage($sanitizedInputs['project_thumbnail'], 'media/' . Auth::guard('admin')->user()->user_name . '/project_thumbnails', $request->file('project_thumbnail')->getClientOriginalName(), 500, 350);
                }
            }
            $project_section->job_position_id = $activeJobPositionId;
            $project_section->resume_project_category_id = !empty($sanitizedInputs['resume_project_category_id']) ? $sanitizedInputs['resume_project_category_id'] :  null;
            $project_section->project_name = !empty($sanitizedInputs['project_name']) ? $sanitizedInputs['project_name'] : null;
            $project_section->project_slug = !empty($sanitizedInputs['project_slug']) ? $sanitizedInputs['project_slug'] : null;
            $project_section->country = !empty($sanitizedInputs['country']) ? $sanitizedInputs['country'] : null;
            $project_section->client_name = !empty($sanitizedInputs['client_name']) ? $sanitizedInputs['client_name'] : null;
            $project_section->website_url = !empty($sanitizedInputs['website_url']) ? $sanitizedInputs['website_url'] : null;
            $project_section->city = !empty($sanitizedInputs['city']) ? $sanitizedInputs['city'] : null;
            $project_section->start_date = !empty($sanitizedInputs['start_date']) ? $sanitizedInputs['start_date'] : null;
            $project_section->end_date = !empty($sanitizedInputs['end_date']) ? $sanitizedInputs['end_date'] : null;
            $project_section->description = !empty($sanitizedInputs['description']) ? $sanitizedInputs['description'] : null;
            $project_section->is_active = 1;
            $project_section->created_by = $userId;
            $project_section->save();

            /** Store Project 5 Nullable _ Project Descriptions _ */
            for ($j = 0; $j < 5; $j++) {
                $description = new ResumeProjectSectionDescriptionsModel();
                $description->description = null;
                $description->sort_number = ($j + 1);
                $description->resume_project_section_id = $project_section->id;
                $description->save();
            }
            $response = ['responseCode' => 1, 'msg' => 'Project saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('project Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the project.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('project Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the project.'];
            return json_encode($response);
        }
    }

    /** Save Multiple project Descriptions */
    public function storeDescriptions(Request $request, $id)
    {
        if (!validatePermissions('resume/project-sections/descriptions/add/{id}')) {
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

        $data['row'] = ResumeProjectSectionsModel::where('id', sanitizeInput($id, 'int'))->first();
        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Project record not found.'];
            return json_encode($response);
        }
        try {
            // dd($sanitizedInputs['description_points']);
            if (isset($sanitizedInputs['description_points']) && is_array($sanitizedInputs['description_points'])) {
                foreach ($sanitizedInputs['description_points'] as $descriptionItem) {
                    $descriptionItem = (object) $descriptionItem;
                    if (isset($descriptionItem)) {
                        if (isset($descriptionItem->id) && !empty($descriptionItem->id)) {
                            $description_point = ResumeProjectSectionDescriptionsModel::whereId($descriptionItem->id)->first();
                        } else {
                            $description_point = new ResumeProjectSectionDescriptionsModel();
                        }

                        $description_point->resume_project_section_id = sanitizeInput($id, 'int');
                        $description_point->description = !empty($descriptionItem->description) ? $descriptionItem->description : null;
                        $description_point->sort_number = $descriptionItem->sort_number ?? null;
                        $description_point->save();
                    }
                }
            }
            $response = ['responseCode' => 1, 'msg' => 'Project descriptions saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('project Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the project.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('project Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the project.'];
            return json_encode($response);
        }
    }

    /** Save Multiple project Images */
    public function storeImages(Request $request, $id)
    {
        if (!validatePermissions('resume/project-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $project_section = ResumeProjectSectionsModel::where('id', sanitizeInput($id, 'int'))->first();
        if (!$project_section) {
            $response = ['responseCode' => 0, 'msg' => 'Project record not found.'];
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
                'project_images.array' => 'Select project images to upload.',
                'project_images.array' => 'The project images must be an array.',
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
            /** Create or Update Resume project Section */
            // if(ResumeProjectSectionImagesModel::where('resume_project_section_id', $id)->count() > 0){
            //     ResumeProjectSectionImagesModel::where('resume_project_section_id', $id)->delete();
            // }
            // dd($sanitizedInputs['project_images']);
            foreach ($sanitizedInputs['project_images'] as $key => $imageFile) {
                // Check if the file exists and is valid
                if ($request->hasFile("project_images.$key")) {
                    $uploadedFile = $request->file("project_images.$key");

                    // Ensure the uploaded file is set and valid
                    if ($uploadedFile) {
                        $newImage = new ResumeProjectSectionImagesModel();
                        $newImage->resume_project_section_id = $id;

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
            $projectImages = ResumeProjectSectionImagesModel::where('resume_project_section_id', $id)->get();
            $response = ['responseCode' => 1, 'msg' => 'Project images uploaded successfully.', 'data' => $projectImages];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('project Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the project.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('project Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the project.'];
            return json_encode($response);
        }
    }

    /** Delete Project Image */
    public function deleteProjectImage(Request $request, $id)
    {
        if (!validatePermissions('resume/project-sections/images/delete/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        try {
            $project_section_image = ResumeProjectSectionImagesModel::where('id', sanitizeInput($id, 'int'))->first();
            if (!$project_section_image) {
                $response = ['responseCode' => 0, 'msg' => 'Project record not found.'];
                return json_encode($response);
            }
            $project_section_image->delete();
            // return 'Project Image Deleted Successfully';
            $response = ['responseCode' => 1, 'msg' => 'Project image deleted successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('project Section image Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while deleting the project image.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('project Section image Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while deleting the project image.'];
            return json_encode($response);
        }
    }



    /** Update Existing project */
    public function update(Request $request, $id)
    {
        if (!validatePermissions('resume/project-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'resume_project_category_id' => 'nullable|integer|exists:resume_project_categories,id',
            'project_name' => 'required|string|min:3|max:250',
            'project_slug' => 'required|string|min:3|max:250',
            'country' => 'nullable|string|min:3|max:100',
            'website_url' => 'nullable|string|min:3',
            'client_name' => 'nullable|string|min:3|max:250',
            'city' => 'nullable|string|min:3|max:100',
            'start_date' => 'nullable|date|max:100',
            'end_date' => 'nullable|date|max:100',
            'project_thumbnail' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'description' => 'nullable|string|min:20|max:500',
        ]);


        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            // Validate id exists or not
            $project_section = ResumeProjectSectionsModel::whereId(sanitizeInput($id, 'int'))->first();
            if (!$project_section) {
                $response = ['responseCode' => 0, 'msg' => 'Project record not found.'];
                return json_encode($response);
            }

            /** Create or Update Resume project Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if (ResumeProjectSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('project_slug', $sanitizedInputs['project_slug'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Project slug already exists.'];
                return json_encode($response);
            }
            // Check Name already exist or not
            if (ResumeProjectSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('project_name', $sanitizedInputs['project_name'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Project name already exists.'];
                return json_encode($response);
            }
            // Update data
            if ($request->hasFile('project_thumbnail')) {
                if (isset($sanitizedInputs['project_thumbnail']) && $request->hasFile('project_thumbnail')) {
                    $project_section->project_thumbnail = $this->resizeAndSaveImage($sanitizedInputs['project_thumbnail'], 'media/' . Auth::guard('admin')->user()->user_name . '/project_thumbnails', $request->file('project_thumbnail')->getClientOriginalName(), 500, 350);
                }
            }
            $project_section->job_position_id = $activeJobPositionId;
            $project_section->resume_project_category_id = !empty($sanitizedInputs['resume_project_category_id']) ? $sanitizedInputs['resume_project_category_id'] :  null;
            $project_section->project_name = !empty($sanitizedInputs['project_name']) ? $sanitizedInputs['project_name'] : null;
            $project_section->project_slug = !empty($sanitizedInputs['project_slug']) ? $sanitizedInputs['project_slug'] : null;
            $project_section->country = !empty($sanitizedInputs['country']) ? $sanitizedInputs['country'] : null;
            $project_section->client_name = !empty($sanitizedInputs['client_name']) ? $sanitizedInputs['client_name'] : null;
            $project_section->website_url = !empty($sanitizedInputs['website_url']) ? $sanitizedInputs['website_url'] : null;
            $project_section->city = !empty($sanitizedInputs['city']) ? $sanitizedInputs['city'] : null;
            $project_section->start_date = !empty($sanitizedInputs['start_date']) ? $sanitizedInputs['start_date'] : null;
            $project_section->end_date = !empty($sanitizedInputs['end_date']) ? $sanitizedInputs['end_date'] : null;
            $project_section->description = !empty($sanitizedInputs['description']) ? $sanitizedInputs['description'] : null;
            $project_section->is_active = 1;
            $project_section->created_by = $userId;
            $project_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Project saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Project Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the project.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('project Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the project.'];
            return json_encode($response);
        }
    }

    public function change($id)
    {
        if (!validatePermissions('resume/project-sections/status-change/{id}')) {
            abort(403);
        }

        $project = ResumeProjectSectionsModel::where('id', sanitizeInput($id, 'int'))->first();

        if (!$project) {
            $response = ['responseCode' => 0, 'msg' => 'project record not found.'];
            return json_encode($response);
        }
        if ($project) {
            $status = ($project->is_active == 0) ? 1 : 0;
            $project->is_active  = $status;
            $project->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'project status changed successfully'];
        return json_encode($response);
    }

    /** Sorting project  */
    public function sorting($id, $sort_number)
    {
        if (!validatePermissions('resume/project-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeProjectSectionsModel::where('id', $id)->first();

        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Project record not found.'];
            return json_encode($response);
        }
        $row->sort_number = $sort_number;
        $row->save();

        return 'project sorted successfully.';
        // $response = ['responseCode' => 1, 'msg' => 'project has been deleted'];
        // return json_encode($response);
    }

    /** Delete project  */
    public function destroy($id)
    {
        if (!validatePermissions('resume/project-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeProjectSectionsModel::where('id', $id)->first();
        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Project record not found.'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Project has been deleted'];
        return json_encode($response);
    }
}
