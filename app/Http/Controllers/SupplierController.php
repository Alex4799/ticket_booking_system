<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function list(){
        $supplier=Supplier::when(request('search_key'),function($query){
                            $query->where('name','like','%'.request('search_key').'%');
                        })->paginate(10);
        return view('main.admin.supplier.list',compact('supplier'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        $supplier=Supplier::create($data);
        $this->removeCache();
        return back()->with(['success'=>'Supplier create successful.']);
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        Supplier::where('id',$request->id)->update($data);
        $this->removeCache();
        return back()->with(['success'=>'Supplier update successful.']);
    }

    public function delete(Request $request){
        Supplier::where('id',$request->id)->delete();
        $this->removeCache();
        return back()->with(['success'=>'Supplier delete successful.']);
    }

    // private function 
    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
            'contact'=>'required',
            'address'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'contact'=>$request->contact,
            'address'=>$request->address,
        ];
    }

    private function removeCache(){
        Cache::forget('supplier');
    }
}
