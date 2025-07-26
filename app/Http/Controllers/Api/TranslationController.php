<?php

namespace App\Http\Controllers\Api;

use App\Models\Translation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @OA\Tag(
 *     name="Translations",
 *     description="API endpoints for managing translations"
 * )
 */
class TranslationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/translations",
     *     summary="List translations",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="tag", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="key", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="value", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="locale", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response="200", description="Successful operation")
     * )
     */
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

        if ($request->filled('locale')) {
            $query->where('locale', $request->locale);
        }

        if ($request->filled('page')) {
            $query->paginate(50, ['*'], 'page', $request->page);
        } else {
            $query->paginate(50);
        }
        
        return response()->json($query->get());
    }

    /**
     * @OA\Post(
     *     path="/api/translations",
     *     summary="Create a translation",
     *     tags={"Translations"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"key", "value", "locale"},
     *             @OA\Property(property="key", type="string"),
     *             @OA\Property(property="value", type="string"),
     *             @OA\Property(property="locale", type="string"),
     *             @OA\Property(property="tag", type="string")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Created")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/translations/{id}",
     *     summary="Update a translation value",
     *     tags={"Translations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"value"},
     *             @OA\Property(property="value", type="string")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Updated")
     * )
     */
    public function update(Request $request, $id)
    {
        $translation = Translation::findOrFail($id);

        $validated = $request->validate([
            'value' => 'required|string',
        ]);

        $translation->update($validated);

        return response()->json($translation);
    }

    /**
     * @OA\Delete(
     *     path="/api/translations/{id}",
     *     summary="Delete a translation",
     *     tags={"Translations"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response="200", description="Deleted"),
     *     @OA\Response(response="404", description="Translation not found")
     * )
     */
    public function destroy($id)
    {
        $translation = Translation::findOrFail($id);
        $translation->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
