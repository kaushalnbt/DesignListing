<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        $colors = Color::all();
        return $this->apiResponse->sendResponse(200, "Colors fetched successfully!", $colors);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:colors,name',
        ]);

        $color = Color::create($request->all());

        return $this->apiResponse->sendResponse(201, "Color created successfully!", $color);
    }

    public function show(Color $color)
    {
        return $this->apiResponse->sendResponse(200, "Color fetched successfully!", $color);
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|unique:colors,name,' . $color->id,
        ]);

        $color->update($request->all());

        return $this->apiResponse->sendResponse(200, "Color updated successfully!", $color);
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return $this->apiResponse->sendResponse(204, "Color deleted successfully!", null);
    }
}
