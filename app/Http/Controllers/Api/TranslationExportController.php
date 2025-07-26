<?php

namespace App\Http\Controllers\Api;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;


/**
 * @OA\Tag(
 *     name="Translations Export",
 *     description="API endpoints for exporting translations"
 * )
 */
class TranslationExportController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/translations/export/{locale}",
     *     summary="Export translations for a specific locale",
     *     tags={"Translations Export"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="locale",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Successful export"),
     *     @OA\Response(response="404", description="Locale not found")
     * )
     */

    public function export(Request $request, string $locale)
    {
        $cacheKey = "translations_export_{$locale}";

        $translations = Cache::remember($cacheKey, 60, function () use ($locale) {
            return Translation::where('locale', $locale)
                ->select('key', 'value', 'tag')
                ->get()
                ->groupBy('tag')
                ->map(function ($group) {
                    return $group->mapWithKeys(fn($t) => [$t->key => $t->value]);
                });
        });

        return response()->json($translations);
    }
}

