<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Genre;
use App\Models\Hall;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\SeatPlan;
use Illuminate\Support\Facades\Storage;

class RecycleController extends Controller
{
    public function list(){
        return view('main.admin.recyclebin.list');
    }

    public function view($item){
        switch ($item) {
            case 'cinema':
                $cinema=Cinema::when(request('search_key'),function($query){
                                $query->where('name','like','%'.request('search_key').'%');
                            })->onlyTrashed()->paginate(10);
                return view('main.admin.recyclebin.cinema',compact('cinema'));

            case 'hall':
                $hall=Hall::select('halls.*','cinemas.name as cinema_name')
                        ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                        ->when(request('search_key'),function($query){
                            $query->where('halls.name','like','%'.request('search_key').'%');
                        })->onlyTrashed()->paginate(10);
                return view('main.admin.recyclebin.hall',compact('hall'));

            case 'genre':
                $genre=Genre::when(request('search_key'),function($query){
                            $query->where('name','like','%'.request('search_key').'%');
                        })->onlyTrashed()->paginate(10);
                return view('main.admin.recyclebin.genre',compact('genre'));

            case 'movie':
                $movie=Movie::when(request('search_key'),function($query){
                            $query->where('name','like','%'.request('search_key').'%');
                        })->onlyTrashed()->paginate(10);
                return view('main.admin.recyclebin.movie',compact('movie'));

            case 'seat_plan':
                $seatPlan=SeatPlan::when(request('search_key'),function($query){
                                $query->where('name','like','%'.request('search_key').'%');
                            })->onlyTrashed()->paginate(10);
                return view('main.admin.recyclebin.seatPlan',compact('seatPlan'));

            default:
                return back()->with(['danger'=>'Something wrong. Please try again.']);
        }

    }

    public function cinemaRestore($id){
        $cinema=Cinema::onlyTrashed()->findOrFail($id);
        $cinema->restore();
        return redirect()->route('admin#recycleView','cinema')->with(['success'=>'Cinema restore successful.']);
    }

    public function cinemaDelete($id){
        $cinema=Cinema::onlyTrashed()->findOrFail($id);
        foreach ($cinema->image as $item) {
            Storage::delete('cinema/'.$item);
        }
        $hall=Hall::where('cinema_id',$id)->withTrashed()->get();
        foreach ($hall as $item) {
            $this->hallDelete($item->id);
        }
        $cinema->forceDelete();
        return redirect()->route('admin#recycleView','cinema')->with(['danger'=>'Cinema permanently delete successful.']);

    }

    // hall

    public function hallRestore($id){
        $hall=Hall::onlyTrashed()->findOrFail($id);
        $hall->restore();
        return redirect()->route('admin#recycleView','hall')->with(['success'=>'Hall restore successful.']);
    }

    public function hallDelete($id){
        $this->deleteHall($id);
        return redirect()->route('admin#recycleView','hall')->with(['danger'=>'Hall permanently delete successful.']);

    }

    // genre

    public function genreRestore($id){
        $genre=Genre::onlyTrashed()->findOrFail($id);
        $genre->restore();
        return redirect()->route('admin#recycleView','genre')->with(['success'=>'Genre restore successful.']);
    }

    public function genreDelete($id){
        $genre=Genre::onlyTrashed()->findOrFail($id);
        $genre->forceDelete();
        return redirect()->route('admin#recycleView','genre')->with(['danger'=>'Genre permanently delete successful.']);

    }

    // movie

    public function movieRestore($id){
        $movie=Movie::onlyTrashed()->findOrFail($id);
        $movie->restore();
        return redirect()->route('admin#recycleView','movie')->with(['success'=>'Movie restore successful.']);
    }

    public function movieDelete($id){
        $movie=Movie::onlyTrashed()->findOrFail($id);
        foreach ($movie->cover as $item) {
            Storage::delete('cover/'.$item);
        }
        foreach($movie->cast as $item){
            Storage::delete('cast/'.$item['image']);
        }
        Storage::delete('poster/'.$movie->poster);
        $movie->forceDelete();
        return redirect()->route('admin#recycleView','movie')->with(['danger'=>'Movie permanently delete successful.']);

    }

    //  seat plan

    public function seatPlanRestore($id){
        $seatPlan=SeatPlan::onlyTrashed()->findOrFail($id);
        $seatPlan->restore();
        return back()->with(['success'=>'Seat plan restore successful.']);
    }

    public function seatPlanDelete($id){
        $seatPlan=SeatPlan::onlyTrashed()->findOrFail($id);
        if (isset($seatPlan->image)) {
            Storage::delete('seat_plan/'.$seatPlan->image);
        }
        $seatPlan->forceDelete();
        return back()->with(['danger'=>'Seat Plan permanently delete successful']);
    }

    // private function 

    private function deleteHall($id){
        $hall=Hall::withTrashed()->findOrFail($id);
        $seat=Seat::where('hall_id',$id)->delete();
        $hall->forceDelete();
    }
}
