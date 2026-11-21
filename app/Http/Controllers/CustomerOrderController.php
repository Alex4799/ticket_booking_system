<?php

namespace App\Http\Controllers;
use App\Events\OrderAlertEvent;
use App\Events\SeatStatusEvent;
use App\Mail\OrderMail;
use App\Mail\VerifyMail;
use App\Models\MoviePlan;
use App\Models\MovieSeat;
use App\Models\Order;
use App\Models\Payment;
use App\Models\WebsiteInfo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class CustomerOrderController extends Controller
{
    public function movie_checkout(Request $request){
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
        // $company=Cache::rememberForever('website', function () {
        //     return WebsiteInfo::where('id',1)->first();
        // });
        $company=WebsiteInfo::where('id',1)->first();
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
            $transacteaseData = $this->generateTransacteasePayload($order);
            $order->signature=$transacteaseData['Signature'];
            $order->save();
            DB::commit();
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
            $order=Order::create($data);
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

    public function paymentCallback(Request $request){
        $data = $request->all();
        $id = $data['UserDefined1'];
        $order =Cache::remember("order_{$id}", now()->addMinutes(10), function () use($id) {
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

        if ($order) {
            if ($order->signature==$data['Signature']) {
                if ($data['RespCode'] === '000') {
                    $order->status = 1;
                    $order->payment_method = 10;
                    $order->transaction_id = $data['TransactionID'] ?? null;
                    $order->transaction_reference_number = $data['TransactionReferenceNumber'] ?? null;
                    event(new OrderAlertEvent($order->id));
                    Mail::to($order->user_email)->queue((new OrderMail([
                        'title' => 'Payment Confirmed',
                        'body' => "Hello {$order->user_name}. Your order OID - {$order->id} has been paid successfully.",
                        'status' => 1
                    ]))->onQueue('emails'));
                    $order->save();
                    Cache::forget("order_{$id}");
                } else {
                    if ($order->status==0) {
                        foreach (json_decode($order->seats) as $item) {
                            $this->changeSeatStatus($item->id,0);
                        }
                        Cache::forget("order_{$id}");
                        $order->delete();
                    }
                }
            }else{
                logger($data);
                return response()->json(['status'=>'failed','message'=>'Unauthorized'], 401);
            }
        }
    }

    public function paymentCancel(Request $req){
        $data = $req->all();
        $id = $data['RequestID'];
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
        if (empty($order)) {
            return redirect()->route('customer#home')
                             ->with(['warning' => 'An error occurred. Please try again after 1min.']);
        }else if($order->status==4){
            return redirect()->route('customer#orderSummary', $order->id)->with([
                                        'success' => "<p>! Your ticket booking was successful. Please make payment 20 mins before show start or booking will be automatically canceled</p>
                                                    <p>! ရုပ်ရှင်ပြသချိန် ၂၀မိနစ် မတိုင်မီ ငွေပေးချေမှု မပြုလုပ်ပါက booking ticket   အလိုလျောက်ပယ်ဖျက်သွားပါမည်ဖြစ်ပါသည်။ ကျေးဇူးတင်ပါသည်</p>"
                                    ]);
        }else{
            return redirect()->route('customer#home')
                             ->with(['warning' => 'An error occurred. Please try again after 1min.']);
        }
    }

    public function paymentSuccess(Request $req){
        $data = $req->all();
        $id = $data['RequestID'];
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

        if ($order) {
            return redirect()->route('customer#orderSummary', $order->id)->with([
                'success' => "<p>! Your movie ticket buying  successfully made and Confirmation mail will sent within an hour.</p>
                    <p>! ရုပ်ရှင်လက်မှတ်ဝယ်ယူမှုအောင်မြင်ပါသည်.ကျေးဇူးတင်ပါသည်</p>"
            ]);
        }else{
            return redirect()->route('customer#home')
                     ->with(['warning' => 'An error occurred. Please try again.']);
        }
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
