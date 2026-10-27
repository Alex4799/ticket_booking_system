<?php

namespace App\Http\Controllers;

use App\Mail\ReplyReport;
use App\Models\Cinema;
use App\Models\Contact;
use App\Models\Review;
use App\Models\Social;
use App\Models\WebsiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{

    //admin
    public function list_admin(){
        $message=Contact::where('reply_id',null)->orderBy('created_at','desc')->paginate(10);
        return view('main.admin.customer_report.list',compact('message'));
    }

    public function view_admin($id){
        $message=Contact::where('id',$id)->first();
        Contact::where('id',$id)->update(['status'=>1]);
        $replyMessage=Contact::where('reply_id',$id)->get();
        return view('main.admin.customer_report.view',compact('message','replyMessage'));
    }

    public function reply_admin(Request $req){
        $contact=Contact::where('id',$req->reply_id)->first();
        $this->validation($req);
        $data=$this->changeFormat($req);
        $data['reply_id']=$req->reply_id;
        $data['status']=1;
        $mailData=[
            'subject'=>$req->subject,
            'body'=>$req->message,
        ];
        Mail::to($contact->email)->send(new ReplyReport($mailData));
        Contact::create($data);
        return redirect()->route('admin#customerReportView',$contact->id)->with(['success'=>'Customer report reply send successful.']);
    }


    //customer
    public function contact(){
        $website=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });
        $social=Social::where('active',1)->get();
        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });
        return view('main.customer.contact.index',compact('website','social','cinema'));
    }

    public function cinemaView($id){
        $cinema=Cinema::where('id',$id)->first();
        $review=Review::where('cinema_id',$id)->orderBy('created_at','desc')->get();
        return view('main.customer.cinema.view',compact('cinema','review'));
    }

    public function reviewSend_customer(Request $req){
        Validator::make($req->all(),[
            'name'=>'required',
            'email'=>'required',
            'review'=>'required',
            'rating'=>'required',
        ])->validate();
        $data=[
            'name'=>$req->name,
            'email'=>$req->email,
            'review'=>$req->review,
            'rating'=>$req->rating,
            'cinema_id'=>$req->cinema_id,
        ];
        Review::create($data);
        return back()->with(['success'=>'Review send successful.']);
    }

    public function send(Request $req){
        $this->validation($req);
        $data=$this->changeFormat($req);
        Contact::create($data);
        return redirect()->route('customer#contactPage')->with(['success'=>'Your message send successful.']);
    }

    // api
    public function getContact(){
        $data=Contact::where('status',0)->get();
        return response()->json($data, 200);
    }

    // private function
    private function validation($req){
        Validator::make($req->all(),[
            'name'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'message'=>'required',
        ])->validate();
    }

    private function changeFormat($req){
        return [
            'name'=>$req->name,
            'email'=>$req->email,
            'subject'=>$req->subject,
            'message'=>$req->message,
        ];
    }
}
