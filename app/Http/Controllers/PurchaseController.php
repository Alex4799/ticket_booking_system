<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function list(){
        $baseQuery=Purchase::when(request('start_date'),function($query){
                                $query->where('purchase.created_at','>=',request('start_date'));
                            })->when(request('end_date'),function($query){
                                $query->where('purchase.created_at','<=',request('end_date'));
                            })
                            ->leftJoin('suppliers','purchases.supplier_id','suppliers.id')
                            ->leftJoin('stores','purchases.store_id','stores.id');

        $purchase=(clone $baseQuery)->when(request('search_key'),function($query){
                                        $query->where('purchases.id',request('search_key'));
                                    })->when(request('status'),function($query){
                                        $query->where('purchase.status',request('status'));
                                    })->when(request('product_name'),function($query){
                                        $query->where('product_name',request('product_name'));
                                    })->select('purchases.*','suppliers.name as supplier_name','stores.name as store_name')
                                    ->paginate(20);

        $summary=(clone $baseQuery)->select('status',DB::raw('SUM(purchases.total_price) as total_price'),
                                    DB::raw('COUNT(purchases.id) as total_count'))
                                ->groupBy('purchases.status')
                                ->get();

        $productFilter=(clone $baseQuery)->select('product_name')->get();

        $product=Cache::rememberForever('product_active', function () {
            return Product::select('products.*','categories.name as category_name')
                        ->leftJoin('categories','products.category_id','categories.id')
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
}
