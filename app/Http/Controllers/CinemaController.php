<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CinemaController extends Controller
{
    public function list(){
        $cinema=Cinema::when(request('search_key'),function($query){
                        $query->where('name','like','%'.request('search_key').'%');
                    })->paginate(10);
        return view('main.admin.cinema.list',compact('cinema'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        $data['image']=[];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imageName=uniqid().$image->getClientOriginalName();
                $image->storeAs('cinema',$imageName);
                array_push($data['image'],$imageName);
            }
        }
        $cinema=Cinema::create($data);
        return redirect()->route('admin#cinemaView',$cinema->id);
    }

    public function view($id){
        $cinema=$this->getCinemaCache($id);
        return view('main.admin.cinema.view',compact('cinema'));
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        Cinema::where('id',$request->id)->update($data);
        $this->reomveCinemaCache($request->id);
        Cache::forget('active_cinemas');
        return back()->with(['success'=>'Cinema update successful.']);
    }

    public function delete(Request $request){
        Cinema::where('id',$request->id)->delete();
        $this->reomveCinemaCache($request->id);
        return redirect()->route('admin#cinemaList')->with(['danger'=>'Cinema delete successful.']);
    }

    // image
    public function image_edit($id){
        $cinema=$this->getCinemaCache($id);
        return view('main.admin.cinema.editImage',compact('cinema'));
    }

    public function image_create(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp'
        ]);
        $cinema=$this->getCinemaCache($request->id);
        $image=$cinema->image;
        if ($request->hasFile('image')) {
            $imageName=uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('cinema',$imageName);
            array_push($image,$imageName);
            Cinema::where('id',$request->id)->update(['image'=>$image]);
            $this->reomveCinemaCache($request->id);
        }
        return back()->with(['success'=>'Image create successful.']);
    }

    public function image_update(Request $request){
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp'
        ]);
        $cinema=$this->getCinemaCache($request->id);
        $image=$cinema->image;
        if ($request->hasFile('image')) {
            if (isset($image[$request->index])) {
                Storage::delete("cinema/".$image[$request->index]);
            }
            $imageName=uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('cinema',$imageName);
            $image[$request->index]=$imageName;
            Cinema::where('id',$request->id)->update(['image'=>$image]);
            $this->reomveCinemaCache($request->id);
        }
        return back()->with(['succcess'=>'Image update successful.']);
    }

    public function image_delete($id,$index){
        $cinema=$this->getCinemaCache($id);
        $image=$cinema->image;
        if (isset($image[$index])) {
            Storage::delete("cinema/".$image[$index]);
            array_splice($image,$index,1);
            // unset($image[$index]);
            Cinema::where('id',$id)->update(['image'=>$image]);
            $this->reomveCinemaCache($id);
        }
        return back()->with(['success'=>'Image delete successful.']);
    }

    // private function
    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
            'location'=>'required',
            'contact'=>'required',
            'image' => 'required'
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'location'=>$request->location,
            'contact'=>$request->contact,
            'active'=>$request->active,
        ];
    }

    // cache
    private function getCinemaCache($id){
        return Cache::rememberForever("cinema{$id}", function () use($id) {
            return Cinema::findOrFail($id);
        });
    }

    private function reomveCinemaCache($id){
        Cache::forget("cinema{$id}");
    }
}
