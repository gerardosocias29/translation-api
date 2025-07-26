<?php

namespace App\Http\Controllers\Api;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class TranslationExportController extends Controller
{
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

