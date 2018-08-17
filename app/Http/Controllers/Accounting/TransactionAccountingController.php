<?php

namespace App\Http\Controllers\Accounting;

use App\Expense;
use App\Traits\ApiResponser;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionAccountingController extends Controller
{
    //
    use ApiResponser;

    public function index(Request $request)
    {
        $transactions = Transaction::with(['products', 'customer']);
        $expenses = new Expense();

        if ($request->select['abbr'] === 'TDT') {
            $transactions = $transactions->where('created_at', '>', Carbon::now()->startOfDay())
                ->where('created_at', '<', Carbon::now()->endOfDay());

            $expenses = $expenses->where('created_at', '>', Carbon::now()->startOfDay())
                ->where('created_at', '<', Carbon::now()->endOfDay());
        }

        if ($request->select['abbr'] === 'YDT') {
            $transactions = $transactions->where('created_at', '>', Carbon::yesterday());
            $expenses = $expenses->where('created_at', '>', Carbon::yesterday());
        }

        if ($request->select['abbr'] === 'TWT') {
            $transactions = $transactions->where('created_at', '>', Carbon::now()->startOfWeek());
            $expenses = $expenses->where('created_at', '>', Carbon::now()->startOfWeek());
        }
        if ($request->select['abbr'] === 'LWT') {
            $currentDate = Carbon::now();
            $agoDate = $currentDate->subDays($currentDate->dayOfWeek)->subWeek();
            $currentDate = Carbon::now();
            $endDate = $currentDate->subDays($currentDate->dayOfWeek);
            $transactions = $transactions->whereBetween('created_at', [$agoDate, $endDate]);

            $expenses = $expenses->whereBetween('created_at', [$agoDate, $endDate]);
        }

        if ($request->select['abbr'] === 'TMT') {
            $currentDate = Carbon::now();
            $agoDate = $currentDate->startOfMonth();
            $currentDate = Carbon::now();
            $endDate = $currentDate->endOfMonth();
            $transactions = $transactions->whereBetween('created_at', [$agoDate, $endDate]);
            $expenses = $expenses->whereBetween('created_at', [$agoDate, $endDate]);
        }

        if ($request->select['abbr'] === 'LMT') {
            $transactions = $transactions->whereMonth('created_at', Carbon::now()->subMonth()->month);
            $expenses = $expenses->whereMonth('created_at', Carbon::now()->subMonth()->month);
        }

        if ($request->select['abbr'] === 'TYT') {
            $currentDate = Carbon::now();
            $agoDate = $currentDate->startOfYear();
            $currentDate = Carbon::now();
            $endDate = $currentDate->endOfYear();
            $transactions = $transactions->whereBetween('created_at', [$agoDate, $endDate]);
            $expenses = $expenses->whereBetween('created_at', [$agoDate, $endDate]);
        }

        if ($request->customdate) {
            $begainDate = Carbon::parse($request->startdate)->startOfDay();
            $endDate = Carbon::parse($request->startdate)->endOfDay();
            $transactions = $transactions->whereBetween('created_at', [$begainDate, $endDate]);
            $expenses = $expenses->whereBetween('created_at', [$begainDate, $endDate]);
        }

        if ($request->customdate && $request->customrangerate) {
            $agoDate = Carbon::parse($request->startdate)->startOfDay();
            $endDate = Carbon::parse($request->enddate)->endOfDay();
            $transactions = $transactions->whereBetween('created_at', [$agoDate, $endDate]);
            $expenses = $expenses->whereBetween('created_at', [$agoDate, $endDate]);
        }

        $transactions = $transactions->orderBy('created_at', 'desc')->get();
        $expenses = $expenses->orderBy('created_at', 'desc')->get();

        $total = $transactions->sum(function($transaction){
        	return$transaction->total + $transaction->service_charge;
		});
        $paymentDue = $transactions->sum('payment_due');
        $discount = $transactions->sum('discount_amount');
        $paid = $transactions->sum('paid');
        $total_product = $transactions->pluck('products')->collapse()->count();

        $chartData = [];

        $transactions->each(function ($transaction) use (&$chartData) {
            $data = [];
            $data['total'] = $transaction->total;
            $data['date'] = Carbon::parse($transaction->created_at)->toFormattedDateString();
            $productName = '';
            if ($transaction->products->count()) {
                $transaction->products->each(function ($product) use (&$productName) {
                    $productName .= $product->name . ', ';
                });
            }
            $data['products'] = $productName . '(' . $transaction->products->count() . ')';
            $data['color'] = $this->rand_color();
            $chartData[] = $data;
        });

        $totalProduct = $transactions->pluck('products')->collapse();

        $salePrice = $totalProduct->sum(function ($product) {
            return $product->pivot->sale_quantity * $product->sale_price;
        });

        $purchasePrice = $totalProduct->sum(function ($product) {
            return $product->pivot->sale_quantity * $product->purchase_price;
        });

        $totalProfit = $salePrice - $purchasePrice;
        $totalExpenses = $expenses->sum('amount');
        $profitAfter = $totalProfit - $totalExpenses - $discount;
        $totalProfitAfterDue = $totalProfit - $paymentDue;


        $data = [
            'total' => number_format((float)$total, 2, '.', ''),
            'payment_due' => number_format((float)$paymentDue, 2, '.', ''),
            'discount' => number_format((float)$discount, 2, '.', ''),
            'total_product' => $total_product,
            'paid' => number_format((float)$paid, 2, '.', ''),
            'transactions' => $transactions,
            'chart_data' => $chartData,
            'total_profit' => number_format((float)$totalProfit, 2, '.', ''),
            'total_expense' => number_format((float)$totalExpenses, 2, '.', ''),
            'profit_after' => number_format((float)$profitAfter, 2, '.', ''),
            'total_profit_after_due' => number_format($totalProfitAfterDue, 2, '.', '')
        ];

        return $this->successResponse($data, 200);
    }


}
