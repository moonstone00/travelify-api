<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Place;
use App\Models\Slider;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //count categories
        $categories = Category::count();

        // count places
        $places = Place::count();

        // count sliders
        $sliders = Slider::count();

        // count users
        $users = User::count();

        return response()->json([
            'success' => true,
            'message' => 'Statistik Data',
            'data' => [
                'categories' => $categories,
                'places' => $places,
                'sliders' => $sliders,
                'users' => $users
            ]
        ]);
    }
}
