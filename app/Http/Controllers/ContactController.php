<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("contact");
    }

    public function contactApi()
    {
        $contacts = Contact::all();
        return Datatables::of($contacts)->addColumn("action", function($contacts){
            return "<div class='d-flex justify-content-center'>
                    <button onclick='editContact($contacts->id)' class='btn btn-info m-1'>Edit</button> 
                    <button onclick='deleteContact($contacts->id)' class='btn btn-danger m-1'>Delete</button> 
                </div>";
        })->addIndexColumn()->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $validate = Validator::make($inputs, [
            'name'=>'required',
            'mobile'=>'required|unique:contacts'
        ] );

        if($validate->fails()){
            return response()->json([
                'title'=>'Error!',
                'message'=>$validate->messages()->all()[0],
                'icon'=>'error',
            ]);
        }

        Contact::create(['name'=>$inputs['name'], 'mobile'=>$inputs['mobile']]);

        return response()->json([
            'title'=>'Success!',
            'message'=>'Contact saved successfully!',
            'icon'=>'success',
        ]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return $contact;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Contact::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);

        $inputs = $request->all();
        $validate = Validator::make($inputs, [
            'name'=>'required',
            'mobile'=>'required'
        ] );

        if($validate->fails()){
            return response()->json([
                'title'=>'Error!',
                'message'=>$validate->messages()->all()[0],
                'icon'=>'error',
            ]);
        }

        $contact->name = $inputs['name'];
        $contact->mobile = $inputs['mobile'];
        $contact->save();

        return response()->json([
            'title'=>'Success!',
            'message'=>'Contact updated successfully!',
            'icon'=>'success',
        ]);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Contact::destroy($id);
        return response()->json([
            'title'=>'Success!',
            'message'=>'Contact deleted successfully!',
            'icon'=>'success',
        ]);
    }
}
