<?php

namespace App\Http\Controllers;

use App\Models\AiTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiTemplateController extends Controller
{
    public function index()
    {
        $templates = auth()->user()->aiTemplates()->latest()->get();
        return response()->json($templates);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:email,voice,message',
            'provider' => 'required|string|in:openai,elevenlabs',
            'template_data' => 'required|array',
            'variables' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $template = auth()->user()->aiTemplates()->create($request->all());
        return response()->json($template, 201);
    }

    public function show(AiTemplate $template)
    {
        $this->authorize('view', $template);
        return response()->json($template);
    }

    public function update(Request $request, AiTemplate $template)
    {
        $this->authorize('update', $template);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|in:email,voice,message',
            'provider' => 'sometimes|required|string|in:openai,elevenlabs',
            'template_data' => 'sometimes|required|array',
            'variables' => 'sometimes|required|array',
            'is_active' => 'sometimes|required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $template->update($request->all());
        return response()->json($template);
    }

    public function destroy(AiTemplate $template)
    {
        $this->authorize('delete', $template);
        $template->delete();
        return response()->json(null, 204);
    }

    public function generate(Request $request, AiTemplate $template)
    {
        $this->authorize('view', $template);

        $validator = Validator::make($request->all(), [
            'data' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $content = $template->generateContent($request->data);
            return response()->json(['content' => $content]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 