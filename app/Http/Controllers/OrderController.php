<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Food;
use App\Models\OrderItem;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{ public function index()
    {
        $order=Order::all();
        return response()->json([
            'status' => 'success',
            'order' => $order,      
        ]);
    }

    public function create_order(Request $request)
    {    $validator = Validator::make($request->all(), [
            'status' => 'required|string|unique:Order,status',
            "date_of_delivery"=>'required|string',
            'item_id'=>'required',            
            ]); 
        dump($request['token']);
        try {
            $userId=JWTAuth::setToken($request['token'])->getPayload()->get('sub');
            $user=User::find($userId);
            
            $order=Order::where('user_id',$userId)->first();
            if(! $order){          
                $order=new Order();
                $order->status=$request->status;
                $order->user_id=$userId;
                $order->date_of_delivery=$request->date_of_delivery;
                $order->save();  
                dump($request['token']);
        }           
            $food= Food::find($request->item_id);
            $orderItem=new OrderItem();
            $orderItem->order_id=$order->id;
            $orderItem->food_id=$request->item_id;
            $orderItem->price=$food->price;
            $orderItem->quantity=$request->quantity;
            $orderItem->save();
            $order->total_price+=$orderItem->price * $orderItem->quantity;
            $order->save();
            $order->orderItem->add($orderItem);
            return response()->json(   ['order'=>$order],200);        
        

        } catch (JWTException $e) {
            return response()->json(   ['errror'=>'token is invalid'],401);        
                    } 

    }


    public function show($id)
    {
        $order=Order::find($id);
        
        return response()->json([
            'status' => 'success',
            'order' => $order,     
        ]);
    }

 
    public function update(Request $request,  $id)
    {        
        
            $validator = Validator::make($request->all(), [
                'status' => 'string|unique:Order,status',
                "date_of_delivery"=>'string',
                'item_id'=>'required', 
           
           ]);
           try {
            $userId=JWTAuth::setToken($request['token'])->getPayload()->get('sub');
            $user=User::find($userId);
            $order=Order::find($userId);
            $order->update($request->all()
               
                );
            return response()->json(   ['order'=>$order],200); 
        } catch (JWTException $e) {
            return response()->json(   ['errror'=>'token is invalid'],401);        
                    }    
   }


    public function destroy( $id)
    {
        $order=Order::find($id);
        if($order){
            $order->delete();
            return response()->json('Order deleted succesfuly', 200);
        }
        else{
             return response()->json('Order not found', 500);
        }
    
    }

    
    public function edit_quantity(Request $request){
        try {
            $userId=JWTAuth::setToken($request['token'])->getPayload()->get('sub');
            $user=User::find($userId);
           // dd($user);
            $order=Order::where('user_id',$userId)->first();
            //dd($order);
                $item_id= $request->item_id;
               
                $orderItems=$order->orderItem->find($item_id)->first();
                dump($orderItems);
                $order->total_price=$order->total_price-($orderItems->price * $orderItems->quantity);
                $orderItems->quantity=$request->quantity;
                $order->total_price=$order->total_price+($orderItems->price * $orderItems->quantity);
                $orderItems->save();           
                $order->save();
                return response()->json([
                    'status' => 'quantity updated Successfully',
                    '$order->orderItem'=>$order->orderItem,
                    'total_price' =>$order->total_price, 
                ], 200);

            
            
            } catch (JWTException $e) {
                return response()->json(   ['errror'=>'token is invalid'],401);        
                        }    
    }
    public function order_Items($id)
    {  
        $order=Order::find($id);  
       if($order){
        
          $orderIOtems=$order->orderItem;
        
          return response()->json([
            'status' => 'success',
            'OrderItem' => $orderIOtems,   
            'total_price' =>$order->total_price, 
        ]);}
        else{
            return response()->json('Order not found', 500);
       }
    }


public function delete_order_Items($id,Request $request)
{
    $order=Order::find($id);
    //$orderItem=OrderItem::find()
    if($order){
       $item_id= $request->item_id;
      // dd($order->orderItem->find($item_id));
        $orderItems=$order->orderItem->find($item_id)->first();
        $order->total_price=$order->total_price-($orderItems->price * $orderItems->quantity);
        $order->save();
        $order->orderItem->find($item_id)->delete();
        $orderItems->delete();
        //dump($order->orderItem->find($item_id));
        if($order->orderItem->empty()){
            $order->delete();
            return response()->json([
                'status' => 'OrderItems deleted Successfully',

            ], 200);
        }
        return response()->json([
            'status' => 'OrderItems deleted Successfully',
              '$order->orderItem'=>$order->orderItem,
            'total_price' =>$order->total_price, 
        ], 200);
    }
    else{
         return response()->json('Order not found', 500);
    }
    
}
}