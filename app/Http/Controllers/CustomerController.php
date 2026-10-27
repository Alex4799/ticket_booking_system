<?php

namespace App\Http\Controllers;

use App\Events\OrderAlertEvent;
use App\Events\SeatStatusEvent;
use App\Mail\OrderMail;
use App\Mail\VerifyMail;
use App\Models\Cinema;
use App\Models\Movie;
use App\Models\MoviePlan;
use App\Models\MovieSeat;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Social;
use App\Models\User;
use App\Models\WebsiteInfo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function home(){
        $website=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });

        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });

        $filterData = Cache::remember('movie_plan_filter', now()->addMinutes(30), function () {
            $activePlans = MoviePlan::query()
                ->where('movie_plans.active', 1)
                ->join('movies', 'movie_plans.movie_id', '=', 'movies.id');

            return [
                'movie' => (clone $activePlans)
                    ->select('movies.id as movie_id', 'movies.name as movie_name')
                    ->distinct()
                    ->get()
                    ->toArray(),

                'date' => (clone $activePlans)
                    ->distinct()
                    ->pluck('movie_plans.date')
                    ->toArray(),
            ];
        });

        $movies = Cache::remember('home_movies', now()->addHours(6), function () {
            $today = now()->toDateString();

            return [
                'now' => Movie::where('active', 1)
                    ->where('release_date', '<=', $today)
                    ->get(),

                'comming' => Movie::where('active', 1)
                    ->where('release_date', '>', $today)
                    ->get(),

                'popular' => Movie::select('movies.id', 'movies.poster', 'movies.rating', 'movies.name',
                    DB::raw('COUNT(orders.id) as order_count'))
                    ->leftJoin('orders','movies.name','orders.movie_name')
                    ->where('movies.featured', 1)
                    ->where('movies.active', 1)
                    ->groupBy('movies.id')
                    ->orderBy('order_count','desc')
                    ->limit(5)
                    ->get(),
            ];
        });
        return view('main.customer.home.home',compact('website','cinema','filterData','movies'));
    }

    public function movieList(){
        $now=Carbon::now()->toDateString();
        $ttl=Carbon::now()->addDay();
        if (request('search_key') || request('type') || request('genre')) {
            $movie=Movie::where('active',1)
                        ->when(request('search_key'),function($query){
                            $query->where('name','like','%'.request('search_key').'%');
                        })
                        ->when(request('type'),function($query) use($now){
                            if (request('type')=='now') {
                                $query->where('release_date', '<=', $now);
                            }else{
                                $query->where('release_date', '>', $now);
                            }
                        })
                        ->when(request('genre'),function($query){
                            $query->where('main_genre',request('genre'));
                        })
                        ->orderBy('created_at','desc')
                        ->get();
        }else{
            $movie=Cache::remember('movie_active',$ttl, function () {
                return Movie::where('active',1)->orderBy('created_at','desc')->get();
            });
        }

        $genre=Cache::remember('active_movie_genre', $ttl, function () {
            return Movie::select('genres.*')
                            ->leftJoin('genres','movies.main_genre','genres.id')
                            ->where('active',1)
                            ->groupBy('movies.main_genre')
                            ->get();
        });

        return view('main.customer.movie.list',compact('movie','genre'));
    }

    public function movie_view($id){
        $movie=Cache::remember("movie{$id}", now()->addMonth(), function () use($id) {
            return Movie::select('movies.*','genres.name as genre_name')
                    ->leftJoin('genres','movies.main_genre','genres.id')
                    ->where('movies.id',$id)
                    ->first();
        });
        return view('main.customer.movie.view',compact('movie'));
    }

    public function movie_book($id){
        $movie=Cache::remember("movie{$id}", now()->addMonth(), function () use($id) {
            return Movie::select('movies.*','genres.name as genre_name')
                    ->leftJoin('genres','movies.main_genre','genres.id')
                    ->where('movies.id',$id)
                    ->first();
        });

        $now=Carbon::now();
        $allMoviePlan=Cache::remember("all_movie_plan_{$id}", now()->addMinutes(30), function () use($id,$now) {
            return MoviePlan::select('movie_plans.*','halls.name as hall_name',
                        'cinemas.name as cinema_name','cinemas.location as cinema_location')
                        ->where('movie_plans.movie_id',$id)
                        ->where('movie_plans.active',1)
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
        });
        $date = $allMoviePlan->pluck('date')->unique()->values();
        $moviePlan=request('date')?$allMoviePlan->where('date',request('date'))->groupBy('cinema_name'):$allMoviePlan->groupBy('cinema_name');
        return view('main.customer.movie.bookTicket',compact('movie','date','moviePlan'));
    }

    public function movie_select_seat($id){
        $moviePlan=Cache::remember("movie_plan_{$id}",now()->addDay(), function () use($id) {
            return MoviePlan::select('movie_plans.*','movies.name as movie_name','movies.run_time','movies.cover','movies.poster','movies.main_genre',
                            'halls.name as hall_name','halls.seat_column','cinemas.name as cinema_name','cinemas.id as cinema_id')
                            ->where('movie_plans.id',$id)
                            ->leftJoin('movies','movie_plans.movie_id','movies.id')
                            ->leftJoin('halls','movie_plans.hall_id','halls.id')
                            ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                            ->first();
        });

        if (empty($moviePlan) || $moviePlan->active!=1) {
            return back()->with(['danger'=>'This movie plan is not get to book. Please another plan.']);
        }

        $movieSeat=MovieSeat::select('movie_seats.*','seats.name as seat_name','seats.seat_type','seats.active',
                        'seat_plans.name as seat_plan_name','seat_plans.seat_color','seat_plans.image')
                        ->leftJoin('seats','movie_seats.seat_id','seats.id')
                        ->leftJoin('seat_plans','seats.seat_plan_id','seat_plans.id')
                        ->where('movie_plan_id',$id)
                        ->get();

        $seatPlan=$movieSeat->groupBy('seat_plan_name')->map(function($item){
            $firstItem=$item->first();
            return [
                'seat_plan_name'=>$firstItem->seat_plan_name,
                'seat_color'=>$firstItem->seat_color,
                'image'=>$firstItem->image,
            ];
        });

        return view('main.customer.movie.selectSeat',compact('moviePlan','seatPlan','movieSeat'));
    }

    public function movie_checkout(Request $request){
        $seat=json_decode($request->seats);
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
        $payment=Cache::remember("payment_cinema_{$moviePlan->cinema_id}",now()->addDay(),function () use($moviePlan) {
            return Payment::where('payments.active',1)
                            ->where('payments.cinema_id',$moviePlan->cinema_id)
                            ->orWhere('id',2)
                            ->get();
        });

        $website=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });

        return view('main.customer.order.paymentInfo',compact('moviePlan','seat','totalPrice','payment','website'));
    }

    public function vreify(Request $request){
        $company=Cache::rememberForever('website', function () {
            return WebsiteInfo::where('id',1)->first();
        });
        if ($request->status=='pending') {
            $body="Hello $request->username. Your email has a order of seat in $company->name Website.
             Please verify your info.";
        }else{
            $body="Hello $request->username. Your email has cancel order of seat in $company->name Website.
             Please verify your info.";
        }
        $mailData=[
            'title'=>"Verify User Info.",
            'body'=>$body,
            'info'=>`Username - $request->username
             Phone - $request->phone`,
            'otp'=>$request->otp,
        ];
        Mail::to($request->email)->send(new VerifyMail($mailData));
        // Mail::to($request->email)->queue((new VerifyMail($mailData))->onQueue('emails'));
        $data=[
            'status'=>'successful',
        ];
        return response()->json($data, 200);
    }

    public function order(Request $request){
        $request->validate([
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'Please fill the recaptcha.'
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
            $data=$this->orderFormat($request);
            $this->changeStatus($seatId,2,$request->movie_plan_id);
            $order=Order::create($data);
            DB::commit();
            $transacteaseData = $this->generateTransacteasePayload($order);
            return view('main.customer.order.transactease_redirect', compact('transacteaseData'));
        } catch (Exception $e) {
            logger($e);
            DB::rollback();
            $seatId = array_column($seat, 'seat_id');
            return redirect()->route('customer#home')->with(['danger'=>'An error occor. Please try again.']);
        }
    }

    public function manual_order(Request $request){
        $request->validate([
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'Please fill the recaptcha.'
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
            $data=$this->orderFormat($request);
            if ($request->hasFile('slip')) {
                $imageName=$request->file('slip')->getClientOriginalName();
                $request->file('slip')->storeAs('slip',$imageName);
                $data['payment_slip']=$imageName;
            }
            $this->changeStatus($seatId,2,$request->movie_plan_id);
            $order=Order::create($data);
            DB::commit();
            event(new OrderAlertEvent($order->id));
            Mail::to($order->user_email)->send(new OrderMail([
                'title'=>"Order Pending.",
                'body'=>"Hello $order->user_name. Your order OID - $order->id is pending now.",
                'status'=>1,
            ]));
            return redirect()->route('customer#orderSummary',$order->id)->with(['success'=>"<p>! Your movie ticket buying  successfully made and Confirmation mail will sent within an hour.</p>
                        <p>! ရုပ်ရှင်လက်မှတ်ဝယ်ယူမှုအောင်မြင်ပါသည်.ကျေးဇူးတင်ပါသည်</p>"]);
        } catch (Exception $e) {
            logger($e);
            DB::rollback();
            $seatId = array_column($seat, 'seat_id');
            $this->changeStatus($seatId,0,$request->movie_plan_id);
            return redirect()->route('customer#home')->with(['danger'=>'An error occor. Please try again.']);
        }
    }

    public function book(Request $request){
        $request->validate([
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'Please fill the recaptcha.'
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
            $data=$this->orderFormat($request);
            $data['status']=4;
            $this->changeStatus($seatId,2,$request->movie_plan_id);
            $order=Order::create($datas);
            DB::commit();
            event(new OrderAlertEvent($order->id));
            Mail::to($order->user_email)->send(new OrderMail([
                'title'=>"Order Booking.",
                'body'=>"Hello $order->user_name, your ticket OID - $order->id has been booked. Please pay 45 minutes before the show.",
                'status'=>1,
            ]));
            return redirect()->route('customer#orderSummary',$order->id)->with(['success'=>"<p>! Your ticket booking was successful. Please made payment 45mins before show start or booking will be automatically cancel</p>
                    <p>! ရုပ်ရှင်ပြသချိန် ၄၅မိနစ် မတိုင်မီ ငွေပေးချေမှု မပြုလုပ်ပါက booking ticket   အလိုလျောက်ပယ်ဖျက်သွားပါမည်ဖြစ်ပါသည်။ ကျေးဇူးတင်ပါသည်</p>"]);
        } catch (Exception $e) {
            logger($e);
            DB::rollback();
            $seatId = array_column($seat, 'seat_id');
            $this->changeStatus($seatId,0,$request->movie_plan_id);
            return redirect()->route('customer#home')->with(['danger'=>'An error occor. Please try again.']);
        }
    }

    public function summary($id){
        $order=Cache::remember("order_{$id}", now()->addMinutes(10), function () use($id) {
            return Order::select('orders.*','movies.poster','cinemas.name as cinema_name','halls.name as hall_name',
                        'promotions.name as promotion_name','promotions.amount','promotions.percentage','movie_plans.online_buy')
                        ->leftJoin('movie_plans','orders.movie_plan_id','movie_plan_id')
                        ->leftJoin('movies','movie_plans.movie_id','movies.id')
                        ->leftJoin('halls','orders.hall_id','halls.id')
                        ->leftJoin('cinemas','orders.cinema_id','cinemas.id')
                        ->leftJoin('promotions','orders.promotion_id','promotions.id')
                        ->where('orders.id',$id)
                        ->first();
        });

        $payment=Cache::remember("payment_cinema_{$order->cinema_id}",now()->addDay(),function () use($order) {
            return Payment::where('payments.active',1)
                            ->where('payments.cinema_id',$order->cinema_id)
                            ->orWhere('id',2)
                            ->get();
        });

        $website=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });

        return view('main.customer.order.orderSummary',compact('order','payment','website'));
    }

    public function finish(Request $request){
        $request->validate([
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'g-recaptcha-response.required' => 'Please fill the recaptcha.'
        ]);
        $website=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });
        if ($website->payment_type) {
            $order=Order::find($request->id);
            $order->payment_type=$request->payment_type;
            $order->save();
            $transacteaseData = $this->generateTransacteasePayload($order);
            return view('main.customer.order.transactease_redirect', compact('transacteaseData'));
        }
        DB::beginTransaction();
        Validator::make($request->all(),[
            'payment_method'=>'required',
            'slip'=>'required',
        ])->validate();
        try {
            $data=[
                'payment_method'=>$request->payment_method,
                'status'=>0,
            ];
            if ($request->hasFile('slip')) {
                $image=uniqid().$request->file('slip')->getClientOriginalName();
                $request->file('slip')->storeAs('slip',$image);
                $data['payment_slip']=$image;
            }
            Order::where('id',$request->id)->update($data);
            DB::commit();
            Cache::forget("order_{$request->id}");
            return back()->with(['success'=>"Order pending successful."]);
        } catch (\Exception $e) {
            DB::rollback();
            logger($e);
            return back()->with(['danger'=>'An error occor. Please try again.']);
        }
    }

    public function orderCheckPage_customer(){
        return view('main.customer.order.check');
    }

    public function orderCheck_customer(Request $req){
        Validator::make($req->all(),[
            'id'=>'required',
            'email'=>"required",
        ])->validate();
        $order=Order::where('id',$req->id)->where('user_email',$req->email)->first();
        if (isset($order)) {
            return redirect()->route('customer#orderSummary',$order->id);
        }else{
            return back()->with(['warning'=>'Your order is not found. Please check again order id and email.']);
        }
    }

    public function about(){
        $website=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });
        $user=User::select('name','position')->get();
        return view('main.customer.about.aboutUs',compact('website','user'));
    }

    // private function
    private function changeFormat($request){
        return [
            'user_name'=>$request->user_name,
            'user_email'=>$request->user_email,
            'user_phone'=>$request->user_phone,
            'payment_method'=>$request->payment_method,
            'payment_type'=>$request->payment_type,
            'promotion_id'=>$request->promotion_id,
            'promotion_price'=>$request->promotion_price,
            'total_price'=>$request->total_price,
            'pay_amount'=>$request->total_price,
            'movie_plan_id'=>$request->movie_plan_id,
            'seats'=>$request->seats,
            'seat_count'=>$request->seat_count,
        ];
    }

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

    private function orderFormat($request){
        $temp=$this->changeFormat($request);
        $moviePlan=Cache::remember("movie_plan_{$request->movie_plan_id}",now()->addDay(), function () use($request) {
            return MoviePlan::select('movie_plans.*','movies.name as movie_name','movies.run_time','movies.cover','movies.poster','movies.main_genre',
                        'halls.name as hall_name','halls.seat_column','cinemas.name as cinema_name','cinemas.id as cinema_id')
                        ->where('movie_plans.id',$request->movie_plan_id)
                        ->leftJoin('movies','movie_plans.movie_id','movies.id')
                        ->leftJoin('halls','movie_plans.hall_id','halls.id')
                        ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                        ->first();
        });
        $temp['movie_name']=$moviePlan->movie_name;
        $temp['show_time']=$moviePlan->start_time;
        $temp['date']=$moviePlan->date;
        $temp['genre']=$moviePlan->main_genre;
        $temp['hall_id']=$moviePlan->hall_id;
        $temp['cinema_id']=$moviePlan->cinema_id;
        $temp['order_from']=1;

        return $temp;
    }

    private function generateTransacteasePayload($order){
        $timestamp = now()->format('Y-m-d\TH:i:s');

        $fields = [
            'MerchantUserID' => env('PGW_MERCHANT_ID'),
            'AccessKey' => env('PGW_ACCESS_KEY'),
            'Channel' => env('PGW_CHANNEL'),
            'RequestID' => $order->id,
            'PaymentMethod' => $order->payment_type,
            'Amount' => number_format($order->total_price, 2, '.', ''),
            'Currency' => 'MMK',
            'InvoiceNo' => 'OID' . $order->id,
            'BillToAddressLine1' => 'Customer Address',
            'BillToAddressLine2' => '',
            'BillToAddressCity' => 'Yangon',
            'BillToAddressPostalCode' => '11051',
            'BillToAddressState' => 'Yangon',
            'BillToAddressCountry' => 'MM',
            'BillToForename' => $order->user_name ?? 'Guest',
            'BillToSurname' => $order->user_name ?? 'Guest',
            'BillToPhone' => $order->user_phone,
            'BillToEmail' => $order->user_email,
            'ExpiredInSeconds' => 300,
            'Remark' => 'Movie Ticket Payment',
            'UserDefined1' => $order->id,
            'UserDefined2' => $order->hall_id,
            'UserDefined3' => $order->movie_plan_id,
            'UserDefined4' => '',
            'UserDefined5' => '',
            'SignedDateTime' => $timestamp,
        ];
        $signedFields = implode(',', array_keys($fields));

        $formBodyString = collect($fields)->map(fn($v, $k) => "$k=$v")->implode(',');
        $fields['SignedFields'] = $signedFields;

        $uri = 'Payments/Request';
        $signaturePlainText = "POST|$uri|{$fields['SignedDateTime']}|{$order->id}|$formBodyString";
        $fields['Signature'] = base64_encode(
            hash_hmac('sha256', $signaturePlainText, env('PGW_SECRET_KEY'), true)
        );
        return $fields;
    }
}
