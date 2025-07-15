<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Statement of Account - Teves Gasoline Station</title>
  <style>
    body {
      font-family: Arial, sans-serif;
     /* margin: 40px;*/
      /*background-color: #f9f9f9;*/
    }
    .header, .info, table {
      width: 100%;
      border-collapse: collapse;
    }
    .header {
      text-align: left;
      margin-bottom: 10px;
    }
    .header h2 {
      margin: 0;
      color: #1a1a1a;
    }
    .header p {
      margin: 2px 0;
    }
    .section-title {
      background-color: #800080;
      color: #fff;
      padding: 8px;
      font-weight: bold;
    }
    .info {
      margin-bottom: 10px;
	  border: 0px solid #ccc;
    }
    .info td {
      padding: 4px 8px;
	  border: 0px solid #ccc;
    }
    table {
      background-color: #fff;
      border: 1px solid #ccc;
    }
    table th, table td {
      border: 1px solid #ccc;
      padding: 8px;
      /*text-align: right;*/
      font-size: 12px;
    }
    table th {
      background-color: #f0f0f0;
      color: #333;
    }
    table td.left {
      text-align: left;
    }
  </style>
</head>
<body>
			<?php
				
				$billing_date = strtoupper(date("M/d/Y"));
				$_billing_time=date_create($receivable_data[0]['billing_time']);
				
				$logo = $receivable_header['branch_logo'];
			?>
			
  <div class="header">
  <table class="info">
   <tr>
      <td align="center" rowspan="4" width="10%"><img src="{{public_path('client_logo/')}}<?=$logo;?>" style="width:112px;"></td>
	  <td width="60%"><b style="font-size:18px;"><?=$receivable_header['branch_name'];?></b></td>
	  <td nowrap >DATE PRINTED:</td>
	  <td>JAN/17/2025</td>
   </tr>
   <tr>
	  <td><div style="font-size:12px;"><?=$receivable_header['branch_address'];?></div></b></td>
	 
   </tr>
   <tr>
	  <td><div style="font-size:12px;">VAT REG. TIN : <?=$receivable_header['branch_tin'];?></div></td>
   </tr> 
   <tr>
	  <td><div style="font-size:12px;"><?=$receivable_header['branch_owner'];?> - <?=$receivable_header['branch_owner_title'];?></div></td>
   </tr>
  </table>
  </div>

  <div class="section-title" style="text-align:center;">SOA - Summary</div>

  <table class="info">
    <tr>
      <td width="18%">ACCOUNT NAME:</td>
	  <td width="52%"style="border-bottom:1px solid #000;">MARK OROZCO</td>
      <td><strong>PERIOD:</strong></td>
	  <td>JAN/17/2025</td>
    </tr>
    <tr>
      <td>TIN:</td>
	  <td>000-000</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>ADDRESS:</td>
	  <td>Madrid Surigao del Sur</td>
      <td></td>
    </tr>
  </table>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Date</th>
        <th>Time</th>
        <th class="left">Description</th>
        <th>Gross Amount</th>
        <th>VATable</th>
        <th>WTax</th>
        <th>Net Amount</th>
        <th>Current Balance</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td><td>01/17/2025</td><td>21:15</td><td class="left">G-T-AR-2442<br>SO PERIOD OF JAN 03-15,2025</td>
        <td>75,791.50</td><td>67,670.98</td><td>0.00</td><td>75,791.50</td><td>75,791.50</td>
      </tr>
      <tr>
        <td>2</td><td>02/01/2025</td><td>21:15</td><td class="left">G-T-AR-2530<br>SO PERIOD OF JAN 17-31,2025</td>
        <td>136,648.25</td><td>122,007.37</td><td>0.00</td><td>136,648.25</td><td>212,439.75</td>
      </tr>
      <tr>
        <td>3</td><td>02/17/2025</td><td>21:15</td><td class="left">G-T-AR-2611<br>SO PERIOD OF FEB 01-15,2025</td>
        <td>47,367.34</td><td>42,292.27</td><td>0.00</td><td>47,367.34</td><td>212,439.75</td>
      </tr>
      <tr>
        <td>4</td><td>03/03/2025</td><td>21:15</td><td class="left">G-T-AR-2678<br>SO PERIOD OF FEB 17-27,2025</td>
        <td>16,387.34</td><td>14,631.55</td><td>0.00</td><td>16,387.34</td><td>212,439.75</td>
      </tr>
      <tr>
        <td>5</td><td>03/17/2025</td><td>21:15</td><td class="left">GT-AR-2734<br>SO PERIOD OF MAR 03-14,2025</td>
        <td>121,614.59</td><td>108,584.46</td><td>0.00</td><td>121,614.59</td><td>334,054.34</td>
      </tr>
      <tr>
        <td>6</td><td>04/02/2025</td><td>21:15</td><td class="left">GT-AR-2797<br>SO PERIOD OF MAR 17-29,2025</td>
        <td>102,827.70</td><td>91,810.45</td><td>0.00</td><td>102,827.70</td><td>436,882.04</td>
      </tr>
      <tr>
        <td>7</td><td>04/16/2025</td><td>21:15</td><td class="left">GT-AR-2867<br>SO PERIOD OF APRIL 1-15,2025</td>
        <td>120,128.03</td><td>107,257.17</td><td>0.00</td><td>120,128.03</td><td>557,010.07</td>
      </tr>
      <tr>
        <td>8</td><td>05/01/2025</td><td>21:15</td><td class="left">GT-AR-2931<br>SO PERIOD OF APRIL 16-30,2025</td>
        <td>61,714.11</td><td>55,101.88</td><td>0.00</td><td>61,714.11</td><td>618,724.18</td>
      </tr>
      <tr>
        <td>9</td><td>05/16/2025</td><td>21:15</td><td class="left">GT-AR-2985<br>SO PERIOD OF MAY 1-15,2025</td>
        <td>44,447.12</td><td>39,684.92</td><td>0.00</td><td>44,447.12</td><td>663,171.30</td>
      </tr>
      <tr>
        <td>10</td><td>06/02/2025</td><td>21:15</td><td class="left">GT-AR-3055<br>SO PERIOD OF MAY 16-31,2025</td>
        <td>97,059.07</td><td>86,659.89</td><td>0.00</td><td>97,059.07</td><td>760,230.37</td>
      </tr>
    </tbody>
  </table>

</body>
</html>
