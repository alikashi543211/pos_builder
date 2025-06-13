<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeSkillSectionsModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumeSkillSectionsController extends Controller
{
    public function index()
    {
        if (!validatePermissions('resume/skill-sections')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $skills = ResumeSkillSectionsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->orderBy('sort_number', 'ASC')
            ->get();
        // dd($skills->display_picture);
        $data = [
            'pageTitle' => 'Skill Section',
            'activeJobPosition' => $activeJobPosition,
            'skills' => $skills
        ];

        return view('admin.resume.skill_sections.index')->with($data);
    }

    public function create()
    {
        if (!validatePermissions('resume/skill-sections/add')) {
            abort(403);
        }
        $data = [];
        $html = view('admin.resume.skill_sections.add')->with($data)->render();

        $response = [
            'responseCode' => 1,
            'html' => $html
        ];
        return json_encode($response);
    }

    /** Edit Skill  */
    public function edit($id)
    {
        if (!validatePermissions('resume/skill-sections/edit/{id}')) {
            abort(403);
        }
        $data = [];
        $data = [
            'pageTitle' => 'Skill Edit'
        ];
        $data['row'] = ResumeSkillSectionsModel::where('id', $id)->first();
        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Skill record not found.'];
            return json_encode($response);
        }

        $html = view('admin.resume.skill_sections.edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }

    /** Save New Skill */
    public function store(Request $request)
    {
        if (!validatePermissions('resume/skill-sections/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return json_encode($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'skill_title' => 'nullable|string|min:3|max:100',
            'skill_rating' => 'nullable|numeric|min:1|max:100',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if ($header_section = ResumeSkillSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('created_by', $userId)
                ->where('skill_title', $sanitizedInputs['skill_title'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Skill already exists.'];
                return json_encode($response);
            }
            $header_section = new ResumeSkillSectionsModel();
            if ($request->hasFile('skill_icon')) {
                if (isset($sanitizedInputs['skill_icon']) && $request->hasFile('skill_icon')) {
                    $header_section->skill_icon = $this->resizeAndSaveImage($sanitizedInputs['skill_icon'], 'media/' . Auth::guard('admin')->user()->user_name . '/skill_icons', $request->file('skill_icon')->getClientOriginalName(), 500, 350);
                }
            }
            $header_section->job_position_id = $activeJobPositionId;
            $header_section->skill_title = $sanitizedInputs['skill_title'] ?? null;
            $header_section->skill_rating = $sanitizedInputs['skill_rating'] ?? null;
            $header_section->is_active = 1;
            $header_section->created_by = $userId;
            $header_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Skill saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Skill Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the skill.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Skill Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the skill.'];
            return json_encode($response);
        }
    }

    /** Update Existing Skill */
    public function update(Request $request, $id)
    {
        if (!validatePermissions('resume/skill-sections/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return json_encode($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        /** Validate the request data */
        $validator = Validator::make($sanitizedInputs, [
            'skill_title' => 'nullable|string|min:3|max:100',
            'skill_rating' => 'nullable|numeric|min:1|max:100',
        ]);

        if ($validator->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validator->errors()->first()];
            return json_encode($response);
        }

        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;
            $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
            if ($header_section = ResumeSkillSectionsModel::where('job_position_id', $activeJobPositionId)
                ->where('id', '!=', $id)
                ->where('created_by', $userId)
                ->where('skill_title', $sanitizedInputs['skill_title'])
                ->first()
            ) {
                $response = ['responseCode' => 0, 'msg' => 'Skill already exists.'];
                return json_encode($response);
            }
            $header_section = ResumeSkillSectionsModel::whereId(sanitizeInput($id, 'int'))->first();
            if ($request->hasFile('skill_icon')) {
                if (isset($sanitizedInputs['skill_icon']) && $request->hasFile('skill_icon')) {
                    $header_section->skill_icon = $this->resizeAndSaveImage($sanitizedInputs['skill_icon'], 'media/' . Auth::guard('admin')->user()->user_name . '/skill_icons', $request->file('skill_icon')->getClientOriginalName(), 500, 350);
                }
            }
            $header_section->job_position_id = $activeJobPositionId;
            $header_section->skill_title = $sanitizedInputs['skill_title'] ?? null;
            $header_section->skill_rating = $sanitizedInputs['skill_rating'] ?? null;
            $header_section->is_active = 1;
            $header_section->created_by = $userId;
            $header_section->save();

            $response = ['responseCode' => 1, 'msg' => 'Skill saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Skill Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the skill.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Skill Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the skill.'];
            return json_encode($response);
        }
    }

    public function change($id)
    {
        if (!validatePermissions('resume/skill-sections/status-change/{id}')) {
            abort(403);
        }

        $skill = ResumeSkillSectionsModel::where('id', sanitizeInput($id, 'int'))->first();

        if (!$skill) {
            $response = ['responseCode' => 0, 'msg' => 'Skill record not found.'];
            return json_encode($response);
        }
        if ($skill) {
            $status = ($skill->is_active == 0) ? 1 : 0;
            $skill->is_active  = $status;
            $skill->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'Skill status changed successfully'];
        return json_encode($response);
    }

    /** Sorting Skill  */
    public function sorting($id, $sort_number)
    {
        if (!validatePermissions('resume/skill-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeSkillSectionsModel::where('id', $id)->first();

        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Skill record not found.'];
            return json_encode($response);
        }
        $row->sort_number = $sort_number;
        $row->save();

        return 'Skill sorted successfully.';
        // $response = ['responseCode' => 1, 'msg' => 'Skill has been deleted'];
        // return json_encode($response);
    }

    /** Delete Skill  */
    public function destroy($id)
    {
        if (!validatePermissions('resume/skill-sections/delete/{id}')) {
            abort(403);
        }
        $row = ResumeSkillSectionsModel::where('id', $id)->first();
        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Skill record not found.'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Skill has been deleted'];
        return json_encode($response);
    }
}
