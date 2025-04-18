<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Session;

class SessionController extends Controller
{
    // GET /sessions
    public function index(Request $request)
    {
        $query = Session::query();
    
        // 💡 If a client_id is passed in the query, filter by it
        if ($request->has('client_id')) {
            $query->where('client_id', $request->query('client_id'));
        }
    
        return $query->get();
    }
    

    // POST /sessions
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'date' => 'required|date',
            'fee' => 'required|numeric|min:0',
        ]);

        return Session::create($validated);
    }

    // GET /sessions/{id}
    public function show($id)
    {
        return Session::findOrFail($id);
    }

    // PUT /sessions/{id}
    public function update(Request $request, $id)
    {
        $session = Session::findOrFail($id);

        $validated = $request->validate([
            'date' => 'sometimes|required|date',
            'fee' => 'sometimes|required|numeric|min:0',
        ]);

        $session->update($validated);

        return $session;
    }

    // DELETE /sessions/{id}
    public function destroy($id)
    {
        $session = Session::findOrFail($id);
        $session->delete();

        return response()->json(['message' => 'Session deleted.']);
    }
}
