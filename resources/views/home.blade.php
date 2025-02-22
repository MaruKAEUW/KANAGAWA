@extends('welcome')
<style>
    body{
    margin-top:20px;
    background:#FAFAFA;
}
.order-card {
    color: #fff !important;
}

.bg-c-blue {
    background: linear-gradient(45deg,#4099ff,#73b4ff) !important;
}

.bg-c-green {
    background: linear-gradient(45deg,#2ed8b6,#59e0c5) !important;
}

.bg-c-yellow {
    background: linear-gradient(45deg,#FFB64D,#ffcb80) !important;
}

.bg-c-pink {
    background: linear-gradient(45deg,#FF5370,#ff869a) !important;
}


.card {
    border-radius: 5px;
    -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    border: none;
    margin-bottom: 30px;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.card .card-block {
    padding: 25px;
}

.order-card i {
    font-size: 26px;
}

.f-left {
    float: left;
    flex: 0 0 70%;
}

.f-right {
    float: right;
}
</style>
@section('content')
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

<div class="container">
    <div class="row">
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-blue order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Tổng só khóa học</h6>
                    <h2 class="text-right align-items-center jusitfy-content-between w-100 d-flex">
                        <i class="fa fa-cart-plus f-left"></i><span>{{ $sum_course }}</span></h2>
                   
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-green order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Tổng số bài viết</h6>
                    <h2 class="text-right align-items-center jusitfy-content-between w-100 d-flex">
                        <i class="fa fa-rocket f-left"></i>
                        <span>{{ $sum_new }}</span>
                    </h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-yellow order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Tổng khóa học đã duyệt </h6>
                    <h2 class="text-right align-items-center jusitfy-content-between w-100 d-flex">
                        <i class="fa fa-refresh f-left"></i><span>{{ $sum_oder_2 }}</span></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-xl-3">
            <div class="card bg-c-pink order-card">
                <div class="card-block">
                    <h6 class="m-b-20">Tổng khóa học chưa duyệt </h6>
                    <h2 class="text-right align-items-center jusitfy-content-between w-100 d-flex">
                        <i class="fa fa-credit-card f-left"></i><span>
                            {{ $sum_oder_1 }}</span></h2>
                </div>
            </div>
        </div>
	</div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Top khóa học bán chạy</h4>
                    <div class="table-responsive">
                        <table class="table table-hover table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">(#) Id</th>
                                    <th scope="col">Tên sản phẩm</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Giá bán</th>
                                    {{-- <th scope="col" colspan="2">Status</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($result as $re)
                                    @if($re['product_id']->status == 1 && $re['product_id']->deleted_at == 1)
                                        <tr>    
                                        
                                                <th scope="row">{{'PR000'.$re['product_id']->id}}</th>
                                                <td>
                                                                                    
                                                    <div>
                                                        <img src="{{ ($re['product_id']->images)}}" alt=""
                                                            class="avatar-xs rounded-circle me-2" 
                                                            style="border-radius:5px !important; width:50px; height:50px;"> {{$re['product_id']->name}}
                                                    </div>
                                                </td>
                                                <td>{{$re['quantity']}}</td>
                                                <td>{{number_format($re['product_id']->price)}}/vnđ</td>
                                        
                                        </tr>
                                    @endif
                                @endforeach
                              
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection