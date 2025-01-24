<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function index() {
        // get categories
        $categories = Category::when(request()->q, function($categories) {
            $categories = $categories->where('name', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        // return with Api Resource
        return new CategoryResource(true, 'List Data Categories', $categories);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2000',
            'name' => 'required|unique:categories'
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // upload image
        $image = $request->file('image');
        $image->storeAs('public/categories', $image->hashName());

        // Create Category
        $category = Category::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'slug' => Str::slug($request->name, '-'),
        ]);

        if($category) {
            // return success with Api Resource
            return new CategoryResource(true, 'Data Category Berhasil Disimpan!', $category);
        }

        // return failed with Api Resource
        return new CategoryResource(false, 'Data Category Gagal Disimpan!', null);
    }

    public function show($id) {
        $category = Category::whereId($id)->first();

        if($category) {
            // return success with Api Resource
            return new CategoryResource(true, 'Detail Data Category!', $category);
        }

        // return failed with Api Resource
        return new CategoryResource(false, 'Detail Data Category Tidak Ditemukan!', null);
    }
}
