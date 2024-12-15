<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class FoodController extends Controller
{ public function index()
    {
        $food=Food::paginate(20);
        return response()->json([
            'status' => 'success',
            'food' => $food,      
        ]);
    }

    public function create(Request $request)
    {
       try {
             $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:Food,title',
            'detail' => 'required|string',
            'price' => 'required|string',
            "image"=>'required',
            'category_id'=>'required'              
            ]);          
                $food=new Food();
                $food->image=upload_images('Food',$request->image);
                $food->title=$request->title;
                $food->detail=$request->detail;
                $food->price=$request->price;
                $food->category_id=$request->category_id;
                $food->save();
                return response()->json(           
                     $food,
                
                );
       
       } catch (Exception $th) {
        return response()->json($th,500); 
       }    
    }


    public function show($id)
    {
        $food=Food::find($id);
        
        return response()->json([
            'status' => 'success',
            'food' => $food,     
        ]);
    }

 
    public function update(Request $request,  $id)
    {        
        try {
            $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:Food,title',
            'detail' => 'required|string',
            'price' => 'required|string',
            "image"=>'required',
            'category_id'=>'required'
           
           ]);
           $food=Food::find($id);
           if($request->hasFile('image')){
            File::delete('images/category/'.$food->image);
            $food->image=upload_images('Food',$request->image);
           }
           $food->title=$request->title;
           $food->detail=$request->detail;
           $food->price=$request->price;
           $food->category_id=$request->category_id;
            $food->update();
               return response()->json(           
                    $food,             
               );

      } catch (Exception $th) {
       return response()->json($th,500); 
      }  
   }


    public function destroy( $id)
    {
        $food=Food::find($id);
        if($food){
            $food->delete();
            return response()->json('Food deleted succesfuly', 200);
        }
        else{
             return response()->json('Food not found', 500);
        }
    
    }
}
