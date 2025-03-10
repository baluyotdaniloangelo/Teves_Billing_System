<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Laravel Ajax ConsoleTvs Charts Tutorial - ItSolutionStuff.com</title>
    </head>
    <body>
  
        <h1>Laravel Ajax ConsoleTvs Charts Tutorial - ItSolutionStuff.com</h1>
  
        <select class="sel" name="year">
			<option value="2022">Year 2022</option>
			<option value="2021">Year 2021</option>
		    <option value="2020">Year 2020</option>
            <option value="2019">Year 2019</option>
            <option value="2018">Year 2018</option>
            <option value="2017">Year 2017</option>
        </select>
  
        <div style="width: 80%;margin: 0 auto;">
            {!! $chart->container() !!}
        </div>
          
      <script src="{{asset('NiceAdmin-pro/assets/vendor/chart.js/chart.js')}}"></script>

  <!-- Bootstrap core JavaScript-->
  <script src="{{asset('/jquery/jquery-3.6.0.min.js')}}"></script>
        {!! $chart->script() !!}
  
        <script type="text/javascript">
            var original_api_url = {{ $chart->id }}_api_url;
            $(".sel").change(function(){
                var year = $(this).val();
                {{ $chart->id }}_refresh(original_api_url + "?year="+year);
            });
        </script>
    </body>
</html>