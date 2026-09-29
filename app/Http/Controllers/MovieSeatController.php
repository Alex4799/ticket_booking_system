<?php

namespace App\Http\Controllers;

use App\Events\SeatStatusEvent;
use App\Models\MovieSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class MovieSeatController extends Controller
{
    public function changeStatus(Request $request){
        $message=[
            'status'=>'failed',
        ];
        if ($request->status=='select_seat') {
            $isAcquired = Redis::set("movie_seat_{$request->id}", 'locked', 'EX', 300, 'NX');
            if (!$isAcquired) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Seat is already selected by another user.'
                ], 200);
            }
            DB::beginTransaction();
            try {
                $seat=MovieSeat::where('id',$request->id)->where('status',0)->lockForUpdate()->first();
                if (isset($seat)) {
                    $seat->update(['status'=>1]);
                    event(new SeatStatusEvent($seat->movie_plan_id,[$seat->id],1));
                    $message=[
                        'status'=>'success',
                    ];
                    DB::commit();
                }else{
                    DB::rollback();
                    Redis::del("movie_seat_{$request->id}");
                }
            } catch (\Exception $e) {
                logger($e);
                DB::rollback();
                Redis::del("movie_seat_{$request->id}");
                return response()->json($message, 200);
            }
        }else if ($request->status=='unselect_seat') {
            DB::beginTransaction();
            try {
                $seat=MovieSeat::where('id',$request->id)->where('status',1)->lockForUpdate()->first();
                if (isset($seat)) {
                    $seat->update(['status'=>0]);
                    event(new SeatStatusEvent($seat->movie_plan_id,[$seat->id],0));
                    Redis::del("movie_seat_{$request->id}");
                    $message=[
                        'status'=>'success',
                    ];
                    DB::commit();
                }else{
                    DB::rollback();
                }
            } catch (\Exception $e) {
                logger($e);
                DB::rollback();
                return response()->json($message, 200);
            }
        }
        return response()->json($message, 200);
    }
}
