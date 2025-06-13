<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeesModel;
use App\Models\DepartmentsModel;
use App\Models\TeamsModel;
use App\Models\Acl\AdminUserModel;
use App\Models\Acl\AdminUserRoleModel;
use Illuminate\Validation\Rule;
use Storage;
use Validator;
use File;
use Auth;

class EmployeesController extends Controller
{
    //

    public function index()
    {
        if(!validatePermissions('employees')) {
            abort(403);
        }

        $data=['pageTitle'=>'All Employees'];
        $data['result'] = EmployeesModel::orderBy('full_name','ASC')->where('is_deleted',0)->get();
        return view('employee.listing')->with($data);
    }

    public function create()
    {
        if(!validatePermissions('employee/add')) {
            abort(403);
        }
        $data['departments'] = DepartmentsModel::orderBy('department_name')->get();
        $html = view('employee/add')->with($data)->render();
        $response = ['responseCode'=>1,'html'=>$html];
        return json_encode($response);
    }

    /** store Employee */
    public function store(Request $request)
    {
        if(!validatePermissions('employee/add')) {
            abort(403);
        }
        // Validate the request data
        $validation = Validator::make($request->all(), [
            'full_name'       => 'bail|required|string|max:100',
            'employee_ad_id'  => 'required|unique:tbl_employees|max:40',
            'email_address'   => 'required|unique:tbl_employees|email|max:40',
            'department'      => 'required|integer',
            'avatar'          => 'nullable|file|mimes:png,jpg|max:2048',
            'image'           => 'nullable|string',
        ]);

        if ($validation->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validation->errors()];
            return response()->json($response);
        }

        // Check if employee already exists
        $row = EmployeesModel::where('employee_ad_id', $request->input('employee_ad_id'))->first();
        if ($row) {
            $response = ['responseCode' => 0, 'msg' => 'This employee already has an account.'];
            return response()->json($response);
        }

        try {
            // Create new AdminUserModel
            $adminUserModel = new AdminUserModel();
            $adminUserModel->employee_ad_id = htmlspecialchars(trim($request->input('employee_ad_id')), ENT_QUOTES, 'UTF-8');
            $adminUserModel->is_active = 1;
            $adminUserModel->user_type = ($request->input('department') == 3) ? 'all' : 'custom';

            // Handle avatar file upload if exists
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $folderPath = 'public/custom_employee_photo';
                $fileName = strtolower($adminUserModel->employee_ad_id) . '.' . $avatar->getClientOriginalExtension();
                $avatar->storeAs($folderPath, $fileName);
            }

            $adminUserModel->save();

            // Save employee information
            $obj = new EmployeesModel();
            $obj->employee_ad_id = htmlspecialchars(trim($request->input('employee_ad_id')), ENT_QUOTES, 'UTF-8');
            $obj->full_name = htmlspecialchars(trim($request->input('full_name')), ENT_QUOTES, 'UTF-8');
            $obj->email_address = htmlspecialchars(trim($request->input('email_address')), ENT_QUOTES, 'UTF-8');
            $obj->is_active = 1;
            $obj->department_id = intval($request->input('department'));
            $obj->added_by = Auth::guard('admin')->user()->employee_ad_id;
            $obj->save();

            // Store default roles
            $adminUserRoleModel = new AdminUserRoleModel();
            $adminUserRoleModel->admin_ID = $adminUserModel->id;
            $adminUserRoleModel->role_ID = 3;
            $adminUserRoleModel->save();

