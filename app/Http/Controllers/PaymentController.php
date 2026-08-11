<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Package;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('payments', compact('packages'));
    }

    public function getPayments(Request $request)
    {
        if ($request->ajax()) {
            $data = Payment::with(['client', 'package'])->select('payments.*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('client_name', function ($row) {
                    return $row->client ? $row->client->full_name : 'N/A';
                })
                ->addColumn('package_name', function ($row) {
                    return $row->package ? $row->package->name : 'None';
                })
                ->addColumn('status_label', function ($row) {
                    if ($row->status === 'invoice_sent') {
                        return '<span class="badge bg-warning text-dark">Invoice Sent</span>';
                    } elseif ($row->status === 'paid') {
                        return '<span class="badge bg-success">Paid</span>';
                    }
                    return '<span class="badge bg-secondary">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('action', function ($row) {
                    return '<button class="btn btn-sm btn-primary view-payment" data-id="'.$row->id.'">Select</button>';
                })
                ->rawColumns(['status_label', 'action'])
                ->make(true);
        }
    }

    public function show($id)
    {
        $payment = Payment::with(['client', 'package'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'payment' => $payment
        ]);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'package_id' => 'nullable|exists:packages,id',
            'amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'paid_at' => 'nullable|date',
            'contract_signed_at' => 'nullable|date',
        ]);

        $fillable = ['package_id', 'amount', 'status', 'payment_method', 'paid_at', 'contract_signed_at'];
        foreach ($fillable as $field) {
            if ($request->has($field)) {
                $payment->$field = $request->$field;
            }
        }

        $payment->save();

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'updated payment',
            'subject_type' => Payment::class,
            'subject_id' => $payment->id,
            'description' => 'Payment updated for client ' . ($payment->client->full_name ?? 'Unknown') . ' to status ' . $payment->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully',
            'payment' => $payment
        ]);
    }
}
