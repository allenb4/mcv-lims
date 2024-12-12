<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

class UtilityController extends Controller
{
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');

        return response()->json(['message' => 'Cache, config, and views cleared successfully!']);
    }
}

