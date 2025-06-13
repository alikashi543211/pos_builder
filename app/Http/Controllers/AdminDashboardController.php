<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Redirect;
use Auth;
class AdminDashboardController extends Controller

{
    public function index(Request $request)

    {  
        if(!validatePermissions('dashboard')) {
            abort(403);
        }
        $data=array();
        $data['pageTitle']='Dashboard';
        return view('admin/adminDashboard')->with($data);

    }

}

