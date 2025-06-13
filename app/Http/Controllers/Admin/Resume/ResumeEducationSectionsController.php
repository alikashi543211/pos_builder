<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeEducationSectionsModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeEducationSectionsController extends Controller
{
    public function index()
    {
        if(!validatePermissions('resume/education-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $educations = ResumeEducationSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();
        // dd($educations->display_picture);
        $data = [
            'pageTitle' => 'Education Section',
            'activeJobPosition' => $activeJobPosition,
            'educations' => $educations
        ];

        return view('admin.resume.education_sections.index')->with($data);
    }

    public function create()
    {
        if(!validatePermissions('resume/education-sections/add')) {
            abort(403);
        }
        $data = [];
        $html = view('admin.resume.education_sections.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit education  */
    public function edit($id)
    {
        if(!validatePermissions('resume/education-sections/edit/{id}')) {
            abort(403);
        }
        $data = [];
        $data = [
            'pageTitle' => 'education Edit'
        ];
        $data['row'] = ResumeEducationSectionsModel::where('id',$id)->first();
        if(!$data['row']){
            $response = ['responseCode' => 0, 'msg' => 'education record not found.'];
            return json_encode($response);
        }

        $html = view('admin.resume.education_sections.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);

    }

    /** Save New education */
    public function store(Request $request)
    {
        if(!validatePermissions('resume/education-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'degree_name' => 'nullable|string|min:3|max:100',
            'institute_name' => 'nullable|string|min:3|max:100',
            'country' => 'nullable|string|min:3|max:100',
            'city'=>'nullable|string|min:3|max:100',
            'start_date' => 'nullable|date|max:100',
            'end_date' => 'nullable|date|max:100',
            'grades' => 'nullable|string|min:3|max:100',
            'description' => 'nullable|string|min:20|max:150',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Education Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if($education_section = ResumeEducationSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('degree_name', $sanitizedInputs['degree_name'])
                ->first())
            {
                $response = ['responseCode' => 0, 'msg' => 'Degree name already exists.'];
                return json_encode($response);
            }
            $education_section = new ResumeEducationSectionsModel();
            $education_section->job_position_id = $activeJobPositionId;
            $education_section->degree_name = $sanitizedInputs['degree_name'] ?? null;
            $education_section->institute_name = $sanitizedInputs['institute_name'] ?? null;
            $education_section->country = $sanitizedInputs['country'] ?? null;
            $education_section->city = $sanitizedInputs['city'] ?? null;
            $education_section->start_date = $sanitizedInputs['start_date'] ?? null;
            $education_section->end_date = $sanitizedInputs['end_date'] ?? null;
            $education_section->grades = $sanitizedInputs['grades'] ?? null;
            $education_section->description = $sanitizedInputs['description'] ?? null;
            $education_section->is_active = 1;
            $education_section->created_by = $userId;
            $education_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Education saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Education Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the education.'];
            return json_encode($response);

        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Education Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the education.'];
            return json_encode($response);
        }
    }

    /** Update Existing education */
    public function update(Request $request, $id)
    {
        if(!validatePermissions('resume/education-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'degree_name' => 'nullable|string|min:3|max:100',
            'institute_name' => 'nullable|string|min:3|max:100',
            'country' => 'nullable|string|min:3|max:100',
            'city'=>'nullable|string|min:3|max:100',
            'start_date' => 'nullable|date|max:100',
            'end_date' => 'nullable|date|max:100',
            'grades' => 'nullable|string|min:3|max:100',
            'description' => 'nullable|string|min:20|max:150',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            // Validate id exists or not
            $education_section = ResumeEducationSectionsModel::whereId(sanitizeInput($id, 'int'))->first();
            if(!$education_section){
                $response = ['responseCode' => 0, 'msg' => 'Education record not found.'];
                return json_encode($response);
            }

            /** Create or Update Resume Education Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;

            // Check Name already exist or not
            if(ResumeEducationSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('degree_name', $sanitizedInputs['degree_name'])
                ->first())
            {
                $response = ['responseCode' => 0, 'msg' => 'Degree name already exists.'];
                return json_encode($response);
            }
            // Update data
            $education_section->job_position_id = $activeJobPositionId;
            $education_section->degree_name = $sanitizedInputs['degree_name'] ?? null;
            $education_section->institute_name = $sanitizedInputs['institute_name'] ?? null;
            $education_section->country = $sanitizedInputs['country'] ?? null;
            $education_section->city = $sanitizedInputs['city'] ?? null;
            $education_section->start_date = $sanitizedInputs['start_date'] ?? null;
            $education_section->end_date = $sanitizedInputs['end_date'] ?? null;
            $education_section->grades = $sanitizedInputs['grades'] ?? null;
            $education_section->description = $sanitizedInputs['description'] ?? null;
            $education_section->is_active = 1;
            $education_section->created_by = $userId;
            $education_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Education saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Education Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the education.'];
            return json_encode($response);

        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Education Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the education.'];
            return json_encode($response);
        }
    }

    public function change($id){
        if(!validatePermissions('resume/education-sections/status-change/{id}')) {
            abort(403);
        }

        $education = ResumeEducationSectionsModel::where('id', sanitizeInput($id, 'int'))->first();

        if(!$education){
            $response = ['responseCode' => 0, 'msg' => 'Education record not found.'];
            return json_encode($response);
        }
        if($education){
            $status = ($education->is_active==0)?1:0;
            $education->is_active  = $status;
            $education->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'Education status changed successfully'];
        return json_encode($response);
    }

    /** Sorting education  */
    public function sorting($id, $sort_number)
    {
        if(!validatePermissions('resume/education-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeEducationSectionsModel::where('id', $id)->first();

        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'Education record not found.'];
            return json_encode($response);
        }
        $row->sort_number = $sort_number;
        $row->save();

        return 'education sorted successfully.';
        // $response = ['responseCode' => 1, 'msg' => 'education has been deleted'];
        // return json_encode($response);
    }

    /** Delete education  */
    public function destroy($id)
    {
        if(!validatePermissions('resume/education-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeEducationSectionsModel::where('id', $id)->first();
        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'Education record not found.'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Education has been deleted'];
        return json_encode($response);
    }

}
