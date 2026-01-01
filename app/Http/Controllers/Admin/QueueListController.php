<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Http\Request;
use App\Models\Counter;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class QueueListController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
       
        $query = Queue::with(['service', 'counter', 'user'])
                      ->orderBy('created_at', 'desc');

        
        if ($user->role !== 'admin') {
            
            $activeCounter = Counter::where('active_user_id', $user->id)->with('services')->first();

            if ($activeCounter) {
              
                $serviceIds = $activeCounter->services->pluck('id')->toArray();
                $query->whereIn('service_id', $serviceIds);
            } else {
                
                $query->where('user_id', $user->id);
            }
        }

        
        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        } else {
           
            $query->whereDate('created_at', Carbon::today());
        }

       
        if ($request->status) {
            $query->where('status', $request->status);
        }

        
        if ($request->search) {
            $query->where('queue_code', 'like', '%' . $request->search . '%');
        }

        $queues = $query->paginate(10)->withQueryString();

        return view('admin.queues.index', compact('queues'));
    }

    public function getDashboardData(Request $request)
    {
        $user = Auth::user();
        
        
        $filterServiceIds = null; 

        
        if ($user->role !== 'admin') {
           
            $counter = Counter::where('active_user_id', $user->id)->with('services')->first();

            if (!$counter) {
                
                return response()->json(['waiting' => [], 'skipped' => [], 'role' => 'staff']);
            }

            
            $filterServiceIds = $counter->services->pluck('id')->toArray();
        }

        
        
       
        $waitingQuery = Queue::where('status', 'pending')
                             ->whereDate('created_at', Carbon::today())
                             ->with('service')
                             ->orderBy('id', 'asc');

        if ($filterServiceIds) {
            $waitingQuery->whereIn('service_id', $filterServiceIds);
        }

        $waiting = $waitingQuery->get()->map(function($q) {
            return [
                'id' => $q->id,
                'code' => $q->queue_code,
                'service_name' => $q->service->name,
                'time' => $q->created_at->format('H:i'),
                'waited_for' => $q->created_at->diffForHumans()
            ];
        });

        
        $skippedQuery = Queue::where('status', 'skipped')
                             ->whereDate('created_at', Carbon::today())
                             ->with('service')
                             ->orderBy('updated_at', 'asc');

        if ($filterServiceIds) {
            $skippedQuery->whereIn('service_id', $filterServiceIds);
        }

        $skipped = $skippedQuery->get()->map(function($q) {
            return [
                'id' => $q->id,
                'code' => $q->queue_code,
                'service_name' => $q->service->name,
                'skipped_at' => $q->updated_at->format('H:i')
            ];
        });

        return response()->json([
            'waiting' => $waiting,
            'skipped' => $skipped,
            'role' => $user->role 
        ]);
    }
}