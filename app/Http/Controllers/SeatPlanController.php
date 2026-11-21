<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\SeatPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SeatPlanController extends Controller
{
    public function list(){
        $seatPlan=SeatPlan::when(request('search_key'),function($query){
                        $query->where('name','like','%'.request('search_key').'%');
                    })->paginate(10);

        return view('main.admin.seatPlan.list',compact('seatPlan'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->hasFile('image')) {
            $imageName=uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('seat_plan',$imageName);
            $data['image']=$imageName;
        }
        $seatPlan=SeatPlan::create($data);
        return redirect()->route('admin#seatPlanView',$seatPlan->id)->with(['success'=>'Seat plan create successful.']);
    }

    public function view($id){
        $seatPlan=$this->getSeatPlan($id);
        $seatCount=Seat::where('seat_plan_id',$id)->where('active',1)->count();
        return view('main.admin.seatPlan.view',compact('seatPlan','seatCount'));
    }

    public function update(Request $request){
        $seatPlan=$this->getSeatPlan($request->id);
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->hasFile('image')) {
            $imageName=uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('seat_plan',$imageName);
            $data['image']=$imageName;
            if (isset($seatPlan->image)) {
                Storage::delete('seat_plan/'.$seatPlan->image);
            }
        }
        SeatPlan::where('id',$request->id)->update($data);
        $this->removeSeatPlanCache($request->id);
        return back()->with(['success'=>'Seat plan update successful.']);
    }

    public function delete(Request $request){
        SeatPlan::where('id',$request->id)->delete();
        $this->removeSeatPlanCache($request->id);
        return redirect()->route('admin#seatPlanList');
    }


    // private function

    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
            'price'=>'required',
            'seat_color'=>'required',
            'image'=>'image|mimes:jpeg,png,jpg,webp',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'price'=>$request->price,
            'seat_color'=>$request->seat_color,
            'active'=>$request->active,
        ];
    }

    private function getSeatPlan($id){
        return Cache::rememberForever("seat_plan{$id}", function () use($id) {
            return SeatPlan::find($id);
        });
    }

    private function removeSeatPlanCache($id){
        Cache::forget("seat_plan{$id}");
    }

}
