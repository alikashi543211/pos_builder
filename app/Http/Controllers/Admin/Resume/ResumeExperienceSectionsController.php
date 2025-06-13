<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeExperienceSectionDescriptionsModel;
use App\Models\Resume\ResumeExperienceSectionsModel;
use App\Resume\ResumeExperienceSectionDescription;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeExperienceSectionsController extends Controller
{
    public function index()
    {
        if(!validatePermissions('resume/experience-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $experiences = ResumeExperienceSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();
        // dd($experiences->display_picture);
        $data = [
            'pageTitle' => 'Experience Section',
            'activeJobPosition' => $activeJobPosition,
            'experiences' => $experiences
        ];

        return view('admin.resume.experience_sections.index')->with($data);
    }

    public function create()
    {
        if(!validatePermissions('resume/experience-sections/add')) {
            abort(403);
        }
        $data = [];
        $html = view('admin.resume.experience_sections.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Add Descriptions  */
    public function addDescriptions($id)
    {
        if(!validatePermissions('resume/experience-sections/descriptions/add/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $data = [];
        $data['row'] = ResumeExperienceSectionsModel::where('id', sanitizeInput($id, 'int'))->first();
        if(!$data['row']){
            $response = ['responseCode' => 0, 'msg' => 'Project record not found.'];
            return json_encode($response);
        }
        $data['experienceDescriptions'] = ResumeExperienceSectionDescriptionsModel::where('resume_experience_section_id', sanitizeInput($id, 'int'))
            ->orderBy('sort_number', 'ASC')
            ->get();
        $html = view('admin.resume.experience_sections.add_descriptions')->with($data)->render();
        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit experience  */
    public function edit($id)
    {
        if(!validatePermissions('resume/experience-sections/edit/{id}')) {
            abort(403);
        }
        $data = [];
        $data = [
            'pageTitle' => 'Experience Edit'
        ];
        // Description Points
        $descriptionPointsFormatted = [];
        $doneIndex = 0;
        $remainingIndex = 0;
        $descriptionPoints = ResumeExperienceSectionDescriptionsModel::where('resume_experience_section_id', sanitizeInput($id, 'int'))->get()->toArray();
        foreach($descriptionPoints as $point){
            $doneIndex = $doneIndex + 1;
            $descriptionPointsFormatted[$doneIndex]['description_id'] = $point['id'];
            $descriptionPointsFormatted[$doneIndex]['sort_number'] = $point['sort_number'];
            $descriptionPointsFormatted[$doneIndex]['description'] = $point['description'];
        }

        for($k = $doneIndex; $k <= 5; $k++){
            $descriptionPointsFormatted[$k]['description_id'] = '';
            $descriptionPointsFormatted[$k]['sort_number'] = '';
            $descriptionPointsFormatted[$k]['description'] = '';
        }

        $data['row'] = ResumeExperienceSectionsModel::where('id',$id)->first();
        $data['descriptionPointsFormatted'] = $descriptionPointsFormatted;

        if(!$data['row']){
            $response = ['responseCode' => 0, 'msg' => 'Experience record not found.'];
            return json_encode($response);
        }

        $html = view('admin.resume.experience_sections.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);

    }

    /** Save New experience */
    public function store(Request $request)
    {
        if(!validatePermissions('resume/experience-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'company_name' => 'nullable|string|min:3|max:100',
            'company_logo' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'designation_name' => 'nullable|string|min:3|max:100',
            'country' => 'nullable|string|min:3|max:100',
            'city'=>'nullable|string|min:3|max:100',
            'start_date' => 'nullable|date|max:100',
            'end_date' => 'nullable|date|max:100',
            'description' => 'nullable|string|min:100|max:500',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume experience Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if($experience_section = ResumeExperienceSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('company_name', $sanitizedInputs['company_name'])
                ->first())
            {
                $response = ['responseCode' => 0, 'msg' => 'Company name already exists.'];
                return json_encode($response);
            }
            $experience_section = new ResumeExperienceSectionsModel();
            if ($request->hasFile('company_logo')) {
                if (isset($sanitizedInputs['company_logo']) && $request->hasFile('company_logo')) {
                    $experience_section->company_logo = $this->resizeAndSaveImage($sanitizedInputs['company_logo'], 'media/' . Auth::guard('admin')->user()->user_name . '/company_logos', $request->file('company_logo')->getClientOriginalName(), 500, 350);
                }
            }
            $experience_section->job_position_id = $activeJobPositionId;
            $experience_section->company_name = $sanitizedInputs['company_name'] ?? null;
            $experience_section->designation_name = $sanitizedInputs['designation_name'] ?? null;
            $experience_section->country = $sanitizedInputs['country'] ?? null;
            $experience_section->city = $sanitizedInputs['city'] ?? null;
            $experience_section->start_date = $sanitizedInputs['start_date'] ?? null;
            $experience_section->end_date = $sanitizedInputs['end_date'] ?? null;
            $experience_section->description = $sanitizedInputs['description'] ?? null;
            $experience_section->is_active = 1;
            $experience_section->created_by = $userId;
            $experience_section->save();

            /** Store Experience 5 Nullable _ Project Descriptions _ */
            for($j = 0; $j < 5; $j++)
            {
                $description = new ResumeExperienceSectionDescriptionsModel();
                $description->description = null;
                $description->sort_number = ($j+1);
                $description->resume_experience_section_id = $experience_section->id;
                $description->save();
            }

            $response = ['responseCode' => 1, 'msg' => 'Experience saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Experience Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the experience.'];
            return json_encode($response);

        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Experience Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the experience.'];
            return json_encode($response);
        }
    }

    /** Save Multiple project Descriptions */
    public function storeDescriptions(Request $request, $id)
    {
        if(!validatePermissions('resume/experience-sections/descriptions/add/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return json_encode($response);
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

        $data['row'] = ResumeExperienceSectionsModel::where('id',sanitizeInput($id, 'int'))->first();
        if(!$data['row']){
            $response = ['responseCode' => 0, 'msg' => 'Experience record not found.'];
            return json_encode($response);
        }
        try {
            // dd($sanitizedInputs['description_points']);
            if(isset($sanitizedInputs['description_points']) && is_array($sanitizedInputs['description_points']))
            {
                foreach($sanitizedInputs['description_points'] as $descriptionItem)
                {
                    $descriptionItem = (object) $descriptionItem;
                    if(isset($descriptionItem)){
                        if(isset($descriptionItem->id) && !empty($descriptionItem->id))
                        {
                            $description_point = ResumeExperienceSectionDescriptionsModel::whereId($descriptionItem->id)->first();
                        }else{
                            $description_point = new ResumeExperienceSectionDescriptionsModel();
                        }

                        $description_point->resume_experience_section_id = sanitizeInput($id, 'int');
                        $description_point->description = !empty($descriptionItem->description) ? $descriptionItem->description : null;
                        $description_point->sort_number = $descriptionItem->sort_number ?? null;
                        $description_point->save();
                    }
                }
            }
            $response = ['responseCode' => 1, 'msg' => 'Experience descriptions saved successfully.'];
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

    /** Update Existing experience */
    public function update(Request $request, $id)
    {
        if(!validatePermissions('resume/experience-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'company_name' => 'nullable|string|min:3|max:100',
            'company_logo' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'designation_name' => 'nullable|string|min:3|max:100',
            'country' => 'nullable|string|min:3|max:100',
            'city'=>'nullable|string|min:3|max:100',
            'start_date' => 'nullable|date|max:100',
            'end_date' => 'nullable|date|max:100',
            'description' => 'nullable|string|min:100|max:500',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            // Validate id exists or not
            $experience_section = ResumeExperienceSectionsModel::whereId(sanitizeInput($id, 'int'))->first();
            if(!$experience_section){
                $response = ['responseCode' => 0, 'msg' => 'Experience record not found.'];
                return json_encode($response);
            }

            /** Create or Update Resume experience Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;

            // Check Name already exist or not
            if(ResumeExperienceSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('company_name', $sanitizedInputs['company_name'])
                ->first())
            {
                $response = ['responseCode' => 0, 'msg' => 'Company name already exists.'];
                return json_encode($response);
            }
            // Update data
            if ($request->hasFile('company_logo')) {
                if (isset($sanitizedInputs['company_logo']) && $request->hasFile('company_logo')) {
                    $experience_section->company_logo = $this->resizeAndSaveImage($sanitizedInputs['company_logo'], 'media/' . Auth::guard('admin')->user()->user_name . '/company_logos', $request->file('company_logo')->getClientOriginalName(), 500, 350);
                }
            }
            $experience_section->job_position_id = $activeJobPositionId;
            $experience_section->company_name = $sanitizedInputs['company_name'] ?? null;
            $experience_section->designation_name = $sanitizedInputs['designation_name'] ?? null;
            $experience_section->country = $sanitizedInputs['country'] ?? null;
            $experience_section->city = $sanitizedInputs['city'] ?? null;
            $experience_section->start_date = $sanitizedInputs['start_date'] ?? null;
            $experience_section->end_date = $sanitizedInputs['end_date'] ?? null;
            $experience_section->description = $sanitizedInputs['description'] ?? null;
            $experience_section->is_active = 1;
            $experience_section->created_by = $userId;
            $experience_section->save();

            if(isset($sanitizedInputs['description_points']) && is_array($sanitizedInputs['description_points']))
            {
                for($i = 1; $i < 5; $i++)
                {
                    if(isset($sanitizedInputs['description_points'][$i])){
                        if(isset($sanitizedInputs['description_point_id'][$i]) && !empty($sanitizedInputs['description_point_id'][$i]))
                        {
                            $description_point = ResumeExperienceSectionDescriptionsModel::whereId($sanitizedInputs['description_point_id'][$i])->first();
                        }else{
                            $description_point = new ResumeExperienceSectionDescriptionsModel();
                        }

                        $description_point->resume_experience_section_id = $experience_section->id;
                        $description_point->description = $sanitizedInputs['description_points'][$i] ?? null;
                        $description_point->sort_number = $sanitizedInputs['description_point_sort_numbers'][$i] ?? null;
                        $description_point->save();
                    }
                }
            }

            $response = ['responseCode' => 1, 'msg' => 'Experience saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Experience Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the experience.'];
            return json_encode($response);

        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Experience Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the experience.'];
            return json_encode($response);
        }
    }

    public function change($id){
        if(!validatePermissions('resume/experience-sections/status-change/{id}')) {
            abort(403);
        }

        $experience = ResumeExperienceSectionsModel::where('id', sanitizeInput($id, 'int'))->first();

        if(!$experience){
            $response = ['responseCode' => 0, 'msg' => 'Experience record not found.'];
            return json_encode($response);
        }
        if($experience){
            $status = ($experience->is_active==0)?1:0;
            $experience->is_active  = $status;
            $experience->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'Experience status changed successfully'];
        return json_encode($response);
    }

    /** Sorting experience  */
    public function sorting($id, $sort_number)
    {
        if(!validatePermissions('resume/experience-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeExperienceSectionsModel::where('id', $id)->first();

        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'Experience record not found.'];
            return json_encode($response);
        }
        $row->sort_number = $sort_number;
        $row->save();

        return 'Experience sorted successfully.';
        // $response = ['responseCode' => 1, 'msg' => 'experience has been deleted'];
        // return json_encode($response);
    }

    /** Delete experience  */
    public function destroy($id)
    {
        if(!validatePermissions('resume/experience-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeExperienceSectionsModel::where('id', $id)->first();
        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'Experience record not found.'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Experience has been deleted'];
        return json_encode($response);
    }

}
