<?php

namespace App\Http\Controllers\User;

use App\Mail\UserCreated;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{

    public function __construct()
    {
//        $this->middleware('client.credentials')->only(['store','resend']);
//
//        $this->middleware('auth:api')->except(['store','resend','verify']);
//
//        $this->middleware('transform.input:'.UserTransformer::class)->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('type')){
            $users = User::where('role', $request->type)
                ->get();
        }else{
            $users = User::all();
        }
        return $this->showAll($users);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // If updating only store id then do this.
        if($request->has('store_id')){
            $user = User::findOrFail($request->user_id);
            $user->store_id = $request->store_id;
            $user->save();

            return $this->showOne($user, 200);
        }

        $rules = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6|confirmed'
        ];

        $this->validate($request, $rules);

        $password = bcrypt($request->password);
        $user = new User();
        $user->name = $request->name;
        $request->has('storeId') ? $user->store_id = $request->storeId : null;
        $user->email = $request->email;
        $user->password = $password;
        $user->verification_token = null;
        $user->role = User::ADMIN_USER;

        $user->save();

        return $this->showOne($user, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
    	if($request->ajax()){
//			return $this->showOne($user, 200);
			$user = User::findOrfail($request->id);
			return $this->showOne($user);
		}

		return view('welcome');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules =[
            'email'     => 'email|unique:users, email'.$user->id,
            'password'  => 'min:6|confirmed',
            'role'     => 'in:'. User::ADMIN_USER . ','. User::REGULAR_USER,
        ];

        if($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('email') && $request->email != $user->email){
            $user->verified = User::VERIFIED_USER;
            $user->email = $request->email;
        }

        $request->has('storeId') ? $user->store_id = $request->storeId : null;

        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        if($request->has('role')){
            $user->role = $request->role;
        }

        if(!$user->isDirty()){
            return $this->errorResponse('You need to specify a different value', 422);
        }

        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //delete the user
        $user->delete();

        return $this->showOne($user);
    }


    public function verify(String $token){
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('User is successfully verified', 200);
    }

    public function resend(User $user){
        if($user->isVerified()){
            return $this->errorResponse('This user is has verified', 409);
        }
        retry(5, function() use ($user){
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('User verification code has been send', 200);
    }
}
