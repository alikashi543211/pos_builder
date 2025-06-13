<?php

namespace App\Http\Controllers\Admin\Resume;

use App\Http\Controllers\Controller;
use App\Models\Resume\ResumeHeaderSectionsModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ResumeGeneratePdfController extends Controller
{
    public function generatePdfView(Request $request)
    {
        if(!validatePermissions('resume/pdf/generate')) {
            abort(403);
        }
        $data = [
            'pageTitle' => 'Generate PDF',
        ];

        return view('admin.resume.generate_pdf.index')->with($data);
    }

    public function generatePdf(Request $request)
    {
        if(!validatePermissions('resume/pdf/generate')) {
            abort(403);
        }
        try{
            $data = [];
            $this->makeDataForPdf($data);
            return $this->makeAndSavePDF($data);
        }catch(QueryException $e){
            // Return success response or redirect with success message
            Log::error('Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'Oops! Something went wrong. Please Contact IT department.'];
            return json_encode($response);
        }catch(Exception $e){
            // Return success response or redirect with success message
            Log::error('Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'Oops! Something went wrong. Please Contact IT department.'];
        }
    }

    private function makeDataForPdf(&$data)
    {
        $data['headerSection'] = [];
        $userId = Auth::guard('admin')->user()->id;
        $activeJobPositionId = getActiveResumeJobPosition()->id ?? null;
        $headerSection = ResumeHeaderSectionsModel::where('job_position_id', $activeJobPositionId)
            ->where('created_by', $userId)
            ->where('is_active', 1)
            ->first();
        if($headerSection)
        {
            $headerSection = (object) $headerSection->toArray();
            if (strpos($headerSection->short_summary, '{exp}') !== false) {
                $headerSection->short_summary = str_replace('{exp}', $headerSection->experience, $headerSection->short_summary);
            }
        }
        $data['headerSection'] = $headerSection ?? [];
    }

    public function makeAndSavePDF(&$data)
    {
        try{
            // dd('ready for pdf', $data);
            $defaultFont = 'Poppins';
            $html = view('admin.test.resume_pdf_new')->with($data)->render();
            $options = new Options();
            $options->set('defaultFont', $defaultFont); // Set default font (if you've added custom fonts)

            $options->set('isHtml5ParserEnabled', true); // Enable HTML5 parsing
            $options->set('isRemoteEnabled', true); // Allow loading remote images and stylesheets

            $dompdf = new Dompdf($options);

            $dompdf->loadHtml($html);

            $dompdf->setPaper('A4',     'portrait');
            // Apply options to remove margins
            $dompdf->set_option('isHtml5ParserEnabled', true); // Enable better HTML5 support
            $dompdf->set_option('isRemoteEnabled', true); // If you're loading remote assets like images

            // Render the PDF
            $dompdf->render();
            $dompdf->stream('document.pdf', array('Attachment' => 0));
            return true;

            // Save image in project
            $folderName = 'media/' . Auth::guard('admin')->user()->user_name . '/generated_pdfs';
            $inputFileName = 'professional-resume.pdf';
            $folderPath = public_path($folderName);
            $fileName = date('H-i').'_'.$inputFileName;
            if (!File::exists($folderPath)) {
                File::makeDirectory($folderPath, 0755, true);
            }
            $directoryPath_File = $folderPath . '/' . $fileName;
            file_put_contents( $directoryPath_File, $dompdf->output());

            return response()->download($directoryPath_File);
        }catch(QueryException $e){
            // Return success response or redirect with success message
            Log::error('Query Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'Oops! Something went wrong. Please Contact IT department.'];
            return json_encode($response);
        }catch(Exception $e){
            // Return success response or redirect with success message
            Log::error('Exception Error : ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'Oops! Something went wrong. Please Contact IT department.'];
        }
    }
}
