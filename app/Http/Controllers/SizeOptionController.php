<?php

namespace App\Http\Controllers;

use App\Http\Requests\SizeOptionStoreRequest;
use App\Http\Requests\SizeOptionUpdateRequest;
use App\Models\SizeOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SizeOptionController extends Controller
{
    public function index(Request $request): View
    {
        $sizeOptions = SizeOption::all();

        return view('sizeOption.index', compact('sizeOptions'));
    }

    public function create(Request $request): View
    {
        return view('sizeOption.create');
    }

    public function store(SizeOptionStoreRequest $request): RedirectResponse
    {
        $sizeOption = SizeOption::create($request->validated());

        $request->session()->flash('sizeOption.id', $sizeOption->id);

        return redirect()->route('sizeOptions.index');
    }

    public function show(Request $request, SizeOption $sizeOption): View
    {
        return view('sizeOption.show', compact('sizeOption'));
    }

    public function edit(Request $request, SizeOption $sizeOption): View
    {
        return view('sizeOption.edit', compact('sizeOption'));
    }

    public function update(SizeOptionUpdateRequest $request, SizeOption $sizeOption): RedirectResponse
    {
        $sizeOption->update($request->validated());

        $request->session()->flash('sizeOption.id', $sizeOption->id);

        return redirect()->route('sizeOptions.index');
    }

    public function destroy(Request $request, SizeOption $sizeOption): RedirectResponse
    {
        $sizeOption->delete();

        return redirect()->route('sizeOptions.index');
    }
}
