<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SocialController extends Controller
{
    public function list(){
        $social=Social::paginate(10);
        return view('main.admin.social.list',compact('social'));
    }

    public function create(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->hasFile('logo')) {
            $data['logo']=$this->storeImage($request);
        }
        $social=Social::create($data);
        return back()->with(['success'=>'Social create successful.']);
    }

    public function update(Request $request){
        $this->validation($request);
        $data=$this->changeFormat($request);
        if ($request->hasFile('logo')) {
            $oldImage=Social::find($request->id)->logo;
            Storage::delete('social/'.$oldImage);
            $data['logo']=$this->storeImage($request);
        }
        Social::where('id',$request->id)->update($data);
        return back()->with(['success'=>'Social update successful.']);
    }

    public function delete(Request $request){
        $social=Social::find($request->id);
        Storage::delete('social/'.$social->logo);
        $social->delete();
        return redirect()->route('admin#socialList')->with(['success'=>'Social delete successful']);
    }

    // private function
    private function validation($request){
        Validator::make($request->all(),[
            'name'=>'required',
            'link'=>'required',
            'logo'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'link'=>$request->link,
            'active'=>$request->active,
        ];
    }

    private function storeImage($request){
        $imageName=uniqid().$request->file('logo')->getClientOriginalName();
        $request->file('logo')->storeAs('/social',$imageName);
        return $imageName;
    }
}
