<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;




use Illuminate\Http\Request;

class LoginController extends Controller
{
  //customer login page
    public function index(){
        return view('login');

   }

   public function authenticate(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->route('account.login')
                ->withInput()
                ->withErrors($validator);
        }

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('account.dashboard');
        } else {
            return redirect()->route('account.login')->with('error', 'Email or password is incorrect');
        }
    }
public function ragister(Request $request){
    return view('ragister');

}


public function processregister(Request $request){

    $validator = Validator::make($request->all(), [
        'email' =>'required|email|unique:users',
        'password' =>'required'
    ]);
    if($validator->passes()){
        
        $user = new User();
        $user->name= $request->name;
        $user->email= $request->email;
        $user->password = Hash::make($request->password);
        $user->role= 'customer';
        $user->save();
        return redirect()->route('account.login')->with('success', 'You have registered successfully');

    }else{
        return redirect()->route('account.ragister')
        ->withInput()
        ->withErrors($validator);
    }


}
   public function logout(){
    Auth::logout();
    return redirect()->route('account.login');
   }

}
