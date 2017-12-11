<?php

namespace App\Http\Controllers;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidAccountException;
use App\Queries\OptimisticLockingTransaction;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Add Depostit
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function deposit()
    {
        request()->validate(['deposit' => 'required|numeric']);

        try {
            $data = [
                'country_id' => 1,
                'amount' => request()->get('deposit'),
                'type' => request()->get('type')
            ];
            (new OptimisticLockingTransaction(auth()->id(), $data))->update();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['deposit' => $e->getMessage()]);
        }

        return redirect()
            ->back()
            ->with('success', 'You successfully insert deposit');
    }

    public function withdraw()
    {
        request()->validate(['withdraw' => 'required|numeric']);

        try {
            $data = [
                'country_id' => 1,
                'amount' => request()->get('withdraw'),
                'type' => request()->get('type')
            ];
            (new OptimisticLockingTransaction(auth()->id(), $data))->update();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['withdraw' => $e->getMessage()]);
        }

        return redirect()
            ->back()
            ->with('success', 'You successfully withdraw money');
    }
}
