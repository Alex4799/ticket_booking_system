<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PromotionController extends Controller
{
    public function list(){
        $promotion=Promotion::select('promotions.*','cinemas.name as cinema_name')
                            ->leftJoin('cinemas','promotions.cinema_id','cinemas.id')
                            ->paginate(10);
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        $hall=Cache::rememberForever('hall_active', function () {
            return Hall::select('halls.*','cinemas.name as cinema_name')
                        ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                        ->where('halls.active',1)
                        ->get();
        });
        $movie=Cache::rememberForever('movie_active', function () {
            return Movie::where('active',1)->get();
        });
        return view('main.admin.promotion.list',compact('promotion','cinema','hall','movie'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->hasFile('image')) {
            $data['image']=$this->storeImage($request);
        }
        $promotion=Promotion::create($data);
        return redirect()->route('admin#promotionView',$promotion->id)->with(['success'=>'Promotion create successful.']);
    }

    public function view($id){
        $promotion=Cache::rememberForever("promotion{$id}", function () use($id) {
            return Promotion::select('promotions.*','cinemas.name as cinema_name','halls.name as hall_name',
                            'movies.name as movie_name')
                            ->leftJoin('cinemas','promotions.cinema_id','cinemas.id')
                            ->leftJoin('halls','promotions.hall_id','halls.id')
                            ->leftJoin('movies','promotions.movie_id','movies.id')
                            ->where('promotions.id',$id)
                            ->first();
        });
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        $hall=Cache::rememberForever('hall_active', function () {
            return Hall::select('halls.*','cinemas.name as cinema_name')
                        ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                        ->where('halls.active',1)
                        ->get();
        });
        $movie=Cache::rememberForever('movie_active', function () {
            return Movie::where('active',1)->get();
        });
        return view('main.admin.promotion.view',compact('promotion','cinema','hall','movie'));
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->hasFile('image')) {
            $oldImage=Promotion::find($request->id)->image;
            Storage::delete('promotion/'.$oldImage);
            $data['image']=$this->storeImage($request);
        }
        Promotion::where('id',$request->id)->update($data);
        $this->removePromotion($request->id);
        return back()->with(['success'=>'Promotion update successful.']);
    }

    public function delete(Request $request){
        $promotion=Promotion::find($request->id);
        Storage::delete('promotion/'.$promotion->image);
        $promotion->delete();
        $this->removePromotion($request->id);
        return redirect()->route('admin#promotionList')->with(['danger'=>'Promotion delete successful.']);
    }

    public function checkPromotion(Request $request){
        $currentDate=now()->toDateString();
        $promotion=Promotion::where('promo_code',$request->promoCode)
                            ->where(function($query) use($request){
                                $query->where('cinema_id',0)
                                    ->orWhere('cinema_id',$request->cinema_id);
                            })->where(function($query) use($request){
                                $query->where('hall_id',0)
                                    ->orWhere('hall_id',$request->hall_id);
                            })->where(function($query) use($request){
                                $query->where('movie_id',0)
                                    ->orWhere('movie_id',$request->movie_id);
                            })->where('start_date','<=',$currentDate)
                            ->where('end_date','>=',$currentDate)
                            ->where('active',1)
                            ->first();
        if (isset($promotion)) {
            if ($promotion->max_count) {
                if ($promotion->max_count<=$promotion->used_count) {
                    $data=[
                        'status'=>'failed',
                    ];
                    return response()->json($data, 200);
                }
            }
            $data=[
                'status'=>'success',
                'data'=>$promotion->toArray(),
            ];
        }else{
            $data=[
                'status'=>'failed',
            ];
        }
        return response()->json($data, 200);
    }

    //customer

    public function list_customer(){
        $promotion=Cache::remember('promotion_active', now()->addMinutes(10), function () {
            return Promotion::where('active',1)->get();
        });
        return view('main.customer.promotion.list',compact('promotion'));
    }

    public function view_customer($id){
        $promotion=Cache::remember("promotion{$id}",now()->addMinutes(30), function () use($id) {
            return Promotion::select('promotions.*','cinemas.name as cinema_name','halls.name as hall_name',
                            'movies.name as movie_name')
                            ->leftJoin('cinemas','promotions.cinema_id','cinemas.id')
                            ->leftJoin('halls','promotions.hall_id','halls.id')
                            ->leftJoin('movies','promotions.movie_id','movies.id')
                            ->where('promotions.id',$id)
                            ->first();
        });
        return view('main.customer.promotion.view',compact('promotion'));
    }

    // private function
    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
            'description'=>'required',
            'promo_code'=>'required',
            'cinema_id'=>'required',
            'start_date'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'description'=>$request->description,
            'promo_code'=>$request->promo_code,
            'cinema_id'=>$request->cinema_id,
            'hall_id'=>$request->hall_id,
            'movie_id'=>$request->movie_id,
            'percentage'=>$request->percentage,
            'amount'=>$request->amount,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'max_count'=>$request->max_count,
            'active'=>$request->active,
            'feature'=>$request->feature,
        ];
    }

    private function storeImage($request){
        $imageName=uniqid().$request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('promotion',$imageName);
        return $imageName;
    }

    private function removePromotion($id){
        Cache::forget("promotion{$id}");
        Cache::forget("promotion_active");
    }
}
