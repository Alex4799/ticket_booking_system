<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Order;
use App\Models\ProductOrder;
use App\Models\ProductOrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function ticket(){
        $currentDate = Carbon::now();

        if (request('start_date')) {
            $startDate = Carbon::parse(request('start_date'))->toDateString();
        }else{
            if ($currentDate->isFriday()) {
                $startDate = $currentDate->toDateString();
            } else {
                $startDate = $currentDate->copy()->previous(Carbon::FRIDAY)->toDateString();
            }
        }
        if (request('end_date')) {
            $endDate = Carbon::parse(request('end_date'))->toDateString();
        }else{
            $endDate=$currentDate->toDateString();
        }

        $cinema=Cinema::select('name','id')->get();

        $baseQuery=Order::when(request('cinema'),function($query){
                            $query->where('orders.cinema_id',request('cinema'));
                        })
                        ->where('orders.created_at','>=',$startDate.' 00:00:00')
                        ->where('orders.created_at','<=',$endDate.' 23:59:59');


        $summary=(clone $baseQuery)
                        ->select('orders.status',DB::raw('SUM(orders.total_price) as total_income'),
                        DB::raw('SUM(orders.seat_count) as total_seat') , DB::raw('COUNT(orders.id) as total_order'))
                        ->groupBy('orders.status')
                        ->get();

        $cinemaReport=(clone $baseQuery)
                        ->select('cinemas.name as cinema_name',DB::raw('SUM(total_price) as total_price'),DB::raw('SUM(orders.seat_count) as total_seat'))
                        ->where('orders.status',1)
                        ->leftJoin('cinemas','orders.cinema_id','cinemas.id')
                        ->groupBy('orders.cinema_id')
                        ->get();

        $hallReport=(clone $baseQuery)
                        ->select('halls.name as hall_name','cinemas.name as cinema_name',DB::raw('SUM(total_price) as total_price'),DB::raw('SUM(orders.seat_count) as total_seat'))
                        ->where('orders.status',1)
                        ->leftJoin('halls','orders.hall_id','halls.id')
                        ->leftJoin('cinemas','orders.cinema_id','cinemas.id')
                        ->groupBy('orders.hall_id')
                        ->get();

        $genreReport=(clone $baseQuery)
                        ->select('genres.name as genre_name',DB::raw('SUM(total_price) as total_price'))
                        ->where('orders.status',1)
                        ->leftJoin('genres','orders.genre','genres.id')
                        ->groupBy('orders.genre')
                        ->get();

        $paymentReport=(clone $baseQuery)
                        ->select('payments.name as payment_name','payments.username',
                        DB::raw('SUM(total_price) as total_price'))
                        ->where('orders.status',1)
                        ->leftJoin('payments','orders.payment_method','payments.id')
                        ->groupBy('orders.payment_method')
                        ->get();

        $dateReport=(clone $baseQuery)
                        ->select('orders.date',DB::raw('SUM(total_price) as total_price'))
                        ->where('orders.status',1)
                        ->groupBy('orders.date')
                        ->get();

        $groupBy=request('group_by')??'daily';
        $selectQuery=$this->selectQuery($groupBy);
        $groupByQuery=$this->groupByQuery($groupBy);
        $movieReport=Order::selectRaw("orders.movie_name,SUM(total_price) as total_price,$selectQuery")
                    ->when(request('cinema'),function($query){
                            $query->where('orders.cinema_id',request('cinema'));
                        })
                    ->where('orders.created_at','>=',$startDate.' 00:00:00')
                    ->where('orders.created_at','<=',$endDate.' 23:59:59')
                    ->where('orders.status',1)
                    ->groupBy('movie_name')
                    ->groupByRaw($groupByQuery)
                    ->get()
                    ->groupBy('movie_name')
                    ->map(function ($items) use ($groupBy) {
                        return $items->keyBy(function ($item) use ($groupBy) {
                            if ($groupBy == 'daily') {
                                return $item->Date;
                            } elseif ($groupBy == 'weekly') {
                                return $item->year . '-' . sprintf('%02d', $item->week);
                            } elseif ($groupBy == 'monthly') {
                                return $item->year . '-' . sprintf('%02d', $item->month);
                            } elseif ($groupBy == 'yearly') {
                                return $item->year;
                            }
                        })->map(function ($item) {
                            return $item->toArray();
                        });
                    });
        $date=[];
        $start=Carbon::parse($startDate);
        $end=Carbon::parse($endDate);
        while($start->lte($end)){
            if ($groupBy=='daily') {
                $date[]=$start->toDateString();
                $start=$start->addDay();
            }else if($groupBy=='weekly'){
                $date[]=[
                    'week'=>sprintf('%02d', $start->week),
                    'year'=>$start->year,
                ];
                $start=$start->addWeek();
            }else if($groupBy=='monthly'){
                $date[]=[
                    'month'=>sprintf('%02d', $start->month),
                    'year'=>$start->year,
                ];
                $start=$start->addMonth();
            }else if($groupBy=='yearly'){
                $date[]=[
                    'year'=>$start->year,
                ];
                $start=$start->addYear();
            }
        }
        $movieCollector=[];
        foreach ($movieReport as $movieName=>$item) {
            $temp=[
                'movie_name'=>$movieName,
                'date_price'=>[],
                'total_price'=>0,
            ];
            foreach ($date as $dateItem) {
                if ($groupBy=='daily') {
                    $order=$item[$dateItem]??null;
                }else if($groupBy=='weekly'){
                    $order=$item[$dateItem['year'].'-'.$dateItem['week']]??null;
                }else if($groupBy=='monthly'){
                    $order=$item[$dateItem['year'].'-'.$dateItem['month']]??null;
                }else if($groupBy=='yearly'){
                    $order=$item[$dateItem['year']]??null;
                }
                $temp['date_price'][]=$order?$order['total_price']:0;
                $temp['total_price']+=$order?$order['total_price']:0;
            }
            $movieCollector[]=$temp;
        }
        $report=[
            'summary'=>$summary,
            'cinema'=>$cinemaReport,
            'hall'=>$hallReport,
            'genre'=>$genreReport,
            'payment'=>$paymentReport,
            'date'=>$dateReport,
            'movie'=>[
                'dateCollector'=>$date,
                'movieCollector'=>$movieCollector,
            ]
        ];
        return view('main.admin.report.ticket',compact('startDate','endDate','cinema','report'));
    }

    public function graph(){
        $currentDate = Carbon::now();

        if (request('start_date')) {
            $startDate = Carbon::parse(request('start_date'))->toDateString();
        }else{
            if ($currentDate->isFriday()) {
                $startDate = $currentDate->toDateString();
            } else {
                $startDate = $currentDate->copy()->previous(Carbon::FRIDAY)->toDateString();
            }
        }
        if (request('end_date')) {
            $endDate = Carbon::parse(request('end_date'))->toDateString();
        }else{
            $endDate=$currentDate->toDateString();
        }

        $cinema=Cinema::select('name','id')->get();

        $baseQuery=Order::when(request('cinema'),function($query){
                            $query->where('orders.cinema_id',request('cinema'));
                        })
                        ->where('orders.created_at','>=',$startDate.' 00:00:00')
                        ->where('orders.created_at','<=',$endDate.' 23:59:59')
                        ->where('orders.status',1);

        $cinemaReport=(clone $baseQuery)
                        ->select('cinemas.name as cinema_name',DB::raw('SUM(total_price) as total_price'))
                        ->leftJoin('cinemas','orders.cinema_id','cinemas.id')
                        ->groupBy('cinema_id')
                        ->get();

        $cinemaData=[
            'cinema_name'=>[],
            'total_price'=>[],
        ];

        foreach($cinemaReport as $item){
            $cinemaData['cinema_name'][]=$item->cinema_name;
            $cinemaData['total_price'][]=$item->total_price;
        }

        $hall=(clone $baseQuery)
                        ->select('halls.name as hall_name','cinemas.name as cinema_name',DB::raw('SUM(total_price) as total_price'))
                        ->leftJoin('halls','orders.hall_id','halls.id')
                        ->leftJoin('cinemas','orders.cinema_id','cinemas.id')
                        ->groupBy('orders.hall_id')
                        ->get();

        $hallData=[
            'hall_name'=>[],
            'total_price'=>[],
        ];

        foreach ($hall as $item) {
            $hallData['hall_name'][]="$item->hall_name ($item->cinema_name)";
            $hallData['total_price'][]=$item->total_price;
        }

        $genreReport=(clone $baseQuery)
                        ->select('genres.name as genre_name',DB::raw('SUM(total_price) as total_price'))
                        ->leftJoin('genres','orders.genre','genres.id')
                        ->groupBy('orders.genre')
                        ->get();

        $genreData=[
            'genre_name'=>[],
            'total_price'=>[],
        ];

        foreach ($genreReport as $item) {
            $genreData['genre_name'][]=$item->genre_name;
            $genreData['total_price'][]=$item->total_price;
        }

        $movieReport=(clone $baseQuery)
                        ->select('orders.movie_name',DB::raw('SUM(total_price) as total_price'))
                        ->groupBy('orders.movie_name')
                        ->get();

        $movieData=[
            'movie_name'=>[],
            'total_price'=>[],
        ];

        foreach ($movieReport as $item) {
            $movieData['movie_name'][]=$item->movie_name;
            $movieData['total_price'][]=$item->total_price;
        }

        $dateReport=(clone $baseQuery)
                        ->select('orders.date',DB::raw('SUM(total_price) as total_price'))
                        ->groupBy('orders.date')
                        ->get();

        $dateData=[
            'date'=>[],
            'total_price'=>[],
        ];

        foreach($dateReport as $item){
            $dateData['date'][]=$item->date;
            $dateData['total_price'][]=$item->total_price;
        }

        $showTime=(clone $baseQuery)
                        ->select('orders.show_time',DB::raw('SUM(total_price) as total_price'))
                        ->groupBy('orders.show_time')
                        ->get();

        $showTimeData=[
            'show_time'=>[],
            'total_price'=>[],
        ];

        foreach ($showTime as $item) {
            $showTimeData['show_time'][]=$item->show_time;
            $showTimeData['total_price'][]=$item->total_price;
        }

        return view('main.admin.report.graph',compact('startDate','endDate','cinema','cinemaData','hallData','genreData','movieData','dateData','showTimeData'));
    }

    public function product(){
        $currentDate = Carbon::now();

        if (request('start_date')) {
            $startDate = Carbon::parse(request('start_date'))->toDateString();
        }else{
            if ($currentDate->isFriday()) {
                $startDate = $currentDate->toDateString();
            } else {
                $startDate = $currentDate->copy()->previous(Carbon::FRIDAY)->toDateString();
            }
        }
        if (request('end_date')) {
            $endDate = Carbon::parse(request('end_date'))->toDateString();
        }else{
            $endDate=$currentDate->toDateString();
        }

        $cinema=Cinema::select('name','id')->get();

        $orderBaseQuery=ProductOrder::when(request('cinema'),function($query){
                            $query->where('product_orders.cinema_id',request('cinema'));
                        })
                        ->whereBetween('product_orders.created_at',[Carbon::parse($startDate)->startOfDay(),Carbon::parse($endDate)->endOfDay()]);

        $summary=(clone $orderBaseQuery)
                        ->select('product_orders.status',DB::raw('SUM(product_orders.total_price) as total_price'),
                        DB::raw('COUNT(product_orders.id) as order_count'))
                        ->groupBy('product_orders.status')
                        ->get();

        $date=(clone $orderBaseQuery)
                        ->selectRaw('DATE(product_orders.created_at) as date,SUM(product_orders.total_price) as total_price')
                        ->where('product_orders.status',1)
                        ->groupBy('date')
                        ->get();

        $promotionPrice=(clone $orderBaseQuery)
                        ->where('product_orders.status',1)
                        ->sum('promotion_price');

        $itemBaseQuery=ProductOrderItem::when(request('cinema'),function($query){
                            $query->where('product_orders.cinema_id',request('cinema'));
                        })
                        ->whereBetween('product_order_items.created_at',[Carbon::parse($startDate)->startOfDay(),Carbon::parse($endDate)->endOfDay()])
                        ->where('product_orders.status',1)
                        ->leftJoin('product_orders','product_order_items.order_id','product_orders.id');

        $cinemaData=(clone $itemBaseQuery)
                        ->select('cinemas.name as cinema_name',
                        DB::raw('SUM(product_order_items.qty*product_order_items.product_price) as total_price'),
                        DB::raw('SUM(product_order_items.profit*product_order_items.qty) as total_profit'))
                        ->leftJoin('cinemas','product_orders.cinema_id','cinemas.id')
                        ->groupBy('product_orders.cinema_id')
                        ->get();

        $productItem=(clone $itemBaseQuery)
                        ->select('product_order_items.product_name','product_order_items.product_price',
                        DB::raw('SUM(product_order_items.qty) as total_qty'),
                        DB::raw('SUM(product_order_items.profit*product_order_items.qty) as total_profit'),
                        DB::raw('SUM(product_order_items.product_price*product_order_items.qty) as total_price'))
                        ->groupBy('product_order_items.product_id')
                        ->groupBy('product_order_items.product_price')
                        ->get();

        $category=(clone $itemBaseQuery)
                        ->select('product_categories.name',
                        DB::raw('SUM(product_order_items.qty) as total_qty'),
                        DB::raw('SUM(product_order_items.profit*product_order_items.qty) as total_profit'),
                        DB::raw('SUM(product_order_items.product_price*product_order_items.qty) as total_price'))
                        ->leftJoin('products','product_order_items.product_id','products.id')
                        ->leftJoin('product_categories','products.category_id','product_categories.id')
                        ->groupBy('product_categories.id')
                        ->get();

        $payment=(clone $orderBaseQuery)
                        ->select('payments.name','payments.username',
                        DB::raw('SUM(product_orders.total_price) as total_price'))
                        ->where('product_orders.status',1)
                        ->leftJoin('payments','product_orders.payment_method','payments.id')
                        ->groupBy('product_orders.payment_method')
                        ->get();

        $reportData=[
            'summary'=>$summary,
            'cinema'=>$cinemaData,
            'product'=>$productItem,
            'category'=>$category,
            'date'=>$date,
            'payment'=>$payment,
        ];

        return view('main.admin.report.product',compact('startDate','endDate','cinema','reportData','promotionPrice'));
    }

    // private function

    private function selectQuery($groupBy){
        return match ($groupBy) {
            'weekly'=>"WEEK(orders.created_at) as week,YEAR(orders.created_at) as year",
            'monthly'=>"MONTH(orders.created_at) as month,YEAR(orders.created_at) as year",
            'yearly'=>"YEAR(orders.created_at) as year",
            default=>"DATE(orders.created_at) as Date",
        };
    }

    private function groupByQuery($groupBy){
        return match ($groupBy) {
            'weekly'=>'WEEK(orders.created_at),YEAR(orders.created_at)',
            'monthly'=>'MONTH(orders.created_at),YEAR(orders.created_at)',
            'yearly'=>'YEAR(orders.created_at)',
            default=>"DATE(orders.created_at)" ,
        };
    }
}
