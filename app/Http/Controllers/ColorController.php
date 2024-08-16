<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColorStoreRequest;
use App\Http\Requests\ColorUpdateRequest;
use App\Models\Color;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ColorController extends Controller
{
    public function index(Request $request): View
    {
        $colors = Color::all();

        return view('color.index', compact('colors'));
    }

    public function create(Request $request): View
    {
        return view('color.create');
    }

    public function store(ColorStoreRequest $request): RedirectResponse
    {
        $color = Color::create($request->validated());

        $request->session()->flash('color.id', $color->id);

        return redirect()->route('colors.index');
    }

    public function show(Request $request, Color $color): View
    {
        return view('color.show', compact('color'));
    }

    public function edit(Request $request, Color $color): View
    {
        return view('color.edit', compact('color'));
    }

    public function update(ColorUpdateRequest $request, Color $color): RedirectResponse
    {
        $color->update($request->validated());

        $request->session()->flash('color.id', $color->id);

        return redirect()->route('colors.index');
    }

    public function destroy(Request $request, Color $color): RedirectResponse
    {
        $color->delete();

        return redirect()->route('colors.index');
    }
}
