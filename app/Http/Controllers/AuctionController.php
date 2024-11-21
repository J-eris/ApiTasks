<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuctionRequest;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{

    public function index()
    {
        $auctions = Auction::with('category', 'user', 'attachments')->paginate(10);
        return response()->json([
            'message' => 'Auctions retrieved successfully',
            'auctions' => $auctions
        ], 200);
    }

    public function store(AuctionRequest $request)
    {
        try {

            $auction = Auction::create($request->all());

            return response()->json([
                'message' => 'Auction created successfully',
                'auction' => $auction,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create auction',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($id)
    {
        $auction = Auction::with('category', 'user')->find($id);

        if ($auction) {
            return response()->json([
                'message' => 'Auction retrieved successfully',
                'auction' => $auction,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Auction not found',
            ], 404);
        }
    }

    public function update(AuctionRequest $request, $id)
    {
        $auction = Auction::find($id);

        if ($auction) {
            $auction->update($request->all());

            return response()->json([
                'message' => 'Auction updated successfully',
                'auction' => $auction,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Auction not found',
            ], 404);
        }
    }


    public function destroy($id)
    {
        $auction = Auction::find($id);

        if ($auction) {
            $auction->delete();
            return response()->json([
                'message' => 'Auction deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Auction not found',
            ], 404);
        }
    }
}
