<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string',
        ]);

        ContactUs::create($request->all());

    return redirect()->back()->with('success', 'Your message has been submitted successfully. We will get back to you soon.');
    }
}
