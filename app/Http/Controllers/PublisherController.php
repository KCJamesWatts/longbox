<?php

namespace App\Http\Controllers;

use App\Models\Publisher;

class PublisherController extends Controller
{
    public function list()
    {
        // Logic to retrieve and return a list of publishers
        $publishers = Publisher::all();

        return view('publishers.list', compact('publishers'));
    }
}
