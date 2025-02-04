<?php

namespace App\Http\Controllers;

use App\Models\Finish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinishController extends Controller
{
    protected $apiResponse;

    public function __construct(ApiResponse $apiResponse)
    {
        $this->apiResponse = $apiResponse;
    }

    public function index()
    {
        try {
            $finishes = Finish::all();
            return $this->apiResponse->sendResponse(200, "Finishes fetched successfully!", $finishes);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while fetching finishes.", null);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:finishes,name',
        ]);

        DB::beginTransaction();
        try {
            $finish = Finish::create($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(201, "Finish created successfully!", $finish);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while creating the finish.", null);
        }
    }

    public function show(Finish $finish)
    {
        try {
            return $this->apiResponse->sendResponse(200, "Finish fetched successfully!", $finish);
        } catch (\Exception $e) {
            return $this->apiResponse->sendResponse(500, "An error occurred while fetching the finish.", null);
        }
    }

    public function update(Request $request, Finish $finish)
    {
        $request->validate([
            'name' => 'required|string|unique:finishes,name,' . $finish->id,
        ]);

        DB::beginTransaction();
        try {
            $finish->update($request->all());
            DB::commit();
            return $this->apiResponse->sendResponse(200, "Finish updated successfully!", $finish);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while updating the finish.", null);
        }
    }

    public function destroy(Finish $finish)
    {
        DB::beginTransaction();
        try {
            $finish->delete();
            DB::commit();
            return $this->apiResponse->sendResponse(204, "Finish deleted successfully!", null);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->apiResponse->sendResponse(500, "An error occurred while deleting the finish.", null);
        }
    }
}
