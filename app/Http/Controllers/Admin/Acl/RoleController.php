<?php

namespace App\Http\Controllers\Admin\Acl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acl\RoleModel;
use App\Models\Acl\RolePrivilgeModel;
use App\Models\Acl\ModuleCategoryModel;
use Session;
use Redirect;
use Validator;
use App\Traits\RoleTrait;
use App\Traits\ResponseTrait;

class RoleController extends Controller
{
    use RoleTrait, ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!validatePermissions('acl/role')){
            abort(403);
        }
        $data=['pageTitle'=>'ACL - User Roles'];
        $data['result'] = RoleModel::orderBy('display_order','ASC')->get();
        return view('admin/acl/role/listing')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!validatePermissions('acl/role/add')){
            return $this->errorResponse('Access denied');
        }

        $data=['pageTitle'=>'ACL - User Roles'];
        $html = view('admin/acl/role/add')->with($data)->render();
        $response = ['responseCode'=>1,'html'=>$html];
        return json_encode($response);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax() || !validatePermissions('acl/role/add')){
            return $this->errorResponse('Access denied');
        }

        $inputs = $request->all();

        if($errorMessage = $this->sanitizeStoreRoleData($inputs))
        {

            return $this->errorResponse($errorMessage);

        }

        $roleModel = new RoleModel();
        $roleModel->role_name       = $inputs['role_name'];
        $roleModel->save();


        $response = ['responseCode' => 1, 'msg' => 'Role has been added successfully'];
        return json_encode($response);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sanitizedId = sanitizeInput($id,'int');

        if (!validatePermissions('acl/role/show/{id}')){
            return $this->errorResponse('Access denied');
        }
        $response = ['responseCode'=>0,'html'=>''];
        if($sanitizedId){
            $row = RoleModel::where('ID',$sanitizedId)->get()->first();
            if($row){
                $data['row'] = $row;
                $html = view('admin/acl/role/inc_show')->with($data)->render();
                $response = ['responseCode'=>1,'html'=>$html];
            }
        }
        return json_encode($response);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $sanitizedId = sanitizeInput($id,'int');

        if (!$request->ajax() || !validatePermissions('acl/role/edit/{id}') || !isInteger($sanitizedId)){
            return $this->errorResponse('Access denied');
        }

        $data=['pageTitle'=>'ACL - User Roles'];
        $data['row'] = RoleModel::where('ID',$sanitizedId)->get()->first();
        if(!$data['row']){
            $response = ['responseCode' => 0, 'msg' => 'Record not found'];
        return json_encode($response);
        }
        $data['catResult'] = ModuleCategoryModel::orderBy('category_name','ASC')->get();
        $html = view('admin/acl/role/edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $sanitizedId = sanitizeInput($id,'int');

        if (!$request->ajax() || !validatePermissions('acl/role/edit/{id}') || !isInteger($sanitizedId)){
            return $this->errorResponse('Access denied');
        }

        $inputs = $request->all();

        if($errorMessage = $this->sanitizeUpdateRoleData($inputs))
        {

            return $this->errorResponse($errorMessage);

        }

        // Find the role model by ID
        $roleModel = RoleModel::find($sanitizedId);

        if (!$roleModel) {
            return response()->json(['responseCode' => 0, 'msg' => 'Role not found'], 404);
        }

        $roleModel = RoleModel::find($sanitizedId);
        $roleModel->role_name    = $inputs['role_name'];
        $roleModel->save();

        //Permissions
        RolePrivilgeModel::where('role_ID',$id)->delete();

        if($request->input('access')){
            foreach($request->input('access') as $moduleId=>$val){
                $rolePrivilgeModelr = new RolePrivilgeModel();
                $rolePrivilgeModelr->role_ID    = $sanitizedId;
                $rolePrivilgeModelr->module_ID  = $moduleId;
                $rolePrivilgeModelr->save();
            }
        }

        $response = ['responseCode' => 1, 'msg' => 'Role has been updated successfully'];
        return json_encode($response);
    }

    //Ajax update display order
    public function updateDisplayOrder(Request $request, $id,$displayOrderValue)
    {
        $sanitizedId = sanitizeInput($id,'int');
        $sanitizedDisplayOrderValue = sanitizeInput($displayOrderValue,'int');

        if($request->ajax()){
            $roleModel = RoleModel::find($sanitizedId);
            $roleModel->display_order = $sanitizedDisplayOrderValue;
            $roleModel->save();
            echo "Done";
        }else{
            abort(403);
        }
    }

    public function searchRole(Request $request){

        if (!$request->ajax() || !validatePermissions('acl/role/search')){
            return $this->errorResponse('Access denied');
        }

        $searchWord = sanitizeInput($request->word,'alphanumeric');

        $data['result'] = RoleModel::where('role_name','LIKE','%'.$searchWord.'%')->with('modules')->get();

        $html = view('admin/acl/role/inc_role_cards')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sanitizedId = sanitizeInput($id,'int');

        if (!validatePermissions('acl/role/delete/{id}')){
            abort(403);
        }

        RoleModel::destroy($sanitizedId);
        Session::flash('flash_message_success','Record has been deleted.');
        return Redirect::route('acl/role');
    }
}
