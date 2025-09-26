<?php

use App\Http\Controllers\Admin\MarketingController;
use App\Models\Marketing;
use App\Models\MarketingAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Marketing API routes
Route::prefix('admin/marketing')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        $marketings = Marketing::with('agents')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $marketings->map(function ($marketing) {
                return [
                    'id' => $marketing->id,
                    'code' => $marketing->code,
                    'name' => $marketing->name,
                    'phone' => $marketing->phone,
                    'email' => $marketing->email,
                    'status' => $marketing->status,
                    'agents_count' => $marketing->agents->count()
                ];
            })
        ]);
    });

    Route::get('/{marketing}/agents', function (Marketing $marketing) {
        $agents = $marketing->agents()
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $agents->map(function ($agent) {
                return [
                    'id' => $agent->id,
                    'name' => $agent->name,
                    'phone' => $agent->phone,
                    'email' => $agent->email,
                    'commission_rate' => $agent->commission_rate,
                    'status' => $agent->status
                ];
            })
        ]);
    });
});