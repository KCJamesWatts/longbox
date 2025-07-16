<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublisherRequest;
use App\Models\Publisher;

class PublisherController extends Controller
{
    public function addView()
    {
        return view('publisher.add');
    }

    public function delete(string $id)
    {
        Publisher::findOrFail($id)->delete();
        return redirect()->route('publisher.list');
    }

    public function listView()
    {
        // Logic to retrieve and return a list of publishers
        $publishers = Publisher::all();

        return view('publisher.list', compact('publishers'));
    }

    public function post(PublisherRequest $request)
    {
        $data = $request->validated();
        Publisher::create($data);
        return redirect()->route('publisher.list');
    }

    public function put(PublisherRequest $request, string $id)
    {
        $data = $request->validated();
        Publisher::findOrFail($id)->update($data);
        return redirect()->route('publisher.list');
    }

    public function showView(string $id)
    {
        $publisher = Publisher::findOrFail($id);
        return view('publisher.show', compact('publisher'));
    }
}
