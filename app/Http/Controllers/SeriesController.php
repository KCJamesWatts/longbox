<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeriesRequest;
use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function addView()
    {
        return view('series.add');
    }

    public function delete(string $id)
    {
        Series::findOrFail($id)->delete();
        return redirect()->route('series.list');
    }

    public function listView()
    {
        // Logic to retrieve and return a list of publishers
        $series = Series::all();

        return view('series.list', compact('series'));
    }

    public function post(SeriesRequest $request)
    {
        $data = $request->validated();
        Series::create($data);
        return redirect()->route('series.list');
    }

    public function put(SeriesRequest $request, string $id)
    {
        $data = $request->validated();
        Series::findOrFail($id)->update($data);
        return redirect()->route('series.list');
    }

    public function showView(string $id)
    {
        $series = Series::findOrFail($id);
        return view('series.show', compact('series'));
    }
}
