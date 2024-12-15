<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category=Category::all();
        return response()->json($category);
    }

    public function create(Request $request)
    {
       try {

             $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:Category,name',
            "image"=>'required'
               
            ]);
          
                $category=new Category();
                $category->image=upload_images('Category',$request->image);
                $category->name=$request->name;
                $category->save();
                return response()->json(           
                     $category,
                
                );        
       } catch (Exception $th) {
        return response()->json($th,500); 
       }
     
   
    }


    
    public function show($id)
    {
        $category=Category::find($id);
        
        return response()->json([
            'status' => 'success',
            'category' => $category,
        
        ]);
    }

 
    public function update(Request $request,  $id)
    {        
        try {
            $validator = Validator::make($request->all(), [
           'name' => 'string|unique:Category,name',
           "image"=>'required'
           ]);
           $category=Category::find($id);
           if($request->hasFile('image')){
            File::delete('images/category/'.$category->image);
            $category->image=upload_images('Category',$request->image);
           }
            $category->name=$request->name;
            $category->update();
               return response()->json(           
                    $category,
               
               );

       
      } catch (Exception $th) {
       return response()->json($th,500); 
      }
    
  
   }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $category=Category::find($id);
        if($category){
            $category->delete();
            return response()->json('category deleted succesfuly', 200);
        }
        else{
             return response()->json('category not found', 500);
        }
    }
}
