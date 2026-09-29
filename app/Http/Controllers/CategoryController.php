<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function list(){
        $category=ProductCategory::when(request('search_key'),function($query){
                        $query->where('name',request('search_key'));
                    })->paginate(20);
        return view('main.admin.category.list',compact('category'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        $category=ProductCategory::create($data);
        $this->removeCache();
        return back()->with(['success'=>'Category create successful.']);
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        ProductCategory::where('id',$request->id)->update($data);
        $this->removeCache();
        return back()->with(['success'=>'Category update successful.']);
    }

    public function delete(Request $request){
        ProductCategory::where('id',$request->id)->delete();
        $this->removeCache();
        return back()->with(['danger'=>'Category delete successful.']);
    }

    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
        ];
    }

    private function removeCache(){
        Cache::forget('category');
    }
}
