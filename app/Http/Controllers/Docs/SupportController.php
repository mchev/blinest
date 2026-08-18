<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use App\Seo\DocsHead;
use App\Services\Donations\DonationGoalService;
use Inertia\Inertia;
use Inertia\Response;

class SupportController extends Controller
{
    public function __construct(
        private DocsHead $docsHead,
        private DonationGoalService $donationGoal,
    ) {}

    public function index(): Response
    {
        $this->docsHead->applySupport();

        return Inertia::render('docs/support/Index', [
            'history' => $this->donationGoal->monthlyHistory(12),
            'recent_donations' => $this->donationGoal->recentDonations(30),
        ]);
    }
}
