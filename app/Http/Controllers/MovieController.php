<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    public function list(){
        $movie=Movie::select('id','poster','name','rating','release_date','active','featured')
                    ->when(request('search_key'),function($query){
                        $query->where('name','%'.request('search_key').'%');
                    })
                    ->paginate(10);
        $genre=Genre::get();
        return view('main.admin.movie.list',compact('movie','genre'));
    }

    public function create(Request $request){
        $this->validation($request,'create');
        $data=$this->changeFormat($request);
        if ($request->hasFile('poster')) {
            $imageName=uniqid().$request->file('poster')->getClientOriginalName();
            $request->file('poster')->storeAs('poster',$imageName);
            $data['poster']=$imageName;
        }
        $movie=Movie::create($data);
        return redirect()->route('admin#movieView',$movie->id)->with(['success'=>'Movie Create successful.']);
    }

    public function view($id){
        $movie=$this->getMovie($id);
        $genre=Genre::get();
        return view('main.admin.movie.view',compact('movie','genre'));
    }

    public function update(Request $request){
        $this->validation($request,'update');
        $data=$this->changeFormat($request);
        if ($request->hasFile('poster')) {
            $movie=$this->getMovie($request->id);
            if (isset($movie->poster)) {
                Storage::delete('poster/'.$movie->poster);
            }
            $imageName=uniqid().$request->file('poster')->getClientOriginalName();
            $request->file('poster')->storeAs('poster',$imageName);
            $data['poster']=$imageName;
        }
        Movie::where('id',$request->id)->update($data);
        $this->removeMovie($request->id);
        return back()->with(['success'=>'Movie update successful']);
    }

    public function delete(Request $request){
        Movie::where('id',$request->id)->delete();
        $this->removeMovie($request->id);
        return redirect()->route('admin#movieList')->with(['danger'=>'Movie delete successful.']);
    }

    public function extraEdit($id){
        $movie=$this->getMovie($id);
        $genre=Genre::get();
        return view('main.admin.movie.extraEdit',compact('movie','genre'));
    }

    public function genreAdd(Request $request){
        $movie=$this->getMovie($request->id);
        $genre=$movie->genre ?? [];
        if (!in_array($request->genre,$genre)) {
            array_push($genre,$request->genre);
            Movie::where('id',$request->id)->update(['genre'=>$genre]);
            $this->removeMovie($request->id);
            return back()->with(['success'=>'Genre add successful.']);
        }else{
            return back()->with(['warning'=>'Genre is already exist in movie.']);
        }
    }

    public function genreDelete($id,$index){
        $movie=$this->getMovie($id);
        $genre=$movie->genre ?? [];
        array_splice($genre,$index,1);
        Movie::where('id',$id)->update(['genre'=>$genre]);
        $this->removeMovie($id);
        return back()->with(['success'=>'Genre delete successful.']);
    }

    public function castAdd(Request $request){
        $movie=$this->getMovie($request->id);
        $cast=$movie->cast ?? [];
        $data=[
            'name'=>$request->name,
        ];
        if ($request->hasFile('image')) {
            $imageName=uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('cast',$imageName);
            $data['image']=$imageName;
        }
        array_push($cast,$data);
        Movie::where('id',$request->id)->update(['cast'=>$cast]);
        $this->removeMovie($request->id);
        return back()->with(['success'=>'Cast create successful.']);
    }

    public function castUpdate(Request $request){
        $movie=$this->getMovie($request->id);
        $cast=$movie->cast ?? [];
        $data=[
            'name'=>$request->name,
            'image'=>$cast[$request->index]['image']??null
        ];
        if ($request->hasFile('image')) {
            if (isset($cast[$request->index]['image'])) {
                Storage::delete('cast/'.$cast[$request->index]['image']);
            }
            $imageName=uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('cast',$imageName);
            $data['image']=$imageName;
        }
        $cast[$request->index]=$data;
        Movie::where('id',$request->id)->update(['cast'=>$cast]);
        $this->removeMovie($request->id);
        return back()->with(['success'=>'Cast update successful.']);
    }

    public function castDelete($id,$index){
        $movie=$this->getMovie($id);
        $cast=$movie->cast ?? [];
        if (isset($cast[$index]['image'])) {
            Storage::delete('cast/'.$cast[$index]['image']);
        }
        array_splice($cast,$index,1);
        Movie::where('id',$id)->update(['cast'=>$cast]);
        $this->removeMovie($id);
        return back()->with(['success'=>'Cast delete successful']);
    }

    public function coverAdd(Request $request){
        $movie=$this->getMovie($request->id);
        $cover=$movie->cover ?? [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imageName=uniqid().$image->getClientOriginalName();
                $image->storeAs('cover',$imageName);
                array_push($cover,$imageName);
            }
        }
        Movie::where('id',$request->id)->update(['cover'=>$cover]);
        $this->removeMovie($request->id);
        return back()->with(['success'=>'Cover create successful.']);
    }

    public function coverUpdate(Request $request){
        $movie=$this->getMovie($request->id);
        $cover=$movie->cover ?? [];
        if ($request->hasFile('image')) {
            if (isset($cover[$request->index])) {
                Storage::delete('cover/'.$cover[$request->index]);
            }
            $imageName=uniqid().$request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('cover',$imageName);
            $cover[$request->index]=$imageName;
        }
        Movie::where('id',$request->id)->update(['cover'=>$cover]);
        $this->removeMovie($request->id);
        return back()->with(['success'=>'Cover update successful.']);
    }

    public function coverDelete($id,$index){
        $movie=$this->getMovie($id);
        $cover=$movie->cover ?? [];
         if (isset($cover[$index])) {
            Storage::delete('cover/'.$cover[$index]);
            array_splice($cover,$index,1);
        }
        Movie::where('id',$id)->update(['cover'=>$cover]);
        $this->removeMovie($id);
        return back()->with(['success'=>'Cover delete successful.']);
    }

    // private function

    private function validation($request,$status){
        Validator::make($request->all(),[
            'name'=>'required',
            'genre'=>'required',
            'poster'=>$status=='create'?'required|image|mimes:jpeg,png,jpg,webp':'image|mimes:jpeg,png,jpg,webp',
            'director'=>'required',
            'release_date'=>'required',
            'run_time'=>'required',
            'subtitle'=>'required',
            'language'=>'required',
            'trailer'=>'required',
            'rating'=>'required',
            'summary'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'active'=>'required',
            'featured'=>'required',
        ])->validate();
    }

    private function changeFormat($request){
        return [
            'name'=>$request->name,
            'main_genre'=>$request->genre,
            'director'=>$request->director,
            'release_date'=>$request->release_date,
            'run_time'=>$request->run_time,
            'subtitle'=>$request->subtitle,
            'language'=>$request->language,
            'trailer'=>$request->trailer,
            'rating'=>$request->rating,
            'summary'=>$request->summary,
            'start_date'=>$request->start_date,
            'end_date'=>$request->end_date,
            'active'=>$request->active,
            'featured'=>$request->featured,
        ];
    }

    // cache

    private function getMovie($id){
        return Cache::remember("movie{$id}", now()->addMonth(), function () use($id) {
            return Movie::select('movies.*','genres.name as genre_name')
                    ->leftJoin('genres','movies.main_genre','genres.id')
                    ->where('movies.id',$id)
                    ->first();
        });
    }
    private function removeMovie($id){
        Cache::forget("movie{$id}");
    }
}

