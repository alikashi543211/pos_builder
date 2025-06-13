<?php

namespace App\Http\Controllers;

use App\Models\Resume\ResumePortfolioSettingsModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Config;

abstract class Controller
{
    /** Resizes and saves both the large and thumbnail images. */
    protected function resizeAndSaveImage($file, $folderName, $originalFileName, $width = 430, $height = 270)
    {
        $folderPath = env('BASE_MEDIA_PATH') . $folderName;
        $filename = date('H-i') . '_' . $originalFileName;
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }
        $categoryImagePath = $folderPath . '/' . $filename;
        Image::make($file)
            ->save($categoryImagePath, 100); // Compress image (100 means No compression)

        return $folderName . '/' . $filename;
    }

    protected function sendEmail($view, $subject, $data, $toEmail = 'alikashi54321@gmail.com')
    {
        try {
            if (isEmailSendingOn()) {
                $from_array = emailFromSettings();
                if (isEmailModeLive()) {
                    $smtp_array = liveEmailSettings();
                } else {
                    $smtp_array = localEmailSettings();
                }
                Config::set('mail.from', $from_array);
                Config::set('mail.mailers.smtp', $smtp_array);

                // Send email
                Mail::send($view, $data, function ($message) use ($subject, $toEmail) {
                    $message->to($toEmail) // Use dynamic recipient
                        ->subject($subject);
                });

                Log::info("Email successfully sent to {$toEmail} with subject: {$subject}");
            }
        } catch (\Exception $e) {
            Log::error("Email sending failed for {$toEmail}. Error: " . $e->getMessage());
        }
    }
}
