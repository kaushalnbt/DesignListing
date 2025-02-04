<?php

namespace App\Http\Controllers;

use App\Models\Finish;
use Illuminate\Http\Request;

class FinishController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        $finishes = Finish::all();
        return $this->apiResponse->sendResponse(200, "Finishes fetched successfully!", $finishes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:finishes,name',
        ]);

        $finish = Finish::create($request->all());

        return $this->apiResponse->sendResponse(201, "Finish created successfully!", $finish);
    }

    public function show(Finish $finish)
    {
        return $this->apiResponse->sendResponse(200, "Finish fetched successfully!", $finish);
    }

    public function update(Request $request, Finish $finish)
    {
        $request->validate([
            'name' => 'required|string|unique:finishes,name,' . $finish->id,
        ]);

        $finish->update($request->all());

        return $this->apiResponse->sendResponse(200, "Finish updated successfully!", $finish);
    }

    public function destroy(Finish $finish)
    {
        $finish->delete();
        return $this->apiResponse->sendResponse(204, "Finish deleted successfully!", null);
    }
}
