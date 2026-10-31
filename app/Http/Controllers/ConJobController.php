<?php

namespace App\Http\Controllers;

use App\Events\SeatStatusEvent;
use App\Models\Movie;
use App\Models\MoviePlan;
use App\Models\MovieSeat;
use App\Models\Order;
use App\Models\Promotion;
use App\Models\WebsiteInfo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ConJobController extends Controller
{
    public function chackMovie(){
        $current_date=now()->toDateString();
        Movie::where('end_date','<',$current_date)
            ->where('active',1)
            ->update(['active'=>0]);
        return response()->json(['status'=>'success'], 200);
    }

    public function checkMoviePlan(){
        $current_date=now()->toDateString();
        $currentTime=now()->format('H:i');
        MoviePlan::where('active',1)
                ->where('date','<=',$current_date)
                ->where('end_time','<',$currentTime)
                ->update(['active'=>0]);
        return response()->json(['status'=>'success'], 200);
    }

    public function checkMovieSeat(){
        $minutesAgo = Carbon::now()->subMinutes(2);
        $expiredSeats = MovieSeat::select('id','movie_plan_id')
            ->where('status', 1)
            ->where('updated_at', '<=', $minutesAgo)
            ->get();

        if ($expiredSeats->isNotEmpty()) {
            $seatIds=[];
            $seatForEvent=[];
            foreach ($expiredSeats as $item) {
                $seatIds[]=$item->id;
                $seatForEvent[$item->movie_plan_id][]=$item->id;
            }
            DB::beginTransaction();
            try {
                MovieSeat::whereIn('id', $seatIds)->update(['status' => 0]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollback();
                logger($e);

                return response()->json([
                    'status'  => 'fail',
                    'message' => $e->getMessage(),
                ], 500);
            }
            foreach ($seatForEvent as $index=>$seat) {
                event(new SeatStatusEvent($index,$seat,0));
            }
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Change seat status successful.',
            'count'   => $expiredSeats->count(),
        ], 200);
    }

    public function checkOrder(){
        $current_date=now()->toDateString();
        $websiteInfo=Cache::rememberForever('website_info', function () {
                        return WebsiteInfo::where('id',1)->first();
                    });
        $minutes=Carbon::now()->addMinutes($websiteInfo->default_order_reject_min)->format('H:i');
        // dd($minutes);
        $overDueBooking=Order::select('id','movie_plan_id','seats')
                        ->where('status',4)->where(function($query) use($current_date,$minutes) {
                            $query->where('date','<',$current_date)
                            ->orWhere(function($q) use($current_date,$minutes) {
                                $q->where('date',$current_date)
                                ->where('show_time','<=',$minutes);
                            });
                        })->get();
        if ($overDueBooking->isNotEmpty()) {
            $orderId=[];
            $allSeatId=[];
            $seatIds=[];
            foreach ($overDueBooking as $item) {
                $orderId[]=$item->id;
                foreach (json_decode($item->seats) as $seat) {
                    $seatIds[$item->movie_plan_id][]=$seat->seat_id;
                    $allSeatId[]=$seat->seat_id;
                }
            }
            DB::beginTransaction();
            try {
                MovieSeat::whereIn('id',$allSeatId)->update(['status'=>0]);
                Order::whereIn('id',$orderId)->update(['status'=>3]);
                DB::commit();
                foreach ($seatIds as $key => $value) {
                    event(new SeatStatusEvent($key,$value,0));
                }
                return response()->json(['status'=>'success'], 200);
            } catch (Exception $e) {
                DB::rollback();
                logger($e);

                return response()->json([
                    'status'  => 'fail',
                    'message' => $e->getMessage(),
                ], 500);
            }
        }
        return response()->json(['status'=>'success'], 200);
    }

    public function checkPromotion(){
        $current_date=now()->toDateString();
        Promotion::where('end_date','<',$current_date)
            ->where('active',1)
            ->update(['active'=>0]);
        return response()->json(['status'=>'success'], 200);
    }
}
