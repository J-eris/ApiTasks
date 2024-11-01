<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{

    public function index(): JsonResponse
    {
        $paymentMethods = PaymentMethod::all();
        return response()->json(['data' => $paymentMethods], 200);
    }

    public function store(StorePaymentMethodRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('proof_image')) {
            $data['proof_image'] = $request->file('proof_image')->store('proof_images', 'public');
        }

        $paymentMethod = PaymentMethod::create($data);

        return response()->json(
            [
                'message' => 'Payment method created successfully',
                'data' => $paymentMethod
            ],
            201
        );
    }

    public function show(PaymentMethod $paymentMethod): JsonResponse
    {
        $payment = PaymentMethod::find($paymentMethod);

        if ($payment) {
            return response()->json(['data' => $payment], 200);
        } else {
            return response()->json(['message' => 'Payment method not found'], 404);
        }
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('proof_image')) {
            // Delete old image if exists
            if ($paymentMethod->proof_image) {
                Storage::disk('public')->delete($paymentMethod->proof_image);
            }
            $data['proof_image'] = $request->file('proof_image')->store('proof_images', 'public');
        }

        $paymentMethod->update($data);

        return response()->json(['data' => $paymentMethod], 200);
    }

    public function destroy(PaymentMethod $paymentMethod): JsonResponse
    {
        if ($paymentMethod->proof_image) {
            Storage::disk('public')->delete($paymentMethod->proof_image);
        }

        $paymentMethod->delete();

        return response()->json(['message' => 'Payment method deleted successfully'], 200);
    }
}
