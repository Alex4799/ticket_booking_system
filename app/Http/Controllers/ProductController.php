<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function list(){
        $product=Product::when(request('search_key'),function($query){
                    $query->where('name',request('search_key'));
                })->paginate(20);
        $category=Cache::rememberForever('category', function () {
                    return ProductCategory::get();
                });
        return view('main.admin.product.list',compact('product','category'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->hasFile('image')) {
            $imageName=uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('product',$imageName);
            $data['image']=$imageName;
        }
        $product=Product::create($data);
        $this->removeCache($request->id);
        return redirect()->route('admin#productView',$product->id)->with(['success'=>'Product create successful.']);
    }

    public function view($id){
        $product=Cache::rememberForever("product_$id", function () use($id) {
            return Product::select('products.*','product_categories.name as category_name')
                        ->leftJoin('product_categories','products.category_id','product_categories.id')
                        ->where('products.id',$id)
                        ->first();
        });
        $category=Cache::rememberForever('category', function () {
            return ProductCategory::get();
        });
        return view('main.admin.product.view',compact('product','category'));
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->hasFile('image')) {
            $product=Cache::rememberForever("product_$request->id", function () use($request) {
                return Product::select('products.*','product_categories.name as category_name')
                            ->leftJoin('product_categories','products.category_id','product_categories.id')
                            ->where('products.id',$request->id)
                            ->first();
            });
            if (isset($product->image)) {
                Storage::delete('product/'.$product->image);
            }
            $imageName=uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('product',$imageName);
            $data['image']=$imageName;
        }
        Product::where('id',$request->id)->update($data);
        $this->removeCache($request->id);
        return back()->with(['success'=>'Product update successful.']);
    }

    public function delete(Request $request){
        $product=Cache::rememberForever("product_$request->id", function () use($request) {
            return Product::select('products.*','product_categories.name as category_name')
                        ->leftJoin('product_categories','products.category_id','product_categories.id')
                        ->where('products.id',$request->id)
                        ->first();
        });
        if (isset($product->image)) {
            Storage::delete('product/'.$product->image);
        }
        Product::where('id',$request->id)->delete();
        $this->removeCache($request->id);
        return redirect()->route('admin#productList')->with(['success'=>'Product delete successful.']);
    }

    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
            'description'=>'required',
            'category_id'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'description'=>$request->description,
            'category_id'=>$request->category_id,
            'active'=>$request->active,
        ];
    }

    private function removeCache($id){
        Cache::forget("product_$id");
        Cache::forget('product_active');
    }

}
