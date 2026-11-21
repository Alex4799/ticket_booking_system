@extends('main.admin.layout.master')
@section('title')
    Product Report
@endsection
@section('content')
<main class="content p-2 mb-5">
    <div class="container-fluid">
        <form action="" method="get" class="bg-body-secondary rounded px-3 py-2 mb-4">
            @csrf
            <div class="d-flex gap-3 flex-wrap">
                <div>
                    <label class="label-small mb-0">Start Date</label>
                    <input type="date" style="font-size: 11pt;" name="start_date" value="{{$startDate}}" id="" class="form-control @error('start_date') is-invalid @enderror">
                    @error('start_date')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div>
                    <label class="label-small mb-0">End Date</label>
                    <input type="date" style="font-size: 11pt;" name="end_date" value="{{$endDate}}" id="" class="form-control @error('end_date') is-invalid @enderror">
                    @error('end_date')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div>
                    <label class="label-small mb-0">Cinema</label>
                    <select name="cinema" class="form-select" style="font-size: 11pt;">
                        <option value="">All</option>
                        @foreach ($cinema as $item)
                            <option value="{{$item->id}}" @if (request('cinema')==$item->name) selected @endif>{{$item->name}}</option>
                        @endforeach
                    </select>
                    @error('cinema')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                {{-- <div>
                    <label class="label-small mb-0">Products</label>
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
            <div class="d-flex align-items-center justify-content-end w-100 mt-3 mb-2">
                <button class="d-flex align-items-center btn btn-primary me-2" style="font-size: 10pt;"><i class="fa-solid fa-filter me-2" style="font-size: 8pt;"></i>Filter</button>
                <a href="{{route('admin#reportProduct')}}" class="d-flex align-items-center btn btn-secondary" style="font-size: 10pt;" ><i class="fa-solid fa-rotate-left me-2" style="font-size: 10pt;"></i>Reset</a>
            </div>
        </form>
        <div class="row">
            <div class="col-lg-6 offset-lg-6">
                @if (request('products'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 10pt;">
                        When filtering products, promotion discounts are not included because promotions are applied at the full order level.
                        <button type="button" class="btn-close border-none shadow-none btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>
        <div class="summary-cards mb-3">
            <div class="row">
                @foreach ($reportData['summary'] as $item)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card-content @if ($item->status==0) pending @elseif($item->status==1) success @elseif($item->status==3) cancel @elseif($item->status==4) booking @else reject @endif">
                            <label class="@if ($item->status==0) pending @elseif($item->status==1) success @elseif($item->status==3) cancel @elseif($item->status==4) booking @else reject @endif">Total Snack @if ($item->status==0) Pending @elseif($item->status==1) Success @elseif($item->status==3) Cancel @elseif($item->status==4) Booking @else Reject @endif</label>
                            <div class="d-flex align-items-center justify-content-between gap-1 flex-wrap">
                                <h5>{{number_format($item->total_price)}} <label class="label-small ms-1">Ks</label></h5>
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

        <div class="row report-row">
            <div class="col-md-7 report-column">
                <div class="report-container-box">
                    <div>
                        <div class="report-title">
                            <div>
                                <h5><i class="fa-solid fa-building me-2"></i>Cinema Income</h5>
                            </div>
                        </div>
                        <div class="report-table">
                            <table id="cinemaReport">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Cinema</th>
                                        <th>Profit</th>
                                        <th>Income</th>
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
                                        <td class="text-end fw-bold">{{number_format($cinemaTotalPrice-$promotionPrice)}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 report-column">
                <div class="report-container-box">
                    <div>
                        <div class="report-title">
                            <div>
                                <h5><i class="fa-solid fa-credit-card me-2"></i>Payment Income</h5>
                            </div>
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
            </div>
        </div>
        <div class="report-container-box">
            <div>
                <div class="report-title">
                    <div>
                        <h5><i class="fa-solid fa-square-poll-vertical me-2"></i>Product Income</h5>
                    </div>
                </div>
                <div class="report-table">
                    <table id="productReport">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Product</th>
                                <th class="text-center">QTY</th>
                                <th class="text-end">Selling Price</th>
                                <th class="text-end">Profit(No promotion include)</th>
                                <th class="text-end">Income</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $totalProductQty=0;
                                $totalProfitPrice=0;
                                $totalProductPrice=0;;
                            ?>
                            @foreach ($reportData['product'] as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->product_name}}</td>
                                    <td class="text-center">{{$item->total_qty}}</td>
                                    <td class="text-end">{{number_format($item->product_price)}}</td>
                                    <td class="text-end">{{number_format($item->total_profit)}}</td>
                                    <td class="text-end">{{number_format($item->total_price)}}</td>
                                </tr>
                                <?php $totalProductQty+=$item->total_qty ?>
                                <?php $totalProfitPrice+=$item->total_profit ?>
                                <?php $totalProductPrice+=$item->total_price ?>
                            @endforeach
                            <tr>
                                <td>Total</td>
                                <td></td>
                                <td class="text-center">{{$totalProductQty}}</td>
                                <td></td>
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
                                <td class="text-end fw-bold">{{number_format($totalProductPrice-$promotionPrice)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="report-container-box">
            <div>
                <div class="report-title">
                    <div>
                        <h5><i class="fa-solid fa-bars-staggered me-2"></i>Category Income</h5>
                    </div>
                </div>
                <div class="report-table">
                    <table id="categoryReport">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Category</th>
                                <th class="text-center">QTY</th>
                                <th class="text-end">Profit(No promotion include)</th>
                                <th class="text-end">Income</th>
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
                                    <td class="text-center">{{$item->total_qty}}</td>
                                    <td class="text-end">{{number_format($item->total_profit)}}</td>
                                    <td class="text-end">{{number_format($item->total_price)}}</td>
                                </tr>
                                <?php $totalCategoryQty+=$item->total_qty ?>
                                <?php $totalCategoryProfit+=$item->total_profit ?>
                                <?php $totalCategoryPrice+=$item->total_price ?>
                            @endforeach
                            <tr>
                                <td>Total</td>
                                <td></td>
                                <td class="text-center">{{$totalCategoryQty}}</td>
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
                                <td class="text-end fw-bold">{{number_format($totalCategoryPrice-$promotionPrice)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="report-container-box">
            <div>
                <div class="report-title">
                    <div>
                        <h5><i class="fa-solid fa-calendar-day me-2"></i>Date</h5>
                    </div>
                </div>
                <div class="report-table">
                    <table id="dateReport">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date</th>
                                <th class="text-end">Income</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach ($reportData['date'] as $item)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$item->date}}</td>
                                    <td class="text-end">{{number_format($item->total_price)}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection


@section('script')
<script>
    $(document).ready(function() {
        $('#cinemaReport,#productReport,#categoryReport,#paymentReport,#dateReport').DataTable({
            dom: 'Bfrtip',
            ordering: false,
            scrollX: true,
            autoWidth: false,
            language: {
                search: '',
                searchPlaceholder: 'Search',
                infoEmpty: 'No records available',
                paginate: {
                        previous: '<i class="fa-solid fa-chevron-left"></i>',
                        next: '<i class="fa-solid fa-chevron-right"></i>'
                    }
            },
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa-solid fa-download me-2"></i> Export',
                    className: 'add-btn',
                    title: 'Product Report'
                }
            ]
        });
    });
</script>

{{-- <script>
    //.................................. multi select start .......................
    document.querySelectorAll('.multi-select').forEach(multi => {
        const placeholder = multi.dataset.placeholder || 'Select' ;
        const selectBox = multi.querySelector('.select-box');
        const dropdown = multi.querySelector('.multi-dropdown');
        const selectedText = multi.querySelector('.selected-text');
        const checkboxes = dropdown.querySelectorAll("input[type='checkbox']");
        const selectAll = dropdown.querySelector("input[value='All']");

        // toggle dropdown
        selectBox.addEventListener('click', function(e){
            e.stopPropagation();
            document.querySelectorAll('.multi-dropdown').forEach(md => {
                if(md != dropdown){ md.classList.remove('show') };
            });
            dropdown.classList.toggle('show');
        });
        dropdown.addEventListener('click', e => e.stopPropagation());

        //checkbox logic
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function(){
                if(cb.value === 'All'){
                    checkboxes.forEach(x => {
                        if(x.value !== 'All') x.checked = cb.checked;
                    });
                }
                else{
                    const others = [...checkboxes].filter(x => x.value !== 'All');
                    selectAll.checked = others.every(x => x.checked);
                }
                updateSelected();
            });
        });

        // update select text
        function updateSelected(){
            const others = [...checkboxes].filter(x => x.value != 'All');
            const checked = others.filter(x => x.checked);
            let temp=[];
            checked.forEach(element => {
                temp.push(element.dataset.label);
            });
            let productData= JSON.stringify(temp);

            document.getElementById('productsFilter').value = productData;

            const total = others.length;

            if(checked.length === 0){
                selectedText.innerText = placeholder;
            }
            else if(checked.length === total){
                selectedText.innerText = 'All';
            }
            else if(checked.length <= 1){
                selectedText.innerText = checked.map(x => x.dataset.label || x.value).join(", ");
            }
            else{
                selectedText.innerText = checked.length + ' selected';
            }
        };
        updateSelected();
    });

    //close dropdown when clicking outside
    document.addEventListener('click', function(){
        document.querySelectorAll('.multi-dropdown').forEach(md => {
            md.classList.remove('show');
        });
    });
    //.................................. multi select end  ...........................

</script> --}}
@endsection
