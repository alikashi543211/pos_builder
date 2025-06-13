<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LanguageModel;
use Validator;
use Auth;
use Storage;

class LanguagesController extends Controller
{
    public function index(){
        if(!validatePermissions('languages')) {
           abort(403);
        }
        $data=['pageTitle'=>'Languages',
        'subTitle'=>'Line Items'
        ];
        $data['result']= LanguageModel::orderBy('name', 'desc')->get();
        return view('language.listing')->with($data);
    }
    /** Create Document */
    public function create()
    {
        if(!validatePermissions('language/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
        $data=['pageTitle'=>'Languages',
        'subTitle'=>'Add Items'
        ];
        $html = view('language.add')->with($data)->render();
        $response = ['responseCode'=>1,'html'=>$html];
        return json_encode($response);
    }
    /** Store Document */
    public function store(Request $request) {
        // Check for permissions
        if (!validatePermissions('language/add')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
        if (!$request->ajax()) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }

        //  sanitization using a custom sanitization function
        $sanitizedInput = sanitizeAllInput($request->all());

        // Validate the request
        $validation = Validator::make($sanitizedInput, [
            'name' => 'bail|required|string|max:255',
            'short_code' => 'bail|required|string|max:255',
        ]);

        if ($validation->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validation->errors()->first()];
            return json_encode($response);
        }
        $dep = new LanguageModel();
        $dep->name = $sanitizedInput['name'];
        $dep->short_code = $sanitizedInput['short_code'];
        $dep->created_by = Auth::guard('admin')->user()->user_name;
        $dep->status = 'Active';
        $dep->save();

        $response = ['responseCode' => 1, 'msg' => 'Language successfully added'];
        return json_encode($response);
    }
    /** Edit Language */
    public function edit($id)
    {
        if(!validatePermissions('Language/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
    
        if (!isValidBase64($id)) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid ID'];
             return json_encode($response);
        }
        
        $decryptedId = base64_decode($id);
        $decryptedId = (int) $decryptedId;
       
        $data = [
            'pageTitle' => 'Languages',
            'subTitle' => 'Edit Items'
        ];
        
        $data['row'] = LanguageModel::where('id', $decryptedId)->first();

        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Record not found'];
             return json_encode($response);
        }

        $html = view('language.edit')->with($data)->render();
        
        $response = ['responseCode' => 1, 'html' => $html];
         return json_encode($response);
    }
    /** Update  Language */
    public function update(Request $request, $id) {
        // Check for permissions
        if (!validatePermissions('Language/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
        if (!$request->ajax()) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
    
        //  sanitization using a custom sanitization function
        $sanitizedInput = sanitizeAllInput($request->all());
    
        // Validate the request
        $validation = Validator::make($sanitizedInput, [
            'name' => 'bail|required|string|max:255',
            'short_code' => 'bail|required|string|max:255',
        ]);
    
        if ($validation->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validation->errors()->first()];
            return json_encode($response);
        }
    
        // Decrypt the ID
        $decryptedId = base64_decode($id);
    
        // Find the Language by ID and update it
        $dep = LanguageModel::find($decryptedId);
        if (!$dep) {
            $response = ['responseCode' => 0, 'msg' => 'Language not found'];
            return json_encode($response);
        }
    
        $dep->name = $sanitizedInput['name'];
        $dep->short_code = $sanitizedInput['short_code'];
        $dep->save();
    
        $response = ['responseCode' => 1, 'msg' => 'Language successfully updated'];
        return json_encode($response);
    }
    /** Update Status  */
    function updateLanguageStatus(Request $request){
        if(!validatePermissions('Language-update-status')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
        if(!$request->ajax()){
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
            return json_encode($response);
        }
       
        $decryptedId = base64_decode($request->documentID);
        
        // Check if the decoded ID is a valid integer
        if (!is_numeric($decryptedId)) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid ID'];
             return json_encode($response);
        }

        $decryptedId = (int) $decryptedId;
       
        $row=LanguageModel::where('id',$decryptedId)->first();
        if(!$row){
            $response = ['responseCode'=>0,'msg'=>'Record Not found'];
            return;
        }
        $status = ($row->status =='Active')?'In-Active':'Active';
        $row->status =$status;
        $row->update();
        $response = ['responseCode'=>1,'msg'=>'Language Status Update Successfully','newStatus'=>$row->status];
        return json_encode($response);


    }
    /** Delete Document */
    public function deleteLanguage(Request $request)
    {
        // Check for permissions
        if (!validatePermissions('Language/delete')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
        if (!$request->ajax()) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions denied'];
        return json_encode($response);
        }
       
    
        // Decrypt the document ID
        $decryptedId = base64_decode($request->documentID);
        if (!is_numeric($decryptedId)) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid ID'];
             return json_encode($response);
        }
    
        $decryptedId = (int) $decryptedId;
        $row = LanguageModel::find($decryptedId);
        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Record Not Found'];
            return json_encode($response);
        }
        $row->delete();
    
        $response = ['responseCode' => 1, 'msg' => 'Language Delete Successful'];
        return json_encode($response);
    }
      



}
