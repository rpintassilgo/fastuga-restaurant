<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  
    public function showAllProducts()
    {
        return ProductResource::collection(Product::paginate(10));
    }

    public function showProduct($id)
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    public function showHotDishes()
    {
        $product = Product::where('type','hot dish')->paginate(10);
        return ProductResource::collection($product);
    }

    public function showColdDishes()
    {
        $product = Product::where('type','cold dish')->paginate(10);
        return ProductResource::collection($product);
    }

    public function showDrinks()
    {
        $product = Product::where('type','drink')->paginate(10);
        return ProductResource::collection($product);
    }

    public function showDesserts()
    {
        $product = Product::where('type','dessert')->paginate(10);
        return ProductResource::collection($product);
    }

    public function createProduct(ProductRequest $request)
    {
        try{
            DB::beginTransaction();

            $product = new Product;

            $product->name = $request->input('name');
            $product->type = $request->input('type');
            $product->description = $request->input('description');
            $product->photo_url = $request->input('photo_url');
            $product->price = $request->input('price');

            $product->save();

            DB::commit();

        } catch(\Throwable $error){
            DB::rollback();
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }

        return new ProductResource($product);
    }

    public function editProduct(ProductRequest $request, $id)
    {
        try{
            DB::beginTransaction();

            $product = Product::findOrFail( $request->id );
            $product->name = $request->input('name');
            $product->type = $request->input('type');
            $product->description = $request->input('description');
            $product->photo_url = $request->input('photo_url');
            $product->price = $request->input('price');

            $product->save();

            DB::commit();
        }
        catch(\Throwable $error){
            DB::rollback();
            return response()->json(['message' => 'Internal server error','error' => $error->getMessage()],500);
        }

        return new ProductResource($product);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail( $id );
        if( $product->delete() ){
            return new ProductResource( $product );
        }
    }
}
