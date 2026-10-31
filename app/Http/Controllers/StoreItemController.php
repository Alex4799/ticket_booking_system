<?php

namespace App\Http\Controllers;

use App\Models\DamageProduct;
use App\Models\Purchase;
use App\Models\Reduce;
use App\Models\StockLog;
use App\Models\StoreItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoreItemController extends Controller
{
    public function view($id){
        $ttl=now()->addDay();
        $storeItem=Cache::remember("store_item_{$id}", $ttl, function () use ($id) {
            return StoreItem::select('store_items.*','products.image')
                            ->leftJoin('products','store_items.product_id','products.id')
                            ->where('store_items.id',$id)
                            ->first();
        });
        return view('main.admin.store.item',compact('storeItem'));
    }

    public function create(Request $request){
        Validator::make($request->all(),[
            'product'=>'required',
            'supplier_id'=>$request->type==1?'':'required',
            'purchase_price'=>'required',
            'selling_price'=>'required',
            'qty'=>$request->type==1?'':'required',
        ])->validate();
        DB::beginTransaction();
        try {
            $product=json_decode($request->product);
            if ($request->type!=1) {
                $purchaseData=$this->changeFormatForPurchase($request);
                Purchase::create($purchaseData);
            }
            $storeItem=StoreItem::where('product_id',$product->id)->where('store_id',$request->store_id)->lockForUpdate()->first();
            if (isset($storeItem)) {
                $storeItem->qty+=$request->qty;
                $storeItem->purchase_price=$request->purchase_price;
                $storeItem->selling_price=$request->selling_price;
                $storeItem->save();
                $this->removeCache($storeItem->id);
            }else{
                $data=$this->changeFormatForStoreItem($request);
                StoreItem::create($data);
            }
            DB::commit();
            return back()->with(['success'=>'Purchase create successful.']);
        } catch (\Exception $e) {
            logger($e);
            DB::rollback();
            return back()->with(['danger'=>'An error occur. Please try again.']);
        }
    }

    public function update(Request $request){
        // dd($request->all());
        Validator::make($request->all(),[
            'selling_price'=>'required',
            'qty'=>$request->type?'':'required',
        ])->validate();
        DB::beginTransaction();
        try {
            $storeItem=StoreItem::where('id',$request->id)->lockForUpdate()->first();
            $data=[
                'qty'=>$request->qty,
                'selling_price'=>$request->selling_price,
                'profit'=>$request->selling_price-$request->purchase_price,
                'instock_level'=>$request->instock_level,
                'type'=>$request->type=='on'?1:0,
                'selling_type'=>$request->selling_type=='on'?1:0,
                'unit'=>$request->unit,
                'barcode'=>$request->barcode,
                'active'=>$request->active,
            ];
            if ($storeItem->selling_price!=$request->selling_price) {
                StockLog::create([
                    'user_id'=>Auth::user()->id,
                    'store_product_id'=>$request->id,
                    'message'=>"Selling price of $storeItem->product_name change $storeItem->selling_price to $request->selling_price."
                ]);
            }
            if ($storeItem->qty!=$request->qty) {
                StockLog::create([
                    'user_id'=>Auth::user()->id,
                    'store_product_id'=>$request->id,
                    'message'=>"Qty of $storeItem->product_name change $storeItem->qty to $request->qty."
                ]);
            }
            StoreItem::where('id',$request->id)->update($data);
            DB::commit();
            $this->removeCache($request->id);
            return back()->with(['success'=>'Store item update successful.']);
        } catch (\Exception $e) {
            logger($e);
            DB::rollback();
            return back()->with(['danger'=>'An error occur. Please try again.']);
        }
    }

    public function damageReduce(Request $request){
        return $this->itemReduce($request,'damage');
    }

    public function reduce(Request $request){
        return $this->itemReduce($request,'normal');
    }

    // private function
    private function changeFormatForPurchase($request){
        $product=json_decode($request->product);
        return [
            'supplier_id'=>$request->supplier_id,
            'product_id'=>$product->id,
            'product_name'=>$product->name,
            'store_id'=>$request->store_id,
            'qty'=>$request->qty,
            'price'=>$request->purchase_price,
            'total_price'=>$request->qty*$request->purchase_price,
            'status'=>1,
        ];
    }

    private function changeFormatForStoreItem($request){
        $product=json_decode($request->product);
        return [
            'product_id'=>$product->id,
            'product_name'=>$product->name,
            'qty'=>$request->qty,
            'purchase_price'=>$request->purchase_price,
            'selling_price'=>$request->selling_price,
            'profit'=>$request->selling_price-$request->purchase_price,
            'instock_level'=>$request->instock_level,
            'store_id'=>$request->store_id,
            'type'=>$request->type??0,
            'selling_type'=>$request->selling_type??0,
            'unit'=>$request->unit,
            'barcode'=>$request->barcode,
            'active'=>$request->active,
        ];
    }

    private function removeCache($id){
        Cache::forget("store_item_{$id}");
    }

    private function itemReduce($request,$status){
        Validator::make($request->all(),[
            'store_item_id'=>'required',
            'qty'=>'required'
        ])->validate();
        DB::beginTransaction();
        try {
            $storeItem=StoreItem::lockForUpdate()->find($request->store_item_id);
            if ($storeItem->qty>=$request->qty) {
                if ($status=='damage') {
                    DamageProduct::create([
                        'store_item_id'=>$storeItem->id,
                        'product_name'=>$storeItem->product_name,
                        'store_id'=>$storeItem->store_id,
                        'qty'=>$request->qty,
                        'total_price'=>$storeItem->purchase_price*$request->qty,
                        'reduce_by'=>Auth::user()->id,
                    ]);
                    $message='Damage store item reduce successful.';
                }else{
                    Reduce::create([
                        'store_item_id'=>$storeItem->id,
                        'product_name'=>$storeItem->product_name,
                        'store_id'=>$storeItem->store_id,
                        'qty'=>$request->qty,
                        'total_price'=>$storeItem->purchase_price*$request->qty,
                        'reduce_by'=>Auth::user()->id,
                    ]);
                    $message='Store item reduce successful.';
                }
                $storeItem->decrement('qty', $request->qty);
                DB::commit();
                $this->removeCache($request->store_item_id);
                return back()->with(['success'=>$message]);
            }else{
                DB::rollback();
                return back()->with(['warning'=>'Qty is not enought to reduce']);
            }
        } catch (\Exception $e) {
            logger($e);
            DB::rollback();
            return back()->with(['danger'=>'An error occur. Please try again']);
        }
    }
}
