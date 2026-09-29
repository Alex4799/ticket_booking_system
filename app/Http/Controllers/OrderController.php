<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{
    public function list(){
        $order=Order::select('orders.*')
                    ->leftJoin('halls','orders.hall_id','halls.id')
                    ->when(request('search_key'),function($query){
                        $query->where(function($q){
                            $q->where('orders.id',request('search_key'))
                            ->orWhere('user_email',request('search_key'));
                        });
                    })->when(request('status'),function($query){
                        $query->where('status',request('status'));
                    })->when(request('from'),function($query){
                        $query->where('order_from',request('from'));
                    })->when(request('cinema'),function($query){
                        $query->where('halls.cinema_id',request('cinema'));
                    })->when(request('movie'),function($query){
                        $query->where('movie_name',request('movie'));
                    })->when(request('start_date'),function($query){
                        $query->where('date','>=',request('start_date'));
                    })->when(request('end_date'),function($query){
                        $query->where('date','<=',request('end_date'));
                    })->orderBy('created_at','desc')->paginate(20);
        $movie=Cache::remember('all_movie_name', now()->addDay(), function () {
            return Order::distinct()->pluck('movie_name');
        });
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        return view('main.admin.order.list',compact('order','movie','cinema'));
    }

    public function view($id){
        $order=Order::select('orders.*','halls.name as hall_name','cinemas.name as cinema_name',
                        'promotions.name as promotion_name','payments.name as payment_name')
                        ->leftJoin('halls','orders.hall_id','halls.id')
                        ->leftjoin('cinemas','halls.cinema_id','cinemas.id')
                        ->leftJoin('promotions','orders.promotion_id','promotions.id')
                        ->leftJoin('payments','orders.payment_method','payments.id')
                        ->where('orders.id',$id)
                        ->first();
        $payment=Cache::rememberForever('payment_active', function () {
            return Payment::where('payments.active',1)->get();
        });
        return view('main.admin.order.view',compact('order','payment'));
    }

    public function changeStatus(Request $request){
        Order::where('id',$request->id)->update([
            'payment_method'=>$request->payment_method,
            'status'=>$request->status,
        ]);
        $status='success';
        if ($request->status==2) {
            $status='reject';
        }else if($request->status==3){
            $status='cancel';
        }
        OrderLog::create([
            'user_id'=>Auth::user()->id,
            'order_id'=>$request->id,
            'message'=>"Change status to $status",
        ]);
        return back()->with(['success'=>'Order status change successful.']);
    }

    public function orderLog(){
        $orderLog=OrderLog::when(request('order_id'),function($query){
                    $query->where('order_id',request('order_id'));
                })->paginate(20);
        return view('main.admin.log.order',compact('orderLog'));
    }
}
