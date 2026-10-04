<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Store;
use App\Models\StoreItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function list(){
        $inventory=Inventory::when(request('search_key'),function($query){
                                $query->where('inventories.id',request('search_key'));
                            })->when(request('filter')!=null,function($query){
                                $query->where('inventories.status',request('filter'));
                            })
                            ->orderBy('created_at','desc')
                            ->paginate(20)
                            ->withQueryString();

        $store=Cache::rememberForever("store_active", function () {
            return Store::select('stores.*','users.name as manager_name','cinemas.name as cinema_name')
                        ->leftJoin('users','stores.manager','users.id')
                        ->leftJoin('cinemas','stores.cinema_id','cinemas.id')
                        ->where('stores.active',1)
                        ->get();
        });

        $storeItem=StoreItem::select('store_items.id','store_items.product_name','store_items.qty','stores.name as store_name')
                            ->where('store_items.active',1)
                            ->leftJoin('stores','store_items.store_id','stores.id')
                            ->when(Auth::user()->position=='store-manager',function($query){
                                $query->where('stores.cinema_id',Auth::user()->location);
                            })
                            ->where('store_items.type','!=',1)
                            ->get();

        return view('main.admin.inventory.list',compact('inventory','store','storeItem'));
    }

    public function create(Request $request){
        Validator::make($request->all(),[
            'product_id' => 'required|exists:store_items,id',
            'receive_store_id' => 'required|exists:stores,id',
            'qty' => 'required|integer|min:1',
        ])->validate();
        DB::beginTransaction();
        try {
            $storeItem=StoreItem::lockForUpdate()->find($request->product_id);
            if ($storeItem->qty<$request->qty) {
                DB::rollback();
                return back()->with(['warning'=>'Qty is over max amount.']);
            }
            if ($storeItem->store_id==$request->receive_store_id) {
                DB::rollback();
                return back()->with(['warning'=>'Send store and receive store are same. Try again.']);
            }
            $data=[
                'send_store_id'=>$storeItem->store_id,
                'store_item_id'=>$storeItem->id,
                'product_name'=>$storeItem->product_name,
                'receive_store_id'=>$request->receive_store_id,
                'qty'=>$request->qty,
                'price'=>$storeItem->purchase_price,
                'total_price'=>$request->qty*$storeItem->purchase_price,
                'additional_fees'=>$request->additional_fees,
                'status'=>0,
            ];
            $storeItem->decrement('qty',$request->qty);
            $inventory=Inventory::create($data);
            DB::commit();
            return redirect()->route('admin#inventoryView',$inventory->id)->with(['success'=>'Inventory transfer create successful.']);
        } catch (\Exception $e) {
            logger($e);
            DB::rollback();
            return back()->with(['danger'=>'An error occur. Please try again.']);
        }
    }

    public function view($id){
        $inventory=Cache::remember("inventory_{$id}", now()->addHour(), function () use($id) {
            return Inventory::select('inventories.*','products.id as product_id','products.image as product_image','send_store.name as send_store_name','receive_store.name as receive_store_name')
                            ->leftJoin('store_items','inventories.store_item_id','store_items.id')
                            ->leftJoin('products','store_items.product_id','products.id')
                            ->leftJoin('stores as send_store','inventories.send_store_id','send_store.id')
                            ->leftJoin('stores as receive_store','inventories.receive_store_id','receive_store.id')
                            ->where('inventories.id',$id)
                            ->first();
        });

        return view('main.admin.inventory.view',compact('inventory'));
    }

    public function change($id,$status){
        DB::beginTransaction();
        try {
            $inventory=Inventory::select('inventories.*','store_items.product_id')
                                ->leftJoin('store_items','inventories.store_item_id','store_items.id')
                                ->where('inventories.id',$id)
                                ->lockForUpdate()
                                ->first();

            if ($inventory->status==1) {
                DB::rollback();
                return back();
            }
            if ($status==1) {
                $storeItem=StoreItem::where('product_id',$inventory->product_id)
                                    ->where('store_id',$inventory->receive_store_id)
                                    ->lockForUpdate()
                                    ->first();
                if (isset($storeItem)) {
                    $storeItem->increment('qty',$inventory->qty);
                }else{
                    StoreItem::create([
                        'product_id'=>$inventory->product_id,
                        'product_name'=>$inventory->product_name,
                        'qty'=>$inventory->qty,
                        'purchase_price'=>$inventory->price,
                        'selling_price'=>$inventory->price,
                        'profit'=>0,
                        'instock_level'=>10,
                        'store_id'=>$inventory->receive_store_id,
                    ]);
                }
            }else if($status==2){
                StoreItem::where('id',$inventory->store_item_id)->lockForUpdate()->increment('qty',$inventory->qty);
            }
            Inventory::where('id',$id)->update(['status'=>$status]);
            DB::commit();
            $this->removeCache($id);
            return back()->with(['success'=>'Store item status change successful.']);
        } catch (\Exception $e) {
            logger($e);
            DB::rollback();
            return back()->with(['warning'=>'An error occur. Please try again.']);
        }
    }

    public function delete(Request $request){
        DB::beginTransaction();
        try {
            $inventory=Inventory::select('inventories.*')
                                ->lockForUpdate()
                                ->where('inventories.id',$request->id)
                                ->first();
            if ($inventory->status==1) {
                DB::rollback();
                return back();
            }
            if ($inventory->status==0) {
                StoreItem::where('id',$inventory->store_item_id)->lockForUpdate()->increment('qty',$inventory->qty);
            }
            Inventory::where('id',$request->id)->delete();
            DB::commit();
            $this->removeCache($request->id);
            return redirect()->route('admin#inventoryList')->with(['success'=>'Inventory history delete successful.']);
        } catch (Exception $e) {
            logger($e);
            DB::rollback();
            return back()->with(['warning'=>'An error occur. Please try again.']);
        }
    }

    // private function
    private function removeCache($id){
        Cache::forget("inventory_{$id}");
    }
}
