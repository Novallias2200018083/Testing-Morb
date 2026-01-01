<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Counter;

use Carbon\Carbon;

class TicketController extends Controller
{
   
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id'
        ]);

        return DB::transaction(function () use ($request) {
            $service = Service::findOrFail($request->service_id);

            
            $todayCount = Queue::where('service_id', $service->id)
                               ->today() 
                               ->lockForUpdate() 
                               ->count();

            $nextNumber = $todayCount + 1;
           
            $code = $service->code . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $queue = Queue::create([
                'service_id' => $service->id,
                'queue_number' => $nextNumber,
                'queue_code' => $code,
                'status' => 'pending',
               
            ]);

            return response()->json([
                'message' => 'Tiket berhasil dicetak',
                'data' => $queue,
                'print_data' => [
                    'service_name' => $service->name,
                    'number' => $code,
                    'date' => now()->format('d-m-Y H:i')
                ]
            ], 201);
        });
    }



    public function monitor()
    {
        $today = Carbon::today();

        
        $counters = Counter::with('services')->get()->map(function ($counter) use ($today) {
            
            
            $active = Queue::where('counter_id', $counter->id)
                ->whereIn('status', ['called', 'serving'])
                ->whereDate('created_at', $today)
                ->with('service') 
                ->first();

           
            $last = null;
            if (!$active) {
                $last = Queue::where('counter_id', $counter->id)
                    ->where('status', 'completed')
                    ->whereDate('created_at', $today)
                    ->with('service')
                    ->latest('updated_at')
                    ->first();
            }

           
            $counter->active_queue = $active;
            $counter->last_queue = $last;
            
            return $counter;
        });

        
        $waitingSummary = Queue::where('status', 'pending')
            ->whereDate('created_at', $today)
            ->select('service_id', DB::raw('count(*) as total'))
            ->groupBy('service_id')
            ->with('service')
            ->get();

       
        $services = Service::all();
        $nextQueues = $services->map(function ($service) use ($today) {
            
            $count = Queue::where('service_id', $service->id)
                ->whereDate('created_at', $today)
                ->count();
            
            $nextNum = $count + 1;
            
            
            $code = $service->code . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
            
            return [
                'service_id' => $service->id,
                'next_code' => $code
            ];
        });

        
        return response()->json([
            'counters' => $counters,          
            'waiting_summary' => $waitingSummary, 
            'next_queues' => $nextQueues,    
            
            
            'active_queues' => $counters->pluck('active_queue')->filter()->values() 
        ]);
    }
}