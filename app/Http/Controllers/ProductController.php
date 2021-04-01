<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(2);
        return response([
            'success' => true,
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'price' => 'required',
        ]);

        $addProduct = Product::create($request->all());

        if ($addProduct) {
            return response([
                'success' => true,
                'item' => $addProduct,
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response([
                'success' => false,
                'message' => 'Product with ID ' . $id . ' does not exists.',
            ]);
        }
        ray($request->all());
        $product->update($request->all());
        return response([
            'success' => true,
            'item' => $product,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response([
                'success' => false,
                'message' => 'Product with ID ' . $id . ' does not exists.',
            ]);
        }

        $product->destroy($id);
        return response([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }

    public function search($name)
    {
        $searchedProduct = Product::where('name', 'like', '%' . $name . '%')->get();

        if ($searchedProduct->count() === 0) {
            return response([
                'success' => false,
                'message' => 'No results found',
            ]);
        }

        return response([
            'success' => true,
            'count' => $searchedProduct->count(),
            'products' => $searchedProduct,
        ]);
    }
}