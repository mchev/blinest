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

    public function edit(Request $request)
    {
        return Inertia::render('Accounts/Edit', [
            'user' => [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
            ],
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

        return Inertia::render('Accounts/Statistics', [
            'summary' => auth()->user()->totalScores()->orderByDesc('score')->with('room')->get(),
            'rounds' => auth()->user()
                ->scores()
                ->whereBetween('created_at', [$start_at, $end_at])
                ->selectRaw("SUM(score) as score, DATE_FORMAT(created_at, '%e/%m/%Y') date")
                ->groupBy('date')
                ->get()
        ]);
    }

    public function likes(Request $request)
    {
        return Inertia::render('Accounts/Likes', [
            'likes' => $request->user()->likes()->paginate(5, ['*'], 'likes'),
        ]);
    }
}
