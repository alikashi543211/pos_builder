<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Session;
use Hash;
use App\Models\Acl\AdminUserModel;
use App\Models\EmployeesModel;
class AdminLoginController extends Controller
{
    private $UserLoginModel = '';
    protected $dummypass = 'HotGoogle$12345';
    protected $role_ID = '1';

    public function __construct()
    {
        $this->AdminUserModel = new AdminUserModel();
    }

    public function default(){
        return view("welcome");
    }
    public function index(){
        return Redirect::to('login');
    }
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return Redirect::to('dashboard');
        }
        return view("admin/adminLogin");
    }

    public function doLogin(Request $request)
    {
        $rules = array(
            'username' => 'required|string',
            'password' => 'required',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            Session::flash('flash_message_error', 'Wrong username and/or password.');
            return redirect()->back();
        } else {

            //Checking Company
            $username = strtolower($request->input('username'));
            $user = AdminUserModel::where('user_name', $username)->first();
            //Validate in local DB
            if(is_null(env('TEST_PASSWORD')))
            {
                if ($user) {

                    if (Hash::check($request->password, $user->password)) {
                        // Password is correct
                        $result = $user;
                    } else {
                        Session::flash('flash_message_error', 'Wrong password.');
                        return redirect()->back();
                    }
                } else {
                    // User not found
                    $result = null;
                }
            }elseif(env('TEST_PASSWORD') == '12345678'){
                $result = $user;
            }


            if(!$result){
                Session::flash('flash_message_error', 'Access denied for provided user.');
                return redirect()->back();
            }

            // Auth::guard('admin')->loginUsingId($isUserRow->id, true);
            Auth::guard('admin')->loginUsingId( $result->id, true);
            if (Auth::guard('admin')->check()) {
                return Redirect::to('dashboard');
            } else{
                echo "issue";
            }

        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
