<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoriesModel;
use App\Models\LanguageModel;
use App\Models\CategoriesLangModel;
use App\Rules\UniqueCategoryNameRule;
use App\Rules\XssFree;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CategoriesController extends Controller
{
    //
    public function index()
    {
        if(!validatePermissions('categories')) {
            abort(403);
         }
        $data=['pageTitle'=>'Categories',
                'subTitle'=>'Line Items'
             ];
        $data['result'] = CategoriesLangModel::whereHas('category')
            ->orderBy('sort_order','ASC')
            ->whereLangId(7)->get();

        return view('categories.listing')->with($data);
    }
    public function create(Request $request){
        if(!validatePermissions('category/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
        $data=['pageTitle'=>'Add Category',
        'subTitle'=>'Add Items'
        ];
        $data['languages']=LanguageModel::where('status',1)->orderBy('id', 'DESC')->get();

        $data['Categories']= CategoriesModel::whereHas('categoriesLang', function ($query) {

        return $query->where('lang_id', '=', 7)->where('status',1)->orderBy('id', 'DESC'); })->get();
        $html = view('categories.add')->with($data)->render();
        $response = ['responseCode'=>1,'html'=>$html];
        return json_encode($response);

    }
    public function store(Request $request)
    {
        // Check permissions
        if (!validatePermissions('category/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }

        if (!$request->ajax()) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid request'];
            return json_encode($response);
        }

        // Sanitize all input
        $input = sanitizeInputWithAllowedTags($request->all());

        // Validate input fields
        $validation = Validator::make($input, [
            'language' => 'required|integer',
            'category_name' => ['required','max:255', new UniqueCategoryNameRule($request->language)],
            'category_title' => 'required|string',
            'page_url' => 'required|unique:tbl_categories_lang|alpha_dash',
            'meta_title' => 'required|string|max:255',
            'parent_id' => 'nullable|integer',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_tag' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'key_information' => 'required|string|max:255',
            'sort_order' => 'required|integer',
            'featured' => 'required|in:0,1',
            'is_product' => 'required|in:0,1',
            'is_clickable' => 'required|in:0,1',
            'top_navigation' => 'required|in:0,1',
            'footer_navigation' => 'required|in:0,1',
            'left_navigation' => 'required|in:0,1',
            'right_navigation' => 'required|in:0,1',
            'full_description' => ['nullable', 'string', new XssFree()],
            'short_summary' => ['nullable', 'string', new XssFree()],
            'category_icon' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Assuming category icon is uploaded as an image file
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Assuming category icon is uploaded as an image file
            'category_banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Assuming category icon is uploaded as an image file
        ]);

        if ($validation->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validation->errors()];
            return json_encode($response);
        }

        DB::beginTransaction();

        try {
            // Insert data into CategoriesModel
            $data = new CategoriesModel();
            $data->added_by = Auth::guard('admin')->user()->user_name;
            $data->date_added = now();
            $data->status = 1;
            if (isset($input['category_icon']) && $request->hasFile('category_icon')) {
                $data->category_icon = uploadFile($input['category_icon'], 'category_icons', $request->file('category_icon')->getClientOriginalName());
            }
            if (isset($input['category_image']) && $request->hasFile('category_image')) {
                $data->category_image = $this->resizeAndSaveImage($input['category_image'], 'categories/categories_images', $request->file('category_image')->getClientOriginalName(), 430, 270);
            }
            if (isset($input['category_banner']) && $request->hasFile('category_banner')) {
                $data->category_banner = uploadFile($input['category_banner'], 'categories/categories_banner', $request->file('category_banner')->getClientOriginalName());
            }
            $data->save();
            $id = $data->id;

            // Create a new category instance
            $category = new CategoriesLangModel;
            $category->category_id  = $id;
            $category->lang_id       = $input['language'];
            $category->parent_id     = $input['parent_id'];
            $category->category_name     = $input['category_name'];
            $category->meta_title   = $input['meta_title'];
            $category->meta_keywords = $input['meta_keywords'];
            $category->page_url = $input['page_url'];
            $category->canonical_tag = $input['canonical_tag'];
            $category->meta_description = $input['meta_description'];
            $category->status = 1;
            $category->key_information = $input['key_information'];
            $category->sort_order = $input['sort_order'];
            $category->featured = $input['featured'];
            $category->top_navigation = $input['top_navigation'];
            $category->footer_navigation = $input['footer_navigation'];
            $category->left_navigation = $input['left_navigation'];
            $category->right_navigation = $input['right_navigation'];
            $category->full_description = $input['full_description'];
            $category->save();

            DB::commit();

            $response = ['responseCode' => 1, 'msg' => 'Category created successfully', 'id' => $category->cat_id];
            return json_encode($response);
        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error or notify the IT team as needed
            $response = ['responseCode' => 0, 'msg' => 'An error occurred. Please contact the IT Team'];
            return json_encode($response);
        }
    }


    public function edit(Request $request,$id){
        if(!validatePermissions('category/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }
        $data=['pageTitle'=>'Add Category',
        'subTitle'=>'Add Items'
        ];
        if (!isValidBase64($id)) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid ID'];
             return json_encode($response);
        }

        $decryptedId = base64_decode($id);
        $decryptedId = (int) $decryptedId;
        $data['row'] = CategoriesLangModel::where('category_id',$decryptedId)->first();
        if(!$data['row']){
            $response = ['responseCode' => 0, 'msg' => 'Category record not found'];
            return json_encode($response);
        }
        $data['languages']=LanguageModel::where('status',1)->orderBy('id', 'DESC')->get();
        $data['Categories']= CategoriesModel::whereHas('categoriesLang', function ($query) {
            return $query->where('lang_id', '=', 7)->where('status',1)->orderBy('id', 'DESC'); })->get();
        $html = view('categories.edit')->with($data)->render();
        $response = ['responseCode'=>1,'html'=>$html];
        return json_encode($response);

    }

    public function update(Request $request, $id)
    {
        // Check permissions
        if (!validatePermissions('category/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
           return json_encode($response);
        }

        if (!$request->ajax()) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid request'];
           return json_encode($response);
        }

        if (!isValidBase64($id)) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid ID'];
             return json_encode($response);
        }

        $decryptedId = base64_decode($id);
        $decryptedId = (int) $decryptedId;
        // Sanitize all input
        $input = sanitizeInputWithAllowedTags($request->all());
        // Validate input fields
        $validation = Validator::make($input, [
            'language' => 'required|integer',
            'category_name' => [
                'required',
                new UniqueCategoryNameRule($request->language, $decryptedId)
            ],
            'page_url' => [
                'required',
                'alpha_dash',
                Rule::unique('tbl_categories_lang')->ignore($decryptedId, 'category_id')
            ],
            'category_title' => 'required|string',
            'parent_id' => 'nullable|integer',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_tag' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'key_information' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'featured' => 'required|in:0,1',
            'top_navigation' => 'required|in:0,1',
            'footer_navigation' => 'required|in:0,1',
            'is_product' => 'required|in:0,1',
            'is_clickable' => 'required|in:0,1',
            'left_navigation' => 'required|in:0,1',
            'right_navigation' => 'required|in:0,1',
            'full_description' => ['nullable', 'string', new XssFree()],
            'short_summary' => ['nullable', 'string', new XssFree()],
            'category_icon' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Assuming category icon is uploaded as an image file
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Assuming category icon is uploaded as an image file
            'category_banner' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Assuming category icon is uploaded as an image file
        ]);

        if ($validation->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validation->errors()];
           return json_encode($response);
        }

        try {
             // Insert data into CmsPagesModel
            $data = CategoriesModel::find($decryptedId);
            $data->last_modified_by = Auth::guard('admin')->user()->user_name;
            $data->last_modified_date = date('Y-m-d H:i:s');
            if (isset($input['category_icon']) && $request->hasFile('category_icon')) {
                $data->category_icon = uploadFile($input['category_icon'], 'category_icons', $request->file('category_icon')->getClientOriginalName());
            }
            if (isset($input['category_image']) && $request->hasFile('category_image')) {
                $data->category_image = $this->resizeAndSaveImage($input['category_image'], 'categories/categories_images', $request->file('category_image')->getClientOriginalName());
            }
            if (isset($input['category_banner']) && $request->hasFile('category_banner')) {
                $data->category_banner = uploadFile($input['category_banner'], 'categories/categories_banner', $request->file('category_banner')->getClientOriginalName());
            }
             $data->update();
             $id = $data->id;

            // Find the category or fail
            $category = CategoriesLangModel::where('category_id',$decryptedId)->where('lang_id' , $request->language)->first();
            $category->parent_id     = @$input['parent_id'];
            $category->category_name = $input['category_name'];
            $category->meta_title = $input['meta_title'];
            $category->meta_keywords = $input['meta_keywords'];
            $category->page_url = $input['page_url'];
            $category->canonical_tag = $input['canonical_tag'];
            $category->meta_description = $input['meta_description'];
            $category->key_information = $input['key_information'];
            $category->sort_order = $input['sort_order'];
            $category->featured = $input['featured'];
            $category->top_navigation = $input['top_navigation'];
            $category->footer_navigation = $input['footer_navigation'];
            $category->left_navigation = $input['left_navigation'];
            $category->right_navigation = $input['right_navigation'];
            $category->full_description = $input['full_description'];
            $category->save();

            $response = ['responseCode' => 1, 'msg' => 'Category updated successfully', 'id' => $category->id];
           return json_encode($response);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'msg' => 'An error occurred. Please contact the IT Team']);
        }
    }


    //Deal status Change
    public function StatusChange(Request $request,$id){
        if(!validatePermissions('category/change-status/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
        if(!$request->ajax()){
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }
        if (!isValidBase64($id)) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid ID'];
             return json_encode($response);
        }

        $decryptedId = base64_decode($id);
        $decryptedId = (int) $decryptedId;

        $obj = CategoriesModel::find($decryptedId);
        if(!$obj){
            $response = ['responseCode'=>0,'msg'=>'Record not found.'];
            return json_encode($response);
        }
        $status = ($obj->status=='0')?'1':'0';
        $obj->status  = $status;
        $obj->save();
        $response = ['status'=> $status,'responseCode'=>1,'msg'=>'Status Update successfully.','newStatus'=>$obj->status];
        return json_encode($response);


    }

     /** Fetch Data on Language Switch */
     public function fetchCategoryContentByLanguage(Request $request)
     {


         $langId = $request->input('lang_id');
         $pageId = $request->input('page_id');
         $decryptedId = base64_decode($pageId);
         $decryptedId = (int) $decryptedId;
         $content = CategoriesLangModel::where('category_id', $decryptedId)
             ->where('lang_id', $langId)
             ->first();

         if ($content) {
             return response()->json([
                 'status' => 'success',
                 'data' => $content
             ]);
         } else {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Content not found'
             ]);
         }
     }

    public function destroy(Request $request, $id){
        if(!validatePermissions('category/destroy/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
        if(!$request->ajax()){
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }
        if (!isValidBase64($id)) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid ID'];
             return json_encode($response);
        }
        $decryptedId = base64_decode($id);
        $decryptedId = (int) $decryptedId;
        $obj = CategoriesModel::find($decryptedId);
        if(!$obj){
            $response = ['responseCode'=>0,'msg'=>'Record not found.'];
            return json_encode($response);
        }
        $obj->delete();
        $response = ['responseCode'=>1,'msg'=>'Category deleted successfully.'];
        return json_encode($response);


    }


}
