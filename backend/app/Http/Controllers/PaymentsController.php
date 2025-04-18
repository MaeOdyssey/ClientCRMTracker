<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::query();

        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        return response()->json($query->get());
    }
}
