<?php

namespace App\Http\Controllers;

use App\categories;
use App\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Products::latest()->get();
        $categorized_products = Products::with('categories')->get()->groupBy('categories.name');        
        $keys = $categorized_products->keys();

        return view('products.index', [
            'products' => $products,
            'categorized_products' => $categorized_products,
            'keys' => $keys
        ]);
    }

    public function show(Products $product)
    {

        $categories = Categories::find($product->category_id);

        return view('products.show', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $categories = Categories::latest()->get();
        $categories = $categories->sortBy('$categories');
        $categories->values()->all();

        return view('products.create', [
            'categories' => $categories
        ]);
       
    }

    public function store()
    {
        Products::create($this->validateProduct());

        return redirect('/products');
    }

    public function edit(Products $product)
    {
        $categories = Categories::latest()->get();
        $categories = $categories->sortBy('$categories');
        $categories->values()->all();

        $current_category =  Categories::find($product->category_id);


        return view('products.edit', 
            [compact('product'),
            'product' => $product,
            'categories' => $categories,
            'current_category' => $current_category
        ]);
    }

    public function update(Products $product)
    {

        $product->update($this->validateProduct());

        return redirect($product->path());
    }

    protected function validateProduct()
    {
        return request()->validate([
            'name' => 'required',
            'price' => 'required',
            'category_id' => 'required' 
        ]);
    }

    public function destroy(Products $product)
    {

        $product->delete();

        return redirect('/products');
    }
}
