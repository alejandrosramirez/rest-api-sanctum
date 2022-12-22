<?php

namespace App\Http\Controllers\Web\Artisan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    /**
     * Clear all cache.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function cache()
    {
        Artisan::call('cache:clear');

        return response()->json(['data' => 'Cache cleared']);
    }

    /**
     * Clear all config cache.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function config()
    {
        Artisan::call('config:clear');

        return response()->json(['data' => 'Config cache cleared']);
    }

    /**
     * Clear all route cache.
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function route()
    {
        Artisan::call('route:clear');

        return response()->json(['data' => 'Route cache cleared']);
    }
}
