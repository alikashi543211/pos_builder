<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumePortfolioSettingsModel;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    public function saveResumePath(Request $request)
    {
        $inputs = $request->all();
        $userId = 2;
        $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
        $portfolioSetting = ResumePortfolioSettingsModel::where('job_position_id', $activeJobPositionId)
            ->where('created_by', 2)
            ->where('key', 'portfolio_download_resume_path_link')
            ->where('is_active', 1)->first();
        if (!$portfolioSetting) {
            $portfolioSetting = new ResumePortfolioSettingsModel();
        }
        $portfolioSetting->value = $inputs['portfolio_download_resume_path_link'] ?? null;
        $portfolioSetting->job_position_id = $activeJobPositionId;
        $portfolioSetting->is_active = 1;
        $portfolioSetting->created_by = $userId;
        $portfolioSetting->key = 'portfolio_download_resume_path_link' ?? null;
        $portfolioSetting->save();
    }
}
