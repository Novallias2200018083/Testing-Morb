<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Admin\QueueOperationController;
use App\Http\Controllers\Admin\QueueListController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\StaffController;
use Illuminate\Http\Request;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/ticket', [TicketController::class, 'store']); 
Route::get('/monitor', [TicketController::class, 'monitor']);




Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

   
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']); 

    Route::get('/dashboard-stats', [QueueListController::class, 'getDashboardData']);
    Route::post('/counter/close', [QueueOperationController::class, 'closeCounter']);
    Route::post('/counter/reopen', [QueueOperationController::class, 'reopenAndCallNext']);

    Route::get('/operator-state', function (Request $request) {
        
        $user = $request->user();
        $counter = \App\Models\Counter::where('active_user_id', $user->id)->first();
        
        $activeQueue = null;
        if($counter) {
            $activeQueue = \App\Models\Queue::where('counter_id', $counter->id)
                ->where('user_id', $user->id)
                ->whereIn('status', ['called', 'serving'])
                ->whereDate('created_at', \Carbon\Carbon::today())
                ->with('service')
                ->first();
        }
        return response()->json(['counter' => $counter, 'active_queue' => $activeQueue]);
    });

    Route::post('/checkin', [QueueOperationController::class, 'setCounter']);
    Route::post('/checkout', function(Request $request) {
        \App\Models\Counter::where('active_user_id', $request->user()->id)
            ->update(['active_user_id' => null, 'status' => 'closed']);
        return response()->json(['message' => 'Checkout berhasil']);
    });

    Route::post('/next', [QueueOperationController::class, 'callNext']);
    Route::post('/queue/{id}/serve', [QueueOperationController::class, 'serve']);
    Route::post('/queue/{id}/recall', [QueueOperationController::class, 'recall']);
    Route::post('/queue/{id}/skip', [QueueOperationController::class, 'skip']);
    Route::post('/queue/{id}/status', [QueueOperationController::class, 'updateStatus']); 

    
    Route::get('/queues', [QueueListController::class, 'index']); 
    
    Route::apiResource('services', ServiceController::class);
    Route::apiResource('counters', CounterController::class);
    Route::apiResource('staff', StaffController::class);
    Route::post('/staff/{id}/toggle', [StaffController::class, 'toggleStatus']);

});