<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'filters' => Request::all('search'),
            'users' => User::query()
                ->filter(Request::only('search'))
                ->select('id', 'name')
                ->orderBy('name')
                ->paginate(20)
                ->withQueryString(),
        ]);
    }
}
