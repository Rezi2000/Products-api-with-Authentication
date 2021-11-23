<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;


class ProductController extends Controller
{

    public function categories($id){

        $products = Product::where([
            ['id',$id],
            ['user_id',auth()->user()->id]
        ])->get();

      //relation with category
        foreach ($products as $product){
            echo $product->category;
        };

    }

    public function index()
    {

        return User::findOrFail(auth()->user()->id)->product;

       // return Product::where('user_id',auth()->user()->id)->get();

    }



    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'description'=>'string',
            'price'=>'required|numeric'
        ]);

        return Product::create([
            'name' => $request->get('name'),
            'description'=>$request->get('description'),
            'price'=>$request->get('price'),
            'user_id' => auth()->user()->id
        ]);

    }


    public function show($id)
    {

       return Product::where('id',$id)->where('user_id',auth()->user()->id)->get();

    }

    public function search($name){

        return Product::where([
           ['name','like','%'.$name.'%'],
           ['user_id',auth()->user()->id]
        ])->get();
    }



    public function update(Request $request, $id)
    {
        $product = Product::where('id',$id)->where('user_id',auth()->user()->id);
        $product->update($request->all());
        return $product->get();
    }



    public function destroy($id)
    {
        if(Product::where('id',$id)->where('user_id',auth()->user()->id)->delete()){
            return response([
                'message' => 'product has been deleted'
            ]);
        }else{
            return response([
                'message' => 'product not found'
            ]);
        }

    }
}
