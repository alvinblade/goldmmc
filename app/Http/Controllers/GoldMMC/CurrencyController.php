<?php

namespace App\Http\Controllers\GoldMMC;

use App\Http\Controllers\Controller;
use App\Models\Currency;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::query()->paginate(10);
        return view('admin.currencies.index', compact('currencies'));
    }
}
