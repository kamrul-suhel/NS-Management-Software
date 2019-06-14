<?php

namespace App\Http\Controllers\Accounting;

use App\Bkash;
use App\CompanyTransaction;
use App\Expense;
use App\Product;
use App\SaleReturn;
use App\Traits\ApiResponser;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TransactionAccountingController extends Controller
{
    //
    use ApiResponser;

    public function index(Request $request)
    {
        $transactions = Transaction::with(['products', 'customer']);
        $expenses = new Expense();
        $companyTransaction = new CompanyTransaction();
        $salesReturn = SaleReturn::select('total_sale_price', 'total_purchase_price');
        $bkash = Bkash::where('status', 0);

        // Transaction exclude pending
        $transactions = $transactions->where('payment_status', '!=', '4');

        // Only admin approved will count
        $transactions = $transactions->where('status', 1);

        if ($request->select['abbr'] === 'TDT') {
            $transactions = $transactions->where('created_at', '>', Carbon::now()->startOfDay())
                ->where('created_at', '<', Carbon::now()->endOfDay());

            $expenses = $expenses->where('created_at', '>', Carbon::now()->startOfDay())
                ->where('created_at', '<', Carbon::now()->endOfDay());

            $companyTransaction = $companyTransaction->where('created_at', '>', Carbon::now()->startOfDay())
                ->where('created_at', '<', Carbon::now()->endOfDay());

            $salesReturn = $salesReturn->where('created_at', '>', Carbon::now()->startOfDay())
                ->where('created_at', '<', Carbon::now()->endOfDay());

            $bkash = $bkash->where('created_at', '>', Carbon::now()->startOfDay())
                ->where('created_at', '<', Carbon::now()->endOfDay());
        }

        if ($request->select['abbr'] === 'YDT') {
            $transactions = $transactions->where('created_at', '>', Carbon::yesterday());
            $expenses = $expenses->where('created_at', '>', Carbon::yesterday());

            $companyTransaction = $companyTransaction->where('created_at', '>', Carbon::yesterday());

            $salesReturn = $salesReturn->where('created_at', '>', Carbon::yesterday());

            $bkash = $bkash->where('created_at', '>', Carbon::yesterday());
        }

        if ($request->select['abbr'] === 'TWT') {
            $transactions = $transactions->where('created_at', '>', Carbon::now()->startOfWeek());
            $expenses = $expenses->where('created_at', '>', Carbon::now()->startOfWeek());
            $companyTransaction = $companyTransaction->where('created_at', '>', Carbon::now()->startOfWeek());

            $salesReturn = $salesReturn->where('created_at', '>', Carbon::now()->startOfWeek());
            $bkash = $bkash->where('created_at', '>', Carbon::now()->startOfWeek());
        }
        if ($request->select['abbr'] === 'LWT') {
            $currentDate = Carbon::now();
            $agoDate = $currentDate->subDays($currentDate->dayOfWeek)->subWeek();
            $currentDate = Carbon::now();
            $endDate = $currentDate->subDays($currentDate->dayOfWeek);
            $transactions = $transactions->whereBetween('created_at', [$agoDate, $endDate]);

            $expenses = $expenses->whereBetween('created_at', [$agoDate, $endDate]);

            $companyTransaction = $companyTransaction->whereBetween('created_at', [$agoDate, $endDate]);

            $salesReturn = $salesReturn->whereBetween('created_at', [$agoDate, $endDate]);

            $bkash = $bkash->whereBetween('created_at', [$agoDate, $endDate]);
        }

        if ($request->select['abbr'] === 'TMT') {
            $currentDate = Carbon::now();
            $agoDate = $currentDate->startOfMonth();
            $currentDate = Carbon::now();
            $endDate = $currentDate->endOfMonth();
            $transactions = $transactions->whereBetween('created_at', [$agoDate, $endDate]);
            $expenses = $expenses->whereBetween('created_at', [$agoDate, $endDate]);

            $companyTransaction = $companyTransaction->whereBetween('created_at', [$agoDate, $endDate]);

            $salesReturn = $salesReturn->whereBetween('created_at', [$agoDate, $endDate]);
            $bkash = $bkash->whereBetween('created_at', [$agoDate, $endDate]);
        }

        if ($request->select['abbr'] === 'LMT') {
            $transactions = $transactions->whereMonth('created_at', Carbon::now()->subMonth()->month);
            $expenses = $expenses->whereMonth('created_at', Carbon::now()->subMonth()->month);

            $companyTransaction = $companyTransaction->whereMonth('created_at', Carbon::now()->subMonth()->month);

            $salesReturn = $salesReturn->whereMonth('created_at', Carbon::now()->subMonth()->month);
            $bkash = $bkash->whereMonth('created_at', Carbon::now()->subMonth()->month);
        }

        if ($request->select['abbr'] === 'TYT') {
            $currentDate = Carbon::now();
            $agoDate = $currentDate->startOfYear();
            $currentDate = Carbon::now();
            $endDate = $currentDate->endOfYear();
            $transactions = $transactions->whereBetween('created_at', [$agoDate, $endDate]);
            $expenses = $expenses->whereBetween('created_at', [$agoDate, $endDate]);

            $companyTransaction = $companyTransaction->whereBetween('created_at', [$agoDate, $endDate]);
            $salesReturn = $salesReturn->whereBetween('created_at', [$agoDate, $endDate]);
            $bkash = $bkash->whereBetween('created_at', [$agoDate, $endDate]);
        }

        if ($request->customdate) {
            $begainDate = Carbon::parse($request->startdate)->startOfDay();
            $endDate = Carbon::parse($request->startdate)->endOfDay();
            $transactions = $transactions->whereBetween('created_at', [$begainDate, $endDate]);
            $expenses = $expenses->whereBetween('created_at', [$begainDate, $endDate]);

            $companyTransaction = $companyTransaction->whereBetween('created_at', [$begainDate, $endDate]);
            $salesReturn = $salesReturn->whereBetween('created_at', [$begainDate, $endDate]);
            $bkash = $bkash->whereBetween('created_at', [$begainDate, $endDate]);
        }

        if ($request->customdate && $request->customrangerate) {
            $agoDate = Carbon::parse($request->startdate)->startOfDay();
            $endDate = Carbon::parse($request->enddate)->endOfDay();
            $transactions = $transactions->whereBetween('created_at', [$agoDate, $endDate]);
            $expenses = $expenses->whereBetween('created_at', [$agoDate, $endDate]);

            $companyTransaction = $companyTransaction->whereBetween('created_at', [$agoDate, $endDate]);
            $salesReturn = $salesReturn->whereBetween('created_at', [$agoDate, $endDate]);
            $bkash = $bkash->whereBetween('created_at', [$agoDate, $endDate]);
        }

        $totalBkash = $bkash->sum('amount');

        // get store specifice transaction.
        $request->has('store_id') ? $transactions->where('store_id', $request->store_id) : '';

        // get expense specified transaction
        $request->has('store_id') ? $expenses->where('store_id', $request->store_id) : '';

        $request->has('store_id') ? $companyTransaction->where('store_id', $request->store_id) : '';

        $request->has('store_id') ? $salesReturn->where('store_id', $request->store_id) : '';

        $transactions = $transactions->orderBy('created_at', 'desc')
            ->get();
        $totalTransaction = $transactions->count();
        $expenses = $expenses->orderBy('created_at', 'desc')->get();

        $total = $transactions->sum(function($transaction){
        	return $transaction->total + $transaction->service_charge;
		});

        $totalSalePrice = $salesReturn->sum('total_sale_price');
        $totalPurchasePrice = $salesReturn->sum('total_purchase_price');

        $total = $total - $totalPurchasePrice;

        $totalServices = $transactions->sum('service_charge');
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
            return $product->pivot->sale_quantity  * $product->purchase_price;
        });

        // get company debit
        $companyDebit = $companyTransaction->sum('debit');

        // Get company due.
        $companyDue = $companyTransaction->sum('balance');

        $company = CompanyTransaction::select('debit', 'credit')
            ->get();

        $companyTotalDebit = $company->sum('debit');
        $companyTotalDue = $company->sum('credit');


        $totalProfit = $salePrice - $purchasePrice + $totalServices;
        $totalExpenses = $expenses->sum('amount');

        $profitAfter = $totalProfit - $totalExpenses - $discount;
        $totalProfitAfterDue = $totalProfit - $paymentDue;
        $cash = $total - $paymentDue - $totalExpenses - $companyDebit - $totalBkash;

        // Total expanse
        $totalExpanse = Expense::select('amount')->get()->sum('amount');

        // Get all transitions
        $totalTransitions = DB::table('transactions')
            ->select(DB::raw('sum(total - payment_due) as total_transaction'))
            ->whereNull('deleted_at')
            ->first();

        // Current balance
        $currentBalance = $totalTransitions->total_transaction - $totalExpanse - $companyTotalDebit;

        $products = Product::where('store_id', $request->store_id)
            ->get();

        $totalStock = $products->sum(function($product){
            return $product->quantity * $product->purchase_price;
        });

        $data = [
            'total_stock' => $totalStock,
            'total' => number_format((float)$total, 2, '.', ''),
            'total_transaction' => $totalTransaction,
            'payment_due' => number_format((float)$paymentDue, 2, '.', ''),
            'discount' => number_format((float)$discount, 2, '.', ''),
            'total_product' => $total_product,
            'paid' => number_format((float)$paid, 2, '.', ''),
            'transactions' => $transactions,
            'chart_data' => $chartData,
            'company_debit' => number_format($companyDebit, 2),
            'company_due' => number_format($companyDue, 2),
            'company_total_debit' => number_format($companyTotalDebit, 2),
            'company_total_due' => number_format($companyTotalDue - $companyTotalDebit, 2),
            'total_profit' => number_format((float)$totalProfit, 2, '.', ''),
            'total_expense' => number_format((float)$totalExpenses, 2, '.', ''),
            'profit_after' => number_format((float)$profitAfter, 2, '.', ''),
            'total_profit_after_due' => number_format($totalProfitAfterDue, 2, '.', ''),
            'cash' => number_format($cash, 2,'.', ''),
            'bkash' => number_format($totalBkash, 2, '.',','),
            'current_balance' => number_format($currentBalance, 2,'.', '')
        ];

        return $this->successResponse($data, 200);
    }


}
