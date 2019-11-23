<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeezerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function releases(Request $request)
    {

       //  curl -X "GET" "https://api.spotify.com/v1/browse/new-releases?country=SE" -H "Accept: application/json" -H "Content-Type: application/json" -H "Authorization: Bearer BQAPWLX-L4fi2L76km7lzGUfU-zcZkNocmm5y8HU8ENKagWdLShth8MY4-7Nj-84kwfwY6mNLlPYKLQGL5Fc3SXGno2kCfKYVMUM4D2hw6kstInysRP_YZAX_kgq2ryKL_RcKkxzVQUPccQ0xaj4uYnSyv-WGT4"

        $token = 

        return response()->json($json);

    }

}