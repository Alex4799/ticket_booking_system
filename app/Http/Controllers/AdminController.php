<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    public function dashboard(Request $request){
        if (Auth::user()->role=='admin') {
            return redirect()->route('admin#profile');
        }else{
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/');
        }
    }

    public function profile(Request $request){
        $user=$request->user();
        return view('main.admin.account.profile',compact('user'));
    }

    public function update(Request $request){
        $user=$request->user();
        Validator::make($request->all(),[
            'name'=>'required',
            'email'=>['required','email',Rule::unique('users')->ignore($user->id)],
        ])->validate();
        $user->update(['name'=>$request->name,'email'=>$request->email]);
        $this->removeCache($user->id);
        return back()->with(['success'=>'Profile update successful.']);
    }

    public function changePassword(Request $request){
        Validator::make($request->all(),[
            'old_password'=>'required',
            'new_password'=>'required|min:6',
            'con_password'=>'required|same:new_password'
        ])->validate();
        $user=$request->user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->password=Hash::make($request->new_password);
            $user->login_fail=0;
            $message=['success'=>'Change password successful'];
        }else{
            $user->login_fail+=1;
            $message=['danger'=>'Wrong old password! Please try again.'];
        }
        $user->save();
        return back()->with($message);
    }

    public function list(){
        $admin=User::when(request('search_key'),function($query){
                    $query->where('name','like','%'.request('search_key').'%');
                })->paginate(10);
        $cinema=$this->getCinema();
        return view('main.admin.account.list',compact('admin','cinema'));
    }

    public function add(Request $request){
        $this->validation($request,'create');
        $data=$this->changeFormat($request,'create');
        $user=User::create($data);
        return back()->with(['success'=>'Admin account create successful.']);
    }

    public function view($id){
        $admin=Cache::rememberForever("admin{$id}", function () use($id) {
            return User::find($id);
        });

        $cinema=$this->getCinema();
        return view('main.admin.account.view',compact('admin','cinema'));
    }

    public function updateAdmin(Request $request){
        $this->validation($request,'update');
        $data=$this->changeFormat($request,'update');
        User::where('id',$request->id)->update($data);
        $this->removeCache($request->id);
        return back()->with(['success'=>'Account update successful.']);
    }

    public function delete(Request $request){
        User::where('id',$request->id)->delete();
        $this->removeCache($request->id);
        return redirect()->route('admin#list')->with(['success'=>'Account delete successful.']);
    }

    // private function
    private function validation($request,$status){
        Validator::make($request->all(),[
            'name'=>'required',
            'email'=>['required','email',Rule::unique('users')->ignore($request->id)],
            'position'=>'required',
            'password'=>$status=='create'?'required|min:6':'',
            'password_confirmation'=>$status=='create'?'required|same:password':'',
        ])->validate();
    }

    private function changeFormat($request,$status){
        $data= [
            'name'=>$request->name,
            'email'=>$request->email,
            'position'=>$request->position,
            'location'=>$request->location,
            'role'=>'admin',
            'can_reject'=>$status=='create'?1:$request->can_reject,
            'ticket_report'=>$status=='create'?1:$request->ticket_report,
            'product_report'=>$status=='create'?1:$request->product_report,
            'purchase'=>$status=='create'?1:$request->purchase,
            'stock'=>$status=='create'?1:$request->stock,
        ];
        if ($status=='create') {
            $data['password']=Hash::make($request->password);
        }
        return $data;
    }

    // cache
    private function removeCache($id) {
        Cache::forget("admin{$id}");
    }

    private function getCinema(){
        $cinemaId=Cinema::where('active',1)->pluck('id');
        return $cinemaId->map(function($id){
            return Cache::rememberForever("cinema{$id}", function () use($id) {
                return Cinema::findOrFail($id);
            });
        })->filter();
    }

}
