<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\User;
use App\Game;
use App\Track;
use App\Score;
//use Analytics;
//use Carbon\Carbon;
//use Spatie\Analytics\Period;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // Resume
        $users_count = User::count();
        $games_count = Game::count();
        $tracks_count = Track::count();
        $scores_count = Score::count();

/*
        // Analytics
        if ($request->startDate) {
            $startDate = Carbon::parse($request->startDate);
            $endDate = Carbon::parse($request->endDate);
        } else {
            $startDate = Carbon::now()->subMonth();
            $endDate = Carbon::now();
        }

        $period = Period::create($startDate, $endDate);
        $maxResults = 10;

        $TotalVisitorsAndPageViews = Analytics::fetchTotalVisitorsAndPageViews($period);
        $MostVisitedPages = Analytics::fetchMostVisitedPages($period, $maxResults);
        $TopBrowsers = Analytics::fetchTopBrowsers($period, $maxResults);
        $TopReferrers = Analytics::fetchTopReferrers($period, $maxResults);
        $UserTypes = Analytics::fetchUserTypes($period, $maxResults);

        // Total
        $totalVisitors = 0;
        $totalPageViews = 0;
        foreach ($TotalVisitorsAndPageViews as $date) {
            $totalVisitors += $date['visitors'];
            $totalPageViews += $date['pageViews'];
        }
*/
        return view('admin.dashboard', [
            'users_count' => $users_count, 
            'games_count' => $games_count, 
            'tracks_count' => $tracks_count,         
            'scores_count' => $scores_count,
            /*
            'analytics' => [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'totalVisitors' => $totalVisitors,
                'totalPageViews' => $totalPageViews,
                'TotalVisitorsAndPageViews' => $TotalVisitorsAndPageViews,
                'MostVisitedPages' => $MostVisitedPages,
                'TopBrowsers' => $TopBrowsers,
                'TopReferrers' => $TopReferrers,
                'UserTypes' => $UserTypes
            ]
            */
        ]);
    }

}
