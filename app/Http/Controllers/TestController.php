<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TestController extends Controller
{

    public function test()
    {
        return response()->json([
            'app_version' => 1,
            'namespace' => app()->getNamespace(),
            'version' => app()->version(),
            'environment' => app()->environment(),
            'db_connection_db_name' => DB::connection()->getDatabaseName(),
        ]);
    }

}
