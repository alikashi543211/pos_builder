<?php

namespace App\Services\Acl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Acl\ModuleCategoryModel;
use App\Models\Acl\ModuleModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Traits\HasPermissionsTrait;
use App\Traits\ModuleCategoryTrait;
use App\Traits\ResponseTrait;

class ModuleCategoryService
{

    use HasPermissionsTrait, ModuleCategoryTrait, ResponseTrait;
    public $moduleCategoryModel,
        $moduleModel;
    public function __construct()
    {
        $this->moduleCategoryModel = new ModuleCategoryModel();
        $this->moduleModel = new ModuleModel();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!validatePermissions('acl/module-categories')) {
            abort(403);
        }
        $data = ['pageTitle' => 'Module Categories'];
        $data['result'] = $this->moduleCategoryModel->newQuery()->orderBy('display_order', 'ASC')->get();
        return view('admin/acl/moduleCategory/listing')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!validatePermissions('acl/module-categories/add')) {
            return $this->errorResponse('Access denied');
        }

        $data = ['pageTitle' => 'Module Categories'];

        $html = view('admin/acl/moduleCategory/add')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        if (!$request->ajax() || !validatePermissions('acl/module-categories/add')) {

            return $this->errorResponse('Access denied');
        }

        $inputs = $request->all();

        if ($errorMessage = $this->sanitizeStoreModuleCategoryData($inputs)) {

            return $this->errorResponse($errorMessage);
        }
        $moduleCategoryModel = new $this->moduleCategoryModel->newInsance();
        $moduleCategoryModel->category_name = $inputs['category_name'];
        $moduleCategoryModel->save();
        $response = ['responseCode' => 1, 'msg' => 'New record has been added successfully'];
        return json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($request, $id)
    {

        $sanitizedId = sanitizeInput($id, 'int');

        if (!$request->ajax() || !validatePermissions('acl/module-categories/show/{id}') || !isInteger($sanitizedId)) {

            return $this->errorResponse('Access denied');
        }

        $response = ['responseCode' => 0, 'html' => ''];
        if ($sanitizedId) {
            $row = $this->moduleCategoryModel->newQuery()->where('ID', $sanitizedId)->get()->first();
            if ($row) {
                $data['row'] = $row;
                $html = view('admin/acl/moduleCategory/inc_show')->with($data)->render();
                $response = ['responseCode' => 1, 'html' => $html];
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
    public function edit($request, $id)
    {
        $sanitizedId = sanitizeInput($id, 'int');

        if (!$request->ajax() || !validatePermissions('acl/module-categories/edit/{id}') || !isInteger($sanitizedId)) {

            return $this->errorResponse('Access denied');
        }

        $data = ['pageTitle' => 'Module Categories'];
        $data['row'] = $this->moduleCategoryModel->newQuery()->where('ID', $sanitizedId)->get()->first();
        if (!$data['row']) {
            abort(404);
        }
        $html = view('admin/acl/moduleCategory/edit')->with($data)->render();
        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        $sanitizedId = sanitizeInput($id, 'int');

        if (!$request->ajax() || !validatePermissions('acl/module-categories/edit/{id}') || !isInteger($sanitizedId)) {

            return $this->errorResponse('Access denied');
        }

        $inputs = $request->all();

        if ($errorMessage = $this->sanitizeStoreModuleCategoryData($inputs)) {

            return $this->errorResponse($errorMessage);
        }

        $moduleCategoryModel = $this->moduleCategoryModel->newQuery()->whereId($sanitizedId)->first();
        $moduleCategoryModel->category_name = $inputs['category_name'];
        $moduleCategoryModel->save();
        $response = ['responseCode' => 1, 'msg' => 'Record has been updated successfully'];
        return json_encode($response);
    }

    //Ajax update display order
    public function updateDisplayOrder($request, $id, $displayOrderValue)
    {
        $sanitizedId = sanitizeInput($id, 'int');

        if ($request->ajax()) {
            $moduleCategoryModel = $this->moduleCategoryModel->newQuery()->whereId($sanitizedId)->first();
            $moduleCategoryModel->display_order = $displayOrderValue;
            $moduleCategoryModel->save();
            echo "Done";
        } else {
            abort(403);
        }
    }

    public function searchModuleCategory($request)
    {

        if (!$request->ajax() || !validatePermissions('acl/module-categories/search')) {
            return $this->errorResponse('Access denied');
        }

        $searchWord = sanitizeInput($request->word, 'alphanumeric');

        $data['result'] = $this->moduleCategoryModel->newQuery()->where('category_name', 'LIKE', '%' . $searchWord . '%')->get();

        $html = view('admin/acl/moduleCategory/inc_module_category_cards')->with($data)->render();
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
        $sanitizedId = sanitizeInput($id, 'int');

        if (!validatePermissions('acl/module-categories/delete/{id}') || !isInteger($sanitizedId)) {
            abort(403);
        }
        $this->moduleModel->newQuery()->where('module_category_ID', $sanitizedId)->delete();
        $this->moduleCategoryModel->newQuery()->whereId($sanitizedId)->delete();
        Session::flash('flash_message_success', 'Record has been deleted.');
        return Redirect::route('acl.module.category.listing');
    }
}
