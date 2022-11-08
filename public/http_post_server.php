<?php
	/*Last Updated March 17, 2022*/
	/*This will Catch the Meter Reading using PHP Post From the Gateway*/
	
	include('connection.php');
   
    $save_to_meter_data= mysqli_real_escape_string($conn,$_GET['save_to_meter_data']);
    $meter_id= mysqli_real_escape_string($conn,$_GET['meter_id']);
    
	$location= mysqli_real_escape_string($conn,$_GET['location']);
	
	$_datetime= mysqli_real_escape_string($conn,$_GET['datetime']);
	$datetime = str_replace('%20',' ',$_datetime);
	$vrms_a= mysqli_real_escape_string($conn,$_GET['vrms_a']);
    $vrms_b= mysqli_real_escape_string($conn,$_GET['vrms_b']);
    $vrms_c= mysqli_real_escape_string($conn,$_GET['vrms_c']);

    $irms_a= mysqli_real_escape_string($conn,$_GET['irms_a']);
    $irms_b= mysqli_real_escape_string($conn,$_GET['irms_b']);
    $irms_c= mysqli_real_escape_string($conn,$_GET['irms_c']);

    $freq= mysqli_real_escape_string($conn,$_GET['freq']);
    $pf= mysqli_real_escape_string($conn,$_GET['pf']);
    $watt= mysqli_real_escape_string($conn,$_GET['watt']);

    $va= mysqli_real_escape_string($conn,$_GET['va']);
    $var= mysqli_real_escape_string($conn,$_GET['var']);
	
    $wh_del= mysqli_real_escape_string($conn,$_GET['wh_del']);
    $wh_rec= mysqli_real_escape_string($conn,$_GET['wh_rec']);
    $wh_net= mysqli_real_escape_string($conn,$_GET['wh_net']);
    $wh_total= mysqli_real_escape_string($conn,$_GET['wh_total']);

    $varh_neg= mysqli_real_escape_string($conn,$_GET['varh_neg']);
    $varh_pos= mysqli_real_escape_string($conn,$_GET['varh_pos']);
    $varh_net= mysqli_real_escape_string($conn,$_GET['varh_net']);
    $varh_total= mysqli_real_escape_string($conn,$_GET['varh_total']);
    $vah_total= mysqli_real_escape_string($conn,$_GET['vah_total']);

    @$max_rec_kw_dmd= mysqli_real_escape_string($conn,$_GET['max_rec_kw_dmd']);
    @$max_rec_kw_dmd_time= mysqli_real_escape_string($conn,$_GET['max_rec_kw_dmd_time']);
    @$max_del_kw_dmd= mysqli_real_escape_string($conn,$_GET['max_del_kw_dmd']);
    @$max_del_kw_dmd_time= mysqli_real_escape_string($conn,$_GET['max_del_kw_dmd_time']);

    @$max_pos_kvar_dmd= mysqli_real_escape_string($conn,$_GET['max_pos_kvar_dmd']);
    @$max_pos_kvar_dmd_time= mysqli_real_escape_string($conn,$_GET['max_pos_kvar_dmd_time']);
    @$max_neg_kvar_dmd= mysqli_real_escape_string($conn,$_GET['max_neg_kvar_dmd']);
    @$max_neg_kvar_dmd_time= mysqli_real_escape_string($conn,$_GET['max_neg_kvar_dmd_time']);

    @$v_ph_angle_a= mysqli_real_escape_string($conn,$_GET['v_ph_angle_a']);
    @$v_ph_angle_b= mysqli_real_escape_string($conn,$_GET['v_ph_angle_b']);
    @$v_ph_angle_c= mysqli_real_escape_string($conn,$_GET['v_ph_angle_c']);

    @$i_ph_angle_a= mysqli_real_escape_string($conn,$_GET['i_ph_angle_a']);
    @$i_ph_angle_b= mysqli_real_escape_string($conn,$_GET['i_ph_angle_c']);
    @$i_ph_angle_c= mysqli_real_escape_string($conn,$_GET['i_ph_angle_c']);

	@$relay_status= mysqli_real_escape_string($conn,$_GET['relay_status']);

	@$mac_addr= mysqli_real_escape_string($conn,$_GET['mac_address']);
	@$soft_rev= mysqli_real_escape_string($conn,$_GET['soft_rev']);

	if($save_to_meter_data ==1){
		
		$sql = "INSERT INTO meter_data 
                            (
							location,
							meter_id,
							datetime,
                            vrms_a,
							vrms_b,
							vrms_c,
                            irms_a,
							irms_b,
							irms_c,
                            freq,
							pf,
							watt,
							va,
							var,
                            wh_del,
							wh_rec,
							wh_net,
							wh_total,
                            varh_neg,
							varh_pos,
							varh_net,
							varh_total,
							vah_total,
							max_rec_kw_dmd,
							max_rec_kw_dmd_time,
							max_del_kw_dmd,
							max_del_kw_dmd_time,
							max_pos_kvar_dmd,
							max_pos_kvar_dmd_time,
							max_neg_kvar_dmd,
							max_neg_kvar_dmd_time,
							v_ph_angle_a,
							v_ph_angle_b,
							v_ph_angle_c,
							i_ph_angle_a,
							i_ph_angle_b,
							i_ph_angle_c,
							mac_addr,
							soft_rev,
							relay_status
							)
                 VALUES 
				 
                           (
						   '$location',
						   '$meter_id',
						   '$datetime',
						   '$vrms_a',
						   '$vrms_b',
						   '$vrms_c',
						   '$irms_a',
						   '$irms_b',
						   '$irms_c',
						   '$freq',
						   '$pf',
						   '$watt',
						   '$va',
						   '$var',
						   '$wh_del',
						   '$wh_rec',
						   '$wh_net',
						   '$wh_total',
						   '$varh_neg',
						   '$varh_pos',
						   '$varh_net',
						   '$varh_total',
						   '$vah_total',
						   '$max_rec_kw_dmd',
						   '$max_rec_kw_dmd_time',
						   '$max_del_kw_dmd',
						   '$max_del_kw_dmd_time',
						   '$max_pos_kvar_dmd',
						   '$max_pos_kvar_dmd_time',
						   '$max_neg_kvar_dmd',
						   '$max_neg_kvar_dmd_time',
						   '$v_ph_angle_a',
						   '$v_ph_angle_b',
						   '$v_ph_angle_c',
						   '$i_ph_angle_a',
						   '$i_ph_angle_b',
						   '$i_ph_angle_c',
						   '$mac_addr',
						   '$soft_rev',
						   '$relay_status'
							)";	

						if (mysqli_query($conn, $sql)) {
						#echo "INSERT";
						} else {
						#echo "Error: " . $sql . "<br>" . mysqli_error($conn);
						}

				$_check_datetime = strtotime($datetime);
				$check_date = date('Y-m-d',$_check_datetime);
				$check_hrs = date('H',$_check_datetime);
				$server_date = date('Y-m-d');				
				$server_hrs = date('H');				

				if($check_date==$server_date && $check_hrs==$server_hrs){				

						/*UPDATE LOCATION DETAILS*/
						/*Update Status every Hour for Gateway List and Site List*/				
						$server_day_H =date('H');
						$server_day_I =date('i');
						
						/*Update at 12AM am and 12PM*/  
						# || $location=='CODE'
						
						if( ($server_day_H % 2 == 0) && ($server_day_I>=0 && $server_day_I <= 14)){ 
						/*UPDATE METER DETAILS*/
						$sql_meter_details_update = "UPDATE meter_details
							   SET last_log_update = '$datetime',
							   soft_rev = '$soft_rev'
							   WHERE `meter_site_name` = '$location' and 
							   meter_name = '$meter_id' ";
							
						mysqli_query($conn, $sql_meter_details_update);
						
						if (mysqli_query($conn, $sql_meter_details_update)) {
						} else {
						echo "Error: " . $sql_meter_details_update . "<br>" . mysqli_error($conn);
						}

						}
						
						}
						
							#RETURN DATETIME RIGHT AFTER RECIEVE OF METER DATA
							$server_time=date('Y-m-d H:i:s');
							echo "OK, $server_time";
							mysqli_close($conn);
						
	} 
	else {
		
							$server_time=date('Y-m-d H:i:s');
							echo "OK, $server_time";
		/*
		$sql_check_meter_id = "SELECT meter_id FROM live_meter_data WHERE meter_id = $meter_id";
		$result_check_meter_id = mysqli_query($conn, $sql_check_meter_id);							
		$count_meter_no_live_meter_data = mysqli_num_rows($result_check_meter_id);
						
		if ($count_meter_no_live_meter_data==0) {
			
				$sql = "INSERT INTO live_meter_data 
									(
									location,
									meter_id,
									datetime,
									vrms_a,
									vrms_b,
									vrms_c,
									irms_a,
									irms_b,
									irms_c,
									freq,
									pf,
									watt,
									va,
									var,
									wh_del,
									wh_rec,
									wh_net,
									wh_total,
									varh_neg,
									varh_pos,
									varh_net,
									varh_total,
									vah_total,
									max_rec_kw_dmd,
									max_rec_kw_dmd_time,
									max_del_kw_dmd,
									max_del_kw_dmd_time,
									max_pos_kvar_dmd,
									max_pos_kvar_dmd_time,
									max_neg_kvar_dmd,
									max_neg_kvar_dmd_time,
									v_ph_angle_a,
									v_ph_angle_b,
									v_ph_angle_c,
									i_ph_angle_a,
									i_ph_angle_b,
									i_ph_angle_c,
									mac_addr,
									soft_rev,
									relay_status
									)
						 VALUES
								   (
								   '$location',
								   '$meter_id',
								   '$datetime',
								   '$vrms_a',
								   '$vrms_b',
								   '$vrms_c',
								   '$irms_a',
								   '$irms_b',
								   '$irms_c',
								   '$freq',
								   '$pf',
								   '$watt',
								   '$va',
								   '$var',
								   '$wh_del',
								   '$wh_rec',
								   '$wh_net',
								   '$wh_total',
								   '$varh_neg',
								   '$varh_pos',
								   '$varh_net',
								   '$varh_total',
								   '$vah_total',
								   '$max_rec_kw_dmd',
								   '$max_rec_kw_dmd_time',
								   '$max_del_kw_dmd',
								   '$max_del_kw_dmd_time',
								   '$max_pos_kvar_dmd',
								   '$max_pos_kvar_dmd_time',
								   '$max_neg_kvar_dmd',
								   '$max_neg_kvar_dmd_time',
								   '$v_ph_angle_a',
								   '$v_ph_angle_b',
								   '$v_ph_angle_c',
								   '$i_ph_angle_a',
								   '$i_ph_angle_b',
								   '$i_ph_angle_c',
								   '$mac_addr',
								   '$soft_rev',
								   '$relay_status'
									)";	
			
							//if (mysqli_query($conn, $sql)) {
							#echo "INSERT";
							//} else {
							#echo "Error: " . $sql . "<br>" . mysqli_error($conn);
							//}
							
							#RETURN DATETIME RIGHT AFTER RECIEVE OF METER DATA
							$server_time=date('Y-m-d H:i:s');
							echo "OK, $server_time";
							mysqli_close($conn);
	
			} else {
					
				$sql = "UPDATE live_meter_data
						   SET 
						   location = '$location',
						   meter_id = '$meter_id',
						   datetime = '$datetime',
						   vrms_a = '$vrms_a',
						   vrms_b = '$vrms_b',
						   vrms_c = '$vrms_c',
						   irms_a = '$irms_a',
						   irms_b = '$irms_b',
						   irms_c = '$irms_c',
						   freq = '$freq',
						   pf = '$pf',
						   watt = '$watt',
						   va = '$va',
						   var = '$var',
						   wh_del = '$wh_del',
						   wh_rec = '$wh_rec',
						   wh_net = '$wh_net',
						   wh_total = '$wh_total',
						   varh_neg = '$varh_neg',
						   varh_pos = '$varh_pos',
						   varh_net = '$varh_net',
						   varh_total = '$varh_total',
						   vah_total = '$vah_total',
						   max_rec_kw_dmd = '$max_rec_kw_dmd',
						   max_rec_kw_dmd_time = '$max_rec_kw_dmd',
						   max_del_kw_dmd = '$max_del_kw_dmd',
						   max_del_kw_dmd_time = '$max_del_kw_dmd_time',
						   max_pos_kvar_dmd = '$max_pos_kvar_dmd',
						   max_pos_kvar_dmd_time = '$max_pos_kvar_dmd_time',
						   max_neg_kvar_dmd = '$max_neg_kvar_dmd',
						   max_neg_kvar_dmd_time = '$max_neg_kvar_dmd_time',
						   i_ph_angle_a = '$i_ph_angle_a',
						   i_ph_angle_b = '$i_ph_angle_b',
						   i_ph_angle_c = '$i_ph_angle_c',
						   mac_addr = '$mac_addr',
						   soft_rev = '$soft_rev',
						   relay_status = '$relay_status'
						WHERE meter_id='$meter_id'";
	
					#if (mysqli_query($conn, $sql)) {
					#echo "UPDATE";
					#} else {
					#echo "Error: " . $sql . "<br>" . mysqli_error($conn);
					#}
					
							#RETURN DATETIME RIGHT AFTER RECIEVE OF METER DATA
							$server_time=date('Y-m-d H:i:s');
							echo "OK, $server_time";
							mysqli_close($conn);
			}*/
			 
	}
?>
