<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class ContactController extends Controller
{

	public function index() {
		return view('contact');
	}

	public function legal() {
		return view('legal');
	}
	
	public function confidentialite() {
		return view('confidentialite');
	}

	public function send(Request $request)
	{

		$data = array(
			'from' => $request->from, 
			'body' => $request->message,
			'to' => 'hello@blinest.com',
			'subject' => 'Nouveau message depuis le site'
		);

		Mail::send('emails.contact', $data, function ($message) use ($data) {
			$message->from($data['from']);
			$message->to($data['to']);
			$message->subject($data['subject']); 
		});

		return redirect()->back()->with('message','Votre message a correctement été envoyé.');

	}
    
}
