<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\DataBase\Transaction;

class TransactionsController extends Controller
{

    public function add(\App\Http\Requests\AddTransactionRequest $request)
    {

        $transaction = Transaction::create([
            'email' => $request->email,
            'amount' => $request->amount,
            'user_id' => Auth::user()->id,
        ]);

        if($transaction){
            if($transaction->status == 'rejected'){
                return response()->json([
                    'status' => $transaction->status,
                    'message' => __('api.transactions_add.rejected'),
                ], JsonResponse::HTTP_OK);
            } else {
                return response()->json([
                    'status' => $transaction->status,
                    'id' => $transaction->id,
                    'message' => __('api.transactions_add.approved'),
                ], JsonResponse::HTTP_OK);
            }

        } else {
            return response()->json([
                'message' => __('api.transactions_add.failed'),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    public function getReportMonth()
    {
        return response()->json([
            'reports' => Transaction::getMonthSum(),
        ], JsonResponse::HTTP_OK);
    }

    public function getReportWeek()
    {
        return response()->json([
            'reports' => Transaction::getWeekSum(),
        ], JsonResponse::HTTP_OK);
    }

}
