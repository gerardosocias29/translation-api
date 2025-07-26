<?php

namespace App\Http\Controllers\Api;

use App\Models\Translation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $query = Translation::query();

        if ($request->filled('tag')) {
            $query->where('tag', $request->tag);
        }

        if ($request->filled('key')) {
            $query->where('key', 'like', '%' . $request->key . '%');
        }

        if ($request->filled('value')) {
            $query->where('value', 'like', '%' . $request->value . '%');
        }

        return response()->json($query->paginate(50));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string',
            'value' => 'required|string',
            'locale' => 'required|string',
            'tag' => 'nullable|string',
        ]);

        $translation = Translation::updateOrCreate(
            [
                'key' => $validated['key'],
                'locale' => $validated['locale'],
                'tag' => $validated['tag'],
            ],
            ['value' => $validated['value']]
        );

        return response()->json($translation, 201);
    }

    public function update(Request $request, $id)
    {
        $translation = Translation::findOrFail($id);

        $validated = $request->validate([
            'value' => 'required|string',
        ]);

        $translation->update($validated);

        return response()->json($translation);
    }

    public function destroy($id)
    {
        $translation = Translation::findOrFail($id);
        $translation->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
