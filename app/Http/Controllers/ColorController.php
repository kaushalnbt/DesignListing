<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColorController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        try {
            $colors = Color::all();
            return $this->apiResponse->sendResponse(200, "Colors fetched successfully!", $colors);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while fetching colors.", null);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:colors,name',
        ]);

        DB::beginTransaction();
        try {
            $color = Color::create($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(201, "Color created successfully!", $color);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while creating the color.", null);
        }
    }

    public function show(Color $color)
    {
        try {
            return $this->apiResponse->sendResponse(200, "Color fetched successfully!", $color);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while fetching the color.", null);
        }
    }

    public function update(Request $request, Color $color)
    {
        $request->validate([
            'name' => 'required|string|unique:colors,name,' . $color->id,
        ]);

        DB::beginTransaction();
        try {
            $color->update($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Color updated successfully!", $color);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while updating the color.", null);
        }
    }

    public function destroy(Color $color)
    {
        DB::beginTransaction();
        try {
            $color->delete();
            DB::commit();
            return $this->apiResponse->sendResponse(204, "Color deleted successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while deleting the color.", null);
        }
    }
}
