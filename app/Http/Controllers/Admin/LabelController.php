<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Label;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Label::all();
        return view('admin.labels.index', compact('labels'));
    }

    public function create()
    {
        return view('admin.labels.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Label::create($validatedData);

        return redirect()->route('admin.labels.index')->with('success', 'Label created successfully.');
    }

    public function edit(Label $label)
    {
        return view('admin.labels.edit', compact('label'));
    }

    public function update(Request $request, Label $label)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $label->update($validatedData);

        return redirect()->route('admin.labels.index')->with('success', 'Label updated successfully.');
    }

    public function destroy(Label $label)
    {
        $label->delete();
        return redirect()->route('admin.labels.index')->with('success', 'Label deleted successfully.');
    }
}
