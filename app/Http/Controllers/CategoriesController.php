<?php

namespace App\Http\Controllers;

use App\categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = categories::latest()->get()->sortBy('name');
        return view('categories.index', ['categories' => $categories]);
    }

    public function show(categories $category)
    {
        return view('categories.show', ['category' => $category]);
    }

    public function create()
    {
        return view('categories.create'); 
    }

    public function store()
    {
        categories::create($this->validateCategory());

        return redirect('/categories');
    }

    public function edit(categories $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(categories $category)
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
