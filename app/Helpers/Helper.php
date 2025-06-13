<?php

use App\Models\Acl\AdminUserModel;
use App\Models\Resume\ResumeJobPositionsModel;
use App\Models\Resume\ResumePortfolioSettingsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('getConsistentTeamColors')) {
    function getConsistentTeamColors($teamNames, $availableColors)
    {
        $teamColorMapping = [];
        $colorCount = count($availableColors);

        foreach ($teamNames as $teamName) {
            $hash = crc32($teamName);
            $colorIndex = $hash % $colorCount;
            $teamColorMapping[$teamName] = $availableColors[$colorIndex];
        }

        return $teamColorMapping;
    }
}

if (!function_exists('emailFromSettings')) {
    function emailFromSettings()
    {
        $data = ResumePortfolioSettingsModel::where('key', 'LIKE', 'email_credentials_section_email_from_%')
            ->pluck('value', 'key')->toArray();
        $from_array = [
            'address' => $data['email_credentials_section_email_from_address'] ?? 'contact@kashiftech.com',
            'name'    => $data['email_credentials_section_email_from_name'] ?? 'KashifTech'
        ];

        return $from_array;
    }
}

if (!function_exists('isEmailSendingOn')) {
    function isEmailSendingOn()
    {
        $result = ResumePortfolioSettingsModel::where('key', 'resume_website_send_email_selection')
            ->where('value', 'yes')
            ->exists();
        return $result;
    }
}

if (!function_exists('isEmailModeLive')) {
    function isEmailModeLive()
    {
        $result = ResumePortfolioSettingsModel::where('key', 'resume_website_send_email_mode_selection')
            ->where('value', 'live')
            ->exists();
        return $result;
    }
}

if (!function_exists('localEmailSettings')) {
    function localEmailSettings()
    {
        $data = ResumePortfolioSettingsModel::where('key', 'LIKE', 'email_credentials_section_email_%')
            ->pluck('value', 'key')->toArray();
        $smtp_array = [
            "transport" => $data['email_credentials_section_email_transport'] ?? "smtp",
            "url" => null,
            "host" => $data['email_credentials_section_email_host'] ?? "smtp.mailtrap.io",
            "port" => $data['email_credentials_section_email_port'] ?? "2525",
            "encryption" => $data['email_credentials_section_email_encryption'] ?? "tls",
            "username" => $data['email_credentials_section_email_username'] ?? "bb82e4cc71dfe099", // Random 16-character hex string
            "password" => $data['email_credentials_section_email_password'] ?? "e6eeac1ec26f7f", // Random 32-character hex string
            "timeout" => null,
            "local_domain" => null,
        ];
        return $smtp_array;
    }
}

if (!function_exists('liveEmailSettings')) {
    function liveEmailSettings()
    {
        $data = ResumePortfolioSettingsModel::where('key', 'LIKE', 'email_credentials_section_live_email_%')
            ->pluck('value', 'key')->toArray();
        $smtp_array = [
            "transport" => $data['email_credentials_section_live_email_transport'] ?? "smtp",
            "url" => null,
            "host" => $data['email_credentials_section_live_email_host'] ?? "smtp.mailtrap.io",
            "port" => $data['email_credentials_section_live_email_port'] ?? "2525",
            "encryption" => $data['email_credentials_section_live_email_encryption'] ?? "tls",
            "username" => $data['email_credentials_section_live_email_username'] ?? "bb82e4cc71dfe099", // Random 16-character hex string
            "password" => $data['email_credentials_section_live_email_password'] ?? "e6eeac1ec26f7f", // Random 32-character hex string
            "timeout" => null,
            "local_domain" => null,
        ];

        return $smtp_array;
    }
}

if (!function_exists('dateFormat')) {
    function dateFormat($date, $format)
    {
        $return = $date;
        if ($date != '') {
            $return = date($format, strtotime($date));
        }
        return $return;
    }
}


if (!function_exists('isInteger')) {
    function isInteger($id)
    {
        // Check if the ID is numeric and an integer
        return intval($id) == $id;
    }
}

if (!function_exists('getFileAttribute')) {
    function getFileAttribute($value)
    {
        if ($value) {
            if (file_exists(storage_path('app/public/' . $value))) {
                // This Storage Will make URL From Storage link folder in public
                $value = Storage::url($value);
            }
        }
        $value = photo('empty.png', 'avatars');

        return $value;
    }
}

if (!function_exists('sendMail')) {
    function sendMail($data)
    {
        Mail::send($data['view'], $data['data'], function ($send) use ($data) {
            $send->to($data['to'])->subject($data['subject']);
        });
        return true;
    }
}


if (!function_exists('storage_url')) {
    function storage_url($path = '')
    {
        if (!file_exists($folderPath = public_path('uploads') . DIRECTORY_SEPARATOR . $path)) {
            return public_path('uploads' . '/blank.png');
        }
        $base_url = public_path('STORAGEPATH');
        // Return the complete URL
        return $path ? $base_url . '/' . trim($path, '/') : $base_url;
    }
}

if (!function_exists('storage_url_for_banner')) {
    function storage_url_for_banner($path = '')
    {
        if (!file_exists($folderPath = env("DIRPATH") . DIRECTORY_SEPARATOR . $path)) {
            return url(env('STORAGEPATH') . '/blank-gallery.png');
        }
        $base_url = env('STORAGEPATH');
        // Return the complete URL
        return $path ? $base_url . '/' . trim($path, '/') : $base_url;
    }
}

