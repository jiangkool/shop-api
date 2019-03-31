<?php

namespace App\Http\Controllers\Api;

use App\Models\Address;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->response->array(auth()->user()->addresses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(AddressRequest $request)
    {
        $user=$request->user();

        $address=new Address([
            'province'        => $request->province, 
            'city'            => $request->city ,
            'district'        => $request->district ,
            'address_details' => $request->address_details
        ]);

        $address->user()->associate($user);

        if($address->save()){

            return $this->response->created();
        }

        return $this->response->noContent();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressRequest $request,$id)
    {
      
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Address $address)
    {
        return $this->response->array($address->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, $id,Address $address)
    {
       $address=$address->where('id',$id)->first();
       $this->authorize('own',$address);
       $address->update($request->all());
       return $this->response->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $address=Address::where('id',$id)->first();
        $this->authorize('own',$address);
        $address->delete();
        return $this->response->noContent();
    }
}