            $response = ['responseCode' => 1, 'msg' => 'Employee has been added successfully.'];
            return response()->json($response);

        } catch (\Exception $e) {
            // Handle exceptions and return a response
            $response = ['responseCode' => 0, 'msg' => 'An error occurred while saving the employee.'];
            return response()->json($response, 500);
        }
    }


    /** Edit Emloyee  */
    public function edit($id)
    {
        if(!validatePermissions('employee/edit/{id}')) {
            abort(403);
        }
        $data=['pageTitle'=>'Employee Edit'];
        $data['row'] = EmployeesModel::where('id',$id)->first();
        $data['teams'] = TeamsModel::orderBy('team_name')->get();
        if(!$data['row']){
            $response = ['responseCode' => 0, 'msg' => 'Employee record not found.'];
            return json_encode($response);
        }
        $data['departments'] = DepartmentsModel::orderBy('department_name')->get();
        $response = ['responseCode' => 0, 'html' => ''];
        $html = view('employee/edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);

    }

    public function update(Request $request, $id)
    {
        if(!validatePermissions('employee/edit/{id}')) {
            abort(403);
        }
        // Validate the request data
        $validation = Validator::make($request->all(), [
            'full_name'       => 'bail|required|string|max:100',
            'employee_ad_id'  => [
                'required',
                'string',
                'max:40',
                Rule::unique('tbl_employees')->ignore($id)
            ],
            'email_address'   => [
                'required',
                'email',
                'max:40',
                Rule::unique('tbl_employees')->ignore($id)
            ],
            'department'      => 'required|integer',
            'avatar'          => 'nullable|file|mimes:png,jpg,jpeg|max:2048', // Validate image file if exists
            'image'           => 'nullable|string' // Base64 image if provided
        ]);

        if ($validation->fails()) {
            return response()->json([
                'responseCode' => 0,
                'msg' => $validation->errors()
            ], 422);
        }

        // Check if employee with the same employee_ad_id exists, excluding the current ID
        $existingEmployee = EmployeesModel::where('id', '!=', $id)
            ->where('employee_ad_id', $request->input('employee_ad_id'))
            ->first();

        if ($existingEmployee) {
            return response()->json([
                'responseCode' => 0,
                'msg' => 'This user already has an account.'
            ], 400);
        }

        try {
            // Update AdminUserModel
            $adminUserModel = AdminUserModel::where('employee_ad_id', $request->input('employee_ad_id'))->first();
            if ($adminUserModel) {
                $adminUserModel->employee_ad_id = htmlspecialchars(trim($request->input('employee_ad_id')), ENT_QUOTES, 'UTF-8');
                $adminUserModel->user_type = ($request->input('department') == 3) ? 'all' : 'custom';
                $adminUserModel->save();
            }

            // Update EmployeesModel
            $employeeModel = EmployeesModel::where('employee_ad_id', $request->input('employee_ad_id'))->first();
            if ($employeeModel) {
                $employeeModel->employee_ad_id = htmlspecialchars(trim($request->input('employee_ad_id')), ENT_QUOTES, 'UTF-8');
                $employeeModel->full_name = htmlspecialchars(trim($request->input('full_name')), ENT_QUOTES, 'UTF-8');
                $employeeModel->email_address = htmlspecialchars(trim($request->input('email_address')), ENT_QUOTES, 'UTF-8');
                $employeeModel->department_id = intval($request->input('department'));
                $employeeModel->updated_by = Auth::guard('admin')->user()->employee_ad_id;
                $employeeModel->save();
            }

            // Handle avatar file upload if provided
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $folderPath = 'public/custom_employee_photo';
                $fileName = strtolower($request->input('employee_ad_id')) . '.' . $avatar->getClientOriginalExtension();
                $avatar->storeAs($folderPath, $fileName);
            }

            // Handle base64 image if provided
            if ($request->filled('image')) {
                $image_parts = explode(";base64,", $request->input('image'));
                if (count($image_parts) === 2) {
                    $image_base64 = base64_decode($image_parts[1]);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $folderPath = Storage::path('public/custom_employee_photo');
                    $fileName = strtolower($request->input('employee_ad_id')) . '.' . $image_type;
                    file_put_contents($folderPath . '/' . $fileName, $image_base64);
                }
            }

            return response()->json([
                'responseCode' => 1,
                'msg' => 'Employee has been updated successfully.'
            ]);

        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Update failed: ' . $e->getMessage());
            return response()->json([
                'responseCode' => 0,
                'msg' => 'An error occurred while updating the employee.'
            ], 500);
        }
    }


    public function change($id){
        if(!validatePermissions('employee/status-change/{id}')) {
            abort(403);
        }

        $employee = EmployeesModel::where('id', $id)->first();

        if(!$employee){
            $response = ['responseCode' => 0, 'msg' => 'Employee record not found.'];
            return json_encode($response);
        }
        if($employee){
            $status = ($employee->is_active==0)?1:0;
            $employee->is_active  = $status;
            $employee->save();
        }


        $obj = AdminUserModel::where('employee_ad_id',  $employee->employee_ad_id)->first();;
        if($obj){
            $status = ($obj->is_active==0)?1:0;
            $obj->is_active  = $status;
            $obj->save();
        }

        $response = ['responseCode' => 1, 'msg' => 'Employee status changed successfully'];
        return json_encode($response);
    }

    /**  */
    public function destroy($id)
    {
        if(!validatePermissions('employee/delete/{id}')) {
            abort(403);
        }
        $row = EmployeesModel::where('id', $id)->first();
        if(!$row){
            $response = ['responseCode' => 0,'msg' => 'Employee record not found.'];
            return json_encode($response);
        }
        $row->is_deleted = 1;
        $row->is_active = 0;
        $row->deleted_by =  Auth::guard('admin')->user()->employee_ad_id;
        $row->update();

        $admin = AdminUserModel::where('employee_ad_id',  $row->employee_ad_id)->first();

        $admin->is_active = 0;
        $admin->is_deleted = 1;
        $admin->update();

        //AdminUserModel::destroy($id);
        $response = ['responseCode' => 1, 'msg' => 'Employee has been deleted'];
        return json_encode($response);

    }

}
