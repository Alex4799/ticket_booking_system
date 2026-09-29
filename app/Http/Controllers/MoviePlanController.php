<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\MoviePlan;
use App\Models\MovieSeat;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Seat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MoviePlanController extends Controller
{
    public function cinemaList(){
        $cinema=Cinema::where('active',1)->get()->map(function($item){
            $item->hall=Hall::select('halls.id','halls.name')->where('cinema_id',$item->id)->get()->toArray();
            return $item->toArray();
        });
        return view('main.admin.moviePlan.cinema',compact('cinema'));
    }

    public function list($id){
        $hall=Cache::rememberForever("hall{$id}", function () use($id) {
            return Hall::select('halls.*','cinemas.name as cinema_name')
                        ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                        ->where('halls.id',$id)
                        ->first();
        });
        $moviePlan=MoviePlan::select('movie_plans.*','movies.name as movie_name')
                        ->leftJoin('movies','movie_plans.movie_id','movies.id')
                        ->where('hall_id',$id)
                        ->orderBy('movie_plans.date','desc')
                        ->orderBy('start_time')
                        ->paginate(10);
        $lastPlan=MoviePlan::where('hall_id',$id)->orderBy('created_at','desc')->first();
        $movie=Cache::rememberForever('movie_active', function () {
            return Movie::where('active',1)->get();
        });
        return view('main.admin.moviePlan.list',compact('hall','moviePlan','movie','lastPlan'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        $dayCount=$request->day_count;
        $date=Carbon::createFromFormat('Y-m-d',$request->date);
        $startTime=$request->start_time;
        $endTime=$request->end_time;
        $moveSeat=[];
        $hall_id=$request->hall_id;
        $seat=Cache::rememberForever("hall{$hall_id}_seat", function () use($hall_id) {
            return Seat::select('seats.*','seat_plans.name as seat_plan_name','seat_plans.seat_color as seat_color','seat_plans.price')
                    ->leftJoin('seat_plans','seats.seat_plan_id','seat_plans.id')
                    ->where('hall_id',$hall_id)
                    ->get();
        });
        DB::beginTransaction();
        try {
            for ($i=0; $i < $dayCount; $i++) {
                $currentDate=$date->copy()->addDays($i)->format('Y-m-d');
                $conflitPlan=MoviePlan::where('date',$currentDate)
                                ->where('active',1)
                                ->where('hall_id',$hall_id)
                                ->where(function($query)use($startTime,$endTime){
                                    $query->where('end_time','>',$startTime)
                                        ->where('start_time','<',$endTime);
                                })->exists();
                if ($conflitPlan) {
                    DB::rollback();
                    return back()->with(['danger'=>"You can't create between another movie plan. $currentDate already have movie plan on this time"]);
                }
                $data['date']=$currentDate;
                $moviePlan=MoviePlan::create($data);
                foreach ($seat as $item) {
                    $moveSeat[]=[
                        'movie_plan_id'=>$moviePlan->id,
                        'hall_id'=>$item->hall_id,
                        'seat_id'=>$item->id,
                        'price'=>$item->price,
                        'created_at'=>now(),
                        'updated_at'=>now(),
                    ];
                }
            }
            MovieSeat::insert($moveSeat);
            DB::commit();
            return back()->with(['success'=>'Movie plan create successful.']);
        } catch (\Exception $e) {
            logger($e);
            DB::rollback();
            return back()->with(['danger'=>'Something is wrong. Please try again.']);
        }

    }

    public function view($id){
        $moviePlan=Cache::remember("movie_plan_{$id}",now()->addDay(), function () use($id) {
            return MoviePlan::select('movie_plans.*','movies.name as movie_name','movies.run_time','movies.cover','movies.poster',
                            'halls.name as hall_name','halls.seat_column','cinemas.name as cinema_name')
                            ->where('movie_plans.id',$id)
                            ->leftJoin('movies','movie_plans.movie_id','movies.id')
                            ->leftJoin('halls','movie_plans.hall_id','halls.id')
                            ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                            ->first();
        });
        $movieSeat=MovieSeat::leftJoin('seats','movie_seats.seat_id','seats.id')
                        ->leftJoin('seat_plans','seats.seat_plan_id','seat_plans.id')
                        ->where('movie_seats.movie_plan_id',$id);

        $seatPlan=(clone $movieSeat)->select('seat_plans.name','seat_plans.id','seat_plans.seat_color',
                        DB::raw('COUNT(seats.id) as seat_count'),'movie_seats.price')
                        ->groupBy('movie_seats.price')
                        ->get();

        $movieSeat=$movieSeat->select('movie_seats.*','seats.name',
                        'seat_plans.seat_color','seats.seat_type','seats.active','seat_plans.name as seat_plan_name')
                        ->get();

        $movie=Cache::rememberForever('movie_active', function () {
            return Movie::where('active',1)->get();
        });

        $payment=Cache::rememberForever('payment_active', function () {
            return Payment::where('payments.active',1)->get();
        });

        $order=Order::where('movie_plan_id',$id)->get();
        $activeOrder=$order->where('status',1);

        $sellingDetail=[
            'totalPromoPrice'=>$activeOrder->sum('promotion_price'),
            'totalSoldSeat'=>$activeOrder->sum('seat_count'),
            'totalSoldPrice'=>$activeOrder->sum('total_price'),
        ];
        $sellingSeat=[];

        foreach ($activeOrder as $item) {
            foreach (json_decode($item->seats) as $seat) {
                if (isset($sellingSeat[$seat->seat_plan])) {
                    $sellingSeat[$seat->seat_plan]['sold_seat']+=1;
                }else{
                    $sellingSeat[$seat->seat_plan]=[
                        'seat_plan'=>$seat->seat_plan,
                        'seat_price'=>$seat->seat_price,
                        'sold_seat'=>1,
                    ];
                }
            }
        }

        return view('main.admin.moviePlan.view',compact('moviePlan','movieSeat','seatPlan','movie','order','sellingDetail','sellingSeat','payment'));
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        $date=Carbon::createFromFormat('Y-m-d',$request->date)->format('Y-m-d');
        $data['date']=$date;
        $conflitPlan=MoviePlan::where('date',$date)
                        ->where('active',1)
                        ->where('hall_id',$request->hall_id)
                        ->where('id','!=',$request->id)
                        ->where(function($query)use($request){
                            $query->where('end_time','>',$request->start_time)
                                ->where('start_time','<',$request->end_time);
                        })->exists();
        if ($conflitPlan) {
            return back()->with(['danger'=>"You can't set movie plan between exist movie plans."]);
        }
        MoviePlan::where('id',$request->id)->update($data);
        $this->removeMoviePlan($request->id);
        return back()->with(['success'=>"Movie Plan update successful."]);
    }

    public function delete(Request $request){
        $moviePlan=Cache::rememberForever("movie_plan_{$request->id}", function () use($request) {
            return MoviePlan::select('movie_plans.*','movies.name as movie_name',
                            'halls.name as hall_name','halls.seat_column')
                            ->where('movie_plans.id',$request->id)
                            ->leftJoin('movies','movie_plans.movie_id','movies.id')
                            ->leftJoin('halls','movie_plans.hall_id','halls.id')
                            ->first();
        });
        if ($moviePlan->active!=0) {
            return back()->with(['danger'=>'If you want to delete this , movie plan need not active']);
        }
        MovieSeat::where('movie_plan_id',$request->id)->delete();
        MoviePlan::where('id',$request->id)->delete();
        $this->removeMoviePlan($request->id);
        return redirect()->route('admin#moviePlanList',$moviePlan->hall_id)->with(['success'=>'Movie plan delete successful.']);
    }

    public function viewMovieSeat($id){
        $movieSeat=MovieSeat::select('movie_seats.*','halls.name as hall_name','movie_plans.date','movie_plans.start_time',
                        'seats.name as seat_name','seat_plans.name as seat_plan_name')
                        ->leftJoin('movie_plans','movie_seats.movie_plan_id','movie_plans.id')
                        ->leftJoin('halls','movie_seats.hall_id','halls.id')
                        ->leftJoin('seats','movie_seats.seat_id','seats.id')
                        ->leftJoin('seat_plans','seats.seat_plan_id','seat_plans.id')
                        ->where('movie_seats.id',$id)
                        ->first();
        return view('main.admin.movieSeat.view',compact('movieSeat'));
    }

    public function updateMovieSeat(Request $request){
        Validator::make($request->all(),[
            'price'=>'required',
            'status'=>'required'
        ])->validate();
        MovieSeat::where('id',$request->id)->update(['price'=>$request->price,'status'=>$request->status]);
        return back()->with(['success'=>'Movie seat update successful.']);
    }

    public function replacePrice(Request $request){
        MovieSeat::leftJoin('seats','movie_seats.seat_id','seats.id')
                ->where('movie_plan_id',$request->id)
                ->where('seats.seat_plan_id',$request->seat_plan)
                ->update(['price'=>$request->price]);
        return back()->with(['success'=>'Seat price replace successful.']);
    }

    // private function

    private function validation($request){
        Validator::make($request->all(),[
            'movie_id'=>'required',
            'hall_id'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
            'clean_time'=>'required',
            'date'=>'required',
            'online_book'=>'required',
            'online_buy'=>'required',
            'active'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'movie_id'=>$request->movie_id,
            'hall_id'=>$request->hall_id,
            'start_time'=>$request->start_time,
            'end_time'=>$request->end_time,
            'clean_time'=>$request->clean_time,
            'online_book'=>$request->online_book,
            'online_buy'=>$request->online_buy,
            'note'=>$request->note,
            'active'=>$request->active,
        ];
    }

    private function removeMoviePlan($id){
        Cache::forget("movie_plan_{$id}");
    }
}
