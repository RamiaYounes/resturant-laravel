<?php

namespace App\Http\Controllers;

use App\Models\BookTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookTable=BookTable::all();
        return response()->json([
            'status' => 'success',
            'bookTable' => $bookTable,      
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'string',
            'guest' => 'required|string',
            "phone"=>'required|string',
            'date'=>'required|string' ,             
            'time'=>'required|string' ,             
            ]);          
                $bookTable=new BookTable();
                $bookTable->name=$request->name;
                $bookTable->email=$request->email;
                $bookTable->phone=$request->phone;
                $bookTable->guest=$request->guest;
                $bookTable->date=$request->date;
                $bookTable->time=$request->time;

                $bookTable->save();
                return response()->json(           
                     $bookTable,
                
                );
     
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookTable  $bookTable
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        $bookTable=BookTable::findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'bookTable' => $bookTable,     
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookTable  $bookTable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|unique:BookTable,name',
            'email' => 'string',
            'guest' => 'string',
            "phone"=>'string',
            'date'=>'string' ,             
            'time'=>'string' ,             
            ]);     
            $bookTable=BookTable::find($id);
            
            $bookTable->update($request->all());
                
            return response()->json([
                'status' => 'success',
                'bookTable' => $bookTable,     
            ]); 

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookTable  $bookTable
     * @return \Illuminate\Http\Response
     */
  
     public function destroy( $id)
     {
         $bookTable=BookTable::find($id);
         if($bookTable){
             $bookTable->delete();
             return response()->json('bookTable deleted succesfuly', 200);
         }
         else{
              return response()->json('bookTable not found', 500);
         }
     
     }
}
