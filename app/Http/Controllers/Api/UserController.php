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
            'users' => User::orderBy('name')
                ->select('id', 'name')
                ->filter(Request::only('search'))
                ->paginate(10)
                ->withQueryString(),
        ]);
    }
}
