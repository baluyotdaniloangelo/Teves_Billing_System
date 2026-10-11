@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	

		
<section class="section">
      <div class="row">

        <div class="col-lg-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"> {{ $title }} </h5>
				<div class="d-flex justify-content-end" id="user_option">
				<div class="btn-group" role="group" aria-label="Basic outlined example"style="margin-top: -50px; position: absolute;">
					    <select class="select_year form-select form-control" name="year" id='select_year'>
						
						<?php
						$current_year = date("Y");
						foreach(range(2021, $current_year) as $year){
							if($year==$current_year){
								echo "<option value='$year' selected>$year</option>";
							}else{
								echo "<option value='$year'>$year</option>";
							}
							
							//foreach(range(1, 12) as $month){
							//	echo date('Y.m.d', strtotime('Last day of ' . date('F', strtotime($year . '-' . $month . '-01')) . $year)) . PHP_EOL;
							//}
							
						}
												
						
						?>
						</select>
				  <button type="button" class="btn btn-success new_item bi bi-arrow-repeat" id="reloadMonthlyData" ></button>
				  </div>
				</div>

				 <div  style="max-height: 300px;">
					{!! $MonthlyChart->container() !!}
				</div>
            </div>
          </div>
        </div>
                   
      </div>
    </section>
</main>
@endsection

@section('page_footer')
    @include('layouts.footer_chart')
@endsection

@push('page_scripts')
{!! $MonthlyChart->script() !!}
<script>
    var original_api_url = {{ $MonthlyChart->id }}_api_url;

    $(".select_year").on("change", function () {
        var year = $(this).val();
        {{ $MonthlyChart->id }}_refresh(original_api_url + "?year=" + year);
    });

    $("body").on("click", "#reloadMonthlyData", function (event) {
        event.preventDefault();
        var year = $("#select_year").val();

        $.ajax({
            url: "{{ route('ReloadMonthlySales') }}",
            type: "POST",
            data: { year: year, _token: "{{ csrf_token() }}" },
            success: function () { reload_monthly_chart(year); },
            error: function (error) { console.log(error); alert(error); }
        });
    });

    function reload_monthly_chart(year) {
        {{ $MonthlyChart->id }}_refresh(original_api_url + "?year=" + year);
    }
</script>
@endpush
