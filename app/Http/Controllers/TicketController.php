<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticket = Ticket::with('user')->paginate(10);
        return response()->json([
            'message' => 'Tickets retrieved successfully',
            'tickets' => $ticket
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $ticket = Ticket::create($validatedData);

            return response()->json([
                'message' => 'Ticket created successfully',
                'ticket' => $ticket,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create ticket',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::with('user')->find($id);

        if ($ticket) {
            return response()->json([
                'message' => 'Ticket retrieved successfully',
                'ticket' => $ticket
            ], 200);
        } else {
            return response()->json([
                'message' => 'Ticket not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(TicketRequest $request, $id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            $validatedData = $request->validated();

            $ticket->update($validatedData);
            return response()->json([
                'message' => 'Ticket updated successfully',
                'ticket' => $ticket,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Ticket not found',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = Ticket::find($id);

        if ($ticket) {
            $ticket->delete();
            return response()->json([
                'message' => 'Ticket deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Ticket not found',
            ], 404);
        }
    }
}
