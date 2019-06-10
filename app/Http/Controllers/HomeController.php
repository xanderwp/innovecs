<?php

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Model\ApiTransactions;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function reportWeek()
    {
        $apiTransactions = new ApiTransactions();
        $reports = $apiTransactions->getWeekReport();
        return view('report-week', [
            'days' => $apiTransactions->_getDays(),
            'reports' => json_decode(json_encode($reports), true)
        ]);
    }

    public function reportMonth()
    {
        $apiTransactions = new ApiTransactions();
        $reports = $apiTransactions->getMonthReport();
        return view('report-month',[
            'reports' => $reports
        ]);
    }

    public function addTransactions(\App\Http\Requests\FormAddTransactionRequest $request)
    {

        $apiTransactions = new ApiTransactions();
        $result = $apiTransactions->addTransaction($request->email, $request->amount);
        if($result) {
            if ($result->status == 'approved') {
                $success = __('form-page.approved-transaction', ['id' => $result->id]);
            } else {
                $success = $result->message;
            }

            return response()->json([
                'success' => $success
            ]);
        } else {
            return response()->json([
                'errors' => [__('form-page.failed')]
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
