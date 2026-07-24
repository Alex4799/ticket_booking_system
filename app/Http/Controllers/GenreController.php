<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    public function list(){
        $genre=Genre::when(request('search_key'),function($query){
            $query->where('name','like','%'.request('search_key').'%');
        })->paginate(10);
        return view('main.admin.genre.list',compact('genre'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        Genre::create($data);
        return back()->with(['success'=>'Genre create successful.']);
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        Genre::where('id',$request->id)->update($data);
        return back()->with(['success'=>'Genre update successful.']);
    }

    public function delete(Request $request){
        Genre::where('id',$request->id)->delete();
        return back()->with(['danger'=>'Genre delete successful.']);
    }

    // private function
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
}
