<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::latest()->get();

        return new CategoryResource(true, 'List Data Categories', $categories);
    }

    public function show($slug) {
        $category = Category::with('places.category', 'places.images')->where('slug', $slug)->first();

        if($category) {
            return new CategoryResource(true, 'List Data Places By: ' . $category->name, $category);
        }

        return new CategoryResource(false, 'Data Category Tidak Ditemukan', null);
    }
}
