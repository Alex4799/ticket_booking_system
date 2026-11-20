<?php

namespace App\Http\Controllers;

use App\Events\SeatStatusEvent;
use App\Models\Cinema;
use App\Models\MovieSeat;
use App\Models\Order;
use App\Models\OrderLog;
use App\Models\Payment;
use App\Models\ProductOrder;
use App\Models\StoreItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
                    })->when(in_array(Auth::user()->position,['manager','ticket-seller']),function($query){
                        $query->where('halls.cinema_id',Auth::user()->location);
                    })
                    ->orderBy('created_at','desc')
                    ->paginate(20)
                    ->withQueryString();
        $movie=Cache::remember('all_movie_name', now()->addDay(), function () {
            return Order::distinct()->pluck('movie_name');
        });
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        return view('main.admin.order.list',compact('order','movie','cinema'));
    }

    public function view($id){
        $order=Cache::remember("order_{$id}", now()->addMinutes(15), function () use($id) {
            return Order::select('orders.*','halls.name as hall_name','cinemas.name as cinema_name',
                        'promotions.name as promotion_name','payments.name as payment_name')
                        ->leftJoin('halls','orders.hall_id','halls.id')
                        ->leftjoin('cinemas','halls.cinema_id','cinemas.id')
                        ->leftJoin('promotions','orders.promotion_id','promotions.id')
                        ->leftJoin('payments','orders.payment_method','payments.id')
                        ->where('orders.id',$id)
                        ->first();
        });
        $payment=Cache::rememberForever('payment_active', function () {
            return Payment::where('payments.active',1)->get();
        });
        return view('main.admin.order.view',compact('order','payment'));
    }

    public function changeStatus(Request $request){
        $order=Cache::remember("order_{$request->id}", now()->addMinutes(15), function () use($request) {
            return Order::select('orders.*','halls.name as hall_name','cinemas.name as cinema_name',
                        'promotions.name as promotion_name','payments.name as payment_name')
                        ->leftJoin('halls','orders.hall_id','halls.id')
                        ->leftjoin('cinemas','halls.cinema_id','cinemas.id')
                        ->leftJoin('promotions','orders.promotion_id','promotions.id')
                        ->leftJoin('payments','orders.payment_method','payments.id')
                        ->where('orders.id',$request->id)
                        ->first();
        });
        DB::beginTransaction();
        try {
            $status='success';
            if ($request->status==2 && $order->status!=2) {
                $status='reject';
                $seatIds=[];
                foreach (json_decode($order->seats) as $seat) {
                    $seatIds[]=$seat->seat_id;
                    event(new SeatStatusEvent($order->movie_plan_id,$seat->seat_id,0));
                }
                MovieSeat::whereIn('id',$seatIds)->update(['status'=>0]);
            }else if($request->status==3){
                $status='cancel';
            }
            Order::where('id',$request->id)->update([
                'payment_method'=>$request->payment_method,
                'status'=>$request->status,
            ]);
            OrderLog::create([
                'user_id'=>Auth::user()->id,
                'order_id'=>$request->id,
                'message'=>"Change status to $status",
            ]);
            DB::commit();
            $this->removeOrderCache($request->id);
            return back()->with(['success'=>'Order status change successful.']);
        } catch (\Exception $e) {
            logger($e);
            DB::rollback();
            return back()->with(['danger'=>'An error occur. Please try again.']);
        }
    }

    public function invoice($id){
        $order=Cache::remember("order_{$id}", now()->addMinutes(15), function () use($id) {
            return Order::select('orders.*','halls.name as hall_name','cinemas.name as cinema_name',
                        'promotions.name as promotion_name','payments.name as payment_name')
                        ->leftJoin('halls','orders.hall_id','halls.id')
                        ->leftjoin('cinemas','halls.cinema_id','cinemas.id')
                        ->leftJoin('promotions','orders.promotion_id','promotions.id')
                        ->leftJoin('payments','orders.payment_method','payments.id')
                        ->where('orders.id',$id)
                        ->first();
        });
        Order::where('id',$id)->update(['ticket_status'=>1]);
        $this->removeOrderCache($id);
        return view('main.admin.seller.invoice',compact('order'));
    }

    public function orderLog(){
        $orderLog=OrderLog::select('order_logs.*','users.name as user_name')
                ->leftJoin('users','order_logs.user_id','users.id')
                ->when(request('order_id'),function($query){
                    $query->where('order_id',request('order_id'));
                })->paginate(20);
        return view('main.admin.log.order',compact('orderLog'));
    }

    public function list_product(){
        $order=ProductOrder::when(request('search_key'),function($query){
                                $query->where('id',request('search_key'));
                            })
                            ->when(in_array(Auth::user()->position,['manager','product-seller']),function($query){
                                $query->where('cinema_id',Auth::user()->location);
                            })
                            ->orderBy('created_at','desc')
                            ->paginate(20)
                            ->withQueryString();
        return view('main.admin.order.product.list',compact('order'));
    }

    public function view_product($id){
        $order=Cache::remember("product_order_{$id}", now()->addMinutes(15), function () use($id) {
            return ProductOrder::select('product_orders.*','promotions.name as promotion_name','cinemas.name as cinema_name',
                            'promotions.percentage as promotion_percentage','promotions.amount as promotion_amount','payments.name as payment_name')
                            ->leftJoin('promotions','product_orders.promotion_id','promotions.id')
                            ->leftJoin('cinemas','product_orders.cinema_id','cinemas.id')
                            ->leftJoin('payments','product_orders.payment_method','payments.id')
                            ->where('product_orders.id',$id)
                            ->first();
        });
        $productItem=json_decode($order->product);
        return view('main.admin.order.product.view',compact('order','productItem'));
    }

    public function changeStatus_product(Request $request){
        $order=Cache::remember("product_order_{$request->id}", now()->addMinutes(15), function () use($request) {
            return ProductOrder::select('product_orders.*','promotions.name as promotion_name','cinemas.name as cinema_name',
                            'promotions.percentage as promotion_percentage','promotions.amount as promotion_amount','payments.name as payment_name')
                            ->leftJoin('promotions','product_orders.promotion_id','promotions.id')
                            ->leftJoin('cinemas','product_orders.cinema_id','cinemas.id')
                            ->leftJoin('payments','product_orders.payment_method','payments.id')
                            ->where('product_orders.id',$request->id)
                            ->first();
        });
        DB::beginTransaction();
        try {
            if($request->status==2 && $order->status!=2){
                $productItem=json_decode($order->product);
                foreach ($productItem as $item) {
                    StoreItem::where('id',$item->id)->where('type',0)->increment('qty',$item->qty);
                }
            }
            ProductOrder::where('id',$request->id)->update(['status'=>$request->status]);
            DB::commit();
            $this->removeProductCache($request->id);
            return back()->with(['success'=>'Order status change successful.']);
        } catch (\Exception $e) {
            logger($e);
            DB::rollback();
            return back()->with(['danger'=>'An error occur. Please try again.']);
        }
    }

    public function invoiceProduct($id){
        $order=Cache::remember("product_order_{$id}", now()->addMinutes(15), function () use($id) {
            return ProductOrder::select('product_orders.*','promotions.name as promotion_name','cinemas.name as cinema_name',
                            'promotions.percentage as promotion_percentage','promotions.amount as promotion_amount','payments.name as payment_name')
                            ->leftJoin('promotions','product_orders.promotion_id','promotions.id')
                            ->leftJoin('cinemas','product_orders.cinema_id','cinemas.id')
                            ->leftJoin('payments','product_orders.payment_method','payments.id')
                            ->where('product_orders.id',$id)
                            ->first();
        });
        $productItem=json_decode($order->product);
        ProductOrder::where('id',$id)->update(['ticket_status'=>1]);
        $this->removeProductCache($id);
        return view('main.admin.seller.product.invoice',compact('order','productItem'));
    }

    // api
    public function orderPendingGet_admin(){
        try {
            $data=Order::where('status',0)->leftJoin('halls','orders.hall_id','halls.id')
            ->where('halls.cinema_id',Auth::user()->location)->get();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            logger($e);
            return response()->json(['status'=>'fail'], 200);
        }
    }

    // private function
    private function removeOrderCache($id){
        Cache::forget("order_{$id}");
    }

    private function removeProductCache($id){
        Cache::forget("product_order_{$id}");
    }
}
