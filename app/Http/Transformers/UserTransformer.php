<?php
namespace App\Http\Transformers;

use App\Models\User;
use Dingo\Api\Http\Request;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

 	public function transform(User $user)
    {
        return [
        	'name'=>$user->name,
        	'email'=>$user->email,
        	'addresses'=>$user->addresses
        ];
    }

}