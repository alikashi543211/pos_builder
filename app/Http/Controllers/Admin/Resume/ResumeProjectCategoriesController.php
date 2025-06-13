<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeProjectCategoriesModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeProjectCategoriesController extends Controller
{
    public function index()
    {
        if (!validatePermissions('resume/project-categories')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $projectCategories = ResumeProjectCategoriesModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();
        // dd($projectCategories->display_picture);
        $data = [
            'pageTitle' => 'Project Categories',
            'activeJobPosition' => $activeJobPosition,
            'projectCategories' => $projectCategories
        ];

        return view('admin.resume.project_categories.index')->with($data);
    }

    public function create()
    {
        if (!validatePermissions('resume/project-categories/add')) {
            abort(403);
        }
        $data = [];
        $html = view('admin.resume.project_categories.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit Project category  */
    public function edit($id)
    {
        if (!validatePermissions('resume/project-categories/edit/{id}')) {
            abort(403);
        }
        $data = [];
        $data = [
            'pageTitle' => 'Project Category Edit'
        ];
        $data['row'] = ResumeProjectCategoriesModel::where('id', $id)->first();
        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Project category record not found.'];
            return json_encode($response);
        }

        $html = view('admin.resume.project_categories.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }

    /** Save New Project category */
    public function store(Request $request)
    {
        if (!validatePermissions('resume/project-categories/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return json_encode($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'category_name' => 'required|string|min:3|max:250',
            'category_slug' => 'required|string|min:3|max:250',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if ($header_section = ResumeProjectCategoriesModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('category_slug', $sanitizedInputs['category_slug'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Project category slug already exists.'];
                return json_encode($response);
            }
            if ($header_section = ResumeProjectCategoriesModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('category_name', $sanitizedInputs['category_name'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Project category already exists.'];
                return json_encode($response);
            }
            $header_section = new ResumeProjectCategoriesModel();
            $header_section->job_position_id = $activeJobPositionId;
            $header_section->category_name = $sanitizedInputs['category_name'] ?? null;
            $header_section->category_slug = $sanitizedInputs['category_slug'] ?? null;
            $header_section->is_active = 1;
            $header_section->created_by = $userId;
            $header_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Project category saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Project category Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the Project category.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Project category Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the Project category.'];
            return json_encode($response);
        }
    }

    /** Update Existing Project category */
    public function update(Request $request, $id)
    {
        if (!validatePermissions('resume/project-categories/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return json_encode($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'category_name' => 'required|string|min:3|max:250',
            'category_slug' => 'required|string|min:3|max:250',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if ($header_section = ResumeProjectCategoriesModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('category_slug', $sanitizedInputs['category_slug'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Project category slug already exists.'];
                return json_encode($response);
            }
            if ($header_section = ResumeProjectCategoriesModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('category_name', $sanitizedInputs['category_name'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Project category already exists.'];
                return json_encode($response);
            }
            $header_section = ResumeProjectCategoriesModel::whereId(sanitizeInput($id, 'int'))->first();
            $header_section->job_position_id = $activeJobPositionId;
            $header_section->category_name = $sanitizedInputs['category_name'] ?? null;
            $header_section->category_slug = $sanitizedInputs['category_slug'] ?? null;
            $header_section->is_active = 1;
            $header_section->created_by = $userId;
            $header_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Project category saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Project category Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the Project category.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Project category Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the Project category.'];
            return json_encode($response);
        }
    }

    public function change($id)
    {
        if (!validatePermissions('resume/project-categories/status-change/{id}')) {
            abort(403);
        }

        $project_category = ResumeProjectCategoriesModel::where('id', sanitizeInput($id, 'int'))->first();

        if (!$project_category) {
            $response = ['responseCode' => 0, 'msg' => 'Project category record not found.'];
            return json_encode($response);
        }
        if ($project_category) {
            $status = ($project_category->is_active == 0) ? 1 : 0;
            $project_category->is_active  = $status;
            $project_category->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'Project category status changed successfully'];
        return json_encode($response);
    }

    /** Sorting Project category  */
    public function sorting($id, $sort_number)
    {
        if (!validatePermissions('resume/project-categories/delete/{id}')) {
            abort(403);
        }
        $row = ResumeProjectCategoriesModel::where('id', $id)->first();

        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Project category record not found.'];
            return json_encode($response);
        }
        $row->sort_number = $sort_number;
        $row->save();

        return 'Project category sorted successfully.';
        // $response = ['responseCode' => 1, 'msg' => 'Project category has been deleted'];
        // return json_encode($response);
    }

    /** Delete Project category  */
    public function destroy($id)
    {
        if (!validatePermissions('resume/project-categories/delete/{id}')) {
            abort(403);
        }
        $row = ResumeProjectCategoriesModel::where('id', $id)->first();
        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Project category record not found.'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Project category has been deleted'];
        return json_encode($response);
    }
}
