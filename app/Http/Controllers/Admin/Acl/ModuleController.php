<?php

namespace App\Http\Controllers\Admin\Acl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acl\ModuleModel;
use App\Models\Acl\ModuleCategoryModel;
use Session;
use Redirect;
use App\Traits\CategoryTrait;
use App\Traits\ResponseTrait;

class ModuleController extends Controller
{

    use CategoryTrait, ResponseTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!validatePermissions('acl/module')){
            abort(403);
        }
        $data=['pageTitle'=>'ACL - Modules'];
        $data['result'] = ModuleModel::orderBy('display_order','ASC')->orderBy('module_category_ID','ASC')->get();
        $data['catResult'] = ModuleCategoryModel::orderBy('category_name','ASC')->get();
        return view('admin/acl/module/listing')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!validatePermissions('acl/module/add')){
            return $this->errorResponse('Access denied');
        }

        $data=['pageTitle'=>'ACL - Modules'];
        $data['catResult'] = ModuleCategoryModel::orderBy('category_name','ASC')->get();
        $html = view('admin/acl/module/add')->with($data)->render();
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
        if (!$request->ajax() || !validatePermissions('acl/module/add')){
            return $this->errorResponse('Access denied');
        }

        $inputs = $request->all();

        if($errorMessage = $this->sanitizeStoreCategoryData($inputs))
        {

            return $this->errorResponse($errorMessage);

        }

        $moduleModel = new ModuleModel();
        $show_in_menu = ($inputs['show_in_menu']==1)?'1':'0';
        $moduleModel->module_category_ID= $inputs['module_category_id'];
        $moduleModel->module_name       = $inputs['module_name'];
        $moduleModel->route             = $inputs['route'];
        $moduleModel->show_in_menu      = $show_in_menu;
        $moduleModel->css_class         = $inputs['css_class'];
        $moduleModel->save();

        $response = ['responseCode' => 1, 'msg' => 'New record has been added successfully'];
        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $sanitizedId = sanitizeInput($id,'int');

        if (!$request->ajax() || !validatePermissions('acl/module/show/{id}') || !isInteger($sanitizedId)){

            return $this->errorResponse('Access denied');
        }

        $response = ['responseCode'=>0,'html'=>''];
        if($sanitizedId){
            $row = ModuleModel::where('ID',$sanitizedId)->get()->first();
            if($row){
                $data['row'] = $row;
                $html = view('admin/acl/module/inc_show')->with($data)->render();
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

        if (!$request->ajax() || !validatePermissions('acl/module/edit/{id}') || !isInteger($sanitizedId)){
            return $this->errorResponse('Access denied');
        }

        $data=['pageTitle'=>'ACL - Module'];
        $data['catResult'] = ModuleCategoryModel::orderBy('category_name','ASC')->get();
        $data['row'] = ModuleModel::where('ID',$sanitizedId)->get()->first();
        if(!$data['row']){
            $response = ['responseCode'=>1,'msg'=>'Record Not Found'];
        }
        $html = view('admin/acl/module/edit')->with($data)->render();
        $response = ['responseCode'=>1,'html'=>$html];
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

        if (!$request->ajax() || !validatePermissions('acl/module/edit/{id}') || !isInteger($sanitizedId)){
            return $this->errorResponse('Access denied');
        }

        $inputs = $request->all();

        if($errorMessage = $this->sanitizeStoreCategoryData($inputs))
        {

            return $this->errorResponse($errorMessage);

        }

        $moduleModel = ModuleModel::find($sanitizedId);
        $show_in_menu = ($inputs['show_in_menu']==1)?'1':'0';

        $moduleModel->module_category_ID    = $inputs['module_category_id'];
        $moduleModel->module_name           = $inputs['module_name'];
        $moduleModel->route                 = $inputs['route'];
        $moduleModel->show_in_menu          = $show_in_menu;
        $moduleModel->css_class             = $inputs['css_class'];
        $moduleModel->save();


        $response = ['responseCode' => 1, 'msg' => 'New record has been updated successfully'];
        return json_encode($response);
    }

    //Ajax update display order
    public function updateDisplayOrder(Request $request, $id,$displayOrderValue)
    {
        $sanitizedId = sanitizeInput($id,'int');
        $sanitizedDisplayOrderValue = sanitizeInput($displayOrderValue,'int');

        if($request->ajax()){
            $moduleModel = ModuleModel::find($sanitizedId);
            $moduleModel->display_order = $sanitizedDisplayOrderValue;
            $moduleModel->save();
            echo "Done";
        }else{
            abort(403);
        }
    }

    public function searchModule(Request $request){

        if (!$request->ajax() || !validatePermissions('acl/module/search')){
            return $this->errorResponse('Access denied');
        }

        $searchWord = sanitizeInput($request->word,'string');
        
        $data['result'] = ModuleModel::where('module_name','LIKE','%'.$searchWord.'%')
                            ->orWhere('route','LIKE','%'.$searchWord.'%')->with('category','roles')->get();

        $html = view('admin/acl/module/inc_module_cards')->with($data)->render();
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

        if (!validatePermissions('acl/module/delete/{id}') || !isInteger($sanitizedId)){
            abort(403);
        }
        ModuleModel::destroy($sanitizedId);
        Session::flash('flash_message_success','Record has been deleted.');
        return Redirect::route('acl.module.listing');
    }
}
