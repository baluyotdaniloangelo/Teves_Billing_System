@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	

		
<section class="section">
      <div class="row">

        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"> {{ $title }} </h5>
				<div class="d-flex justify-content-end" id="user_option">
				<div class="btn-group" role="group" aria-label="Basic outlined example"style="margin-top: -50px; position: absolute;">
					    <select class="select_year form-select form-control" name="year">
						<option value="2022">Year 2022</option>
						<option value="2022">Year 2022</option>
						<option value="2021">Year 2021</option>
						<option value="2020">Year 2020</option>
						</select>
				  <button type="button" class="btn btn-success new_item bi bi-arrow-repeat" data-bs-toggle="modal" data-bs-target="#CreateUserModal"></button>
				  </div>
				</div>
		
				
				
				 <div  style="max-height: 400px;">
					{!! $chart->container() !!}
				</div>
				
				
				
				{!! $chart->script() !!}


            </div>
          </div>
        </div>
                   
            </div>
          </div>
