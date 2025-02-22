@extends('welcome')

@section('title', 'Thống kê doanh thu')

@section('content')
@php
use Illuminate\Support\Carbon;
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <div class="main-content">
     
        <div class="page-content">
            <div class="container-fluid" >
                <form action="{{route('order.statistic')}}" class="row" method="get">
                    <div class="col-md-4" style="margin-top:30px !important;">
                        <label for="">Từ ngày</label>
                        <input type="date" name="start" class="form-control" value="{{Carbon::parse($data_start_)->format('Y-m-d')}}">
                    </div>
                    <div class="col-md-4" style="margin-top:30px !important;">
                        <label for="">Đến ngày ngày</label>

                        <input type="date" name="end" class="form-control" value="{{Carbon::parse($data_end)->format('Y-m-d')}}">
                    </div>
                    <div class="col-md-4" style="margin-top:30px !important;">
                        <button class="btn btn-primary" style="margin-top:28px;"><i class="fa-solid fa-filter" style="margin-right:5px;"></i>Tìm kiếm</button>
                    </div>
                    
                </form>
                
                 
                    
                 
              
               
                <div class="mt-3 ">
                    <div style=" height:100vh;">
                        <h5 class="text-center"> Thống kê theo biểu đồ</h5>
                        <canvas class="mb-5" id="myChart" ></canvas>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('myChart');
    

    var dateArray = @json($date);
    var countData = @json($total);
  

   
    new Chart(ctx, {
        type: 'line'
        , data: {
            labels: dateArray
            , datasets: [{
                label: 'Thống kê doanh thu theo ngày'
                , data: countData
                ,    backgroundColor: [
      'rgba(255, 99, 132, 0.2)',
      'rgba(255, 159, 64, 0.2)',
      'rgba(255, 205, 86, 0.2)',
      'rgba(75, 192, 192, 0.2)',
      'rgba(54, 162, 235, 0.2)',
      'rgba(153, 102, 255, 0.2)',
      'rgba(201, 203, 207, 0.2)'
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
      'rgb(54, 162, 235)',
      'rgb(153, 102, 255)',
      'rgb(201, 203, 207)'
    ],
        borderWidth: 1
                    }]
                }
                , options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
    </script>

@endsection