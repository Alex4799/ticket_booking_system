<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Store;
use App\Models\StoreItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function list(){
        $baseQuery=Purchase::when(request('start_date'),function($query){
                                $query->where('purchases.created_at','>=',request('start_date').' 00:00:00');
                            })->when(request('end_date'),function($query){
                                $query->where('purchases.created_at','<=',request('end_date'). ' 23:59:59');
                            });

        $purchase=(clone $baseQuery)->when(request('search_key'),function($query){
                                        $query->where('purchases.id',request('search_key'));
                                    })->when(request('status')!=null,function($query){
                                        $query->where('purchases.status',request('status'));
                                    })->when(request('product_name'),function($query){
                                        $query->where('product_name',request('product_name'));
                                    })
                                    ->leftJoin('suppliers','purchases.supplier_id','suppliers.id')
                                    ->leftJoin('stores','purchases.store_id','stores.id')
                                    ->select('purchases.*','suppliers.name as supplier_name','stores.name as store_name')
                                    ->orderBy('created_at','desc')
                                    ->paginate(20);

        $summary=(clone $baseQuery)->when(request('search_key'),function($query){
                                        $query->where('purchases.id',request('search_key'));
                                    })->when(request('status')!=null,function($query){
                                        $query->where('purchases.status',request('status'));
                                    })->when(request('product_name'),function($query){
                                        $query->where('product_name',request('product_name'));
                                    })
                                ->select('status',DB::raw('SUM(purchases.total_price) as total_price'),
                                    DB::raw('COUNT(purchases.id) as total_count'))
                                ->groupBy('purchases.status')
                                ->get();

        $productFilter=(clone $baseQuery)->distinct()->pluck('purchases.product_name');

        $product=Cache::rememberForever('product_active', function () {
            return Product::select('products.*','product_categories.name as category_name')
                        ->leftJoin('product_categories','products.category_id','product_categories.id')
                        ->where('active',1)
                        ->get();
        });

        $supplier=Cache::rememberForever('supplier', function () {
            return Supplier::get();
        });

        $store=Cache::rememberForever("store_active", function () {
            return Store::select('stores.*','users.name as manager_name','cinemas.name as cinema_name')
                        ->leftJoin('users','stores.manager','users.id')
                        ->leftJoin('cinemas','stores.cinema_id','cinemas.id')
                        ->where('stores.active',1)
                        ->get();
        });

        return view('main.admin.purchase.list',compact('purchase','summary','productFilter','product','supplier','store'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        $purchase=Purchase::create($data);
        return redirect()->route('admin#purchaseView',$purchase->id)->with(['success'=>'Purchase create successful.']);
    }

    public function view($id){
        $purchase=Cache::remember("purchase_{$id}", now()->addDay(), function () use($id) {
            return Purchase::select('purchases.*','suppliers.name as supplier_name','stores.name as store_name','products.image as product_image')
                            ->where('purchases.id',$id)
                            ->leftJoin('suppliers','purchases.supplier_id','suppliers.id')
                            ->leftJoin('stores','purchases.store_id','stores.id')
                            ->leftJoin('products','purchases.product_id','products.id')
                            ->first();
        });
        $product=Cache::rememberForever('product_active', function () {
            return Product::select('products.*','product_categories.name as category_name')
                        ->leftJoin('product_categories','products.category_id','product_categories.id')
                        ->where('active',1)
                        ->get();
        });

        $supplier=Cache::rememberForever('supplier', function () {
            return Supplier::get();
        });

        $store=Cache::rememberForever("store_active", function () {
            return Store::select('stores.*','users.name as manager_name','cinemas.name as cinema_name')
                        ->leftJoin('users','stores.manager','users.id')
                        ->leftJoin('cinemas','stores.cinema_id','cinemas.id')
                        ->where('stores.active',1)
                        ->get();
        });
        return view('main.admin.purchase.view',compact('purchase','product','supplier','store'));
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        $purchase=Purchase::where('id',$request->id)->update($data);
        $this->removeCache($request->id);
        return back()->with(['success'=>'Purchase update successful.']);
    }

    public function delete(Request $request){
        Purchase::where('id',$request->id)->delete();
        $this->removeCache($request->id);
        return redirect()->route('admin#purchaseList')->with(['danger'=>'Purchase delete successful.']);
    }

    public function changeStatus($id,$status){
        DB::beginTransaction();
        $purchase=Cache::remember("purchase_{$id}", now()->addDay(), function () use($id) {
            return Purchase::select('purchases.*','suppliers.name as supplier_name','stores.name as store_name','products.image as product_image')
                            ->where('purchases.id',$id)
                            ->leftJoin('suppliers','purchases.supplier_id','suppliers.id')
                            ->leftJoin('stores','purchases.store_id','stores.id')
                            ->leftJoin('products','purchases.product_id','products.id')
                            ->first();
        });
        if ($status==1 && $purchase->status!=1) {
            Purchase::where('id',$id)->lockForUpdate()->update(['status'=>1]);
            $storeItem=StoreItem::where('product_id',$purchase->product_id)
                                ->where('store_id',$purchase->store_id)
                                ->lockForUpdate()
                                ->first();

            if (isset($storeItem)) {
                $storeItem->qty+=$purchase->qty;
                $storeItem->purchase_price=$purchase->price;
                $storeItem->save();
            }else{
                StoreItem::create([
                    'product_id'=>$purchase->product_id,
                    'product_name'=>$purchase->product_name,
                    'qty'=>$purchase->qty,
                    'purchase_price'=>$purchase->price,
                    'selling_price'=>$purchase->price,
                    'profit'=>0,
                    'instock_level'=>10,
                    'store_id'=>$purchase->store_id,
                    'type'=>0,
                    'selling_type'=>0,
                ]);
            }
        }else{
            Purchase::where('id',$id)->lockForUpdate()->update(['status'=>$status]);
        }
        DB::commit();
        $this->removeCache($id);
        return back()->with(['success'=>'Change status successful.']);
    }

    // private function
    private function validation($request){
        Validator::make($request->all(),[
            'supplier_id'=>'required',
            'product'=>'required',
            'store_id'=>'required',
            'qty'=>'required',
            'price'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        $product=json_decode($request->product);
        return [
            'supplier_id'=>$request->supplier_id,
            'product_id'=>$product->id,
            'product_name'=>$product->name,
            'store_id'=>$request->store_id,
            'qty'=>$request->qty,
            'price'=>$request->price,
            'total_price'=>$request->qty*$request->price,
        ];
    }

    private function removeCache($id){
        Cache::forget("purchase_{$id}");
    }
}
