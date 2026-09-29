<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\SeatPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SeatController extends Controller
{
    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->count==1) {
            Seat::create($data);
        }else{
            for ($i=0; $i < $request->count; $i++) {
                $data['name']=$request->name.$i+1;
                Seat::create($data);
            }
        }
        Cache::forget("hall{$request->hall_id}_seat");
        return back()->with(['success'=>'Seat create successful.']);
    }

    public function view($id){
        $seat=Cache::rememberForever("seat{$id}", function () use($id) {
            return Seat::select('seats.*','halls.name as hall_name','seat_plans.name as seat_plan_name','seat_plans.seat_color')
                ->leftJoin('halls','seats.hall_id','halls.id')
                ->leftJoin('seat_plans','seats.seat_plan_id','seat_plans.id')
                ->where('seats.id',$id)
                ->first();
        });
        $seatPlan=SeatPlan::where('active',1)->get();
        return view('main.admin.seat.view',compact('seat','seatPlan'));
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        Seat::where('id',$request->id)->update($data);
        $this->removeSeat($request->id,$request->hall_id);
        return back()->with(['success'=>'Seat update successful.']);
    }

    public function delete(Request $request){
        $seat=Seat::find($request->id);
        $seat->delete();
        $this->removeSeat($request->id,$seat->hall_id);
        return redirect()->route('admin#hallView',$seat->hall_id);
    }

    // private function
    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
            'hall_id'=>'required',
            'seat_plan_id'=>'required',
            'seat_type'=>'required',
            'active'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'hall_id'=>$request->hall_id,
            'seat_plan_id'=>$request->seat_plan_id,
            'seat_type'=>$request->seat_type,
            'active'=>$request->active,
        ];
    }

    private function removeSeat($id,$hall_id){
        Cache::forget("seat{$id}");
        Cache::forget("hall{$hall_id}_seat");
    }
}
