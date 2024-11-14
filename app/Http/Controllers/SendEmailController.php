<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SendEmail;
use App\Jobs\SendMailJob;
use App\Models\User;

class SendEmailController extends Controller
{
    public function index() {

        return view('emails.kirim-email');
    }

    public function store(Request $request) {
        $data = $request->all();
        dispatch(new SendMailJob($data));
        return redirect()->route('kirim-email')->with('success', 'Email berhasil dikirim!');
    }

    public function send($email, $name) {
        $user = User::where('email', $email)->first();
        $content = [
            'name' => $name,
            'subject' => "Tugas PPW2 Pertemuan 12",
            'body' => $email . ' ' . $user->created_at

        ];

        Mail::to('ibanezahista28@gmail.com')->send(new SendEmail($content));
        return redirect()->route('dashboard')->with('success', 'Email Berhasil Dikirim');
    }
}
