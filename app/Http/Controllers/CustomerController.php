<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Movie;
use App\Models\MoviePlan;
use App\Models\MovieSeat;
use App\Models\User;
use App\Models\WebsiteInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function home(){
        $website=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });

        $cinema=Cache::rememberForever('cinema_active', function () {
            return Cinema::where('active',1)->get();
        });

        $filterData = Cache::remember('movie_plan_filter', now()->addMinutes(30), function () {
            $activePlans = MoviePlan::query()
                ->where('movie_plans.active', 1)
                ->join('movies', 'movie_plans.movie_id', '=', 'movies.id');

            return [
                'movie' => (clone $activePlans)
                    ->select('movies.id as movie_id', 'movies.name as movie_name')
                    ->distinct()
                    ->get()
                    ->toArray(),

                'date' => (clone $activePlans)
                    ->distinct()
                    ->pluck('movie_plans.date')
                    ->toArray(),
            ];
        });

        $movies = Cache::remember('home_movies', now()->addHours(6), function () {
            $today = now()->toDateString();

            return [
                'now' => Movie::where('active', 1)
                    ->where('release_date', '<=', $today)
                    ->get(),

                'comming' => Movie::where('active', 1)
                    ->where('release_date', '>', $today)
                    ->get(),

                'popular' => Movie::select('movies.id', 'movies.poster', 'movies.rating', 'movies.name',
                    DB::raw('COUNT(orders.id) as order_count'))
                    ->leftJoin('orders','movies.name','orders.movie_name')
                    ->where('movies.featured', 1)
                    ->where('movies.active', 1)
                    ->groupBy('movies.id')
                    ->orderBy('order_count','desc')
                    ->limit(5)
                    ->get(),
            ];
        });
        return view('main.customer.home.home',compact('website','cinema','filterData','movies'));
    }

    public function movieList(){
        $now=Carbon::now()->toDateString();
        $ttl=Carbon::now()->addDay();
        if (request('search_key') || request('type') || request('genre')) {
            $movie=Movie::where('active',1)
                        ->when(request('search_key'),function($query){
                            $query->where('name','like','%'.request('search_key').'%');
                        })
                        ->when(request('type'),function($query) use($now){
                            if (request('type')=='now') {
                                $query->where('release_date', '<=', $now);
                            }else{
                                $query->where('release_date', '>', $now);
                            }
                        })
                        ->when(request('genre'),function($query){
                            $query->where('main_genre',request('genre'));
                        })
                        ->orderBy('created_at','desc')
                        ->get();
        }else{
            $movie=Cache::remember('movie_active',$ttl, function () {
                return Movie::where('active',1)->orderBy('created_at','desc')->get();
            });
        }

        $genre=Cache::remember('active_movie_genre', $ttl, function () {
            return Movie::select('genres.*')
                            ->leftJoin('genres','movies.main_genre','genres.id')
                            ->where('active',1)
                            ->groupBy('movies.main_genre')
                            ->get();
        });

        return view('main.customer.movie.list',compact('movie','genre'));
    }

    public function movie_view($id){
        $movie=Cache::remember("movie{$id}", now()->addMonth(), function () use($id) {
            return Movie::select('movies.*','genres.name as genre_name')
                    ->leftJoin('genres','movies.main_genre','genres.id')
                    ->where('movies.id',$id)
                    ->first();
        });
        return view('main.customer.movie.view',compact('movie'));
    }

    public function movie_book($id){
        $movie=Cache::remember("movie{$id}", now()->addMonth(), function () use($id) {
            return Movie::select('movies.*','genres.name as genre_name')
                    ->leftJoin('genres','movies.main_genre','genres.id')
                    ->where('movies.id',$id)
                    ->first();
        });

        $now=Carbon::now();
        $allMoviePlan=Cache::remember("all_movie_plan_{$id}", now()->addMinutes(30), function () use($id,$now) {
            return MoviePlan::select('movie_plans.*','halls.name as hall_name',
                        'cinemas.name as cinema_name','cinemas.location as cinema_location')
                        ->where('movie_plans.movie_id',$id)
                        ->where('movie_plans.active',1)
                        ->where(function($query) use($now){
                            $query->where('movie_plans.date','>',$now->toDateString())
                                ->orWhere(function($item) use ($now){
                                    $item->where('movie_plans.date','=',$now->toDateString())
                                        ->where('movie_plans.end_time','>',$now->toTimeString());
                                });
                        })
                        ->leftJoin('halls','movie_plans.hall_id','halls.id')
                        ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                        ->orderBy('date')
                        ->orderBy('start_time')
                        ->get();
        });
        $date = $allMoviePlan->pluck('date')->unique()->values();
        $moviePlan=request('date')?$allMoviePlan->where('date',request('date'))->groupBy('cinema_name'):$allMoviePlan->groupBy('cinema_name');
        return view('main.customer.movie.bookTicket',compact('movie','date','moviePlan'));
    }

    public function movie_select_seat($id){
        $moviePlan=Cache::remember("movie_plan_{$id}",now()->addDay(), function () use($id) {
            return MoviePlan::select('movie_plans.*','movies.name as movie_name','movies.run_time','movies.cover','movies.poster','movies.main_genre',
                            'halls.name as hall_name','halls.seat_column','cinemas.name as cinema_name','cinemas.id as cinema_id')
                            ->where('movie_plans.id',$id)
                            ->leftJoin('movies','movie_plans.movie_id','movies.id')
                            ->leftJoin('halls','movie_plans.hall_id','halls.id')
                            ->leftJoin('cinemas','halls.cinema_id','cinemas.id')
                            ->first();
        });

        if (empty($moviePlan) || $moviePlan->active!=1) {
            return back()->with(['danger'=>'This movie plan is not get to book. Please another plan.']);
        }

        $movieSeat=MovieSeat::select('movie_seats.*','seats.name as seat_name','seats.seat_type','seats.active',
                        'seat_plans.name as seat_plan_name','seat_plans.seat_color','seat_plans.image')
                        ->leftJoin('seats','movie_seats.seat_id','seats.id')
                        ->leftJoin('seat_plans','seats.seat_plan_id','seat_plans.id')
                        ->where('movie_plan_id',$id)
                        ->get();

        $seatPlan=$movieSeat->groupBy('seat_plan_name')->map(function($item){
            $firstItem=$item->first();
            return [
                'seat_plan_name'=>$firstItem->seat_plan_name,
                'seat_color'=>$firstItem->seat_color,
                'image'=>$firstItem->image,
            ];
        });

        return view('main.customer.movie.selectSeat',compact('moviePlan','seatPlan','movieSeat'));
    }

    public function about(){
        $website=Cache::rememberForever('website_info', function () {
            return WebsiteInfo::where('id',1)->first();
        });
        $user=User::select('name','position')->get();
        return view('main.customer.about.aboutUs',compact('website','user'));
    }


}
