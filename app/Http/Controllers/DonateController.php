<?php

namespace App\Http\Controllers;

use App\UserDonation;
use Illuminate\Http\Request;

class DonateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('donate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function thankyou(Request $request)
    {
        if ($request->session()->has('status')) {
            return view('donate.thankyou');
        } else {
            return redirect('/');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email:rfc,dns',
            'amount' => 'required',
        ]);

        \Stripe\Stripe::setApiKey(env('STRIPE_KEY_SECRET'));

        try 
        { 
            //dd($request->amount);
            $customer = \Stripe\Customer::create(array(
                'name' => $request->name,
                'email' => $request->email,
                'source' => $request->source,
            )); 

            // `source` is obtained with Stripe.js; see https://stripe.com/docs/payments/accept-a-payment-charges#web-create-token
            $charge = \Stripe\Charge::create([
              'amount' => $request->amount,
              'currency' => 'eur',
              //'source' => $request->source,
              'customer' => $customer->id,
              'receipt_email' => $request->email,
              'description' => 'Don à l\'intention de blinest.com',
            ]);

            $userDonation = new UserDonation([
                'user_id' => auth()->user()->id,
                'amount' => $request->amount
            ]);

            $userDonation->save();

            $request->session()->flash('status', 'Le paiement a été validé!');

            return response()->json($charge);

        }

        catch (\Exception $ex) {
            flash($ex->getMessage())->error();
            return redirect('/membership');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
