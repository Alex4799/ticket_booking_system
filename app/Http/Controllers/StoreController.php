<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoreItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function list(){
        $store=Store::select('stores.*','users.name as manager_name','cinemas.name as cinema_name')
                    ->when(request('search_key'),function($query){
                        $query->where('stores.name','like','%'.request('search_key').'%');
                    })
                    ->leftJoin('users','stores.manager','users.id')
                    ->leftJoin('cinemas','stores.cinema_id','cinemas.id')
                    ->paginate(20);
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        $storeManager=User::where('position','manager')->orWhere('position','store-manager')->get();

        return view('main.admin.store.list',compact('store','cinema','storeManager'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        $store=Store::create($data);
        $this->removeCache($store->id);
        return redirect()->route('admin#storeView',$store->id)->with(['success'=>'Store create successful.']);
    }

    public function view($id){
        $store=Cache::rememberForever("store_{$id}", function () use($id) {
            return Store::select('stores.*','users.name as manager_name','cinemas.name as cinema_name')
                        ->leftJoin('users','stores.manager','users.id')
                        ->leftJoin('cinemas','stores.cinema_id','cinemas.id')
                        ->where('stores.id',$id)
                        ->first();
        });
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        $storeManager=User::where('position','manager')->orWhere('position','store-manager')->get();

        $storeItem=StoreItem::where('store_id',$id)->get();

        $product=Cache::rememberForever('product_active', function () {
            return Product::select('products.*','product_categories.name as category_name')
                        ->leftJoin('product_categories','products.category_id','product_categories.id')
                        ->where('active',1)
                        ->get();
        });

        $supplier=Cache::rememberForever('supplier', function () {
            return Supplier::get();
        });

        return view('main.admin.store.view',compact('store','cinema','storeManager','storeItem','product','supplier'));
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        Store::where('id',$request->id)->update($data);
        $this->removeCache($request->id);
        return back()->with(['success'=>'Store update successful.']);
    }

    public function delete(Request $request){
        Store::where('id',$request->id)->delete();
        $this->removeCache($request->id);
        return redirect()->route('admin#storeList')->with(['success'=>'Store delete successful.']);
    }

    // private function
    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
            'manager'=>'required',
            'contact'=>'required',
            'cinema_id'=>'required',
            'location'=>'required',
            'active'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'manager'=>$request->manager,
            'contact'=>$request->contact,
            'cinema_id'=>$request->cinema_id,
            'location'=>$request->location,
            'active'=>$request->active,
        ];
    }

    private function removeCache($id){
        Cache::forget("store_{$id}");
        Cache::forget('store_active');
    }

}
