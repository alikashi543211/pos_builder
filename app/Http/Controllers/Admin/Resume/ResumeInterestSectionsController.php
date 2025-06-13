<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeInterestSectionsModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeInterestSectionsController extends Controller
{
    public function index()
    {
        if(!validatePermissions('resume/interest-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $interests = ResumeInterestSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();
        // dd($interests->display_picture);
        $data = [
            'pageTitle'=>'Interest Section',
            'activeJobPosition' => $activeJobPosition,
            'interests' => $interests
        ];

        return view('admin.resume.interest_sections.index')->with($data);
    }

    public function create()
    {
        if(!validatePermissions('resume/interest-sections/add')) {
            abort(403);
        }
        $data = [];
        $html = view('admin.resume.interest_sections.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit interest  */
    public function edit($id)
    {
        if(!validatePermissions('resume/interest-sections/edit/{id}')) {
            abort(403);
        }
        $data = [];
        $data = [
            'pageTitle' => 'Interest Edit'
        ];
        $data['row'] = ResumeInterestSectionsModel::where('id',$id)->first();
        if(!$data['row']){
            $response = ['responseCode' => 0, 'msg' => 'Interest record not found.'];
            return json_encode($response);
        }

        $html = view('admin.resume.interest_sections.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);

    }

    /** Save New Interest */
    public function store(Request $request)
    {
        if(!validatePermissions('resume/interest-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'interest'=>'nullable|string|min:3|max:100',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if($header_section = ResumeInterestSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('interest', $sanitizedInputs['interest'])
                ->first())
            {
                $response = ['responseCode' => 0, 'msg' => 'Interest already exists.'];
                return json_encode($response);
            }
            $interest_section = new ResumeInterestSectionsModel();
            $interest_section->job_position_id = $activeJobPositionId;
            $interest_section->interest = $sanitizedInputs['interest'] ?? null;
            $interest_section->is_active = 1;
            $interest_section->created_by = $userId;
            $interest_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Interest saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('interest Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the interest.'];
            return json_encode($response);

        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('interest Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the interest.'];
            return json_encode($response);
        }
    }

    /** Update Existing interest */
    public function update(Request $request, $id)
    {
        if(!validatePermissions('resume/interest-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'interest'=>'nullable|string|min:3|max:100',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if($header_section = ResumeInterestSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('interest', $sanitizedInputs['interest'])
                ->first())
            {
                $response = ['responseCode' => 0, 'msg' => 'interest already exists.'];
                return json_encode($response);
            }
            $interest_section = ResumeInterestSectionsModel::whereId(sanitizeInput($id, 'int'))->first();
            $interest_section->job_position_id = $activeJobPositionId;
            $interest_section->interest = $sanitizedInputs['interest'] ?? null;
            $interest_section->is_active = 1;
            $interest_section->created_by = $userId;
            $interest_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Interest saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('interest Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the interest.'];
            return json_encode($response);

        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('interest Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the interest.'];
            return json_encode($response);
        }
    }

    public function change($id){
        if(!validatePermissions('resume/interest-sections/status-change/{id}')) {
            abort(403);
        }

        $interest = ResumeInterestSectionsModel::where('id', sanitizeInput($id, 'int'))->first();

        if(!$interest){
            $response = ['responseCode' => 0, 'msg' => 'Interest record not found.'];
            return json_encode($response);
        }
        if($interest){
            $status = ($interest->is_active==0)?1:0;
            $interest->is_active  = $status;
            $interest->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'Interest status changed successfully'];
        return json_encode($response);
    }

    /** Sorting interest  */
    public function sorting($id, $sort_number)
    {
        if(!validatePermissions('resume/interest-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeInterestSectionsModel::where('id', $id)->first();

        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'Interest record not found.'];
            return json_encode($response);
        }
        $row->sort_number = $sort_number;
        $row->save();

        return 'Interest sorted successfully.';
        // $response = ['responseCode' => 1, 'msg' => 'interest has been deleted'];
        // return json_encode($response);
    }

    /** Delete interest  */
    public function destroy($id)
    {
        if(!validatePermissions('resume/interest-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeInterestSectionsModel::where('id', $id)->first();
        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'Interest record not found.'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Interest has been deleted'];
        return json_encode($response);
    }

}
