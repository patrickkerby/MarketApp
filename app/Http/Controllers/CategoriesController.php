<?php

namespace App\Http\Controllers;

use App\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Categories::latest()->get()->sortBy('name');
        return view('categories.index', ['categories' => $categories]);
    }

    public function show(Categories $category)
    {
        return view('categories.show', ['category' => $category]);
    }

    public function create()
    {
        return view('categories.create'); 
    }

    public function store()
    {
        Categories::create($this->validateCategory());

        return redirect('/categories');
    }

    public function edit(Categories $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Categories $category)
    {

        $category->update($this->validateCategory());

        return redirect('/categories');
    }

    protected function validateCategory()
    {
        return request()->validate([
            'name' => 'required',
            'desc' => 'required'
        ]);
    }

}
