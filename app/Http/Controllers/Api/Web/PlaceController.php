<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlaceResource;
use App\Models\Place;

class PlaceController extends Controller
{
    public function index() {
        $places = Place::with('category', 'images')->when(request()->q, function($places) {
            $places = $places->where('title', 'like', '%' . request()->q . '%');
        })->latest()->paginate(8);

        // return with Api Resource
        return new PlaceResource(true, 'List Data Places', $places);
    }

    public function show($slug) {
        $place = Place::with('category', 'images')->where('slug', $slug)->first();

        if($place) {
            return new PlaceResource(true, 'Detail Data Places : '.$place->name, $place);
        }

        return new PlaceResource(false, 'Data Place Tidak Ditemukan', null);
    }

    public function all_places() {
        $places = Place::with('category', 'images')->latest()->get();

        // return with Api Resource
        return new PlaceResource(true, 'List Data Places', $places);
    }
}
