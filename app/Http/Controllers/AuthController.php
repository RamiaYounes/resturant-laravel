<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'signup','admin_login']]);
    }

    public function login(Request $request)
    {   $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users',
            'password' => 'required|string|min:6'

        ]);

        if ($validator->fails()) {
            return response()->json(['valerrors'=>$validator->errors()],401);
        }
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                
                'errors' => 'Unauthorized',
            ],401);
        }
        $user = Auth::user();

        return response()->json([
            'status' => 200,
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',200
            ]
        ]);

    }
    public function admin_login(Request $request){
      
            try {
                if (! $token = JWTAuth::attempt(['email'=>$request['email'],'password'=>$request['password'],'role'=>'1'])) {
                    return response()->json(['errors' => 'Invalid credentials'], 401);
                }
    
                // Get the authenticated user.
                $user = auth()->user();
    
    
                return response()->json([
                    'status' => 'success',
                    'user' => $user,
                'token'=>$token]);
            } catch (JWTException $e) {
                return response()->json(['error' => 'Could not create token'], 500);
            }

    }
 
    public function signup(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'phone' => 'min:10',
            'address' => 'string',
        ]);
        if ($validator->fails()) {
            
            return response()->json(['errors'=>$validator->errors()], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            "phone"=>$request->phone,
            "address"=>$request->address,
        ]);

        

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
          
        ]);
    }
       public function profile(Request $request)
    { //dd($request->bearerToken());
        $userId=JWTAuth::setToken($request->bearerToken())->getPayload()->get('sub');
        $user=User::find($userId);
       // print(Auth::user());
        if(! $user){
            return response()->json(["error"=>'you dont have profile yet ,sign up and log in to have one']);
        }
        return response()->json($user);
    }
    public function editProfile(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'string|confirmed|min:6',
            'phone' => 'min:10',
            'address' => 'string',
        ]);
        $userId=JWTAuth::setToken($request->bearerToken())->getPayload()->get('sub');
        $user=User::find($userId);
        $user->name=$request->name;
        $user->phone=$request->phone;
        $user->password=$request->password;
        $user->email=$request->email;
        $user->address=$request->address;

        $user->update();
 
        return response()->json($user);
    }
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
       // auth()->logout();
        return response()->json(['message' => 'User is logged out']);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    protected function createNewToken($token)
    {
        $user = auth()->user();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }
    public function users()
    {
        $users = User::all();

        return response()->json([
            
            'user' => $users
        ]);
    }

    public function show_user(Request $request)
    {//dd($request['token']);
        $userId=JWTAuth::setToken($request['token'])->getPayload()->get('sub');
        $user=User::find($userId);

        return response()->json([
            
            'user' => $user
        ]);
    }
    
    public function delete_user(Request $request)
    {
        $userId=JWTAuth::setToken($request['token'])->getPayload()->get('sub');
        $user=User::find($userId);
        $user->delete();
        return response()->json([
            
            'Message' => 'deleted successfully'
        ]);
    }
    
}