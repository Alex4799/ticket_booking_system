<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Hall;
use App\Models\Seat;
use App\Models\SeatPlan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HallController extends Controller
{
    public function list(){
        $hall=Hall::select('halls.*','cinemas.name as cinema_name')
                        ->when(request('search_key'),function($query){
                            $query->where('name','like','%'.request('search_key').'%');
                        })
                        ->when(Auth::user()->position=='manager',function($query){
                            $query->where('cinema_id',Auth::user()->location);
                        })
                        ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                        ->paginate(10);
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });

        return view('main.admin.hall.list',compact('hall','cinema'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        $hall=Hall::create($data);
        Cache::forget('hall_active');
        return redirect()->route('admin#hallView',$hall->id);
    }

    public function view($id){
        $hall=$this->getHall($id);
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        $seatPlan=SeatPlan::where('active',1)->get();
        $seat=Cache::rememberForever("hall{$id}_seat", function () use($id) {
            return Seat::select('seats.*','seat_plans.name as seat_plan_name','seat_plans.seat_color as seat_color','seat_plans.price')
                    ->leftJoin('seat_plans','seats.seat_plan_id','seat_plans.id')
                    ->where('hall_id',$id)
                    ->get();
        });
        $summary = $seat->groupBy('seat_plan_name')->map(function ($group) {
                        $first = $group->first();
                        return [
                            'seat_plan_name' => $first->seat_plan_name,
                            'seat_color'     => $first->seat_color,
                            'seat_count'     => $group->count(),
                        ];
                    })->values();
        return view('main.admin.hall.view',compact('hall','cinema','seatPlan','seat','summary'));
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        Hall::where('id',$request->id)->update($data);
        $this->removeHallCache($request->id);
        return back()->with(['success'=>'Hall update successful.']);
    }

    public function delete(Request $request){
        Hall::where('id',$request->id)->delete();
        $this->removeHallCache($request->id);
        return redirect()->route('admin#hallList');
    }

    public function duplicate(Request $request){
        DB::beginTransaction();
        try {
            $originalHall=$this->getHall($request->id);
            $originalSeat=Seat::where('hall_id',$request->id)->get();
            $data=$this->changeFormat($originalHall);
            $data['du_id']=$originalHall->id;
            $newHall=Hall::create($data);
            $now=now();
            $newSeat=$originalSeat->map(function($item) use ($newHall,$now){
                return [
                    'name'=>$item->name,
                    'hall_id'=>$newHall->id,
                    'seat_plan_id'=>$item->seat_plan_id,
                    'seat_type'=>$item->seat_type,
                    'active'=>$item->active,
                    'created_at'=>$now,
                    'updated_at'=>$now
                ];
            })->toArray();
            if (!empty($newSeat)) {
                Seat::insert($newSeat);
            }
            DB::commit();
            return redirect()->route('admin#hallView',$newHall->id);
        } catch (Exception $e) {
            logger($e);
            DB::rollBack();
            return back()->with(['danger'=>'Something is wrong . Please try again']);
        }
    }

    public function replaceSeatPlan(Request $request){
        Validator::make($request->all(),[
            'from'=>'required',
            'to'=>'required|different:from',
            'hall_id'=>'required',
        ],[
            'to.different' => 'The new seat plan must be different from the current seat plan.',
        ])->validate();
        DB::beginTransaction();
        try {
            $seatCount=Seat::where('hall_id',$request->hall_id)
                        ->where('seat_plan_id',$request->from)
                        ->update([
                            'seat_plan_id'=>$request->to,
                            'updated_at'=>now(),
                        ]);
            DB::commit();
            return back()->with(['success'=>"Successfully replaced seat plan for {$seatCount} seat(s)."]);
        } catch (\Exception $e) {
            logger($e);
            DB::rollBack();
            return back()->with(['danger'=>'Something is wrong . Please try again']);
        }
    }

    // private function
    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
            'cinema_id'=>'required',
            'seat_column'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'cinema_id'=>$request->cinema_id,
            'seat_column'=>$request->seat_column,
            'active'=>$request->active,
        ];
    }

    // cache

    private function removeHallCache($id){
        Cache::forget("hall{$id}");
        Cache::forget('hall_active');
    }

    private function getHall($id) {
        return Cache::rememberForever("hall{$id}", function () use($id) {
            return Hall::select('halls.*','cinemas.name as cinema_name')
                        ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                        ->where('halls.id',$id)
                        ->first();
        });
    }
}
