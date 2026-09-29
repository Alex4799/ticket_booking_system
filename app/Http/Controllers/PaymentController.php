<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function list(){
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        $payment=Payment::select('payments.*','cinemas.name as cinema_name')
                        ->leftJoin('cinemas','payments.cinema_id','cinemas.id')
                        ->paginate(10);
        return view('main.admin.payment.list',compact('payment','cinema'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->hasFile('image')) {
            $data['image']=$this->storeImage($request);
        }
        $payment=Payment::create($data);
        Cache::forget('payment_active');
        return redirect()->route('admin#paymentView',$payment->id)->with(['success'=>'Payment method create successful.']);
    }

    public function view($id){
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        $payment=Cache::rememberForever("payment{$id}", function () use($id) {
            return Payment::select('payments.*','cinemas.name as cinema_name')
                        ->leftJoin('cinemas','payments.cinema_id','cinemas.id')
                        ->where('payments.id',$id)
                        ->first();
        });
        return view('main.admin.payment.view',compact('cinema','payment'));
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->hasFile('image')) {
            $oldImage=Payment::where('id',$request->id)->first()->image;
            if ($oldImage) {
                Storage::delete('payment/'.$oldImage);
            }
            $data['image']=$this->storeImage($request);
        }
        Payment::where('id',$request->id)->update($data);
        $this->removePayment($request->id);
        return back()->with(['success'=>'Payment method update successful.']);
    }

    public function delete(Request $request){
        $payment=Payment::find($request->id);
        if ($payment->image) {
            Storage::delete('payment/'.$payment->image);
        }
        $payment->delete();
        $this->removePayment($request->id);
        return redirect()->route('admin#paymentList')->with(['danger'=>'Payment method delete successful.']);
    }

    // private function
    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
            'username'=>'required',
            'number'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'username'=>$request->username,
            'number'=>$request->number,
            'cinema_id'=>$request->cinema_id,
            'active'=>$request->active,
        ];
    }

    private function storeImage($request){
        $imageName=uniqid().$request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('payment',$imageName);
        return $imageName;
    }

    private function removePayment($id){
        Cache::forget("payment{$id}");
        Cache::forget('payment_active');
    }
}
