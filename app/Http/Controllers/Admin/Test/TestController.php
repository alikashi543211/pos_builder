<?php

namespace App\Http\Controllers\Admin\Test;

use App\Http\Controllers\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TestController extends Controller
{
    public function testMail()
    {
        try {
            // Config::set('mail.mailers.smtp.username', 'new_username_value');
            // $smtp_array = [
            //     "transport" => "smtp",
            //     "url" => null,
            //     "host" => "smtp.mailtrap.io",
            //     "port" => "2525",
            //     "encryption" => "tls",
            //     "username" => bin2hex(random_bytes(8)), // Random 16-character hex string
            //     "password" => bin2hex(random_bytes(16)), // Random 32-character hex string
            //     "timeout" => null,
            //     "local_domain" => null,
            // ];
            // $smtp_array = [
            //     "transport" => "smtp",
            //     "url" => null,
            //     "host" => "smtp.mailtrap.io",
            //     "port" => "2525",
            //     "encryption" => "tls",
            //     "username" => "bb82e4cc71dfe0", // Random 16-character hex string
            //     "password" => "92c33187f4e4b0", // Random 32-character hex string
            //     "timeout" => null,
            //     "local_domain" => null,
            // ];
            // Config::set('mail.mailers.smtp', $smtp_array);
            // dd(Config::get('mail.mailers.smtp'));

            $data = ['testMessage' => 'This is a test email from KashifTech.'];
            $subject = "Test Mail";
            // dd($data, $subject);
            $this->sendEmail('front.emails.test_message', $subject, $data);

            return response()->json(['message' => 'Test email sent successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Mail sending failed!', 'message' => $e->getMessage()], 500);
        }
    }
    public function resumePdf()
    {
        $data = [];
        $defaultFont = 'Poppins';
        // return view('admin.test.test_svg');
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
        // return view('admin.test.resume_pdf')->with($data);
    }
}
