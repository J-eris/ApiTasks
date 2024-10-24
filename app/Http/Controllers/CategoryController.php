<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::with('auctions')->paginate(10);
        return response()->json([
            'message' => 'Categories retrieved successfully',
            'categories' => $category
        ], 200);
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
    public function store(CategoryRequest $request)
    {
        $validatedData = $request->validated();

        $category = Category::create($validatedData);
        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category,
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with('auctions')->find($id);

        if ($category) {
            return response()->json([
                'message' => 'Category retrieved successfully',
                'category' => $category,
            ], 200);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::find($id);

        if ($category) {
            $validatedData = $request->validated();

            $category->update($validatedData);
            return response()->json([
                'message' => 'Category updated successfully',
                'category' => $category,
            ], 200);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            return response()->json([
                'message' => 'Category deleted successfully',
            ], 200);
        } else {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }
}