if (!function_exists('storage_url_for_document')) {
    function storage_url_for_document($path = '')
    {
        if (!file_exists($folderPath = env("DIRPATH") . DIRECTORY_SEPARATOR . $path)) {
            return null;
        }
        $base_url = env('STORAGEPATH');
        // Return the complete URL
        return $path ? $base_url . '/' . trim($path, '/') : $base_url;
    }
}


if (!function_exists('uploadFile')) {
    function uploadFile($file, $folderName, $originalFileName)
    {
        $folderPath = public_path('uploads') . DIRECTORY_SEPARATOR . $folderName;
        $filename = date('H-i') . '_' . $originalFileName;
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }
        $file->move($folderPath, $filename);

        return 'uploads/' . $folderName . '/' . $filename;
    }
}




/**User Photo  */

if (!function_exists('photo')) {
    function gallery_photo($ad_ID)
    {
        $return = url('assets/media/avatars' . '/empty.png');
        $folderArray = ['custom_employee_photo', 'employees'];
        $extArray = ['jpg', 'png'];
        $path = public_path('assets/uploads');
        foreach ($folderArray as $Folder) {
            foreach ($extArray as $ext) {
                $fileName = $ad_ID . '.' . $ext;
                $photPath = $path . '/' . $Folder . '/' . $fileName;
                if (file_exists($photPath)) {
                    return url('assets/uploads' . '/' . $Folder . '/' . $fileName . '?' . time());
                }
            }
        }
        return $return;
    }
}

if (!function_exists('photo')) {
    function photo($ad_ID)
    {
        $return = url('assets/media/avatars' . '/blank.png');
        $folderArray = ['custom_employee_photo', 'employees'];
        $extArray = ['jpg', 'png'];
        $path = public_path('assets/uploads');
        foreach ($folderArray as $Folder) {
            foreach ($extArray as $ext) {
                $fileName = $ad_ID . '.' . $ext;
                $photPath = $path . '/' . $Folder . '/' . $fileName;
                if (file_exists($photPath)) {
                    return url('assets/uploads' . '/' . $Folder . '/' . $fileName . '?' . time());
                }
            }
        }
        return $return;
    }
}

if (!function_exists('getLoggedInAdminDetail')) {
    function getLoggedInAdminDetail()
    {
        $adminUser = AdminUserModel::whereId(Auth::guard('admin')->user()->id)->first();
        if ($adminUser) {
            if (!$adminUser->employee) {
                return null;
            }
            return $adminUser->employee;
        }

        return null;
    }
}

/**Validate Permissions By Slug */
function validatePermissions($slug)
{
    return App\Traits\HasPermissionsTrait::getModulesPremissionsBySlug($slug);
    //return true;
}


function sanitizeInput($value, $type = 'string')
{
    $value = strip_tags($value);
    switch ($type) {
        case 'int':
            return intval($value);
        case 'float':
            return floatval($value);
        case 'bool':
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        case 'alphanumeric':
            return preg_replace('/[^a-zA-Z0-9_. ]/', '', $value);
        case 'string':
        default:
            $value = addslashes($value);
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

//Sanitize All request
function sanitizeAllInput(array $input)
{
    return array_map(function ($value) {
        if (is_string($value)) {
            // Strip HTML tags and encode special characters
            return htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
        }
        return $value;
    }, $input);
}
function stripTagsExcept($text)
{
    // Replace <strong> with <b>
    $modifiedString = str_replace('<strong>', '<b>', $text);
    $modifiedString = str_replace('</strong>', '</b>', $modifiedString);
    $allowedTags = '<b><br><br /><ul><ol><li>';
    return strip_tags($modifiedString, $allowedTags);
}

function sanitizeInputWithAllowedTags(array $input)
{
    // Define allowed HTML tags
    $allowedTags = '<p><b><u><i>';
    $editorsArray = editorsArray();
    $userInputs = $input;
    array_map(function ($value, $key) use ($allowedTags, $editorsArray, &$userInputs) {
        if (is_string($value) && !in_array($key, $editorsArray)) {
            // Strip unwanted HTML tags while allowing specific tags
            return strip_tags($userInputs[$key], $allowedTags);
        }
        return $userInputs[$key];
    }, $input, array_keys($input));

    return $userInputs;
}

function editorsArray()
{
    return [
        'full_description',
        'short_summary_editor',
        'company_overview',
        'short_description',
        'long_description',
    ];
}

function isValidBase64($data)
{

    return preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $data) && base64_encode(base64_decode($data, true)) === $data;
}

function allowedStatusChangeArray()
{
    return ['In Review', 'Approved', 'In Modification', 'Disabled'];
}

if (!function_exists('getActiveResumeJobPosition')) {
    function getActiveResumeJobPosition()
    {
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPosition = ResumeJobPositionsModel::where('created_by', $userId)
            ->where('is_active', 1)->first();
        if ($activeJobPosition) {
            return $activeJobPosition;
        }
        if (ResumeJobPositionsModel::where('created_by', $userId)->count() == 0) {
            $activeJobPosition = new ResumeJobPositionsModel();
            $activeJobPosition->created_by = $userId;
            $activeJobPosition->title = 'Web Developer';
            $activeJobPosition->is_active = 1;
            $activeJobPosition->save();

            return $activeJobPosition->fresh();
        }
        return 'N/A';
    }
}

if (!function_exists('renderStars')) {
    function renderStars($rating)
    {
        $output = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $output .= '<span style="color: gold;font-size:20px;">&#9733;</span>'; // Filled star
            } else {
                $output .= '<span style="color: gray;font-size:20px;">&#9734;</span>'; // Empty star
            }
        }
        return $output;
    }
}
