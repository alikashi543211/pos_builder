<?php

namespace App\Http\Controllers\Admin\Acl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Acl\ModuleCategoryService;

/**
 * Controller for handling Module Category related actions in the Admin ACL module.
 */
class ModuleCategoryController extends Controller
{
    /**
     * Service class instance for module category operations.
     *
     * @var ModuleCategoryService
     */
    public $moduleCategoryService;

    /**
     * Constructor initializes the ModuleCategoryService.
     */
    public function __construct()
    {
        $this->moduleCategoryService = new ModuleCategoryService();
    }

    /**
     * Display a listing of module categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->moduleCategoryService->index();
    }

    /**
     * Show the form for creating a new module category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->moduleCategoryService->create();
    }

    /**
     * Store a newly created module category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->moduleCategoryService->store($request);
    }

    /**
     * Display the specified module category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return $this->moduleCategoryService->show($request, $id);
    }

    /**
     * Show the form for editing the specified module category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        return $this->moduleCategoryService->show($request, $id);
    }

    /**
     * Update the specified module category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->moduleCategoryService->update($request, $id);
    }

    /**
     * Update the display order of a module category via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  int  $displayOrderValue
     * @return \Illuminate\Http\Response
     */
    public function updateDisplayOrder(Request $request, $id, $displayOrderValue)
    {
        return $this->moduleCategoryService->updateDisplayOrder($request, $id, $displayOrderValue);
    }

    /**
     * Search for module categories based on request filters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchModuleCategory(Request $request)
    {
        return $this->moduleCategoryService->searchModuleCategory($request);
    }

    /**
     * Remove the specified module category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->moduleCategoryService->destroy($id);
    }
}
