<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use App\Http\Transformers\UserTransformer;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;

class HomeController extends Controller
{
    use Helpers;

    public function index()
    {
    	return $this->response->collection(User::all(),UserTransformer::class);
    }

    public function login(UserLoginRequest $request)
    {
    	$data=$request->only(['name','password']);

    	if (auth()->attempt($data)) {

    		return $this->response->array(['token'=>auth()->user()->createToken('MyApp')->accessToken]);
    	}else{

    		return $this->response->error(trans('auth.failed'),404);
    	}

    }

    public function register(UserRegisterRequest $request)
    {
    	if (User::updateOrCreate([
    		'name'=>$request->name,
    		'email'=>$request->email,
    		'password'=>app('hash')->make($request->password)
    	])) {
    		return $this->response->created();
    	}else{
    		return $this->response->errorInternal();
    	}
    }

    public function me()
    {
        return $this->response->array(auth()->user());
    }

}
