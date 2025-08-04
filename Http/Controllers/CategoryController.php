<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'category_name' => 'required|max:255',
            'description'   => 'nullable|max:255',
        ]);

        $validateData['slug'] = Str::slug($validateData['category_name']);

        $category = Category::create($validateData);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);
        return view('categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'category_name' => 'required|max:255',
            'description' => 'max:255'
        ]);

        $validatedData['slug'] = Str::slug($validateData['category_name']);

        $category = Category::find($id);
        $category->update($validateData);
        return redirect()
            ->route('categories.index')
            ->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        try {
            $category->delete();
            return redirect()
                ->route('categories.index')
                ->with('success', 'Category deleted successfully');
        } catch (Exception $e) {
            if ($e->getCode() == '23000') {
                return redirect()->route('categories.index')->with('error', 'Category is linked some posts.');
            }
            throw $e;
        }
    }
}
