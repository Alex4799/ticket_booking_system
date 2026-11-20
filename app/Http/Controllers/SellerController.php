<?php

namespace App\Http\Controllers;

use App\Events\SeatStatusEvent;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\MoviePlan;
use App\Models\MovieSeat;
use App\Models\Order;
use App\Models\Payment;
use App\Models\ProductCategory;
use App\Models\ProductOrder;
use App\Models\ProductOrderItem;
use App\Models\Promotion;
use App\Models\StoreItem;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class SellerController extends Controller
{
    public function movie(){
        $currentDate=now()->toDateString();
        $currentTime=now()->format('H:i');
        $movie=MoviePlan::select('movie_plans.id','movie_plans.movie_id','movies.name','movies.poster','movie_plans.date',
                        'movies.genre','movies.run_time','movies.release_date','halls.name as hall_name','movie_plans.start_time')
                        ->when(in_array(Auth::user()->position,['manager','ticket-seller']),function($query){
                            $query->where('halls.cinema_id',Auth::user()->location);
                        })
                        ->where('movie_plans.active',1)
                        ->whereRaw("STR_TO_DATE(CONCAT(movie_plans.date, ' ', movie_plans.end_time), '%Y-%m-%d %H:%i:%s') > ?", [Carbon::now()])
                        ->leftJoin('movies','movie_plans.movie_id','movies.id')
                        ->leftJoin('halls','movie_plans.hall_id','halls.id')
                        ->get()
                        ->groupBy('movie_id')
                        ->map(function($row) use ($currentDate,$currentTime){
                            $item=$row->first();
                            $item['show_time']=$row->filter(function($item) use ($currentDate,$currentTime){
                                        if ($item->date==$currentDate && $item->end_time<$currentTime) {
                                            return $item;
                                        }
                                        // return $item->date==$currentDate && $item->end_time>$currentTime;
                                    })->map(function($plan) use ($currentDate){
                                        return [
                                            'id' => $plan->id,
                                            'name' => $plan->hall_name,
                                            'start_time' => $plan->start_time,
                                        ];
                                    })->toArray();
                            return $item->toArray();
                        });
        return view('main.admin.seller.movie',compact('movie'));
    }

    public function moviePlan($id){
        $movie=Cache::remember("movie{$id}", now()->addMonth(), function () use($id) {
            return Movie::select('movies.*','genres.name as genre_name')
                    ->leftJoin('genres','movies.main_genre','genres.id')
                    ->where('movies.id',$id)
                    ->first();
        });
        $now=Carbon::now();
        $allMoviePlan=MoviePlan::select('movie_plans.id','movie_plans.date','movie_plans.start_time',
                        'halls.name as hall_name','cinemas.name as cinema_name')
                        ->where('movie_plans.movie_id',$id)
                        ->where('movie_plans.active',1)
                        ->when(in_array(Auth::user()->position,['manager','ticket-seller']),function($query){
                            $query->where('halls.cinema_id',Auth::user()->location);
                        })
                        ->where(function($query) use($now){
                            $query->where('movie_plans.date','>',$now->toDateString())
                                ->orWhere(function($item) use ($now){
                                    $item->where('movie_plans.date','=',$now->toDateString())
                                        ->where('movie_plans.end_time','>',$now->toTimeString());
                                });
                        })
                        ->leftJoin('halls','movie_plans.hall_id','halls.id')
                        ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                        ->orderBy('date')
                        ->orderBy('start_time')
                        ->get();

        $date = $allMoviePlan->pluck('date')->unique()->values();

        $moviePlan=request('date')?$allMoviePlan->where('date',request('date'))->values():$allMoviePlan;

        return view('main.admin.seller.moviePlan',compact('moviePlan','date','movie'));
    }

    public function selectSeat($id){
        $moviePlan=Cache::remember("movie_plan_{$id}",now()->addDay(), function () use($id) {
            return MoviePlan::select('movie_plans.*','movies.name as movie_name','movies.run_time','movies.cover','movies.poster','movies.main_genre',
                            'halls.name as hall_name','halls.seat_column','cinemas.name as cinema_name','cinemas.id as cinema_id')
                            ->where('movie_plans.id',$id)
                            ->leftJoin('movies','movie_plans.movie_id','movies.id')
                            ->leftJoin('halls','movie_plans.hall_id','halls.id')
                            ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                            ->first();
        });
        $movieSeat=MovieSeat::select('movie_seats.*','seats.name as seat_name','seats.seat_type','seats.active',
                        'seat_plans.name as seat_plan_name','seat_plans.seat_color')
                        ->leftJoin('seats','movie_seats.seat_id','seats.id')
                        ->leftJoin('seat_plans','seats.seat_plan_id','seat_plans.id')
                        ->where('movie_plan_id',$id)
                        ->get();

        $seatPlan=$movieSeat->groupBy(function($item){
            return "{$item->seat_plan_name}-{$item->price}";
        })->map(function($group){
            $item=$group->first();
            return [
                'seat_plan_name'=>$item->seat_plan_name,
                'price'=>$item->price,
                'seat_color'=>$item->seat_color,
                'avaliable'=>$group->where('status',0)->count(),
                'selected'=>$group->where('status',1)->count(),
                'sold'=>$group->where('status',2)->count(),
                'total'=>$group->count()
            ];
        })->toArray();
        return view('main.admin.seller.selectSeat',compact('moviePlan','movieSeat','seatPlan'));
    }

    public function checkOut(Request $request){
        $seat=json_decode($request->seats);
        if (count($seat)==0) {
            return back()->with(['warning'=>'Please select seat first.']);
        }
        $seatId=[];
        $totalPrice=0;
        foreach ($seat as $item) {
            array_push($seatId,$item->seat_id);
            $totalPrice+=$item->seat_price;
        }
        $haveSeat=MovieSeat::whereIn('id',$seatId)->where('status',2)->exists();
        if ($haveSeat) {
            $this->changeStatus($seatId,0,$request->moviePlanId);
            return back()->with(['danger'=>'You select a sold seat of other get','clear_seats' => true]);
        }
        $moviePlan=Cache::remember("movie_plan_{$request->moviePlanId}",now()->addDay(), function () use($request) {
            return MoviePlan::select('movie_plans.*','movies.name as movie_name','movies.run_time','movies.cover','movies.poster','movies.main_genre',
                            'halls.name as hall_name','halls.seat_column','cinemas.name as cinema_name','cinemas.id as cinema_id')
                            ->where('movie_plans.id',$request->moviePlanId)
                            ->leftJoin('movies','movie_plans.movie_id','movies.id')
                            ->leftJoin('halls','movie_plans.hall_id','halls.id')
                            ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                            ->first();
        });
        $payment=Cache::rememberForever('payment_active', function () {
            return Payment::where('payments.active',1)->get();
        });
        return view('main.admin.seller.checkout',compact('seat','totalPrice','moviePlan','payment'));
    }

    public function order(Request $request){
        $request->validate([
            'seats' => 'required',
            'movie_plan_id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $seat=json_decode($request->seats);
            $seatId = array_column($seat, 'seat_id');
            $movieSeat=MovieSeat::whereIn('id',$seatId)->lockForUpdate()->get();
            $haveSeat=$movieSeat->contains('status',2);
            $samePlan=$movieSeat->contains('movie_plan_id','!=',$request->movie_plan_id);
            if ($haveSeat || count($seatId)==0 || $samePlan) {
                DB::rollback();
                $this->changeStatus($seatId,0,$request->movie_plan_id);
                return redirect()->route('seller#selectSeat',$request->movie_plan_id)->with(['danger'=>'You select a sold seat of other get','clear_seats' => true]);
            }
            $this->changeStatus($seatId,2,$request->movie_plan_id);
            $moviePlan=Cache::remember("movie_plan_{$request->movie_plan_id}",now()->addDay(), function () use($request) {
                return MoviePlan::select('movie_plans.*','movies.name as movie_name','movies.run_time','movies.cover','movies.poster','movies.main_genre',
                            'halls.name as hall_name','halls.seat_column','cinemas.name as cinema_name','cinemas.id as cinema_id')
                            ->where('movie_plans.id',$request->movie_plan_id)
                            ->leftJoin('movies','movie_plans.movie_id','movies.id')
                            ->leftJoin('halls','movie_plans.hall_id','halls.id')
                            ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                            ->first();
            });
            $data=[
                'user_name'=>$request->user_name,
                'user_email'=>$request->user_email,
                'user_phone'=>$request->user_phone,
                'payment_method'=>$request->order_status=='book'?2:$request->payment_method,
                'promotion_id'=>$request->promotion_id,
                'promotion_price'=>$request->promotion_price,
                'total_price'=>$request->total_price,
                'pay_amount'=>$request->pay_amount,
                'change'=>$request->pay_amount!=null?$request->pay_amount-$request->total_price:0,
                'movie_name'=>$moviePlan->movie_name,
                'show_time'=>$moviePlan->start_time,
                'date'=>$moviePlan->date,
                'hall_id'=>$moviePlan->hall_id,
                'cinema_id'=>$moviePlan->cinema_id,
                'genre'=>$moviePlan->main_genre,
                'movie_plan_id'=>$request->movie_plan_id,
                'seats'=>$request->seats,
                'seat_count'=>count($seatId),
                'status'=>$request->order_status=='book'?4:1,
                'order_from'=>0,
            ];
            $order=Order::create($data);
            if ($request->promotion_id!=null) {
                Promotion::where('id',$request->promotion_id)->increment('used_count');
            }
            DB::commit();
            return redirect()->route('admin#orderView',$order->id);
        } catch (\Exception $e) {
            logger($e);
            DB::rollback();
            $this->changeStatus($seatId,0,$request->movie_plan_id);
             return redirect()->route('seller#selectSeat',$request->movie_plan_id)->with(['danger'=>'An error occur . Please try again.','clear_seats' => true]);
        }

    }

    public function orderHallRental(Request $request){
        Validator::make($request->all(),[
            'user_name'=>'required',
            'user_email'=>'required',
            'user_phone'=>'required',
            'price'=>'required',
            'payment_method'=>'required',
        ])->validate();

        $moviePlan=Cache::remember("movie_plan_{$request->movie_plan_id}",now()->addDay(), function () use($request) {
            return MoviePlan::select('movie_plans.*','movies.name as movie_name','movies.run_time','movies.cover','movies.poster','movies.main_genre',
                            'halls.name as hall_name','halls.seat_column','cinemas.name as cinema_name','cinemas.id as cinema_id')
                            ->where('movie_plans.id',$request->movie_plan_id)
                            ->leftJoin('movies','movie_plans.movie_id','movies.id')
                            ->leftJoin('halls','movie_plans.hall_id','halls.id')
                            ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                            ->first();
        });

        $seatCount=MovieSeat::where('movie_plan_id',$request->movie_plan_id)->count();
        $seat=[
            [
                'seat_id'=>'Hall Rental',
                'seat_name'=>'Hall Rental',
                'seat_price'=>$request->price,
                'seat_plan'=>'Hall Rental'
            ]
        ];
        $data=[
            'user_name'=>$request->user_name,
            'user_email'=>$request->user_email,
            'user_phone'=>$request->user_phone,
            'payment_method'=>$request->payment_method,
            'total_price'=>$request->price,
            'movie_plan_id'=>$request->movie_plan_id,
            'seats'=>json_encode($seat),
            'seat_count'=>$seatCount,
            'movie_name'=>$moviePlan->movie_name,
            'show_time'=>$moviePlan->start_time,
            'hall_id'=>$moviePlan->hall_id,
            'genre'=>$moviePlan->main_genre,
            'date'=>$moviePlan->date,
            'order_from'=>0,
            'status'=>1,
        ];
        if ($request->movie==0) {
            $data['movie_name']='Hall Rental';
            $data['genre']=0;

        }
        MovieSeat::where('movie_plan_id',$request->movie_plan_id)->update(['status'=>2]);
        MoviePlan::where('id',$request->movie_plan_id)->update(['active'=>0]);
        Cache::forget("movie_plan_{$request->movie_plan_id}");
        Order::create($data);
        return back()->with(['success'=>'Hall rental create successful.']);
    }

    public function lateTicket(Request $request){
        $moviePlan=MoviePlan::where('id',$request->id)->where('active',0)->first();
        if ($moviePlan) {
            return redirect()->route('seller#selectSeat',$moviePlan->id);
        }else{
            return back()->with(['warning'=>'Wrong movie plan id or this movie plan is still active']);
        }
    }

    // product
    public function cart(){
        $cinema_id=request('cinema_id')??Auth::user()->location??1;
        $storeItem=StoreItem::select('store_items.*','products.image as product_image','products.category_id')
                            ->leftJoin('stores','store_items.store_id','stores.id')
                            ->leftJoin('products','store_items.product_id','products.id')
                            ->where('stores.cinema_id',$cinema_id)
                            ->where('store_items.active',1)
                            ->where('store_items.selling_type',0)
                            ->get();
        $category=Cache::rememberForever('category', function () {
            return ProductCategory::get();
        });
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        return view('main.admin.seller.product.list',compact('storeItem','category','cinema','cinema_id'));
    }

    public function checkOutProduct(Request $request){
        $cinema_id=$request->cinema_id;
        $cart=json_decode($request->data);
        $totalPrice=0;
        foreach ($cart as $item) {
            $storeItem=StoreItem::find($item->id);
            if ($storeItem->qty<$item->qty && $storeItem->type==0) {
                return back()->with(['warning'=>"Qty of $item->product_name is not enough. System is reduce to max qty.","overQty"=>['id'=>$item->id,'max_qty'=>$storeItem->qty]]);
            }
            $totalPrice+=$item->product_price*$item->qty;
        }
        $payment=Cache::rememberForever('payment_active', function () {
            return Payment::where('payments.active',1)->get();
        });
        return view('main.admin.seller.product.paymentInfo',compact('cart','totalPrice','payment','cinema_id'));
    }

    public function orderProduct(Request $request){
        DB::beginTransaction();
        try {
            $cart=json_decode($request->product);
            $productOrder=ProductOrder::create([
                'payment_method'=>$request->payment_method,
                'total_price'=>$request->total_price,
                'pay_amount'=>$request->pay_amount,
                'change'=>$request->pay_amount?$request->pay_amount-$request->total_price:0,
                'product'=>$request->product,
                'promotion_id'=>$request->promotion_id,
                'promotion_price'=>$request->promotion_price,
                'cinema_id'=>$request->cinema_id,
                'status'=>1,
            ]);
            $productItem=[];
            foreach ($cart as $item) {
                $storeItem=StoreItem::lockForUpdate()->find($item->id);
                if ($storeItem->qty<$item->qty && $storeItem->type==0) {
                    DB::rollback();
                    return back()->with(['warning'=>"Qty of $item->product_name is not enough. System is reduce to max qty.","overQty"=>['id'=>$item->id,'max_qty'=>$storeItem->qty]]);
                }
                $productItem[]=[
                    'order_id'=>$productOrder->id,
                    'product_id'=>$storeItem->product_id,
                    'store_item_id'=>$storeItem->id,
                    'product_name'=>$storeItem->product_name,
                    'product_price'=>$storeItem->selling_price,
                    'profit'=>$storeItem->profit,
                    'store_id'=>$storeItem->store_id,
                    'instock'=>$storeItem->qty,
                    'qty'=>$item->qty,
                    'created_at'=>now(),
                    'updated_at'=>now(),
                ];
                if ($storeItem->type==0) {
                    $storeItem->qty=$storeItem->qty-$item->qty;
                    $storeItem->save();
                }
            }
            ProductOrderItem::insert($productItem);
            DB::commit();
            return redirect()->route('admin#orderViewProduct',$productOrder->id)->with(['success'=>'Order create successful.']);
        } catch (Exception $e) {
            logger($e);
            DB::rollback();
            return back()->with(['error'=>'An error occur. Please try again.']);
        }
    }

    // private function
    private function changeStatus($seatId,$status,$movie_plan_id){
        $redisKeys=[];
        if (!empty($seatId)) {
            foreach ($seatId as $item) {
                $redisKeys[]="movie_seat_{$item}";
            }
            event(new SeatStatusEvent($movie_plan_id,$seatId,$status));
            Redis::del($redisKeys);
            MovieSeat::whereIn('id',$seatId)
                        ->when($status==0,function($query){
                            $query->where('status',1);
                        })->update(['status'=>$status]);
        }
    }
}
