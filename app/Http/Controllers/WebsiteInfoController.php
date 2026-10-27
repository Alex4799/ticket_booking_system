<?php

namespace App\Http\Controllers;

use App\Models\WebsiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WebsiteInfoController extends Controller
{
    public function view(){
        $website=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });
        return view('main.admin.website.view',compact('website'));
    }

    public function update(Request $request){
        Validator::make($request->all(),[
            'name'=>'required',
            'contact'=>'required',
            'default_order_reject_min'=>'required'
        ])->validate();
        $data=[
            'name'=>$request->name,
            'contact'=>$request->contact,
            'cover_letter'=>$request->cover_letter,
            'footer_letter'=>$request->footer_letter,
            'aboutus_letter'=>$request->aboutus_letter,
            'payment_type'=>$request->payment_type,
            'default_order_reject_min'=>$request->default_order_reject_min,
        ];
        if ($request->hasFile('logo')) {
            $websiteInfo=Cache::rememberForever('website_info', function () {
                return WebsiteInfo::where('id',1)->first();
            });
            $this->removeImage($websiteInfo->logo);
            $data['logo']=$this->storeImage($request,'logo');
        }
        WebsiteInfo::where('id',1)->update($data);
        $this->removeCache();
        return back()->with(['success'=>'Website info update successful.']);
    }

    public function extraEdit(){
        $website=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });
        return view('main.admin.website.extraEdit',compact('website'));
    }

    public function addCover(Request $request){
        $websiteInfo=WebsiteInfo::find(1);
        $cover=$websiteInfo->cover_image??[];
        if ($request->hasFile('image')) {
            $cover[]=$this->storeImage($request,'image');
        }
        $websiteInfo->cover_image=$cover;
        $websiteInfo->save();
        $this->removeCache();
        return back()->with(['success'=>'Cover image add successful.']);
    }

    public function updateCover(Request $request){
        $websiteInfo=WebsiteInfo::find(1);
        $cover=$websiteInfo->cover_image??[];
        if ($request->hasFile('image')) {
            $this->removeImage($cover[$request->index]);
            $cover[$request->index]=$this->storeImage($request,'image');
        }
        $websiteInfo->cover_image=$cover;
        $websiteInfo->save();
        $this->removeCache();
        return back()->with(['success'=>'Cover image update successful.']);
    }

    public function deleteCover($index){
        $websiteInfo=WebsiteInfo::find(1);
        $cover=$websiteInfo->cover_image??[];
        $this->removeImage($cover[$index]);
        array_splice($cover,$index,1);
        $websiteInfo->cover_image=$cover;
        $websiteInfo->save();
        $this->removeCache();
        return back()->with(['success'=>'Cover image delete successful.']);
    }

    public function addAbout(Request $request){
        $websiteInfo=WebsiteInfo::find(1);
        $about=$websiteInfo->about_image??[];
        if ($request->hasFile('image')) {
            $about[]=$this->storeImage($request,'image');
        }
        $websiteInfo->about_image=$about;
        $websiteInfo->save();
        $this->removeCache();
        return back()->with(['success'=>'About image add successful.']);
    }

    public function updateAbout(Request $request){
        $websiteInfo=WebsiteInfo::find(1);
        $about=$websiteInfo->about_image??[];
        if ($request->hasFile('image')) {
            $this->removeImage($about[$request->index]);
            $about[$request->index]=$this->storeImage($request,'image');
        }
        $websiteInfo->about_image=$about;
        $websiteInfo->save();
        $this->removeCache();
        return back()->with(['success'=>'About image update successful.']);
    }

    public function deleteAbout($index){
        $websiteInfo=WebsiteInfo::find(1);
        $about=$websiteInfo->about_image??[];
        $this->removeImage($about[$index]);
        array_splice($about,$index,1);
        $websiteInfo->about_image=$about;
        $websiteInfo->save();
        $this->removeCache();
        return back()->with(['success'=>'About image delete successful.']);
    }

    // api
    public function getWebsite(){
        $data=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });
        return response()->json($data, 200);
    }

    // private function
    private function removeImage($image){
        if ($image) {
            Storage::delete('website/'.$image);
        }
    }

    private function storeImage($request,$fileName){
        $imageName=uniqid().$request->file($fileName)->getClientOriginalName();
        $request->file($fileName)->storeAs('website',$imageName);
        return $imageName;
    }

    private function removeCache(){
        Cache::forget('website_info');
    }


}
