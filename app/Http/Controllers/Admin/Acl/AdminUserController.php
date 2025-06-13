<?php

namespace App\Http\Controllers\Admin\Acl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acl\AdminUserModel;
use App\Models\Acl\AdminUserRoleModel;
use App\Models\Acl\RoleModel;
use App\Models\EmployeesModel;
use App\Models\DepartmentsModel;
use App\Rules\NoHtmlTags;
use App\Traits\AdminUserTrait;
use Illuminate\Validation\Rule;
use Session;
use Redirect;
use Storage;
use Auth;
use File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class AdminUserController extends Controller
{
    use AdminUserTrait;
    public function index()
    {
        if(!validatePermissions('acl/users')) {
            abort(403);
        }

        $data=['pageTitle'=>'Manage Users'];
        $data['result'] = AdminUserModel::with(['employee'])->get();
        return view('admin/acl/adminUser/listing')->with($data);
    }

    public function create()
    {
        if(!validatePermissions('acl/users/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }
        $data = [];
        $this->getDropdownsData($data);
        $html = view('admin/acl/adminUser/add')->with($data)->render();
        $response = ['responseCode'=>1,'html'=>$html];
        return json_encode($response);
    }

    public function store(Request $request)
    {

        if(!validatePermissions('acl/users/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }
        if (!$request->ajax()) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }
        $sanitizedInputs = sanitizeAllInput($request->all());

        // Validate the request data
        $validation = Validator::make($sanitizedInputs, [
            'first_name'      => ['bail', 'required', 'string', 'max:100', new NoHtmlTags()],
            'last_name'       => ['bail', 'required', 'string', 'max:100', new NoHtmlTags()],
            'email_address'   => ['bail', 'required', 'email', 'max:40', 'unique:tbl_employees', new NoHtmlTags()],
            'mobile_number'   => ['bail', 'nullable', 'string', 'max:40', new NoHtmlTags()],
            'user_name'       => ['bail', 'required', 'string', 'max:40', 'unique:tbl_employees', new NoHtmlTags()],
            'department_id'   => ['bail', 'required', 'integer', new NoHtmlTags()],
            'designation_id'  => ['bail', 'required', 'integer', new NoHtmlTags()],
            'country_id'      => ['bail', 'required', 'integer', new NoHtmlTags()],
            'city_id'         => ['bail', 'required', 'integer', new NoHtmlTags()],
            'avatar'          => ['bail', 'nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048', new NoHtmlTags()], // Validate avatar if present
            'report_to'       => ['bail', 'nullable', 'string', 'max:40', 'exists:tbl_admin,user_name', new NoHtmlTags()],
            'role'            => ['bail', 'nullable', 'integer', 'exists:tbl_roles,ID'], // Validate roles if present
            'password'       => ['bail', 'required', 'string', 'min:8', 'max:20', new NoHtmlTags()],
        ]);


        if ($validation->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validation->errors()->first()];
            return json_encode($response);

        }

        // Check if an existing user with the same employee_ad_id already exists
        $existingAdminUser = AdminUserModel::where('user_name', $sanitizedInputs['user_name'])->first();
        if ($existingAdminUser) {
            $response = ['responseCode' => 0, 'msg' => 'This user already has an account'];
            return json_encode($response);

        }

        try {
            // Save Employee Data
            $employee = new EmployeesModel();
            $employee->user_name = $sanitizedInputs['user_name'];
            $employee->first_name = $sanitizedInputs['first_name'];
            $employee->last_name = $sanitizedInputs['last_name'];
            $employee->email_address = $sanitizedInputs['email_address'];
            $employee->mobile_number = $sanitizedInputs['mobile_number'];
            $employee->designation_id = $sanitizedInputs['designation_id'];
            $employee->department_id = $sanitizedInputs['department_id'];
            $employee->country = $sanitizedInputs['country_id'];
            $employee->city_id = $sanitizedInputs['city_id'];
            $employee->report_to = $sanitizedInputs['report_to'];

            // Handle file upload for avatar
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $employee->custom_photo = uploadFile($image, 'user_management', $image->getClientOriginalName());
            }

            $employee->save();

            // Save Admin Data
            $adminUser = new AdminUserModel();
            $adminUser->user_name = $sanitizedInputs['user_name'];
            $adminUser->is_active = 1;
            $adminUser->password = Hash::make($sanitizedInputs['password']);
            $adminUser->save();

            // Save user roles if present
            if (isset($sanitizedInputs['role'])) {

                $adminUserRoleModel = new AdminUserRoleModel();
                $adminUserRoleModel->admin_ID = $adminUser->id;
                $adminUserRoleModel->role_ID = intval($sanitizedInputs['role']);
                $adminUserRoleModel->save();

            }
            $response = ['responseCode' => 1, 'msg' => 'User has been added successfully'];
            return json_encode($response);


        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Error saving employee: ' . $e->getMessage());
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while adding the employee.'];
            return json_encode($response);

        }
    }


    public function show($id)
    {
        $response = ['responseCode'=>0,'html'=>''];
        if($id){
            $row = AdminUserModel::where('ID',$id)->get()->first();
            if($row){
                $data['row'] = $row;
                $html = view('admin/acl/adminUser/inc_show')->with($data)->render();
                $response = ['responseCode'=>1,'html'=>$html];
            }
        }
        return json_encode($response);

    }

    public function edit($id)
    {
        if(!validatePermissions('acl/users/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }

        $data=['pageTitle'=>'Admin Users'];
        $data['row'] = AdminUserModel::where('id', sanitizeInput($id, 'int'))->first();
        if(!$data['row']){
            abort(404);
        }
        // dd($data['row']->employee);
        $this->getDropdownsData($data);
        $data['userRoleId'] = AdminUserRoleModel::where('admin_ID', $data['row']->id)->value('role_ID');
        $response = ['responseCode' => 0, 'html' => ''];
        $html = view('admin/acl/adminUser/edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);

    }

    public function update(Request $request, $id)
    {
        if(!validatePermissions('acl/users/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }
        if (!$request->ajax()) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }
        $sanitizedInputs = sanitizeAllInput($request->all());

        // Validate the request data
         $validation = Validator::make($sanitizedInputs, [
            'first_name'      => ['bail', 'required', 'string', 'max:100', new NoHtmlTags()],
            'last_name'       => ['bail', 'required', 'string', 'max:100', new NoHtmlTags()],
            'email_address'   => ['bail', 'required', 'email', 'max:40', new NoHtmlTags()],
            'mobile_number'   => ['bail', 'nullable', 'string', 'max:40', new NoHtmlTags()],
            'user_name'       => ['bail', 'required', 'string', 'max:40', new NoHtmlTags()],
            'department_id'   => ['bail', 'required', 'integer', new NoHtmlTags()],
            'designation_id'  => ['bail', 'required', 'integer', new NoHtmlTags()],
            'country_id'      => ['bail', 'required', 'integer', new NoHtmlTags()],
            'city_id'         => ['bail', 'required', 'integer', new NoHtmlTags()],
            'avatar'          => ['bail', 'nullable', 'file', 'mimes:png,jpg,jpeg', 'max:2048', new NoHtmlTags()], // Validate avatar if present
            'report_to'       => ['bail', 'nullable', 'string', 'max:40', 'exists:tbl_admin,user_name', new NoHtmlTags()],
            'role'            => ['bail', 'nullable', 'integer', 'exists:tbl_roles,ID'], // Validate roles if present
            'password'       => ['bail', 'nullable', 'string', 'min:8', 'max:20', new NoHtmlTags()],
        ]);

        if ($validation->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validation->errors()->first()];
            return json_encode($response);

        }

        // Check if another user with the same user_name exists, excluding the current ID
        $existingUser = AdminUserModel::where('id', '!=', sanitizeInput($id, 'int'))
            ->where('user_name', $sanitizedInputs['user_name'])
            ->first();

        if ($existingUser) {
            $response = ['responseCode' => 0, 'msg' => 'This user already has an account.'];
            return json_encode($response);

        }

        try {
            $adminUser = AdminUserModel::whereId(sanitizeInput($id, 'int'))->first();
            $employee = EmployeesModel::where('user_name', $adminUser->user_name)->first();
            $employee->user_name = $sanitizedInputs['user_name'];
            $employee->first_name = $sanitizedInputs['first_name'];
            $employee->last_name = $sanitizedInputs['last_name'];
            $employee->email_address = $sanitizedInputs['email_address'];
            $employee->mobile_number = $sanitizedInputs['mobile_number'];
            $employee->designation_id = $sanitizedInputs['designation_id'];
            $employee->department_id = $sanitizedInputs['department_id'];
            $employee->country = $sanitizedInputs['country_id'];
            $employee->city_id = $sanitizedInputs['city_id'];
            $employee->report_to = $sanitizedInputs['report_to'];
            // dd($request->all(), $request->avatar);
            // Handle file upload for avatar
            if ($request->hasFile('avatar')) {
                $image = $request->file('avatar');
                $employee->custom_photo = $path = uploadFile($image, 'user_management', $image->getClientOriginalName());

            }

            $employee->save();

            $adminUser->user_name = $sanitizedInputs['user_name'];
            $adminUser->password = Hash::make($sanitizedInputs['password']);
            $adminUser->save();

            // Delete old roles and save new roles
            AdminUserRoleModel::where('admin_ID', $adminUser->id)->delete();
            if (isset($sanitizedInputs['role'])) {

                $adminUserRoleModel = new AdminUserRoleModel();
                $adminUserRoleModel->admin_ID = $adminUser->id;
                $adminUserRoleModel->role_ID = intval($sanitizedInputs['role']);
                $adminUserRoleModel->save();

            }
            $response = ['responseCode' => 1, 'msg' => 'User has been updated successfully.'];
            return json_encode($response);


        } catch (\Exception $e) {
            // Log the exception
            Log::info('Update failed: ' . $e->getMessage());

            return response()->json([
                'responseCode' => 0,
                'msg' => $e->getMessage()
            ], 500);
        }
    }


    public function change(Request $request, $id){

        if(!validatePermissions('acl/users/change/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return response()->json($response);
        }
        if (!$request->ajax()) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return response()->json($response);
        }
        $obj = AdminUserModel::find($id);
        if($obj){
            $status = ($obj->is_active==0)?1:0;
            $obj->is_active  = $status;
            $obj->save();
        }
        return response()->json(['responseCode' => 1, 'msg' => 'Status updated successfully.']);
    }



    public function destroy($id)
    {
        $row = AdminUserModel::where('id', sanitizeInput($id, 'int'))->first();
        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'Employee record not found.'];
            return json_encode($response);
        }

        if($employee = $row->employee)
        {
            $employee->delete();
        }
        $row->delete();

        SuccessFlashMessage("User Deleted Successfully");
        return redirect()->back();
    }

    public function profile(){

        $username = Auth::guard('admin')->user()->user_name;
        if($username){
            $data=['pageTitle'=>'User Profile'];
            $data['row'] = EmployeesModel::where('user_name',$username)->get()->first();
            return view('admin/acl/adminUser/profile')->with($data);
        }else{
            return Redirect::route('dashboard');
        }
    }



}
