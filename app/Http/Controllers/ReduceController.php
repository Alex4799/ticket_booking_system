<?php

namespace App\Http\Controllers;

use App\Models\DamageProduct;
use App\Models\Reduce;
use App\Models\StoreItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReduceController extends Controller
{
    public function list(){
        $baseQuery=Reduce::when(request('product'),function($query){
                            $query->where('product_name',request('product'));
                        })->when(request('start_date'),function($query){
                            $query->where('reduces.created_at','>=',request('start_date').' 00:00:00');
                        })->when(request('end_date'),function($query){
                            $query->where('reduces.created_at','<=',request('end_date'). ' 23:59:59');
                        })->when(request('search_key'),function($query){
                            $query->where('reduces.id',request('search_key'));
                        });
        $total_price=(clone $baseQuery)->sum('total_price');

        $product=Reduce::distinct()->pluck('reduces.product_name');

        $reduce=(clone $baseQuery)->select('reduces.*','users.name as user_name')
                            ->leftJoin('users','reduces.reduce_by','users.id')
                            ->orderBy('created_at','desc')
                            ->paginate(10)
                            ->withQueryString();

        return view('main.admin.reduce.reduce',compact('total_price','product','reduce'));
    }

    public function delete(Request $request){
        DB::beginTransaction();
        $reduce=Reduce::lockForUpdate()->find($request->id);
        $storeItem=StoreItem::find($reduce->store_item_id);
        if (isset($storeItem)) {
            $storeItem->increment('qty',$reduce->qty);
        }
        $reduce->delete();
        DB::commit();
        return back()->with(['success'=>'Reduce delete successful.']);
    }

    public function list_damage(){
        $baseQuery=DamageProduct::when(request('product'),function($query){
                            $query->where('product_name',request('product'));
                        })->when(request('start_date'),function($query){
                            $query->where('damage_products.created_at','>=',request('start_date').' 00:00:00');
                        })->when(request('end_date'),function($query){
                            $query->where('damage_products.created_at','<=',request('end_date'). ' 23:59:59');
                        })->when(request('search_key'),function($query){
                            $query->where('damage_products.id',request('search_key'));
                        });
        $total_price=(clone $baseQuery)->sum('total_price');

        $product=DamageProduct::distinct()->pluck('damage_products.product_name');

        $damage=(clone $baseQuery)->select('damage_products.*','users.name as user_name')
                            ->leftJoin('users','damage_products.reduce_by','users.id')
                            ->orderBy('created_at','desc')
                            ->paginate(10)
                            ->withQueryString();

        return view('main.admin.reduce.damage',compact('total_price','product','damage'));
    }

    public function delete_damage(Request $request){
        DB::beginTransaction();
        $damage=DamageProduct::lockForUpdate()->find($request->id);
        $storeItem=StoreItem::find($damage->store_item_id);
        if (isset($storeItem)) {
            $storeItem->increment('qty',$damage->qty);
        }
        $damage->delete();
        DB::commit();
        return back()->with(['success'=>'Damage product delete successful.']); 
    }
}
