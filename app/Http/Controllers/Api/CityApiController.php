<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\City;
use App\Http\Resources\CityResource;
use App\Http\Controllers\Controller;

class CityApiController extends Controller
{
    public function index()
    {
        $city = City::all();
        return CityResource::collection($city);
    }

    public function show($id)
    {
        $city = City::findOrFail($id);
        return new CityResource($city);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_name' => 'required|max:100',
        ]);

        $city = City::create($request->all());
        return new CityResource($city);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'city_name' => 'required|max:100',
        ]);

        $city = City::findorFail($id);
        $city->update($request->all());
        return new CityResource($city);
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        return response()->json(['message' => 'City deleted successfully.'], 200);
    }
}
