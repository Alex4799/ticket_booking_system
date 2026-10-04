@extends('main.admin.layout.master')
@section('title')
    Product Report
@endsection
@section('content')
<main class="content p-4 mb-5">
    <div class="bg-body-secondary rounded p-2 mb-4">
        <form action="" method="get">
            @csrf
            <div class="d-flex flex-wrap gap-1">
                <div class="me-1">
                    <div>
                        <label for="" style="font-size: 11pt;">Start Date</label>
                        <input type="date" name="start_date" value="{{$startDate}}" id="" class="form-control @error('start_date') is-invalid @enderror">
                        @error('start_date')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="me-1">
                    <div>
                        <label for="" style="font-size: 11pt;">End Date</label>
                        <input type="date" name="end_date" value="{{$endDate}}" id="" class="form-control @error('end_date') is-invalid @enderror">
                        @error('end_date')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="me-1">
                    <div class="">
                        <label for="" style="font-size: 11pt;">Cinema</label>
                        <select name="cinema" id="" class="form-control">
                            <option value="">All</option>
                            @foreach ($cinema as $item)
                                <option value="{{$item->id}}" @if (request('cinema')==$item->name) selected @endif>{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('cinema')
                            <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>
                </div>

                {{-- multi select  --}}
                {{-- <div class="me-1">
                    <label for="" style="font-size: 11pt;">Products</label>
                    <div class="multi-select" data-placeholder="Select Products">
                        <div class="select-box">
                            <span class="selected-text"></span>
                            <span class="arrow"><i class="fa-solid fa-caret-down"></i></span>
                        </div>

                        @php
                            $filterProducts = request('products') ? json_decode(request('products')) : [];
                        @endphp
                        <div class="multi-dropdown">
                            <label>
                                <input type="checkbox" value="All" @if (count($filterProducts)==0 || count($product)==count($filterProducts) ) checked @endif > Select All
                            </label>
                            <div class="text-content">
                                @foreach ($product as $item)
                                    <label>
                                        <input type="checkbox" data-label="{{$item->name}}" {{count($filterProducts)==0 ||in_array($item->name,$filterProducts)?'checked':''}}>
                                        <span class="label-text">{{$item->name}}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            {{-- <input type="hidden" name="products" id="productsFilter" value="{{request('products')}}"> --}}
            <div class="d-flex align-items-center justify-content-end mt-2 mb-1">
                <input type="submit" value="Filter" class="btn btn-primary me-2" style="font-size: 11pt;">
                <a href="{{route('admin#reportProduct')}}" style="font-size: 11pt;" class="btn btn-danger">Clear</a>
            </div>
        </form>
    </div>

    <div>
        @if (request('products'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span>When filtering products, promotion discounts are not included because promotions are applied at the full order level.</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="summary-cards mt-4 mb-3">
        <div class="row">
            @foreach ($reportData['summary'] as $item)
                <div class="col-md-4 mb-3">
                    <div class="rounded py-3 px-3 bg-white">
                        <label for="">Total Snack @if ($item->status==0) Pending @elseif($item->status==1) Success @elseif($item->status==3) Cancel @elseif($item->status==4) Booking @else Reject @endif</label>
                        <div class="d-flex align-items-center justify-content-between gap-1 flex-wrap">
                            <h5>{{number_format($item->total_price)}}</h5>
                            @if ($item->status==0)
                                <p class="rep-pending">{{$item->order_count}}</p>
                            @elseif($item->status==1)
                                <p class="rep-success">{{$item->order_count}}</p>
                            @elseif($item->status==3)
                                <p class="rep-cancel">{{$item->order_count}}</p>
                            @elseif($item->status==4)
                                <p class="rep-booking">{{$item->order_count}}</p>
                            @else
                                <p class="rep-reject">{{$item->order_count}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Cinema</h5>
            </div>
            <div class="report-table">
                <table id="cinemaReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Cinema Name</th>
                            <th>Total Profit</th>
                            <th>Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $cinemaTotalPrice=0;
                            $cinemaTotalProfit=0;
                        ?>
                        @foreach ($reportData['cinema'] as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->cinema_name}}</td>
                                <td class=" text-end">{{number_format($item->total_profit)}}</td>
                                <td class=" text-end">{{number_format($item->total_price)}}</td>
                            </tr>
                            <?php $cinemaTotalProfit+=$item->total_profit ?>
                            <?php $cinemaTotalPrice+=$item->total_price ?>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td class="text-end">{{number_format($cinemaTotalProfit)}}</td>
                            <td class="text-end">{{number_format($cinemaTotalPrice)}}</td>
                        </tr>
                        <tr>
                            <td>Total Promotion Price</td>
                            <td></td>
                            <td></td>
                            <td class="text-end">- {{number_format($promotionPrice)}}</td>
                        </tr>
                        <tr>
                            <td>Total Income</td>
                            <td></td>
                            <td></td>
                            <td class="text-end">{{number_format($cinemaTotalPrice-$promotionPrice)}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Product Income</h5>
            </div>
            <div class="report-table">
                <table id="productReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Product</th>
                            <th class="text-end">Selling Price</th>
                            <th>QTY</th>
                            <th class="text-end">Profit(No promotion include)</th>
                            <th class="text-end">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $totalProductQty=0;
                            $totalProfitPrice=0;
                            $totalProductPrice=0;
                        ?>
                        @foreach ($reportData['product'] as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->product_name}}</td>
                                <td class=" text-end">{{$item->product_price}}</td>
                                <td>{{$item->total_qty}}</td>
                                <td class=" text-end">{{number_format($item->total_profit)}}</td>
                                <td class=" text-end">{{number_format($item->total_price)}}</td>
                            </tr>
                            <?php $totalProductQty+=$item->total_qty ?>
                            <?php $totalProfitPrice+=$item->total_profit ?>
                            <?php $totalProductPrice+=$item->total_price ?>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td></td>
                            <td>{{$totalProductQty}}</td>
                            <td class="text-end">{{number_format($totalProfitPrice)}}</td>
                            <td class="text-end">{{number_format($totalProductPrice)}}</td>
                        </tr>
                        <tr>
                            <td>Total Promotion Price</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-end">- {{number_format($promotionPrice)}}</td>
                        </tr>
                        <tr>
                            <td>Total Income</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-end">{{number_format($totalProductPrice-$promotionPrice)}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Product Category Income</h5>
            </div>
            <div class="report-table">
                <table id="categoryReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Category</th>
                            <th>QTY</th>
                            <th class="text-end">Profit(No promotion include)</th>
                            <th class="text-end">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $totalCategoryQty=0;
                            $totalCategoryProfit=0;
                            $totalCategoryPrice=0;
                        ?>
                        @foreach ($reportData['category'] as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->total_qty}}</td>
                                <td class=" text-end">{{number_format($item->total_profit)}}</td>
                                <td class=" text-end">{{number_format($item->total_price)}}</td>
                            </tr>
                            <?php $totalCategoryQty+=$item->total_qty ?>
                            <?php $totalCategoryProfit+=$item->total_profit ?>
                            <?php $totalCategoryPrice+=$item->total_price ?>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>Total</td>
                            <td></td>
                            <td>{{$totalCategoryQty}}</td>
                            <td class="text-end">{{number_format($totalCategoryProfit)}}</td>
                            <td class="text-end">{{number_format($totalCategoryPrice)}}</td>
                        </tr>
                        <tr>
                            <td>Total Promotion Price</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-end">- {{number_format($promotionPrice)}}</td>
                        </tr>
                        <tr>
                            <td>Total Income</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-end">{{number_format($totalCategoryPrice-$promotionPrice)}}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Date</h5>
            </div>
            <div class="report-table">
                <table id="dateReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th class="text-end">Total Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportData['date'] as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->date}}</td>
                                <td class=" text-end">{{number_format($item->total_price)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <div>
            <div class="d-flex justify-content-between">
                <h5 class="py-2">Payment</h5>
            </div>
            <div class="report-table">
                <table id="paymentReport">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Payment Name</th>
                            <th>User Name</th>
                            <th class="text-end">Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reportData['payment'] as $item)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->username}}</td>
                                <td class=" text-end">{{number_format($item->total_price)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('#cinemaReport,#productReport,#categoryReport,#paymentReport,#dateReport').DataTable({
            dom: 'Bfrtip', // Enable Buttons
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export <i class="fa-solid fa-download ms-2"></i>',
                    className: 'export-btn',
                    title: 'Product Report',
                }
            ]
        });
    });
</script>
@endsection
