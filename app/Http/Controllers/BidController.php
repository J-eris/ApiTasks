<?php

namespace App\Http\Controllers;

use App\Http\Requests\BidRequest;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{

    public function index()
    {
        $bids = Bid::with('auction', 'user')->paginate(10);
        return response()->json([
            'message' => 'Bids retrieved successfully',
            'bids' => $bids
        ], 200);
    }

    public function store(BidRequest $request)
    {
        try {
            $bid = Bid::create($request->all());

            return response()->json([
                'message' => 'Bid created successfully',
                'bid' => $bid,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create bid',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($id)
    {
        $bid = Bid::with('auction', 'user')->find($id);

        if ($bid) {
            return response()->json([
                'message' => 'Bid retrieved successfully',
                'bid' => $bid,
            ], 200);
        } else {
            return response()->json(['message' => 'Bid not found'], 404);
        }
    }

    public function update(BidRequest $request, $id)
    {
        $bid = Bid::find($id);

        if ($bid) {
            $bid->update($request->all());
            
            return response()->json([
                'message' => 'Bid updated successfully',
                'bid' => $bid,
            ], 200);
        } else {
            return response()->json(['message' => 'Bid not found'], 404);
        }
    }

    public function destroy($id)
    {
        $bid = Bid::find($id);

        if ($bid) {
            $bid->delete();
            return response()->json([
                'message' => 'Bid deleted successfully',
            ], 200);
        } else {
            return response()->json(['message' => 'Bid not found'], 404);
        }
    }
}
