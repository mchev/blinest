<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\User;
use App\Game;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if (!empty($request->sort_by)) {
            $order = $request->sort_by;
        } else {
            $order = "created_at";
        }

        if($request->search) {

            $search = $request->search;

            $users = User::orderBy($order, 'DESC')
                                ->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%')
                                ->withCount('scores')
                                ->paginate(20);

        } else {

            $search = '';
            $users = User::withCount('scores')->orderBy($order, 'desc')->paginate(20);

        }

        return view('admin.users.index', compact('users', 'search'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function table()
    {
        $users = User::withCount('scores')->orderBy($order, 'desc')->paginate(20);

        return response()->json($users);
    }


    public function search(Request $request)
    {

        if ($request->get('query')) {

            $users = User::where('name', 'like', '%' . $request->get('query') . '%')
                        ->orWhere('email', 'like', '%' . $request->get('query') . '%')
                        ->limit(5)
                        ->get();

            return response()->json($users);
        }

    }

    // 3 => Moderator Role

    public function attach(Request $request, User $user)
    {
        $user->roles()->attach(3, ['game_id' => $request->get('game_id')]);
    }

    public function detach(User $user, Game $game)
    {
        $user->roles()->wherePivot('game_id', $game->id)->detach(3);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect('/admin/users');
    }
}
