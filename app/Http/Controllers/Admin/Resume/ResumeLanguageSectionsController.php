<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeLanguageSectionsModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeLanguageSectionsController extends Controller
{
    public function index()
    {
        if(!validatePermissions('resume/language-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $resumeLanguages = ResumeLanguageSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();

        $data = [
            'pageTitle'=>'Language Section',
            'activeJobPosition' => $activeJobPosition,
            'resumeLanguages' => $resumeLanguages
        ];

        return view('admin.resume.language_sections.index')->with($data);
    }

    public function create()
    {
        if(!validatePermissions('resume/language-sections/add')) {
            abort(403);
        }
        $data = [];
        $html = view('admin.resume.language_sections.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit language  */
    public function edit($id)
    {
        if(!validatePermissions('resume/language-sections/edit/{id}')) {
            abort(403);
        }
        $data = [];
        $data = [
            'pageTitle' => 'Language Edit'
        ];
        $data['row'] = ResumeLanguageSectionsModel::where('id',$id)->first();
        if(!$data['row']){
            $response = ['responseCode' => 0, 'msg' => 'language record not found.'];
            return json_encode($response);
        }

        $html = view('admin.resume.language_sections.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);

    }

    /** Save New language */
    public function store(Request $request)
    {
        if(!validatePermissions('resume/language-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'language'=>'nullable|string|min:3|max:100',
            'language_rating'=>'nullable|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if($header_section = ResumeLanguageSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('language_title', $sanitizedInputs['language_title'])
                ->first())
            {
                $response = ['responseCode' => 0, 'msg' => 'language already exists.'];
                return json_encode($response);
            }
            $header_section = new ResumeLanguageSectionsModel();
            $header_section->job_position_id = $activeJobPositionId;
            $header_section->language_title = $sanitizedInputs['language_title'] ?? null;
            $header_section->language_rating = $sanitizedInputs['language_rating'] ?? null;
            $header_section->is_active = 1;
            $header_section->created_by = $userId;
            $header_section->save();

            $response = ['responseCode' => 1, 'msg' => 'language saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('language Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the language.'];
            return json_encode($response);

        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('language Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the language.'];
            return json_encode($response);
        }
    }

    /** Update Existing language */
    public function update(Request $request, $id)
    {
        if(!validatePermissions('resume/language-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'language_title'=>'nullable|string|min:3|max:100',
            'language_rating'=>'nullable|numeric|min:1|max:5',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if($header_section = ResumeLanguageSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('language_title', $sanitizedInputs['language_title'])
                ->first())
            {
                $response = ['responseCode' => 0, 'msg' => 'language already exists.'];
                return json_encode($response);
            }
            $header_section = ResumeLanguageSectionsModel::whereId(sanitizeInput($id, 'int'))->first();
            $header_section->job_position_id = $activeJobPositionId;
            $header_section->language_title = $sanitizedInputs['language_title'] ?? null;
            $header_section->language_rating = $sanitizedInputs['language_rating'] ?? null;
            $header_section->is_active = 1;
            $header_section->created_by = $userId;
            $header_section->save();

            $response = ['responseCode' => 1, 'msg' => 'language saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('language Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the language.'];
            return json_encode($response);

        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('language Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the language.'];
            return json_encode($response);
        }
    }

    public function change($id){
        if(!validatePermissions('resume/language-sections/status-change/{id}')) {
            abort(403);
        }

        $language = ResumeLanguageSectionsModel::where('id', sanitizeInput($id, 'int'))->first();

        if(!$language){
            $response = ['responseCode' => 0, 'msg' => 'language record not found.'];
            return json_encode($response);
        }
        if($language){
            $status = ($language->is_active==0)?1:0;
            $language->is_active  = $status;
            $language->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'language status changed successfully'];
        return json_encode($response);
    }

    /** Sorting language  */
    public function sorting($id, $sort_number)
    {
        if(!validatePermissions('resume/language-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeLanguageSectionsModel::where('id', $id)->first();

        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'language record not found.'];
            return json_encode($response);
        }
        $row->sort_number = $sort_number;
        $row->save();

        return 'language sorted successfully.';
        // $response = ['responseCode' => 1, 'msg' => 'language has been deleted'];
        // return json_encode($response);
    }

    /** Delete language  */
    public function destroy($id)
    {
        if(!validatePermissions('resume/language-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeLanguageSectionsModel::where('id', $id)->first();
        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'language record not found.'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'language has been deleted'];
        return json_encode($response);
    }

}

