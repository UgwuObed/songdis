<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use lluminate\Support\Facades\Mail;

class EmailTemplateController extends Controller
{
    public function index()
    {
        return response()->json(EmailTemplate::all());
    }

    public function show(EmailTemplate $template)
    {
        return response()->json($template);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:email_templates',
            'subject' => 'required|string',
            'content' => 'required|string',
            'variables' => 'nullable|array'
        ]);

        $template = EmailTemplate::create($validated);
        return response()->json($template, 201);
    }

    public function update(Request $request, EmailTemplate $template)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'content' => 'required|string',
            'variables' => 'nullable|array'
        ]);

        $template->update($validated);
        return response()->json($template);
    }

    public function destroy(EmailTemplate $template)
    {
        $template->delete();
        return response()->json(null, 204);
    }
}