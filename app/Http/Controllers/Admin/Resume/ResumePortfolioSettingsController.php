<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeHeaderSectionsModel;
use App\Models\Resume\ResumePortfolioSettingsModel;
use App\Rules\LinkedInValidationRule;
use App\Traits\ResumeHeaderSectionsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResumePortfolioSettingsController extends Controller
{

    public function generalSection()
    {
        if (!validatePermissions('resume/portfolio-settings')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumePortfolioSettingsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->pluck('value', 'key')->toArray();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Portfolio Settings',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => (object) $headerSection
        ];

        return view('admin.resume.portfolio_settings.general_section')->with($data);
    }

    public function summarySection()
    {
        if (!validatePermissions('resume/portfolio-settings/summary-section')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumePortfolioSettingsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->pluck('value', 'key')->toArray();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Portfolio Settings',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => (object) $headerSection
        ];

        return view('admin.resume.portfolio_settings.summary_section')->with($data);
    }

    public function emailSection()
    {
        if (!validatePermissions('resume/portfolio-settings/email-section')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumePortfolioSettingsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->pluck('value', 'key')->toArray();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Portfolio Settings',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => (object) $headerSection
        ];

        return view('admin.resume.portfolio_settings.email_section')->with($data);
    }

    public function aboutSection()
    {
        if (!validatePermissions('resume/portfolio-settings/about-section')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumePortfolioSettingsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->pluck('value', 'key')->toArray();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Portfolio Settings',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => (object) $headerSection
        ];

        return view('admin.resume.portfolio_settings.about_section')->with($data);
    }

    public function contactSection()
    {
        if (!validatePermissions('resume/portfolio-settings/contact-section')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumePortfolioSettingsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->pluck('value', 'key')->toArray();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Portfolio Settings',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => (object) $headerSection
        ];

        return view('admin.resume.portfolio_settings.contact_section')->with($data);
    }

    public function skillSection()
    {
        if (!validatePermissions('resume/portfolio-settings/skill-section')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumePortfolioSettingsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->pluck('value', 'key')->toArray();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Portfolio Settings',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => (object) $headerSection
        ];

        return view('admin.resume.portfolio_settings.skill_section')->with($data);
    }

    public function resumeSection()
    {
        if (!validatePermissions('resume/portfolio-settings/resume-section')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumePortfolioSettingsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->pluck('value', 'key')->toArray();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Portfolio Settings',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => (object) $headerSection
        ];

        return view('admin.resume.portfolio_settings.resume_section')->with($data);
    }

    public function portfolioSection()
    {
        if (!validatePermissions('resume/portfolio-settings/portfolio-section')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumePortfolioSettingsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->pluck('value', 'key')->toArray();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Portfolio Settings',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => (object) $headerSection
        ];

        return view('admin.resume.portfolio_settings.portfolio_section')->with($data);
    }

    public function serviceSection()
    {
        if (!validatePermissions('resume/portfolio-settings/service-section')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumePortfolioSettingsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->pluck('value', 'key')->toArray();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Portfolio Settings',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => (object) $headerSection
        ];

        return view('admin.resume.portfolio_settings.service_section')->with($data);
    }

    public function testimonialSection()
    {
        if (!validatePermissions('resume/portfolio-settings/testimonial-section')) {
            abort(403);
        }
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = getActiveResumeJobPosition();
        $headerSection = ResumePortfolioSettingsModel::where('created_by', $userId)
            ->where('job_position_id', $activeJobPosition->id)
            ->where('is_active', 1)->pluck('value', 'key')->toArray();
        // dd($headerSection->display_picture);
        $data = [
            'pageTitle' => 'Portfolio Settings',
            'activeJobPosition' => $activeJobPosition,
            'headerSection' => (object) $headerSection
        ];

        return view('admin.resume.portfolio_settings.testimonial_section')->with($data);
    }

    public function store(Request $request)
    {
        if (!validatePermissions('resume/portfolio-settings')) {
            $response = ['responseCode' => 0, 'msg' => 'Permission Denied'];
            return json_encode($response);
        }
        $sanitizedInputs = sanitizeInputWithAllowedTags($request->all());
        try {
            /** Create or Update Resume Header Section */
            $userId = Auth::guard('admin')->user()->id;

            foreach ($sanitizedInputs as $inputKey => $inputValue) {
                $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
                if (!$header_section = ResumePortfolioSettingsModel::where('job_position_id', $activeJobPositionId)
                    ->where('created_by', $userId)
                    ->where('key', $inputKey)
                    ->where('created_by', $userId)
                    ->where('is_active', 1)->first()) {
                    $header_section = new ResumePortfolioSettingsModel();
                }
                if ($request->hasFile($inputKey)) {
                    if (isset($sanitizedInputs[$inputKey]) && $request->hasFile($inputKey)) {
                        $header_section->value = $this->resizeAndSaveImage($sanitizedInputs[$inputKey], 'media/' . Auth::guard('admin')->user()->user_name . '/portfolio_settings', $request->file($inputKey)->getClientOriginalName(), 400, 400);
                    }
                } else {
                    $header_section->value = $inputValue ?? null;
                }
                $header_section->job_position_id = $activeJobPositionId;
                $header_section->is_active = 1;
                $header_section->created_by = $userId;
                $header_section->key = $inputKey ?? null;
                $header_section->save();
            }
            $response = ['responseCode' => 1, 'msg' => 'Portfolio settings saved successfully.'];
            return json_encode($response);
        } catch (QueryException $e) {
            // Handle exceptions and return a response
            Log::error('Header Section Saving Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the resume header section.'];
            return json_encode($response);
        } catch (Exception $e) {
            // Handle exceptions and return a response
            Log::error('Header Section Saving Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the resume header section.'];
            return json_encode($response);
        }
    }
}
