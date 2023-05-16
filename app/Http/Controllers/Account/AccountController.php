<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Round;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index()
    {
        return Inertia::render('Accounts/Index');
    }

    public function edit()
    {
        return Inertia::render('Accounts/Edit', [
            'user' => auth()->user(),
            'subscriptions' => auth()->user()->subscriptions()->get()->transform(fn($subscription) => [
                'id' => $subscription->id,
                'name' => $subscription->name,
                'status' => $subscription->stripe_status,
                'created_at' => $subscription->created_at->format('d/m/Y'),
                'ends_at' => $subscription->ends_at ? $subscription->ends_at->format('d/m/Y') : '-',
            ])
        ]);
    }

    public function statistics(Request $request)
    {
        $start_at = now()->subDays(300);
        $end_at = now();

        // dd(auth()->user()->totalScores()->with('room')->get());
        // dd(auth()->user()->scores()->whereBetween('created_at', [$start_at, $end_at])->groupBy('round_id')->with('round.room')->get());

        return Inertia::render('Accounts/Statistics', [
            'summary' => auth()->user()->totalScores()->with('room')->get(),
            'rounds' => auth()->user()
                ->scores()
                ->whereBetween('created_at', [$start_at, $end_at])
                ->selectRaw("SUM(score) as score, DATE_FORMAT(created_at, '%e/%m/%Y') date")
                ->groupBy('date')
                ->get()
        ]);
    }

}
