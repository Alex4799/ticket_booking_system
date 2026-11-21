<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <title>Ticket</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body, html {
            width: 80mm;
            height: auto;
            margin: 0;  /* Remove default margin for print */
            padding: 0;
        }
        @font-face {
            font-family: 'Noto Sans Myanmar';
            src: url('{{ storage_path('fonts/NotoSansMyanmar-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        .ticket {
            width: 100%;
        }
        .m{
            margin-bottom: 10px;
        }
        .border-top {
            border-top: 2px solid black; /* Adjust thickness and color */
        }
    </style>
</head>
<body>
@php
    use App\Helpers\TimeHelper;
    use Carbon\Carbon;
@endphp
    <div class="ticket">
        <div class="m">
            {{Carbon::now()->format('F j, Y (l)  h:i:A')}}
        </div>
            <table class="table table-primary">
                <thead>
                  <tr>
                    <th scope="col" colspan="2"><div class="m">PID - {{$order->id}}</div></th>
                  </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2"><div class="m">{{$order->cinema_name}}</div></td>
                    </tr>
                    <tr>
                        <td colspan="2"><div class="m">{{Carbon::parse($order->date)->format('l, F j, Y')}}</div></td>
                    </tr>
                </tbody>
            </table>
            <table class="">
                <thead>
                    <tr>
                        <th scope="col" class="m">Product Name</th>
                        <th scope="col" class="m">Qty</th>
                        <th scope="col" class="m">Price</th>
                      </tr>
                </thead>
                <tbody>
                    @foreach ($productItem as $item)
                        <tr>
                            <td>{{$item->product_name}}</td>
                            <td>{{$item->qty}}</td>
                            <td>{{$item->qty*$item->product_price}} MMK</td>
                        </tr>
                    @endforeach
                    @if ($order->promotion_id!=null)
                        <tr>
                            <td colspan="2"><div class="m">{{$order->promotion_name}}</div></td>
                            @if ($order->promotion_amount==null)
                                <td><div class="m">- {{$order->promotion_percentage}} %</div></td>
                            @else
                                <td><div class="m">- {{$order->promotion_amount}} MMK</div></td>
                            @endif
                        </tr>
                    @endif
                    <tr class="border-top">
                        <td colspan="2" style="border-top: 2px solid black;"><div class="m">Total</div></td>
                        <td><div class="m" style="border-top: 2px solid black;">{{$order->total_price}} MMK</div></td>
                    </tr>
                    <tr class="">
                        <td colspan="2"><div class="m">Pay Amount</div></td>
                        <td><div class="m">{{$order->pay_amount}} MMK</div></td>
                    </tr>
                    <tr class="">
                        <td colspan="2"><div class="m">Change</div></td>
                        <td><div class="m">{{$order->change}} MMK</div></td>
                    </tr>
                </tbody>
            </table>
            <h3>*Please retain your ticket as proof of purchase*</h3>
    </div>
</body>
<script>
    // Automatically trigger print dialog when the page is fully loaded
    window.onload = function() {
        window.print();
    };
</script>
</html>
