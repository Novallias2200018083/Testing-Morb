<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use Illuminate\Http\Request;
use App\Models\Counter;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Service;

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

        
        if ($user->role === 'admin') {
            
           
            $stats = [
                'pending'   => Queue::whereDate('created_at', Carbon::today())->where('status', 'pending')->count(),
                'serving'   => Queue::whereDate('created_at', Carbon::today())->where('status', 'serving')->count(),
                'completed' => Queue::whereDate('created_at', Carbon::today())->where('status', 'completed')->count(),
                'skipped'   => Queue::whereDate('created_at', Carbon::today())->where('status', 'skipped')->count(),
            ];

           
            $counters = Counter::with('activeUser')->get()->map(function($c) {
                $servedToday = Queue::where('counter_id', $c->id)
                                    ->whereDate('created_at', Carbon::today())
                                    ->where('status', 'completed')
                                    ->count();
                return [
                    'name' => $c->name,
                    'status' => $c->status, 
                    'operator' => $c->activeUser ? $c->activeUser->name : '-', 
                    'served_count' => $servedToday
                ];
            });

           
            $services = Service::withCount(['queues' => function($q) {
                $q->whereDate('created_at', Carbon::today())->where('status', 'pending');
            }])->get()->map(function($s) {
                return [
                    'name' => $s->name,
                    'pending_count' => $s->queues_count 
                ];
            });

            
            $monitoring = Service::get()->map(function($s) {
                
                
                $active = Queue::where('service_id', $s->id)
                               ->where('status', 'serving')
                               ->whereDate('created_at', Carbon::today())
                               ->with('counter') 
                               ->first(); 

                
                $next = Queue::where('service_id', $s->id)
                             ->where('status', 'pending')
                             ->whereDate('created_at', Carbon::today())
                             ->orderBy('id', 'asc') 
                             ->limit(5)
                             ->get()
                             ->pluck('queue_code'); 

                return [
                    'service_name' => $s->name,
                    'current_code' => $active ? $active->queue_code : '--', 
                    'current_counter' => $active && $active->counter ? $active->counter->name : null, 
                    'next_queue' => $next, 
                    'total_pending' => $next->count() 
                ];
            });

            return response()->json([
                'role' => 'admin',
                'stats' => $stats,
                'counters' => $counters,
                'services' => $services,   
                'monitoring' => $monitoring 
            ]);
        }

        
        $counter = Counter::where('active_user_id', $user->id)->with('services')->first();

       
        if (!$counter) {
            return response()->json([
                'role' => 'staff',
                'message' => 'Anda belum memilih loket.',
                'waiting' => [],
                'skipped' => []
            ]);
        }

        
        $filterServiceIds = $counter->services->pluck('id')->toArray();

        
        $waiting = Queue::where('status', 'pending')
                        ->whereDate('created_at', Carbon::today())
                        ->whereIn('service_id', $filterServiceIds)
                        ->with('service')
                        ->orderBy('id', 'asc')
                        ->get()
                        ->map(function($q) {
                            return [
                                'id' => $q->id,
                                'code' => $q->queue_code,
                                'service_name' => $q->service->name,
                                'time' => $q->created_at->format('H:i'),
                                'waited_for' => $q->created_at->diffForHumans()
                            ];
                        });

        
        $skipped = Queue::where('status', 'skipped')
                        ->whereDate('created_at', Carbon::today())
                        ->whereIn('service_id', $filterServiceIds)
                        ->with('service')
                        ->orderBy('updated_at', 'desc') 
                        ->get()
                        ->map(function($q) {
                            return [
                                'id' => $q->id,
                                'code' => $q->queue_code,
                                'service_name' => $q->service->name,
                                'skipped_at' => $q->updated_at->format('H:i')
                            ];
                        });

        return response()->json([
            'role' => 'staff',
            'counter_name' => $counter->name,
            'waiting' => $waiting,
            'skipped' => $skipped
        ]);
    }
}