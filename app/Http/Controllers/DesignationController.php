<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DesignationsModel;
use Validator;
use Auth;
use Storage;

class DesignationController extends Controller
{
    public function index(){
        if(!validatePermissions('designations')) {
           abort(403);
        }
        $data=['pageTitle'=>'Designations',
        'subTitle'=>'Line Items'
        ];
        $data['result']= DesignationsModel::orderBy('designation_name', 'desc')->get();

        return view('designation.listing')->with($data);
    }
    /** Create Document */
    public function create()
    {
        if(!validatePermissions('designation/add')) {
              $response = ['responseCode' => 0, 'msg' => 'Permissions Denied'];
            return json_encode($response);
        }
        $data=['pageTitle'=>'Designation',
        'subTitle'=>'Add Items'
        ];
        $html = view('designation.add')->with($data)->render();
        $response = ['responseCode'=>1,'html'=>$html];
        return json_encode($response);
    }
    /** Store Document */
    public function store(Request $request) {
        // Check for permissions
        if (!validatePermissions('designation/add')) {
              $response = ['responseCode' => 0, 'msg' => 'Permissions Denied'];
            return json_encode($response);
        }
        if (!$request->ajax()) {
              $response = ['responseCode' => 0, 'msg' => 'Permissions Denied'];
            return json_encode($response);
        }

        //  sanitization using a custom sanitization function
        $sanitizedInput = sanitizeAllInput($request->all());

        // Validate the request
        $validation = Validator::make($sanitizedInput, [
            'designation_name' => 'bail|required|string|max:255',
        ]);

        if ($validation->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validation->errors()->first()];
            return json_encode($response);
        }
        $dep = new DesignationsModel();
        $dep->designation_name = $sanitizedInput['designation_name'];
        $dep->created_by = Auth::guard('admin')->user()->user_name;
        $dep->designation_status = 'Active';
        $dep->save();

        $response = ['responseCode' => 1, 'msg' => 'Designation successfully added'];
        return json_encode($response);
    }
    /** Edit Designation */
    public function edit($id)
    {
        if(!validatePermissions('designation/edit/{id}')) {
              $response = ['responseCode' => 0, 'msg' => 'Permissions Denied'];
            return json_encode($response);
        }

        if (!isValidBase64($id)) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid ID'];
            return json_encode($response);
        }

        $decryptedId = base64_decode($id);
        $decryptedId = (int) $decryptedId;

        $data = [
            'pageTitle' => 'Designation',
            'subTitle' => 'Edit Items'
        ];

        $data['row'] = DesignationsModel::where('id', $decryptedId)->first();

        if (!$data['row']) {
            $response = ['responseCode' => 0, 'msg' => 'Record not found'];
          return json_encode($response);
        }

        $html = view('designation.edit')->with($data)->render();

        $response = ['responseCode' => 1, 'html' => $html];
        return json_encode($response);
    }
    /** Update  Designation */
    public function update(Request $request, $id) {
        // Check for permissions
        if(!validatePermissions('designation/edit/{id}')) {
            $response = ['responseCode' => 0, 'msg' => 'Permissions Denied'];
            return json_encode($response);
        }
        if (!$request->ajax()) {
              $response = ['responseCode' => 0, 'msg' => 'Permissions Denied'];
            return json_encode($response);
        }

        //  sanitization using a custom sanitization function
        $sanitizedInput = sanitizeAllInput($request->all());

        // Validate the request
        $validation = Validator::make($sanitizedInput, [
            'designation_name' => 'bail|required|string|max:255',
        ]);

        if ($validation->fails()) {
            $response = ['responseCode' => 0, 'msg' => $validation->errors()->first()];
            return json_encode($response);
        }

        // Decrypt the ID
        $decryptedId = base64_decode($id);

        // Find the Designation by ID and update it
        $dep = DesignationsModel::find($decryptedId);
        if (!$dep) {
            $response = ['responseCode' => 0, 'msg' => 'Designation not found'];
            return json_encode($response);
        }

        $dep->designation_name = $sanitizedInput['designation_name'];
        $dep->save();

        $response = ['responseCode' => 1, 'msg' => 'Designation successfully updated'];
        return json_encode($response);
    }
    /** Update Status  */
    function updateDesignationStatus(Request $request){
        if(!validatePermissions('Designation-update-status')) {
              $response = ['responseCode' => 0, 'msg' => 'Permissions Denied'];
            return json_encode($response);
        }
        if(!$request->ajax()){
              $response = ['responseCode' => 0, 'msg' => 'Permissions Denied'];
            return json_encode($response);
        }

        $decryptedId = base64_decode($request->documentID);

        // Check if the decoded ID is a valid integer
        if (!is_numeric($decryptedId)) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid ID'];
          return json_encode($response);
        }

        $decryptedId = (int) $decryptedId;

        $row=DesignationsModel::where('id',$decryptedId)->first();
        if(!$row){
            $response = ['responseCode'=>0,'msg'=>'Record Not found'];
            return;
        }
        $status = ($row->designation_status =='Active')?'In-Active':'Active';
        $row->designation_status =$status;
        $row->update();
        $response = ['responseCode'=>1,'msg'=>'Designation Status Update Successfully','newStatus'=>$row->designation_status];
        return json_encode($response);


    }
    /** Delete Document */
    public function deleteDesignation(Request $request)
    {
        // Check for permissions
        if (!validatePermissions('designation/delete/{id}')) {
              $response = ['responseCode' => 0, 'msg' => 'Permissions Denied'];
            return json_encode($response);
        }
        if (!$request->ajax()) {
              $response = ['responseCode' => 0, 'msg' => 'Permissions Denied'];
            return json_encode($response);
        }

        // Decrypt the document ID
        $decryptedId = base64_decode($request->documentID);
        if (!is_numeric($decryptedId)) {
            $response = ['responseCode' => 0, 'msg' => 'Invalid ID'];
          return json_encode($response);
        }

        $decryptedId = (int) $decryptedId;
        $row = DesignationsModel::find($decryptedId);
        if (!$row) {
            $response = ['responseCode' => 0, 'msg' => 'Record Not Found'];
            return json_encode($response);
        }
        $row->delete();

        $response = ['responseCode' => 1, 'msg' => 'Designation Delete Successful'];
        return json_encode($response);
    }




}
