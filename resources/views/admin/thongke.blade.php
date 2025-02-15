@extends('admin.layouts.master')
@section('content')

<div class="container-fluid"><!--begin::Row-->
  <div class="row"><!--begin::Col-->
    <div class="col-lg-3 col-6"><!--begin::Small Box Widget 1-->
      <div class="small-box text-bg-primary">
        <div class="inner">
          <h3>{{\App\Models\Bai_viet::count()}}</h3>
          <p>Bài viết</p>
        </div><svg class="small-box-icon" style="display:none" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"></path>
        </svg><a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
          More info <i class="bi bi-link-45deg"></i></a>
      </div><!--end::Small Box Widget 1-->
    </div><!--end::Col-->
    <div class="col-lg-3 col-6"><!--begin::Small Box Widget 2-->
      <div class="small-box text-bg-success">
        <div class="inner">
          <h3>{{\App\Models\DatPhong::count()}}</h3>
          <p>Số lượng đặt phòng</p>
        </div><svg class="small-box-icon" style="display:none" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z"></path>
        </svg><a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
          More info <i class="bi bi-link-45deg"></i></a>
      </div><!--end::Small Box Widget 2-->
    </div><!--end::Col-->
    <div class="col-lg-3 col-6"><!--begin::Small Box Widget 3-->
      <div class="small-box text-bg-warning">
        <div class="inner">
          <h3>{{\App\Models\User::count()}}</h3>
          <p>Người Dùng</p>
        </div><svg class="small-box-icon" style="display:none" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path d="M6.25 6.375a4.125 4.125 0 118.25 0 4.125 4.125 0 01-8.25 0zM3.25 19.125a7.125 7.125 0 0114.25 0v.003l-.001.119a.75.75 0 01-.363.63 13.067 13.067 0 01-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 01-.364-.63l-.001-.122zM19.75 7.5a.75.75 0 00-1.5 0v2.25H16a.75.75 0 000 1.5h2.25v2.25a.75.75 0 001.5 0v-2.25H22a.75.75 0 000-1.5h-2.25V7.5z"></path>
        </svg><a href="#" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
          More info <i class="bi bi-link-45deg"></i></a>
      </div><!--end::Small Box Widget 3-->
    </div><!--end::Col-->
    <div class="col-lg-3 col-6"><!--begin::Small Box Widget 4-->
      <div class="small-box text-bg-danger">
        <div class="inner">
          <h3>{{\App\Models\LienHe::count()}}</h3>
          <p>Liên Hệ</p>
        </div><svg class="small-box-icon" style="display:none" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path clip-rule="evenodd" fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"></path>
          <path clip-rule="evenodd" fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"></path>
        </svg><a href="#" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
          More info <i class="bi bi-link-45deg"></i></a>
      </div><!--end::Small Box Widget 4-->
    </div><!--end::Col-->


    
   
     <div class="row">
      <div class="col col-md-8">
        <h1>Thống kê số lượng đặt phòng theo ngày</h1>

      <form action="{{ route('admin.dashboard.index') }}" method="GET" class="form-inline">
        <div class="form-group">
          <label for="start_date">Từ ngày:</label>
          <input type="date" name="start_date" id="start_date" class="form-control">
        </div>

        <div class="form-group">
          <label for="end_date">Đến ngày:</label>
          <input type="date" name="end_date" id="end_date" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Lọc</button>
      </form> 

      

<canvas  id="mychart" style="width: 400px; height: 200px;"></canvas>

  <script>
    const labels = @json($labels);
    const data = @json($data);

    const chart = new Chart('mychart', {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Số đơn đặt hàng ',
          data: data,
          backgroundColor: '#FF8633',
          borderColor: '#FF8633',
        }
      
    ]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });
  </script>
      </div>

<div class="col col-md-4">
  <div class="col-md-7">
    <p class="text-center"><strong><h4>Tỉ Lệ Phòng Đã Đặt</h4></strong></p>
    
    </div><!-- /.progress-group -->
    <div class="progress-group">
        Phòng đã đặt/Tổng phòng
        <span class="float-end"><b>{{ $tong_so_phong_da_dat }}</b>/{{ $tong_so_phong }}</span>
        <div class="progress progress-sm">
            <div class="progress-bar text-bg-warning" style="" id="tile"></div>
        </div>
    </div><!-- /.progress-group -->

    <script>
      // Assuming 'tongSoPhongDaDat' and 'tongSoPhong' are updated dynamically
      function updateOccupancyRate() {
        const tongSoPhongDaDat = {{ $tong_so_phong_da_dat }};
        const tongSoPhong = {{ $tong_so_phong }};
    
        const occupancyRate = (tongSoPhongDaDat / tongSoPhong) * 100;
     
        const element = document.getElementById('tile');
  element.style.width = occupancyRate + '%';
       
    
      }
    
      // Update initially
      updateOccupancyRate();
    
      // You can update the occupancy rate dynamically based on your data source
      // (e.g., using websockets, AJAX, etc.)
    </script>
</div><!-- /.col -->
</div>
     </div>

     <h1>Lọc thống kê doanh thu</h1>
   
     <form action="{{ route('admin.dashboard.index') }}" method="GET" class="form-inline">
    

       <div class="form-group">
         <label for="loc_doanh_thu">Chọn ngày tháng cần lọc</label>
         <input type="date" name="loc_doanh_thu" id="loc_doanh_thu" class="form-control">
       </div>

       <button type="submit" class="btn btn-success">Lọc</button>
     </form> 
     <div class="row">
      <div class="col col-md-3">
        
        <h4>Thống kê doanh thu  năm</h4>

        <canvas id="myChart" width="400" height="200"></canvas>
        
        <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels:[{{ $locNam }}],
                datasets: [{
                    label: 'Doanh thu theo năm',
                    data: {!! json_encode($nam->pluck('doanh_thu')) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        </script>
      </div>
    {{-- end 1 --}}
      <div  class="col col-md-3">
        <h4>Thống kê doanh thu tháng</h4>

        <canvas id="myChart2" width="400" height="200"></canvas>
        
        <script>
        var ctx = document.getElementById('myChart2').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels:[{{ $thang }}],
                datasets: [{
                    label: 'Doanh thu theo tháng',
                    data: {!! json_encode($doanh_thu_thang_nay->pluck('doanh_thu')) !!},
                    backgroundColor: '#FF7F50',
                    borderColor: '#FF7F50',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        </script>
      </div>
    {{-- end 2 --}}
      <div  class="col col-md-3">
        <h4>Thống kê doanh thu tuần</h4>

        <canvas id="myChart3" width="400" height="200"></canvas>
        
        <script>
        var ctx = document.getElementById('myChart3').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels:['tuần  '],
                datasets: [{
                    label: 'Doanh thu theo tuần',
                    data: {!! json_encode($doanh_thu_tuan_nay->pluck('doanh_thu')) !!},
                    backgroundColor: '#FFFF00',
                    borderColor: '#FFFF00',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        </script>
      </div>
      {{-- end 3 --}}
      <div  class="col col-md-3">
        <h4>Thống kê doanh thu ngày</h4>

        <canvas id="myChart4" width="400" height="200"></canvas>
        
        <script>
        var ctx = document.getElementById('myChart4').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels:[{{ $today }}],
                datasets: [{
                    label: 'Doanh thu theo ngày',
                    data: {!! json_encode($doanh_thu_hom_nay->pluck('doanh_thu')) !!},
                    backgroundColor: '#7CFC00',
                    borderColor: '#7CFC00',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
        </script>
      </div>
      {{-- end 4 --}}
    </div>

    
@endsection
