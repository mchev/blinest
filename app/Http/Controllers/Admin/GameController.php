<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Game;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->search) {

            $search = $request->search;

            $games = Game::orderBy('public', 'DESC')
                                ->orderBy('hit', 'DESC')
                                ->where('title', 'like', '%' . $search . '%')
                                ->orWhere('description', 'like', '%' . $search . '%')
                                ->orWhereHas('user', function($query) use ($search) {
                                    $query->where('name', 'like', '%' . $search . '%');
                                })
                                ->paginate(20);

        } else {

            $search = '';

            $games = Game::orderBy('public', 'DESC')->orderBy('hit', 'DESC')->paginate(20);

        }

        return view('admin.games.index', compact('games', 'search'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        $request->validate([
            'title' => 'required|max:255|unique:games,title,' . $game->id,
            'description' => 'required|max:255',
        ]);

        $filename = '';

        if ($request->thumbnail) {

            $img = Image::make($request->thumbnail)
                ->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('webp');

            $filename = uniqid() . '.webp';

            Storage::disk('public')->put('games/'.$filename, $img);

            $game->thumbnail = $filename;

        }

        // Generate new slug
        $slug = Str::slug($request->get('title'), '-');

        $game->title = $request->get('title');
        $game->description = $request->get('description');
        $game->public = ($request->get('public') == true) ? 1 : 0;
        $game->slug = $slug;

        $game->save();

        return view('admin.games.edit', compact('game'))->with('success', 'Le blind test a été modifié');
    }

    /**
     * Delete all games who doesnt have tracks and not updated since 6 months
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function clean()
    {

        $games = Game::doesntHave('tracks')
                    ->whereDate('updated_at', '<', Carbon::now()->subMonths(12)->startOfMonth())
                    ->where('public', 0)
                    ->delete();

        return redirect('/admin');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        $game->delete();

        return redirect('/admin/games');
    }
}
