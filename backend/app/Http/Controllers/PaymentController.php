<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    // GET /payments
    public function index(Request $request)
    {
        $query = Payment::query();

        if ($clientId = $request->query('client_id')) {
            $query->where('client_id', $clientId);
        }

        return $query->get();
    }

    // POST /payments
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'client_paid' => 'required|numeric|min:0',
            'insurance_paid' => 'required|numeric|min:0',
        ]);

        return Payment::create($validated);
    }

    // DELETE /payments/{id}
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted.']);
    }

    // EDIT/UPDATE payments
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
    
        $validated = $request->validate([
            'client_paid' => 'sometimes|required|numeric|min:0',
            'insurance_paid' => 'sometimes|required|numeric|min:0',
        ]);
    
        $payment->update($validated);
    
        return $payment;
    }
    
}
