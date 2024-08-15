<?php

namespace App\Http\Controllers;

use App\Http\Requests\SizeCategoryStoreRequest;
use App\Http\Requests\SizeCategoryUpdateRequest;
use App\Models\SizeCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SizeCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $sizeCategories = SizeCategory::all();

        return view('sizeCategory.index', compact('sizeCategories'));
    }

    public function create(Request $request): View
    {
        return view('sizeCategory.create');
    }

    public function store(SizeCategoryStoreRequest $request): RedirectResponse
    {
        $sizeCategory = SizeCategory::create($request->validated());

        $request->session()->flash('sizeCategory.id', $sizeCategory->id);

        return redirect()->route('sizeCategories.index');
    }

    public function show(Request $request, SizeCategory $sizeCategory): View
    {
        return view('sizeCategory.show', compact('sizeCategory'));
    }

    public function edit(Request $request, SizeCategory $sizeCategory): View
    {
        return view('sizeCategory.edit', compact('sizeCategory'));
    }

    public function update(SizeCategoryUpdateRequest $request, SizeCategory $sizeCategory): RedirectResponse
    {
        $sizeCategory->update($request->validated());

        $request->session()->flash('sizeCategory.id', $sizeCategory->id);

        return redirect()->route('sizeCategories.index');
    }

    public function destroy(Request $request, SizeCategory $sizeCategory): RedirectResponse
    {
        $sizeCategory->delete();

        return redirect()->route('sizeCategories.index');
    }
}
