<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QueueOperationController extends Controller
{
    
    public function setCounter(Request $request)
    {
        $request->validate(['counter_id' => 'required|exists:counters,id']);
        $user = Auth::user();

       
        $targetCounter = Counter::find($request->counter_id);
        if ($targetCounter->active_user_id && $targetCounter->active_user_id !== $user->id) {
            return response()->json(['message' => 'Loket ini sedang digunakan petugas lain!'], 409);
        }

       
        Counter::where('active_user_id', $user->id)->update(['active_user_id' => null, 'status' => 'closed']);

        
        $targetCounter->update([
            'active_user_id' => $user->id,
            'status' => 'open'
        ]);

        return response()->json(['message' => "Berhasil masuk ke {$targetCounter->name}"]);
    }

    
    public function callNext()
    {
        $user = Auth::user();
        
        
        $counter = Counter::where('active_user_id', $user->id)->with('services')->first();

        if (!$counter) {
            return response()->json(['message' => 'Anda belum memilih loket! Silakan pilih loket dahulu.'], 400);
        }

        return DB::transaction(function () use ($user, $counter) {
            
            $current = Queue::where('user_id', $user->id)
                            ->whereDate('created_at', Carbon::today())
                            ->whereIn('status', ['called', 'serving'])
                            ->first();
            
            if ($current) {
                $current->update(['status' => 'completed', 'served_at' => now(), 'completed_at' => now()]);
            }

            
            $allowedServiceIds = $counter->services->pluck('id')->toArray();

            if (empty($allowedServiceIds)) {
                return response()->json(['message' => 'Loket ini belum disetting untuk melayani layanan apapun.'], 400);
            }

            
            $nextQueue = Queue::whereIn('service_id', $allowedServiceIds)
                              ->where('status', 'pending')
                              ->whereDate('created_at', Carbon::today())
                              ->orderBy('id', 'asc') 
                              ->lockForUpdate() 
                              ->first();

            if (!$nextQueue) {
                return response()->json(['message' => 'Tidak ada antrian menunggu untuk layanan di loket ini.'], 404);
            }

           
            $nextQueue->update([
                'status' => 'called',    
                'counter_id' => $counter->id,
                'user_id' => $user->id,
                'called_at' => now()
            ]);

            return response()->json([
                'message' => 'Memanggil nomor ' . $nextQueue->queue_code,
                'data' => $nextQueue->load('service')
            ]);
        });
    }

   
    public function recall($id)
    {
        $user = Auth::user();
        $counter = Counter::where('active_user_id', $user->id)->first();
        
        if(!$counter) return response()->json(['message' => 'Error loket'], 400);

       
        DB::transaction(function () use ($user, $counter, $id) {
            
            Queue::where('user_id', $user->id)
                 ->whereIn('status', ['called', 'serving'])
                 ->whereDate('created_at', Carbon::today())
                 ->update(['status' => 'completed', 'completed_at' => now()]);

           
            $queue = Queue::findOrFail($id);
            $queue->update([
                'status' => 'called',
                'counter_id' => $counter->id,
                'user_id' => $user->id,
                'called_at' => now()
            ]);
        });

        return response()->json(['message' => 'Memanggil ulang...']);
    }

   
    public function closeCounter(Request $request)
    {
        $request->validate(['reason' => 'required|string|max:50']);
        $user = Auth::user();

        Counter::where('active_user_id', $user->id)
               ->update([
                   'active_user_id' => null, 
                   'status' => 'closed',
                   'closing_reason' => $request->reason
               ]);

        return response()->json(['message' => 'Loket ditutup']);
    }

    
    public function serve($id)
    {
        $queue = Queue::findOrFail($id);
        
        if ($queue->user_id != Auth::id()) {
            return response()->json(['message' => 'Anda tidak berhak memproses antrian ini'], 403);
        }

        $queue->update([
            'status' => 'serving', 
            'served_at' => now() 
        ]);
        
        return response()->json(['message' => 'Status berubah menjadi Serving']);
    }

    
    public function skip($id)
    {
        $queue = Queue::findOrFail($id);
        
        if ($queue->user_id != Auth::id()) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        $queue->update([
            'status' => 'skipped', 
            'completed_at' => now()
        ]);
        
        return response()->json(['message' => 'Antrian dilewati.']);
    }

    
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:serving,completed,skipped']);
        $queue = Queue::findOrFail($id);

        if ($queue->user_id != Auth::id()) return response()->json(['message' => 'Akses ditolak'], 403);

        $updateData = ['status' => $request->status];
        if($request->status == 'completed') {
            $updateData['completed_at'] = now();
        }

        $queue->update($updateData);
        return response()->json(['message' => 'Status diperbarui']);
    }
}