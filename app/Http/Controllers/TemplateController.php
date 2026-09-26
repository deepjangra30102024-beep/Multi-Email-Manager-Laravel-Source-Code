<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = Auth::user()->emailTemplates()->latest()->get();
        return view('templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        Auth::user()->emailTemplates()->create($validated);

        return redirect()->route('templates.index')->with('success', 'Template created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }
        return view('templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmailTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
        ]);

        $template->update($validated);

        return redirect()->route('templates.index')->with('success', 'Template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplate $template)
    {
        if ($template->user_id !== Auth::id()) {
            abort(403);
        }

        $template->delete();

        return redirect()->route('templates.index')->with('success', 'Template deleted successfully.');
    }
}
