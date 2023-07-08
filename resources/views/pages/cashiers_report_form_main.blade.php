@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	
    <section class="section">	  
          <div class="card">
			  <div class="card">
				<div class="card-header ">
				  <h5 class="card-title">&nbsp;{{ $title }}</h5>
					<div class="d-flex justify-content-end" id="cashier_report_option"></div>				  
				  </div>
				</div>			  
		 
            <div class="card-body">			
				<div class="p-d3">
									
						<form class="g-2 needs-validation" id="CashierReportformNew">
						
						<div class="row mb-2">
						  <label for="teves_branch" class="col-sm-3 col-form-label">Branch</label>
						  <div class="col-sm-4">
							<select class="form-select form-control" required="" name="teves_branch" id="teves_branch">
								<?php $teves_branch = $CashiersReportData[0]['teves_branch']; ?>
								<option value="GT" <?php if($teves_branch=='GT'){ echo "selected";} else{} ?>>GT</option>
								<option value="Teves" <?php if($teves_branch=='Teves'){ echo "selected";} else{} ?>>Teves</option>
							</select>
							
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="forecourt_attendant" class="col-sm-3 col-form-label">Forecourt Attendant</label>
						  <div class="col-sm-4">
							<input type="text" class="form-control" name="forecourt_attendant" id="forecourt_attendant" value="{{ $CashiersReportData[0]['forecourt_attendant'] }}">
							<span class="valid-feedback" id="forecourt_attendantError" title="Required"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="report_date" class="col-sm-3 col-form-label">Report Date</label>
						  <div class="col-sm-4">
							<input type="date" class="form-control " name="report_date" id="report_date" value="{{ $CashiersReportData[0]['report_date'] }}" required>
							<span class="valid-feedback" id="report_dateError"></span>
						  </div>
						</div>
						
						<div class="row mb-2">
						  <label for="shift" class="col-sm-3 col-form-label">Shift</label>
						  <div class="col-sm-4">
							<input type="text" class="form-control " name="shift" id="shift" value="{{ $CashiersReportData[0]['shift'] }}">
							<span class="valid-feedback" id="shiftError"></span>
						  </div>
						</div>						
									
						</div>
						 <div class="text-center">				
						  <button type="submit" class="btn btn-success btn-sm bi bi-save-fill navbar_icon" id="update-cashiers-report"> Submit</button>
						  <button type="reset" class="btn btn-primary btn-sm bi bi-backspace-fill navbar_icon" id="clear-cashiers-report"> Reset</button>
						  </div>
						  </form><!-- End Multi Columns Form -->
						<hr>
						<div class="card-body">
						  <!-- Default Tabs -->
						  <ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
							<li class="nav-item flex-fill" role="presentation">
							  <button class="nav-link w-100 active" id="Product-tab" data-bs-toggle="tab" data-bs-target="#Product-justified" type="button" role="tab" aria-controls="product" aria-selected="false" tabindex="-1">Product</button>
							</li>
							<li class="nav-item flex-fill" role="presentation">
							  <button class="nav-link w-100" id="other_sales" data-bs-toggle="tab" data-bs-target="#other_sales-justified" type="button" role="tab" aria-controls="other_sales" aria-selected="false" tabindex="-1">Other Sales</button>
							</li>
							<li class="nav-item flex-fill" role="presentation"><!--MISCELLANEOUS ITEMS-->
							  <button class="nav-link w-100" id="miscellaneous-item-tab" data-bs-toggle="tab" data-bs-target="#contact-justified" type="button" role="tab" aria-controls="contact" aria-selected="true">Contact</button>
							</li>
							<li class="nav-item flex-fill" role="presentation">
							  <button class="nav-link w-100" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-justified" type="button" role="tab" aria-controls="contact" aria-selected="true">Contact</button>
							</li>
						  </ul>
						  
						  <div class="tab-content pt-2" id="myTabjustifiedContent">
							@include('pages.cashiers_report_form_p1') 
							@include('pages.cashiers_report_form_p2') 
							
							<div class="tab-pane fade" id="contact-justified" role="tabpanel" aria-labelledby="miscellaneous-item">
							  Saepe animi et soluta ad odit soluta sunt. Nihil quos omnis animi debitis cumque. Accusantium quibusdam perspiciatis qui qui omnis magnam. Officiis accusamus impedit molestias nostrum veniam. Qui amet ipsum iure. Dignissimos fuga tempore dolor.
							</div>
							
							<div class="tab-pane fade" id="contact-justified" role="tabpanel" aria-labelledby="contact-tab">
							  Saepe animi et soluta ad odit soluta sunt. Nihil quos omnis animi debitis cumque. Accusantium quibusdam perspiciatis qui qui omnis magnam. Officiis accusamus impedit molestias nostrum veniam. Qui amet ipsum iure. Dignissimos fuga tempore dolor.
							</div>
							
						  </div><!-- End Default Tabs -->

						</div>
						<datalist id="product_data_other_sales"><span style="font-family: DejaVu Sans; sans-serif;"><option label="66.5 | Super 91" data-id="11" data-price="66.5" value="Super 91"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="61.2 | Diesel" data-id="12" data-price="61.2" value="Diesel"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="67.4 | Premium 95" data-id="13" data-price="67.4" value="Premium 95"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="284.45 | Zoelo Extreme 1L" data-id="14" data-price="284.45" value="Zoelo Extreme 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="274.61 | Brake Fluid 900ml" data-id="15" data-price="274.61" value="Brake Fluid 900ml"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="4359 | Petrolubes Gear Oil 140" data-id="16" data-price="4359" value="Petrolubes Gear Oil 140"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="192 | 800ML Landex Brake Fluid DOT 4" data-id="17" data-price="192" value="800ML Landex Brake Fluid DOT 4"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="199 | Radiator Coolant 1L" data-id="18" data-price="199" value="Radiator Coolant 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="275 | ATF Oil 1L" data-id="19" data-price="275" value="ATF Oil 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="89 | Air Freshener (E - World)" data-id="20" data-price="89" value="Air Freshener (E - World)"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="4420 | Zoelo Extreme 1 pail" data-id="21" data-price="4420" value="Zoelo Extreme 1 pail"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="1080 | Zoelo Extreme 4L" data-id="22" data-price="1080" value="Zoelo Extreme 4L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="18320 | Deruibo 599 Mining Lug 12.00R20 PR" data-id="23" data-price="18320" value="Deruibo 599 Mining Lug 12.00R20 PR"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="5178.3 | Grease MP3" data-id="24" data-price="5178.3" value="Grease MP3"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="251 | Cyclomax Titan 4T 1L" data-id="25" data-price="251" value="Cyclomax Titan 4T 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="130 | Premium Coolant White 1L" data-id="26" data-price="130" value="Premium Coolant White 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="16250 | Ligid 12R20" data-id="27" data-price="16250" value="Ligid 12R20"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="243.88 | Gear Oil 140 1L" data-id="28" data-price="243.88" value="Gear Oil 140 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="680 | Zoelo Syntech (Diesel)" data-id="29" data-price="680" value="Zoelo Syntech (Diesel)"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="308 | WD 40 277ml" data-id="30" data-price="308" value="WD 40 277ml"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="109 | Brake Fluid Phoenix 250ml" data-id="31" data-price="109" value="Brake Fluid Phoenix 250ml"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="182 | Grasa MP3 500g" data-id="32" data-price="182" value="Grasa MP3 500g"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="241 | Cyclomax Titan 4T 800ml" data-id="33" data-price="241" value="Cyclomax Titan 4T 800ml"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="234 | Gear Oil 90 1L" data-id="34" data-price="234" value="Gear Oil 90 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="260 | Phoenix Cyclomax 2T 1L" data-id="35" data-price="260" value="Phoenix Cyclomax 2T 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="3330 | Petrolubes Oil 10 1pail" data-id="36" data-price="3330" value="Petrolubes Oil 10 1pail"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="250 | Accelerate Extra Prem Monograde" data-id="37" data-price="250" value="Accelerate Extra Prem Monograde"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="3230 | Oil 40 1pail" data-id="38" data-price="3230" value="Oil 40 1pail"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="1200 | Delo Sporty 5W40 1L" data-id="39" data-price="1200" value="Delo Sporty 5W40 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="1250 | LPG" data-id="40" data-price="1250" value="LPG"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="359 | WD40 352ml" data-id="41" data-price="359" value="WD40 352ml"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="3230 | Hydraulic Oil 68" data-id="42" data-price="3230" value="Hydraulic Oil 68"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="226 | Landex Fluid 900ml" data-id="43" data-price="226" value="Landex Fluid 900ml"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="207 | Racing 800ml" data-id="44" data-price="207" value="Racing 800ml"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="24100 | Phoenix Oil 40 1drum" data-id="45" data-price="24100" value="Phoenix Oil 40 1drum"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="2830 | Oil 10 1pail" data-id="46" data-price="2830" value="Oil 10 1pail"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="304 | Racing 4T 1L" data-id="47" data-price="304" value="Racing 4T 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="148 | WD 40 100ml" data-id="48" data-price="148" value="WD 40 100ml"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="4081.2 | Pail Gear Oil 90" data-id="49" data-price="4081.2" value="Pail Gear Oil 90"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="4799 | Road Cruza 195 R15 RA350" data-id="50" data-price="4799" value="Road Cruza 195 R15 RA350"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="2801.95 | 1Case Cyclomax Titan 4T 800ML" data-id="51" data-price="2801.95" value="1Case Cyclomax Titan 4T 800ML"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="2867.44 | 1Case Cyclomax Titan 4T 1L" data-id="52" data-price="2867.44" value="1Case Cyclomax Titan 4T 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="2831.95 | 1Case Cyclomax Racing 4T 800ML" data-id="53" data-price="2831.95" value="1Case Cyclomax Racing 4T 800ML"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="3225.77 | 1Case Cyclomax Racing 4T 1L" data-id="54" data-price="3225.77" value="1Case Cyclomax Racing 4T 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="1819.14 | 1Case Cyclomax Force 4T 800ML" data-id="55" data-price="1819.14" value="1Case Cyclomax Force 4T 800ML"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="2846.77 | 1Case Cyclomax Force 4T 1L" data-id="56" data-price="2846.77" value="1Case Cyclomax Force 4T 1L"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="2763 | 1Case Fork Oil 200ML" data-id="57" data-price="2763" value="1Case Fork Oil 200ML"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="25550 | DRUM Petrolubes 0il 40" data-id="58" data-price="25550" value="DRUM Petrolubes 0il 40"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="25550 | DRUM Phoenix Hydraulic Oil 68" data-id="59" data-price="25550" value="DRUM Phoenix Hydraulic Oil 68"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="150 | Armor All Protectant 118ml" data-id="60" data-price="150" value="Armor All Protectant 118ml"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="8700 | Motolite Battery" data-id="61" data-price="8700" value="Motolite Battery"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="950 | Fuel Filter" data-id="62" data-price="950" value="Fuel Filter"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="3230 | Engine Oil 40" data-id="63" data-price="3230" value="Engine Oil 40"></option></span><span style="font-family: DejaVu Sans; sans-serif;"><option label="3230 | Engine Oil 30" data-id="64" data-price="3230" value="Engine Oil 30"></option></span></datalist>
                   
				</div>									        
            </div>
          </div>

    </section>
</main>

@endsection