<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\Resumelive_linkSectionDescriptionsModel;
use App\Models\Resume\ResumeLiveLinkSectionsModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeLiveLinkSectionsController extends Controller
{
    public function index()
    {
        if(!validatePermissions('resume/live-link-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $live_links = ResumeLiveLinkSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();
        $data = [
            'pageTitle' => 'Live Link Section',
            'activeJobPosition' => $activeJobPosition,
            'live_links' => $live_links
        ];

        return view('admin.resume.live_link_sections.index')->with($data);
    }

    public function create()
    {
        if(!validatePermissions('resume/live-link-sections/add')) {
            abort(403);
        }
        $data = [];
        $html = view('admin.resume.live_link_sections.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit Live Link  */
    public function edit($id)
    {
        if(!validatePermissions('resume/live-link-sections/edit/{id}')) {
            abort(403);
        }
        $data = [];
        $data = [
            'pageTitle' => 'Live Link Edit'
        ];

        $data['row'] = ResumeLiveLinkSectionsModel::where('id',$id)->first();

        if(!$data['row']){
            $response = ['responseCode' => 0, 'msg' => 'Live Link record not found.'];
            return json_encode($response);
        }

        $html = view('admin.resume.live_link_sections.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);

    }

    /** Save New live_link */
    public function store(Request $request)
    {
        if(!validatePermissions('resume/live-link-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'link_name' => 'nullable|string|min:3|max:100',
            'live_link' => 'nullable|string|url|max:1000',
            'link_thumbnail' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }
        try {
            /** Create or Update Resume live_link Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if(ResumeLiveLinkSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('link_name', $sanitizedInputs['link_name'])
                ->first())
            {
                $response = ['responseCode' => 0, 'msg' => 'Link name already exists.'];
                return json_encode($response);
            }
            $live_link_section = new ResumeLiveLinkSectionsModel();
            if ($request->hasFile('link_thumbnail')) {
                if (isset($sanitizedInputs['link_thumbnail']) && $request->hasFile('link_thumbnail')) {
                    $live_link_section->link_thumbnail = $this->resizeAndSaveImage($sanitizedInputs['link_thumbnail'], 'media/' . Auth::guard('admin')->user()->user_name . '/live_link_thumbnails', $request->file('link_thumbnail')->getClientOriginalName(), 150, 50);
                }
            }
            $live_link_section->job_position_id = $activeJobPositionId;
            $live_link_section->link_name = $sanitizedInputs['link_name'] ?? null;
            $live_link_section->live_link = $sanitizedInputs['live_link'] ?? null;
            $live_link_section->is_active = 1;
            $live_link_section->created_by = $userId;
            $live_link_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Live Link saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Live Link Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the live_link.'];
            return json_encode($response);

        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Live Link Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the live_link.'];
            return json_encode($response);
        }
    }

    /** Update Existing live_link */
    public function update(Request $request, $id)
    {
        if(!validatePermissions('resume/live-link-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return response()->json($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'link_name' => 'nullable|string|min:3|max:100',
            'live_link' => 'nullable|string|url|max:1000',
            'link_thumbnail' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            // Validate id exists or not
            $live_link_section = ResumeLiveLinkSectionsModel::whereId(sanitizeInput($id, 'int'))->first();
            if(!$live_link_section){
                $response = ['responseCode' => 0, 'msg' => 'Live Link record not found.'];
                return json_encode($response);
            }

            /** Create or Update Resume live_link Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;

            // Check Name already exist or not
            if(ResumeLiveLinkSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('link_name', $sanitizedInputs['link_name'])
                ->first())
            {
                $response = ['responseCode' => 0, 'msg' => 'Live Link name already exists.'];
                return json_encode($response);
            }
            // Update data
            if ($request->hasFile('link_thumbnail')) {
                if (isset($sanitizedInputs['link_thumbnail']) && $request->hasFile('link_thumbnail')) {
                    $live_link_section->link_thumbnail = $this->resizeAndSaveImage($sanitizedInputs['link_thumbnail'], 'media/' . Auth::guard('admin')->user()->user_name . '/live_link_thumbnails', $request->file('link_thumbnail')->getClientOriginalName(), 150, 50);
                }
            }
            $live_link_section->job_position_id = $activeJobPositionId;
            $live_link_section->link_name = $sanitizedInputs['link_name'] ?? null;
            $live_link_section->live_link = $sanitizedInputs['live_link'] ?? null;
            $live_link_section->is_active = 1;
            $live_link_section->created_by = $userId;
            $live_link_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Live Link saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Live Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the Live Link.'];
            return json_encode($response);

        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('live_link Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the Live Link.'];
            return json_encode($response);
        }
    }

    public function change($id){
        if(!validatePermissions('resume/live-link-sections/status-change/{id}')) {
            abort(403);
        }

        $live_link = ResumeLiveLinkSectionsModel::where('id', sanitizeInput($id, 'int'))->first();

        if(!$live_link){
            $response = ['responseCode' => 0, 'msg' => 'Live Link record not found.'];
            return json_encode($response);
        }
        if($live_link){
            $status = ($live_link->is_active==0)?1:0;
            $live_link->is_active  = $status;
            $live_link->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'live_link status changed successfully'];
        return json_encode($response);
    }

    /** Sorting live_link  */
    public function sorting($id, $sort_number)
    {
        if(!validatePermissions('resume/live-link-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeLiveLinkSectionsModel::where('id', $id)->first();

        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'Live Link record not found.'];
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
        if(!validatePermissions('resume/live-link-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeLiveLinkSectionsModel::where('id', $id)->first();
        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'Live Link record not found.'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Live Link has been deleted'];
        return json_encode($response);
    }

}
