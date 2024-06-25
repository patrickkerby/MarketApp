<?php

namespace App\Http\Controllers;

use App\categories;
use App\Products;
use App\product_quantities;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;


class ProductsController extends Controller
{
    public function index()
    {
        $categorized_products = Products::with('categories')->get()->sortBy('categories.name')->groupBy('categories.name');        
        $keys = $categorized_products->keys();
        $products = products::latest()->get();
        $products = $products->sortBy('name');
        $products->values()->all();

        return view('products.index', [
            'categorized_products' => $categorized_products,
            'keys' => $keys,
            'products' => $products
        ]);
    }

    public function show(Products $product)
    {

        $categories = categories::find($product->category_id);

    }

    public function create()
    {
        $categories = categories::latest()->get();
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
        $categories = categories::latest()->get();
        $categories = $categories->sortBy('name');
        $categories->values()->all();
        
        $current_category = categories::find($product->category_id);

        $products = products::latest()->get();
        $products = $products->sortBy('name');
        $products->values()->all();
        
        $quantities = Product_Quantities::where('product_id', $product->id)->get()->all();
        $quantities_count = count($quantities);
                
        $deletable = false;

        if ($quantities) {            
            $deletable = false;
        }
        else {
            $deletable = true;            
        }

        return view('products.edit', 
            [compact('product'),
            'product' => $product,
            'products' => $products,
            'categories' => $categories,
            'current_category' => $current_category,
            'quantities' => $quantities,
            'deleteable' => $deletable,
            'quantities_count' => $quantities_count
        ]);
    }

    public function update(Request $request, Products $product, $id )
    {
        $products = products::latest()->get();
        $products = $products->sortBy('name');
        $products->values()->all();
        $product = products::find($id);  

        $categories = categories::latest()->get();
        $categories = $categories->sortBy('$categories');
        $categories->values()->all();
        $current_category = categories::find($product->category_id);
        
        $quantities = Product_Quantities::where('product_id', $product->id)->get()->all();
        $quantities_count = count($quantities);        
        $deletable = false;

        if ($quantities) {            
            $deletable = false;
        }
        else {
            $deletable = true;            
        }

        $newProductID = $request['newproduct_id']; 
        if($newProductID == null) {
            $newProductID = $id;
        }

        Product_Quantities::where('product_id', $product->id)->update(['product_id' => $newProductID]);               
        
        if ($request['name'] == null) {
            $request['name'] = $product->name;
        }
        else {
            $product->name = $request['name'];
        }
        $product->name = $request['name'];
        
        if ($request['price'] == null) {
            $request['price'] = $product->price;
        }
        else {
            $product->price = $request['price'];
        }
        $product->price = $request['price'];
        
        if ($request['category_id'] == null) {
            $request['category_id'] = $product->category_id;
        }
        else {
            $product->category_id = $request['category_id'];
        }

        if($request['archived'] == null) {
            $request['archived'] = $product->archived;
        }
        else {
            $product->archived = $request['archived'];
        }
        
        $product->save();


        // return redirect()->back()->with('success', 'Item updated successfully.');

        return view('products.edit', 
            [compact('product'),
            'product' => $product,
            'products' => $products,
            'categories' => $categories,
            'current_category' => $current_category,
            'deleteable' => $deletable,
            'quantities' => $quantities,
            'quantities_count' => $quantities_count
        ])->with('success', 'Item updated successfully.');
        
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

        // return redirect('/products');
        return redirect('/products')->with('message','Product has been deleted');

    }
}
