<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {     
     $products = Product::all();
     //return response()->json($products);
	 //$token = Auth::getToken();	 
     /*$apy = Auth::getPayload($token)->toArray();
		print_r($apy);
		if ($apy === 'Token has expired') { 
			return Auth::TokenExpired();
		} else {
			return Auth::TokenInvalid();
		}*/	
		
		$token = ''; 
		try {
			$tokenFetch = Auth::parseToken();			
			if ($tokenFetch) { 
				$token = str_replace("Bearer ", "", header('Authorization')); 
				return response()->json($products); 
			} else {
			$token = 'Token Not Found'; 
			} 
			} 
		catch (\Tymon\JWTAuth\Exceptions\JWTException $e) 
			{
			$token = 'Token is invalid or expired';
			} 
			return $token;
		
    }

    public function create(Request $request)
    {
		//validate incoming request 
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
			'description' => 'required',
        ]);

        try 
        {
           $product = new Product;
           $product->name= $request->name;
		   $product->price = $request->price;
		   $product->description= $request->description;
           $product->save();
            return response()->json( [
                        'entity' => 'products', 
                        'action' => 'create', 
                        'result' => 'success'
            ], 201);

        } 
        catch (\Exception $e) 
        {
            return response()->json( [
                       'entity' => 'products', 
                       'action' => 'create', 
                       'result' => 'failed'
            ], 409);
        }

		
       /* $product = new Product;
       $product->name= $request->name;
       $product->price = $request->price;
       $product->description= $request->description;       
       $product->save();
       return response()->json($product);*/
    }

     public function show($id)
     {
        $product = Product::find($id);
        return response()->json($product);
     }

     public function update(Request $request, $id)
     { 
        $product= Product::find($id);
        
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->description = $request->input('description');
        $product->save();
        return response()->json($product);
     }

     public function destroy($id)
     {
        $product = Product::find($id);
        $product->delete();
         return response()->json('product removed successfully');
     }


}
