<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
  
    public function index()
    {
        return Product::all();
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $product = new Product;
        $product->name = $request->input('name');
        $product->type = $request->input('type');
        $product->description = $request->input('description');
        $product->photo_url = $request->input('photo_url');
        $product->price = $request->input('price');

        if( $product->save() ){
            return new ProductResource($product);
        }
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail( $request->id );
        $product->name = $request->input('name');
        $product->type = $request->input('type');
        $product->description = $request->input('description');
        $product->photo_url = $request->input('photo_url');
        $product->price = $request->input('price');

        if( $product->save() ){
            return new ProductResource($product);
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail( $id );
        if( $product->delete() ){
            return new ArtigoResource( $product );
        }
    }
}
