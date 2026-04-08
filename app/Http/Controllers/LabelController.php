<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;
use App\Models\Label;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LabelController extends Controller
{
    public function index(): View
    {
        $labels = Label::query()->orderBy('id')->withCount('tasks')->get();

        return view('labels.index', compact('labels'));
    }

    public function create(): View
    {
        $label = new Label();

        return view('labels.create', compact('label'));
    }

    public function store(StoreLabelRequest $request): RedirectResponse
    {
        Label::query()->create($request->validated());

        return Redirect::route('labels.index')->with('status', __('labels.flash.created'));
    }

    public function edit(Label $label): View
    {
        return view('labels.edit', compact('label'));
    }

    public function update(UpdateLabelRequest $request, Label $label): RedirectResponse
    {
        $label->update($request->validated());

        return Redirect::route('labels.index')->with('status', __('labels.flash.updated'));
    }

    public function destroy(Label $label): RedirectResponse
    {
        if ($label->tasks()->exists()) {
            return Redirect::route('labels.index')->with('error', __('labels.flash.delete_forbidden'));
        }

        $label->delete();

        return Redirect::route('labels.index')->with('status', __('labels.flash.deleted'));
    }
}
