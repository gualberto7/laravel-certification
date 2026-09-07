<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('tags.index', [
            'tags' => Tag::withCount('tasks')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request): RedirectResponse
    {
        Tag::create($request->validated());

        return redirect()
            ->route('tags.index')
            ->with('status', 'Tag created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag): View
    {
        return view('tags.edit', [
            'tag' => $tag
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $tag->update($request->validated());

        return redirect()
            ->route('tags.index')
            ->with('status', 'Tag updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()
            ->route('tags.index')
            ->with('status', 'Tag deleted.');
    }
}
