<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Label;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $labels = Label::all(['id', 'name']);
        return response()->json(['data' => $labels], 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:labels,name',
        ]);

        $label = Label::create($data);
        return response()->json(['data' => $label], 201);
    }

    public function update(Request $request, Label $label)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:labels,name,'. $label->id,
        ]);

        $label->update($data);
        return response()->json(['data' => $label], 200);
    }

    public function destroy(Label $label)
    {
        $label->delete();
        return response()->json(null, 204);
    }
}
