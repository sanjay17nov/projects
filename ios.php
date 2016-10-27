<?php
include("include/connection.php");
$tag=$_POST['tag'];
if($tag == 'login_professor')
{
	$email=trim($_POST['email']);
	$password=trim($_POST['password']);
	if($email!="" && $password!="")
	{
	$sql="select * from students where email='".$email."' and password='".$password."' and type='prof' ";
	$rs=mysql_query($sql);
	$res=mysql_fetch_array($rs);
	if($res['email']==$email)
		{
			$response["status"] = "0";
			$response["error"] = "perfect";
			
			$response["id"] = $res["id"];
			//$response["fname"] = $res["fname"];
			//$response["lname"] = $res["lname"];
			$response["first_name"] = $res["first_name"];
			$response["email"] = $res["email"];
			$response["department"] = $res["department"];
			$response["dob"] = $res["dob"];
			$response["gender"] = $res["gender"];
			$response["university"] = $res["university"];
			$response["pic"] = $res["pic"];
			$response["last_online"] = $res["last_online"];
			$response["user_status"] = $res["status"];
			$response["education"] = $res["education"];
			$response["start_time"] = $res["start_time"];
			$response["end_time"] = $res["end_time"];
			$response["office_room"] = $res["office_room"];
			$response["desig"] = $res["desig"];
			$response["type"] = $res["type"];
		}
		else {
		// user not found
		$response["status"] = "2";
		$response["error"] = "Incorrect Email or Password!";	
		}
	}
	else {
		// user not found
		$response["status"] = "2";
		$response["error"] = "Please fill both content Email or Password!";	
	}
	echo json_encode($response);
	return;
}
if($tag == 'login_student')
{
	$email=trim($_POST['email']);
	$password=trim($_POST['password']);
	if($email!="" && $password!="")
	{
	$sql="select * from students where email='".$email."' and password='".$password."'  and type='stu'";
	$rs=mysql_query($sql);
	$res=mysql_fetch_array($rs);
	if($res['email']==$email)
		{
			$response["status"] = "0";
			$response["error"] = "perfect";
			
			$response["id"] = $res["id"];
			//$response["fname"] = $res["fname"];
			//$response["lname"] = $res["lname"];
			$response["first_name"] = $res["first_name"];
			$response["email"] = $res["email"];
			$response["department"] = $res["department"];
			$response["dob"] = $res["dob"];
			$response["gender"] = $res["gender"];
			$response["university"] = $res["university"];
			$response["pic"] = $res["pic"];
			$response["last_online"] = $res["last_online"];
			$response["user_status"] = $res["status"];
			$response["education"] = $res["education"];
			$response["type"] = $res["type"];
		}
		else {
		// user not found
		$response["status"] = "2";
		$response["error"] = "Incorrect Email or Password!";	
		}
	}
	else {
		// user not found
		$response["status"] = "2";
		$response["error"] = "Please fill both content Email or Password!";	
	}
	echo json_encode($response);
	return;
}
if($tag == 'add_event')
{
	$username=trim($_POST['username']);
	$event_name=trim($_POST['event_name']);
	$desc=trim($_POST['desc']);
	$start=date("Y-m-d", strtotime(trim($_POST['start'])));
	$end=date("Y-m-d", strtotime(trim($_POST['end'])));
	$end_time=date("H:i:s", strtotime(trim($_POST['end_time'])));
	$start_time=date("H:i:s", strtotime(trim($_POST['start_time'])));
	$type = $_POST['type'];
	$location = $_POST['location'];
	$recurring = $_POST['recurring'];
	$days = $_POST['days'];
	$ar = json_decode($_POST['tag_users']);
	if($type == "class")
	{
			if($username!="" && $event_name!="" && $start!="" && $end!="" && $end_time!="" && $start_time!="" )
			{
				$event_name_lower=strtolower($event_name);
				$temp_slug = str_replace(' ', '-', $event_name_lower);
				//Check event slug unique or not
				$sql_slug_check="select * from 	event_meta where event_slug like'".$temp_slug."%'";
				$rs_slug_check=mysql_query($sql_slug_check);
				$num_rows = mysql_num_rows($rs_slug_check);
				if($num_rows>0)
						{
							$num_rows=$num_rows+1;
							$final_slug=$temp_slug."-".$num_rows;
						}
				else
				{
					$final_slug=$temp_slug;
				}
				
				//store event
				
				$sql_insert_event_meta="insert into event_meta set event_slug='".$final_slug."' , event_name='".$event_name."' , descp='".$desc."' , start='".$start."' , end='".$end."' , start_time='".$start_time."' , end_time='".$end_time."' , username='".$username."', type='" .$type. "', days='".$days."' , recurring='".$recurring."' , location='".$location."' ";
				mysql_query($sql_insert_event_meta);
				$un=time();
				$class_code = classCode($un);
				$sql_class_create="insert into class set class_title='".$event_name."' , class_code='".$class_code."' , profasar_id='".$username."' , event_slug='".$final_slug."'";
				mysql_query($sql_class_create);
				$sql_cls_member="insert into event_users set class_code='".$class_code."' , tagged_username='".$username."' , tagged_by_username='".$username."' , type='class' , event_slug='".$final_slug."' , status='Accepted'";
				$sql_cls_group="insert into class_group_member set class_code='".$class_code."' , user_id='".$username."' , user_type='prof' ";
				$rs_cls_group=mysql_query($sql_cls_group);
				
	$rs_cls_member=mysql_query($sql_cls_member);
				//exit;
				$response["status"] = "0";
				$response["class_code"] = $class_code;
				echo json_encode($response);
				return;
			}
			else{
				$response["status"] = "1";
				echo json_encode($response);
				return;
			}
	}
	else
	{
	if($username!="" && $event_name!="" && $start!="" && $end!="" && $end_time!="" && $start_time!="" )
			{
				$event_name_lower=strtolower($event_name);
				$temp_slug = str_replace(' ', '-', $event_name_lower);
				//Check event slug unique or not
				$sql_slug_check="select * from 	event_meta where event_slug like'".$temp_slug."%'";
				$rs_slug_check=mysql_query($sql_slug_check);
				$num_rows = mysql_num_rows($rs_slug_check);
				if($num_rows>0)
						{
							$num_rows=$num_rows+1;
							$final_slug=$temp_slug."-".$num_rows;
						}
				else
				{
					$final_slug=$temp_slug;
				}
				
				//store event
				
				$sql_insert_event_meta="insert into event_meta set event_slug='".$final_slug."' , event_name='".$event_name."' , descp='".$desc."' , start='".$start."' , end='".$end."' , start_time='".$start_time."' , end_time='".$end_time."' , username='".$username."', type='" .$type. "', days='".$days."' , recurring='".$recurring."' , location='".$location."' ";
				mysql_query($sql_insert_event_meta);
				$sql_check_event="select * from event_meta where event_slug='".$final_slug."'";
				$rs_check_event=mysql_query($sql_check_event);
				$res_check_event=mysql_fetch_array($rs_check_event);
				$sql_insert_event_user="insert into event_users set event_slug='".$final_slug."' , tagged_username='".$username."' , status='Accepted' , tagged_by_username='".$username."' , type='".$type."' ";
				$rs_insert_event_user=mysql_query($sql_insert_event_user);
				
				$sql_user_det="select * from students where id='".$username."'";
				$rs_user_det=mysql_query($sql_user_det);
				$res_user_det=mysql_fetch_array($rs_user_det);
				
				
				$count = count($ar);
				for($c=0;$c<$count;$c++)
				{
					$sql_insert_event_user2="insert into event_users set event_slug='".$final_slug."' , tagged_username='".$ar[$c]."' , status='Pending' , tagged_by_username='".$username."' ";
					$rs_insert_event_user2=mysql_query($sql_insert_event_user2);
					if($type == "event")
					{
							$sql_token="select * from students where id='".$ar[$c]."'";
							$rs_token=mysql_query($sql_token);
							while($row = mysql_fetch_array($rs_token))
							{
								$tokens[] = $row["token"];
							}
							
							$sql_notifi="insert into notification set type='".$type."' , title='Event Invite' , message='".$res_user_det['first_name']."has invited you to ".$event_name."' , user_id='".$ar[$c]."' , date='".$start."' , event_slug='".$final_slug."' ";
							$rs_notifi=mysql_query($sql_notifi);
							$msg=$res_user_det['first_name']."has invited you to ".$event_name;
							$message = array( "title"=> "Event Invite" , "body" => $msg , "date" => $ar[$c] , "event_slug" => $final_slug);
							$data = array( "title"=> "Event Invite" , "body" => $msg , "date" => $ar[$c] , "event_slug" => $final_slug , "type" => "event" ); 
							$message_status = send_notification($tokens, $message , $data);
					}
					elseif( $type == "appo")
					{
							$sql_token="select * from students where id='".$ar[$c]."'";
							$rs_token=mysql_query($sql_token);
							while($row = mysql_fetch_array($rs_token))
							{
								$tokens[] = $row["token"];
							}
							
							$sql_notifi="insert into notification set type='".$type."' , title='Appointment Request' , message='".$res_user_det['first_name']." at ".$start."' , user_id='".$ar[$c]."' , date='".$start."' , event_slug='".$final_slug."' ";
							$rs_notifi=mysql_query($sql_notifi);
							$msg=$res_user_det['first_name']." at ".$start;
							$message = array( "title"=> "Appointment Request" , "body" => $msg , "date" => $ar[$c] , "event_slug" => $final_slug);
							$data = array( "title"=> "Appointment Request" , "body" => $msg , "date" => $ar[$c] , "event_slug" => $final_slug , "type" => "appo");
							$message_status = send_notification($tokens, $message, $data);
					}
				}
				
				
				
				$response["status"] = "0";
				$response["push"] = $message_status;
				
				echo json_encode($response);
				return;
			}
			else{
				$response["status"] = "1";
				echo json_encode($response);
				return;
			}	
	}
	
	
}

function send_notification ($tokens, $message , $data=NULL)
	{
		$url = 'https://fcm.googleapis.com/fcm/send';
		
			$fields = array(
			 'registration_ids' => $tokens,
			 'priority'=> 10,
			 'notification' => $message,
			 'data' => $data
			);
		$headers = array(
			'Authorization:key=AIzaSyDoxsCtRp6ZPpci8OwnRSwwtzVBhMX8zD8',
			'Content-Type:application/json'
			);
			//print_r($fields);
	   $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POST, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	   curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
       $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       return $result;
		
		
	}
//generate unique code
function classCode($num) {
    $scrambled = (240049382*$num + 37043083) % 308915753;
    return base_convert($scrambled, 10, 26);
}

if($tag == 'get_all_events')
{
	$username = trim($_POST['username']);
	$month = trim($_POST['month']);
	$year = trim($_POST['year']);
	$date=$year."-".$month;
//	

	if( $username != "" )
	{		
		$sql="select * from event_meta where event_slug in ( select distinct event_slug from event_users where tagged_username='".$username."') and start BETWEEN '2016-01-01' AND  '2016-12-31'";
		//)='".$username."' and start like '".$date."-%'";
		$rs=mysql_query($sql);
		while($row = mysql_fetch_array($rs))
		{
			$event_slug = $row['event_slug'];
			
			$event = array('event_slug' => $row['event_slug'], 
			'event_name' => $row['event_name'], 
			'start' => $row['start'], 
			'end' => $row['end'], 
			'descp' => $row['descp'], 
			'pic' => $row['pic'], 
			'username' => $row['username'], 
			'start_time' => $row['start_time'], 
			'end_time' => $row['end_time'], 
			'type'=>$row['type'],
			'location'=>$row['location'] ,
			'recurring'=>$row['recurring'] ,
			'days'=>$row['days'] ,
			'notes'=>$row['notes'] );
			
			//get other users , for resolve
			$tagged_by_user = array();
			$tagged_user = array();
			
			if ($row['type'] == 'event') {
				$sql2 = "select * from event_users where event_slug='" .$event_slug. "' and tagged_username='" .$username. "'";	
			} else {
				$sql2 = "select * from event_users where event_slug='" .$event_slug. "' and tagged_username!=tagged_by_username";
			}
			//get class information
			if($row['type'] == 'class') 
			{ 
				$sql_class_info="select * from class where event_slug='".$row['event_slug']."'";
				$rs_class_info=mysql_query($sql_class_info);
				$res_class_info=mysql_fetch_array($rs_class_info);
				$class = array('class_title' => $res_class_info['class_title'],
				'class_code' => $res_class_info['class_code'] );
			}
			
			$rs2 = mysql_query($sql2);	
			if ($row2 = mysql_fetch_array($rs2) ) {
				$tagged_by_user = getMinimumUserDetails($row2['tagged_by_username']);
				$tagged_user = getMinimumUserDetails($row2['tagged_username']);
				$status = $row2['status'];
			}
			 
			$d[] = array ( 'event_details' => $event, 'tagged_user' => $tagged_user, 'tagged_by' => $tagged_by_user,  'status' => $status , 'class' => $class );
		}
		$sql_check_stu="select * from students where id='".$username."' and type='stu'";
		$rs_check_stu=mysql_query($sql_check_stu);
		$con=mysql_num_rows($rs_check_stu);
	
		if($con>0)
		{
			$sql_pro_select="select distinct tagged_by_username from event_users where tagged_username = '".$username."' and type = 'class'";
			$rs_pro_select=mysql_query($sql_pro_select);
			while($res_pro_select = mysql_fetch_array($rs_pro_select))
			{
				$sql_pro_event="select * from event_meta where username='".$res_pro_select['tagged_by_username']."' and type='Office'";
				$rs_pro_event=mysql_query($sql_pro_event);
				while($res_pro_event = mysql_fetch_array($rs_pro_event))
				{
					$event = array('event_slug' => $res_pro_event['event_slug'], 
					'event_name' => $res_pro_event['event_name'], 
					'start' => $res_pro_event['start'], 
					'end' => $res_pro_event['end'], 
					'descp' => $res_pro_event['descp'], 
					'pic' => $res_pro_event['pic'], 
					'username' => $res_pro_event['username'], 
					'start_time' => $res_pro_event['start_time'], 
					'end_time' => $res_pro_event['end_time'], 
					'type'=>$res_pro_event['type'],
					'location'=>$res_pro_event['location'] ,
					'recurring'=>$res_pro_event['recurring'] ,
					'days'=>$res_pro_event['days'] ,
					'notes'=>$res_pro_event['notes'] );
					
					$tagged_by_user = getMinimumUserDetails($res_pro_event['username']);
				$tagged_user = getMinimumUserDetails($res_pro_event['username']);
					
					$d[] = array ( 'event_details' => $event, 'tagged_user' => $tagged_user, 'tagged_by' => $tagged_by_user );
				}
				
			}
		}
		
		
		$sql_events_string="select * from students where id='".$username."' ";
		$rs_events_string=mysql_query($sql_events_string);
		$res_events_string=mysql_fetch_array($rs_events_string);
			
			
		$array = array('array' => $d , 'events_string' => $res_events_string['events_string'] , 'google_string' => $res_events_string['google_string']);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
	else{
		$response["status"] = "1";
		echo json_encode($response);
		return;
	}
	
}
if($tag == 'get_all_students')
{
	$university=$_POST['university'];
	$department=$_POST['department'];
	$user=$_POST['id'];
	$sql_user="select * from students where university='".$university."' and department='".$department."' and id!='".$user."' and type='stu' ";
	$rs=mysql_query($sql_user);
	$co=mysql_num_rows($rs);
	
	if($co>0)
	{
		while($res = mysql_fetch_array($rs))
		{
		$d[] = array ( 'id' => $res['id'], 'first_name' => $res['first_name'], 'pic' => $res['pic'], 'email'=>$res['email'], 'gender'=>$res['gender'], 'dob'=>$res['dob'], 'university'=>$res['university'], 'department'=>$res['department'], 'education'=>$res['education'], 'user_status'=>$res['status'], 'type'=>$res['type'] );
		}
				
		$array = array('array' => $d);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
	else{
		$response["status"] = "1";
		echo json_encode($response);
		return;
	}
}

if($tag == 'get_all_teachers')
{
	$university=$_POST['university'];
	$department=$_POST['department'];
	$user=$_POST['id'];
	$sql_user="select * from students where university='".$university."' and department='".$department."' and id!='".$user."' and type='prof' ";
	$rs=mysql_query($sql_user);
	$co=mysql_num_rows($rs);
	
	if($co>0)
	{
		while($res = mysql_fetch_array($rs))
		{
		$d[] = array ( 'id' => $res['id'], 'first_name' => $res['first_name'], 'pic' => $res['pic'], 'email'=>$res['email'], 'gender'=>$res['gender'], 'dob'=>$res['dob'], 'university'=>$res['university'], 'department'=>$res['department'], 'education'=>$res['education'], 'user_status'=>$res['status'], 'type'=>$res['type'], 'status'=>$res['status'] , 'start_time'=>$res['start_time'] , 'end_time'=>$res['end_time'] );
		}
				
		$array = array('array' => $d);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
	else{
		$response["status"] = "1";
		echo json_encode($response);
		return;
	}
}

if($tag == 'all_notification')
{
	$username = $_POST['username'];
	$sql_noti="select * from event_users where tagged_username='".$username."' or tagged_by_username='".$username."' order by id desc";
	$rs_noti=mysql_query($sql_noti);
	$array = array();
	while($res = mysql_fetch_array($rs_noti))
	{
		//echo "hello";
		//if($res['tagged_username'] != $res['tagged_by_username'] )
	//	{
			$event = getEventDetails($res['event_slug']);			
			$tagged_by_user = getMinimumUserDetails($res['tagged_by_username']);
			$tagged_user = getMinimumUserDetails($res['tagged_username']);
			
			//$d[] = array ( 'event_name' => $res_event['event_name'] , 'event_slug' => $res_event['event_slug'] , 'descp' => $res_event['descp'] , 'tagged_by_username' => $res['tagged_by_username'], 'pic' => $res_tag_user['pic'] ,  'username' => $res_tag_user['first_name'] ,  'status' => $res['status']   );
			$d[] = array ( 'event_details' => $event, 'tagged_user' => $tagged_user, 'tagged_by' => $tagged_by_user,  'status' => $res['status'] );
			//exit;
			//echo $res['tagged_username'];
		//echo $res['tagged_by_username'];	
			$array = array('array' => $d);		
	//	}
	//	else
	//	{
			
		//echo "hello";	
			
	//	}
	}
	header('Content-type: application/json');
				
	echo json_encode( $array );
}
if($tag == 'update_notification' )
{
	$tagged_by_username=$_POST['tagged_by_username'];
	$tagged_username=$_POST['tagged_username'];
	$status=$_POST['status'];
	$event_slug=$_POST['event_slug'];
	$msg=$_POST['msg'];
	if($tagged_by_username!="" && $tagged_username!="" && $status!="" && $event_slug!="")
	{
		
		if($status=='Resolved')
		{
			$sql_up="update event_users set status='".$status."' where tagged_username='".$tagged_username."' and tagged_by_username='".$tagged_by_username."' and event_slug='".$event_slug."'";
			$sql_msg="insert into message set event_slug='".$event_slug."' , from_user='".$tagged_username."' , to_user='".$tagged_by_username."' , msg='".$msg."'  , time=now() ";
		}
		else
		{
			$sql_up="update event_users set status='".$status."' where tagged_username='".$tagged_username."' and tagged_by_username='".$tagged_by_username."' and event_slug='".$event_slug."'";
			
			
			
							$sql_token="select * from students where id='".$tagged_by_username."'";
							$rs_token=mysql_query($sql_token);
							$sql_token1="select * from students where id='".$tagged_username."'";
							$rs_token1=mysql_query($sql_token1);
							$row1 = mysql_fetch_array($rs_token1);
							while($row = mysql_fetch_array($rs_token))
							{
								$tokens[] = $row["token"];
							}
							
							$sql_notifi="insert into notification set type='appo' , title='Appointment ".$status."' , message='".$row1['first_name']." has ".$status."' , user_id='".$tagged_by_username."' , date='".$start."' , event_slug='".$event_slug."' ";
							$rs_notifi=mysql_query($sql_notifi);
							$msg=$row1['first_name']." has ".$status;
							$message = array( "title"=> "Appointment '".$status."'" , "body" => $msg , "date" => $ar[$c] , "event_slug" => $event_slug);
							$data = array( "title"=> "Appointment '".$status."'" , "body" => $msg , "date" => $ar[$c] , "event_slug" => $event_slug , "type" => "appo" );
							$message_status = send_notification($tokens, $message, $data);
			
			
		}
		$rs_up=mysql_query($sql_up);
		$rs_msg=mysql_query($sql_msg);
		if (!$rs_up) {
			$response["status"] = "1";
		}
		else
		{
		$response["status"] = "0";
		$response["push"] = $message_status;
		}
	}
	echo json_encode($response);
	return;	
}

function getUserDetails($id) {
	$sql = "select * from students where id='" . $id . "'";
	$res = mysql_query($sql);
	$arr = array();
	while($row = mysql_fetch_array($res)) {
		$arr = array(
			"id" => $row["id"],
			"first_name" => $row["first_name"],  
			"email" => $row["email"],
			"department" => $row["department"],
			"dob" => $row["dob"],
			"gender" => $row["gender"],
			"university" => $row["university"],
			"pic" => $row["pic"],
			"last_online" => $row["last_online"],
			"user_status" => $row["status"],
			"education" => $row["education"],
			"office_room" => $row["office_room"],
			"desig" => $row["desig"],
			"type" => $row["type"],
			"start_time" => $row['start_time'],
			"end_time" => $row['end_time']
		);
	}
	return $arr;
}

function getMinimumUserDetails($id) {
	$sql = "select * from students where id='" . $id . "'";
	$res = mysql_query($sql);

	$arr = array();
	while($row = mysql_fetch_array($res)) {
		$arr = array(
			"id" => $row["id"],
			"first_name" => $row["first_name"],
			"start_time" => $row['start_time'],
			"end_time" => $row['end_time'],
			//"email" => $row["email"],
			//"department" => $row["department"],
			//"dob" => $row["dob"],
			//"gender" => $row["gender"],
			//"university" => $row["university"],
			"pic" => $row["pic"],
			//"last_online" => $row["last_online"],
			"status" => $row["status"],
			"token" => $row["token"],
			//"education" => $row["education"],
			"type" => $row["type"]
		);
	}
	return $arr;
}

function getEventDetails($event_slug) {
	$sql = "select * from event_meta where event_slug='" . $event_slug . "'";
	$res = mysql_query($sql);
	$arr = array();
	while($row = mysql_fetch_array($res)) {
		$arr = array(
			'event_slug' => $row['event_slug'], 
			'event_name' => $row['event_name'], 
			'start' => $row['start'], 
			'end' => $row['end'], 
			'descp' => $row['descp'], 
			'pic' => $row['pic'], 
			'username' => $row['username'], 
			'start_time' => $row['start_time'], 
			'end_time' => $row['end_time'], 
			'type'=>$row['type'] 
		);
	}
	return $arr;
}
if($tag=="add_message")
{
	$msg=mysql_real_escape_string($_POST['msg']);
	$to_user=$_POST['to_user'];
	$from_user=$_POST['from_user'];
	$event_slug=$_POST['event_slug'];
	if($event_slug != "" && $from_user !="" && $to_user != "" && $msg !="")
	{
		$sql_msg="insert into message set event_slug='".$event_slug."' , from_user='".$from_user."' , to_user='".$to_user."' , msg='".$msg."'"; // , time=now() ";
		$rs_msg=mysql_query($sql_msg);
		
		$from_user_det = getMinimumUserDetails($from_user);
		$sql_token="select * from students where id ='".$to_user."'";
		$rs_token=mysql_query($sql_token);
		while($row = mysql_fetch_array($rs_token))
		{
			$tokens[] = $row["token"];
		}
		
		
							//$tokens[] = $to_user_det["token"];
							$message = array( "title"=> $from_user_det['first_name'] , "to_user" => $to_user , "from_user" => $from_user , "type" => "resolve" , "body" => $msg , "event_slug" => $event_slug );
							$data = array( "title"=> $from_user_det['first_name'] , "to_user" => $to_user , "from_user" => $from_user , "type" => "resolve" , "body" => $msg , "event_slug" => $event_slug );
							$message_status = send_notification($tokens, $message , $data);
		
		
	}
	if (!$rs_msg) {
		$response["status"] = "1";
	}
	else
	{
		$response["status"] = "0";
	}
	echo json_encode($response);
	return;
}
if($tag == "get_all_messages" )
{
	//$event_slug = trim($_POST['event_slug ']);
	//exit;
	$event_slug = $_POST['event_slug'];
	if($event_slug != "")
	{
		$sql="select * from message where event_slug='".$event_slug."' order by time";
		$rs=mysql_query($sql);
		
		while($res=mysql_fetch_array($rs))
		{
		$from_user = getMinimumUserDetails($res['from_user']);
		$to_user = getMinimumUserDetails($res['to_user']);
		$d[] = array ( 'id' => $res['id'], 'event_slug' => $res['event_slug'], 'from_user' => $from_user, 'to_user'=>$to_user, 'msg'=>$res['msg'] , time=>$res['time'] );
		}
				
		$array = array('array' => $d);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
}
if($tag == "get_all_groups" )
{
	$username = trim($_POST['username']);
	$university = trim($_POST['university']);
	$department = trim($_POST['department']);
	//$date=$year."-".$month;
	if($username!="" && $department!="" && $university!="")
	{
		$sql_groups="select * from students where id='".$username."'and university='".$university."' and department='".$department."' ";
		$rs_groups=mysql_query($sql_groups);
		$res_groups=mysql_fetch_array($rs_groups);
		$sql_det="select * from group_master where group_id='".$res_groups['group_id']."'";
		$rs_det=mysql_query($sql_det);
		$res_det=mysql_fetch_array($rs_det);
		$d[] = array ( 'group_id' => $res_det['group_id'], 'group_name' => $res_det['group_name'], 'group_image' => $res_det['group_image'] );
					
		$array = array('array' => $d);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
	else{
		$response["status"] = "1";
		echo json_encode($response);
		return;
	}
}	
if($tag == "get_all_groups_msg" )
{

	//$event_slug = trim($_POST['event_slug ']);
	//exit;
	$group_id = $_POST['group_id'];
	
	if($group_id != "")
	{
		$sql="select * from group_chat where group_id='".$group_id."' order by time";
		$rs=mysql_query($sql);
		
		while($res=mysql_fetch_array($rs))
		{
		$from_user = getMinimumUserDetails($res['from_member']);
		//$to_user = getMinimumUserDetails($res['to_user']);
		$d[] = array ( 'id' => $res['id'], 'group_id' => $res['group_id'], 'from_user' => $from_user,  'msg'=>$res['test'] , time=>$res['time'] );
		}
				
		$array = array('array' => $d);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
	else{
		$response["status"] = "1";
		echo json_encode($response);
		return;
	}
}
if($tag=="add_message_group")
{
	$msg=mysql_real_escape_string($_POST['msg']);
	//$to_user=$_POST['to_user'];
	$from_user=$_POST['from_user'];
	$group_id=$_POST['group_id'];
	if($group_id != "" && $from_user !="" && $msg!="")
	{
		$sql_msg="insert into group_chat set group_id='".$group_id."' , from_member='".$from_user."' , test='".$msg."'"; // , time=now() ";
		$rs_msg=mysql_query($sql_msg);
		$from_user_det = getMinimumUserDetails($from_user);
		$sql_group_members="select * from class_group_member where class_code='".$group_id."'";
		$rs_group_members=mysql_query($sql_group_members);
		while($row = mysql_fetch_array($rs_group_members))
		{
				$row["user_id"];
				
				if($row["user_id"] != $from_user)
				{
					$sql_token="select * from students where id='".$row["user_id"]."'";
					$rs_token=mysql_query($sql_token);
					while($row1 = mysql_fetch_array($rs_token))
					{
						if($row1["token"] != "" && $row1["type"] != "prof")
						{
							$tokens[] = $row1["token"];	
						}
					}
						
				}
				
				
		}
		
							$message = array( "title"=> $from_user_det['first_name'] , "group_id" => $group_id , "from_user" => $from_user , "type" => "group-chat" , "body" => $msg );
							$data = array( "title"=> $from_user_det['first_name'] , "group_id" => $group_id , "from_user" => $from_user , "type" => "group-chat" , "body" => $msg );
							$message_status = send_notification($tokens, $message , $data);
							
		
	}
	if (!$rs_msg) {
		$response["status"] = "1";
	}
	else
	{
		$response["status"] = "0";
		$response["push"] = $message_status;
	}
	echo json_encode($response);
	return;
}
if($tag=="faculty_status")
{
	$userid=$_POST['userid'];
	$user_status=$_POST['user_status'];
	if($user_status!="" && $userid!="")
	{
		$sql_up="update students set status='".$user_status."' where id='".$userid."' ";
		$rs_up=mysql_query($sql_up);
		if(!rs_up)
		{
		$response["status"] = "1";
		}
		else
		{
			$response["status"] = "0";
		}
	}
	else
	{
		$response["status"] = "0";
	}
	echo json_encode($response);
	return;
}

if($tag=="add_message_chat")
{
	$msg=mysql_real_escape_string($_POST['msg']);
	$to_user=$_POST['to_user'];
	$from_user=$_POST['from_user'];
	//$event_slug=$_POST['event_slug'];
	if($from_user !="" && $to_user != "" && $msg !="")
	{
		$sql_msg="insert into message_chat set from_user='".$from_user."' , to_user='".$to_user."' , msg='".$msg."'"; // , time=now() ";
		$rs_msg=mysql_query($sql_msg);
		$from_user_det = getMinimumUserDetails($from_user);
		$sql_token="select * from students where id ='".$to_user."'";
		$rs_token=mysql_query($sql_token);
		while($row = mysql_fetch_array($rs_token))
		{
			$tokens[] = $row["token"];
		}
		
		
							//$tokens[] = $to_user_det["token"];
							$message = array( "title"=> $from_user_det['first_name'] , "to_user" => $to_user , "from_user" => $from_user , "type" => "single chat" , "body" => $msg );
							$data = array("title"=> $from_user_det['first_name'] , "to_user" => $to_user , "from_user" => $from_user , "type" => "single-chat" , "body" => $msg );
							$message_status = send_notification($tokens, $message , $data);
							
							
	}
	if (!$rs_msg) {
		$response["status"] = "1";
	}
	else
	{
		$response["status"] = "0";
		$response["push"] = $message_status;
	}
	echo json_encode($response);
	return;
}
if($tag == "get_all_messages_chat" )
{
	//$event_slug = trim($_POST['event_slug ']);
	//exit;
	//$event_slug = $_POST['event_slug'];
	$to_user=$_POST['to_user'];
	$from_user=$_POST['from_user'];
	if($to_user!="" && $from_user!="" )
	{
		$sql="select * from message_chat where ( to_user='".$to_user."' and from_user='".$from_user."' ) or ( from_user='".$to_user."' and to_user='".$from_user."' ) order by time";
		$rs=mysql_query($sql);
		
		while($res=mysql_fetch_array($rs))
		{
		$from_user = getMinimumUserDetails($res['from_user']);
		$to_user = getMinimumUserDetails($res['to_user']);
		$d[] = array ( 'id' => $res['id'], 'from_user' => $from_user, 'to_user'=>$to_user, 'msg'=>$res['msg'] , time=>$res['time'] );
		}
				
		$array = array('array' => $d);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
}

if($tag == "get_personal_chats" )
{
	//$event_slug = trim($_POST['event_slug ']);
	//exit;
	//$event_slug = $_POST['event_slug'];
	$to_user=$_POST['userid'];
	if($to_user!="")
	{
		$sql="select DISTINCT  to_user ,  from_user from message_chat where ( to_user='".$to_user."' ) or ( from_user='".$to_user."' ) order by time";
		$rs=mysql_query($sql);
		
		while($res=mysql_fetch_array($rs))
		{
			if($res['from_user'] == $to_user)
			{
				$user[] = array ( 'user' =>getMinimumUserDetails($res['to_user']));
			}
			elseif($res['to_user'] == $to_user)
			{
				$user[] = array ( 'user' =>getMinimumUserDetails($res['from_user']));
			}
		
		//$d[] = array ( 'user' => $user );
		}
				
		$array = array('array' => $user);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
}

if($tag == "update_time")
{
	$userid=$_POST['userid'];
	$end_time=date("H:i", strtotime(trim($_POST['end_time'])));
	$start_time=date("H:i", strtotime(trim($_POST['start_time'])));
	if($userid!="" && $end_time!="" && $start_time!="")
	{
		$sql_up_time="update students set start_time='".$start_time."' , end_time='".$end_time."' where id='".$userid."' ";
		$rs_up_time=mysql_query($sql_up_time);
		if (!$rs_up_time) 
		{
		$response["status"] = "1";
		}
		else
		{
			$response["status"] = "0";
		}
		echo json_encode($response);
		return;
		
	}
	else
	{
		$response["status"] = "1";
		echo json_encode($response);
		return;
	}
}
if($tag == "register_student")
{
	$name=trim($_POST['name']);
	$email=trim($_POST['email']);
	$pass=trim($_POST['password']);
	$univ=trim($_POST['univ']);
	$dept=trim($_POST['dept']);
	$sql_check="select * from students where email='".$email."'";
	$rs_check=mysql_query($sql_check);
	$res_check=mysql_fetch_array($rs_check);
	if($res_check['email'] != $email)
	{
		$sql_insert="insert into students set first_name='".$name."' , email='".$email."' , password='".$pass."' , university='".$univ."' , department='".$dept."' , status='1' ,  type='stu' ";
		$rs=mysql_query($sql_insert);
		
				$sql="select * from students where email='".$email."' and password='".$pass."'  and type='stu'";
				$rs=mysql_query($sql);
				$res=mysql_fetch_array($rs);
					if($res['email']==$email)
						{
							$response["status"] = "0";
							$response["error"] = "perfect";
							
							$response["id"] = $res["id"];
							//$response["fname"] = $res["fname"];
							//$response["lname"] = $res["lname"];
							$response["first_name"] = $res["first_name"];
							$response["email"] = $res["email"];
							$response["department"] = $res["department"];
							$response["dob"] = $res["dob"];
							$response["gender"] = $res["gender"];
							$response["university"] = $res["university"];
							$response["pic"] = $res["pic"];
							$response["last_online"] = $res["last_online"];
							$response["user_status"] = $res["status"];
							$response["education"] = $res["education"];
							$response["type"] = $res["type"];
						}
						else {
						// user not found
						$response["status"] = "2";
						$response["error"] = "Incorrect Email or Password!";	
						}
	}
	else
	{
	//email exist	
	$response["status"] = "2";
	$response["error"] = "Eamil id alrady exist!";
	}
	echo json_encode($response);
	return;
	
}
if($tag == "register_professor")
{
	$name=trim($_POST['name']);
	$email=trim($_POST['email']);
	$pass=trim($_POST['password']);
	$univ=trim($_POST['univ']);
	$dept=trim($_POST['dept']);
	$desig=trim($_POST['desig']);
	$office_room=trim($_POST['office_room']);
	$sql_check="select * from students where email='".$email."'";
	$rs_check=mysql_query($sql_check);
	$res_check=mysql_fetch_array($rs_check);
	if($res_check['email'] != $email)
	{
		$sql_insert="insert into students set first_name='".$name."' , email='".$email."' , password='".$pass."' , university='".$univ."' , department='".$dept."' , type='prof' , desig='".$desig."' , office_room='".$office_room."' ";
		$rs=mysql_query($sql_insert);
		
				$sql="select * from students where email='".$email."' and password='".$pass."'  and type='prof'";
				$rs=mysql_query($sql);
				$res=mysql_fetch_array($rs);
					if($res['email']==$email)
						{
							$response["status"] = "0";
							$response["error"] = "perfect";
							
							$response["id"] = $res["id"];
							//$response["fname"] = $res["fname"];
							//$response["lname"] = $res["lname"];
							$response["first_name"] = $res["first_name"];
							$response["email"] = $res["email"];
							$response["department"] = $res["department"];
							$response["dob"] = $res["dob"];
							$response["gender"] = $res["gender"];
							$response["university"] = $res["university"];
							$response["pic"] = $res["pic"];
							$response["last_online"] = $res["last_online"];
							$response["user_status"] = $res["status"];
							$response["education"] = $res["education"];
							$response["desig"] = $res["desig"];
							$response["office_room"] = $res["office_room"];
							$response["start_time"] = $res["start_time"];
							$response["end_time"] = $res["end_time"];
							$response["type"] = $res["type"];
						}
						else {
						// user not found
						$response["status"] = "2";
						$response["error"] = "Incorrect Email or Password!";	
						}
	}
	else
	{
	//email exist	
	$response["status"] = "2";
	$response["error"] = "Eamil id alrady exist!";
	}
	echo json_encode($response);
	return;	
}
if($tag == "update_student")
{
	$userid=trim($_POST['userid']);
	$name=trim($_POST['name']);
	$univ=trim($_POST['univ']);
	$dept=trim($_POST['dept']);
	
	$sql_update="update students set first_name='".$name."' . university='".$univ."' , department='".$dept."' where id='".$userid."' ";
	$rs_update=mysql_query($sql_update);
	 
	 				$sql="select * from students where email='".$email."' and type='stu'";
					$rs=mysql_query($sql);
					$res=mysql_fetch_array($rs);
					if($res['email']==$email)
						{
							$response["status"] = "0";
							$response["error"] = "perfect";
							
							$response["id"] = $res["id"];
							//$response["fname"] = $res["fname"];
							//$response["lname"] = $res["lname"];
							$response["first_name"] = $res["first_name"];
							$response["email"] = $res["email"];
							$response["department"] = $res["department"];
							$response["dob"] = $res["dob"];
							$response["gender"] = $res["gender"];
							$response["university"] = $res["university"];
							$response["pic"] = $res["pic"];
							$response["last_online"] = $res["last_online"];
							$response["user_status"] = $res["status"];
							$response["education"] = $res["education"];
							$response["type"] = $res["type"];
						}
						else {
						// user not found
						$response["status"] = "2";                                                                                                              
						$response["error"] = "not found!";	
						}
	echo json_encode($response);
	return;	
}
if($tag == "update_professor")
{
	$userid=trim($_POST['userid']);
	$name=trim($_POST['name']);
	$univ=trim($_POST['univ']);
	$dept=trim($_POST['dept']);
	
	$sql_update="update students set first_name='".$name."' . university='".$univ."' , department='".$dept."' where id='".$userid."' ";
	$rs_update=mysql_query($sql_update);
	 
	 				$sql="select * from students where email='".$email."' and type='prof'";
					$rs=mysql_query($sql);
					$res=mysql_fetch_array($rs);
					if($res['email']==$email)
						{
							$response["status"] = "0";
							$response["error"] = "perfect";
							
							$response["id"] = $res["id"];
							//$response["fname"] = $res["fname"];
							//$response["lname"] = $res["lname"];
							$response["first_name"] = $res["first_name"];
							$response["email"] = $res["email"];
							$response["department"] = $res["department"];
							$response["dob"] = $res["dob"];
							$response["gender"] = $res["gender"];
							$response["university"] = $res["university"];
							$response["pic"] = $res["pic"];
							$response["last_online"] = $res["last_online"];
							$response["user_status"] = $res["status"];
							$response["education"] = $res["education"];
							$response["type"] = $res["type"];
						}
						else {
						// user not found
						$response["status"] = "2";
						$response["error"] = "not found!";	
						}
	echo json_encode($response);
	return;	
}
if($tag == "redeem_code")
{
	$userid=trim($_POST['userid']);
	$class_code=trim($_POST['class_code']);	
	$sql_cls="select * from class where class_code='".$class_code."'";
	$rs_cls=mysql_query($sql_cls);
	$co=mysql_num_rows($rs_cls);
	
	if($co>0)
	{
	$res_cls=mysql_fetch_array($rs_cls);
	$sql_cls_member="insert into event_users set class_code='".$class_code."' , tagged_username='".$userid."' , tagged_by_username='".$res_cls['profasar_id']."' , type='class' , event_slug='".$res_cls['event_slug']."' , status='Accepted'";
	$rs_cls_member=mysql_query($sql_cls_member);
	$sql_cls_group="insert into class_group_member set class_code='".$class_code."' , user_id='".$userid."' , user_type='stu' ";
	$rs_cls_group=mysql_query($sql_cls_group);
	
	$from_user = getMinimumUserDetails($userid);
	
							$sql_token="select * from students where id='".$res_cls['profasar_id']."'";
							$rs_token=mysql_query($sql_token);
	
							while($row = mysql_fetch_array($rs_token))
							{
								$tokens[] = $row["token"];
							}
							
							$sql_notifi="insert into notification set type='class' , title='Accepted Class' , message='".$from_user['first_name']." accept class ".$class_code."' , user_id='".$res_cls['profasar_id']."' , date='".$start."' , event_slug='".$res_cls['event_slug']."' ";
							$rs_notifi=mysql_query($sql_notifi);
							$msg=$from_user['first_name']." accept class ".$class_code;
							$message = array( "title"=> "Accepted Class" , "body" => $msg , "date" => $ar[$c] , "event_slug" => $res_cls['event_slug'] , "type" => "class");
							$data = array( "title"=> "Accepted Class" , "body" => $msg , "date" => $ar[$c] , "event_slug" => $res_cls['event_slug'] , "type" => "class" );
							$message_status = send_notification($tokens, $message, $data);
								
							$response["status"] = "0";
							$response["push"] = $response;
			
	}
	else
	{
		$response["status"] = "1";
		$response["error"] = "invalid code!";
	}
	echo json_encode($response);
	return;
}
if($tag == "save_user_cal")
{
	$userid=trim($_POST['userid']);
	$events_string=trim(mysql_real_escape_string($_POST['events_string']));
	if($userid != "" && $events_string != "")
	{
		//update students set events_string='hello' where id='8' 
		$sql_events_string="update students set events_string='".$events_string."' where id='".$userid."' ";
		$rs_events_string=mysql_query($sql_events_string);
		$response["status"] = "0";
	}
	echo json_encode($response);
	return;
}
if($tag == "save_google_cal")
{
	$userid=trim($_POST['userid']);
	$google_string=trim(mysql_real_escape_string($_POST['google_string']));
	if($userid != "" && $google_string != "")
	{
		//update students set events_string='hello' where id='8' 
		$sql_google_string="update students set google_string='".$google_string."' where id='".$userid."' ";
		$rs_google_string=mysql_query($sql_google_string);
		$response["status"] = "0";
	}
	echo json_encode($response);
	return;
}
if($tag == "get_user_cal")
{
	$userid=trim($_POST['userid']);
	if($userid != "")
	{
		$sql_events_string="select * from students where id='".$userid."' ";
		$rs_events_string=mysql_query($sql_events_string);
		$co=mysql_num_rows($rs_cls);
		if($co>0)
			{
				$res_events_string=mysql_query($rs_events_string);
				$response["status"] = "0";
				$response["events_string"] = $res_events_string['events_string'];
			}
			else{
				$response["status"] = "1";
				$response["error"] = "user not present!";
			}
		
	}
	else
	{
		$response["status"] = "1";
		$response["error"] = "userid null!";
	}
	echo json_encode($response);
	return;
}
if($tag == "get_next7_events")
{	
	$userid=trim($_POST['userid']);
	if($userid != "")
	{		
		echo $sql="select * from event_meta where event_slug in ( select distinct event_slug from event_users where tagged_username='".$userid."') and start like'".$date."-%'";
		exit;
		//)='".$username."' and start like '".$date."-%'";
		$rs=mysql_query($sql);
		while($row = mysql_fetch_array($rs))
		{
			$event_slug = $row['event_slug'];
			
			$event = array('event_slug' => $row['event_slug'], 
			'event_name' => $row['event_name'], 
			'start' => $row['start'], 
			'end' => $row['end'], 
			'descp' => $row['descp'], 
			'pic' => $row['pic'], 
			'username' => $row['username'], 
			'start_time' => $row['start_time'], 
			'end_time' => $row['end_time'], 
			'type'=>$row['type'] );
			
			//get other users , for resolve
			$tagged_by_user = array();
			$tagged_user = array();
			
			if ($row['type'] == 'event') {
				$sql2 = "select * from event_users where event_slug='" .$event_slug. "' and tagged_username='" .$username. "'";	
			} else {
				$sql2 = "select * from event_users where event_slug='" .$event_slug. "' and tagged_username!=tagged_by_username";
			}
			
			$rs2 = mysql_query($sql2);	
			if ($row2 = mysql_fetch_array($rs2) ) {
				$tagged_by_user = getMinimumUserDetails($row2['tagged_by_username']);
				$tagged_user = getMinimumUserDetails($row2['tagged_username']);
				$status = $row2['status'];
			}
			 
			$d[] = array ( 'event_details' => $event, 'tagged_user' => $tagged_user, 'tagged_by' => $tagged_by_user,  'status' => $status );
		}
				
		$array = array('array' => $d);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
	else{
		$response["status"] = "1";
		echo json_encode($response);
		return;
	}
}

if($tag == 'get_all_groups_members')
{
	$userid=$_POST['userid'];
	$sql_user="select * from event_users where tagged_username='".$userid."' and type='class' ";
	$rs=mysql_query($sql_user);
	$co=mysql_num_rows($rs);
	//$x=1;
	if($co>0)
	{
		while($res = mysql_fetch_array($rs))
		{
			$sql_class_gorup="select * from class where class_code='".$res['class_code']."'";
			$rs_class_group=mysql_query($sql_class_gorup);
			$res_class_group=mysql_fetch_array($rs_class_group);			
			$sql_class_group_member="select * from class_group_member where class_code='".$res['class_code']."'";
			$rs_class_group_member=mysql_query($sql_class_group_member);
			//$y=1;
			$member = array();
			while($res_class_group_member = mysql_fetch_array($rs_class_group_member))
			{
				
				$member[] = array ('members' => getUserDetails($res_class_group_member['user_id']) ); //, 'x' =>$x , 'y' => $y);	
				//$members[] = array ('members' => $member , 'x' =>$x , 'y' => $y);
				//$y++;if
			}
			//echo $res_class_group['class_title'];
		$d[] = array ( 'group_name' => $res_class_group['class_title'], 'group_code' => $res['class_code'], 'members' => $member );
		$x++;
		}
				
		$array = array('array' => $d);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
	else{
		$response["status"] = "1";
		$response["error"] = "No group present";
		echo json_encode($response);
		return;
	}
}
if($tag == 'get_all_profs')
{
	$userid=$_POST['userid'];
	$sql_user="select * from event_users where tagged_username='".$userid."' and type='class' ";
	$rs=mysql_query($sql_user);
	$co=mysql_num_rows($rs);
	//$x=1;
	if($co>0)
	{
		while($res = mysql_fetch_array($rs))
		{
			$sql_class_gorup="select * from class where class_code='".$res['class_code']."'";
			$rs_class_group=mysql_query($sql_class_gorup);
			$res_class_group=mysql_fetch_array($rs_class_group);			
			$sql_class_group_member="select * from class_group_member where class_code='".$res['class_code']."' and user_type='prof'";
			$rs_class_group_member=mysql_query($sql_class_group_member);
			//$y=1;
			while($res_class_group_member = mysql_fetch_array($rs_class_group_member))
			{
				$member = getUserDetails($res_class_group_member['user_id']);	
				$members[] = array ('Prof' => $member );// ,'x' =>$x , 'y' => $y);
				//$y++;
			}
			$x++;
		//$d[] = array (  'members' => $members );
		}
				
		$array = array('array' => $members);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
	else{
		$response["status"] = "1";
		$response["error"] = "No group present";
		echo json_encode($response);
		return;
	}
}
if($tag == 'get_all_classes')
{	
	$userid=$_POST['userid'];
	$sql_class_events="select * from event_meta where username='".$userid."' and type='class'";
	$rs_class_events=mysql_query($sql_class_events);
	$co=mysql_num_rows($rs_class_events);
	
	if($co>0)
	{
	while($res_class_events = mysql_fetch_array($rs_class_events))
	{
		$sql_class_details="select * from class where event_slug='".$res_class_events['event_slug']."'";
		$rs_class_details=mysql_query($sql_class_details);
		$res_class_details=mysql_fetch_array($rs_class_details);
		
		$event = array('event_slug' => $res_class_events['event_slug'], 
			'event_name' => $res_class_events['event_name'], 
			'start' => $res_class_events['start'], 
			'end' => $res_class_events['end'], 
			'descp' => $res_class_events['descp'], 
			'pic' => $res_class_events['pic'], 
			'username' => $res_class_events['username'], 
			'start_time' => $res_class_events['start_time'], 
			'end_time' => $res_class_events['end_time'], 
			'type'=>$res_class_events['type'],
			'location'=>$res_class_events['location'] ,
			'recurring'=>$res_class_events['recurring'] ,
			'days'=>$res_class_events['days'] ,
			'notes'=>$res_class_events['notes'] );
		$class = array('class_title' => $res_class_details['class_title'],
			'class_code' => $res_class_details['class_code'] );	
		$d[] = array ( 'event_details' => $event, 'class_details' => $class );				
	}
	$array = array('array' => $d);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
	else{
		$response["status"] = "1";
		echo json_encode($response);
		return;
	}
}
if($tag == "update_user")
{
	$userid=trim($_POST['userid']);
	
	$name=trim($_POST['name']);
	$univ=trim($_POST['univ']);
	$dept=trim($_POST['dept']);
	$desig=trim($_POST['desig']);
	$end_time=date("H:i", strtotime(trim($_POST['end_time'])));
	$start_time=date("H:i", strtotime(trim($_POST['start_time'])));
	$office_room=trim($_POST['office_room']);
	$sql_check="select * from students where id='".$userid."'";
	$rs_check=mysql_query($sql_check);
	$res_check=mysql_fetch_array($rs_check);
	if($res_check['id'] == $userid)
	{
		$sql_up="update students set first_name='".$name."', university='".$univ."' , department='".$dept."' , desig='".$desig."' , office_room='".$office_room."' , start_time='".$start_time."' , end_time='".$end_time."' where id='".$userid."' ";
		
		$rs=mysql_query($sql_up);
		
				$sql="select * from students where id='".$userid."'";
				$rs=mysql_query($sql);
				$res=mysql_fetch_array($rs);
					if($res['id']==$userid)
						{
							$response["status"] = "0";
							$response["error"] = "perfect";
							
							$response["id"] = $res["id"];
							//$response["fname"] = $res["fname"];
							//$response["lname"] = $res["lname"];
							$response["first_name"] = $res["first_name"];
							$response["email"] = $res["email"];
							$response["department"] = $res["department"];
							$response["dob"] = $res["dob"];
							$response["gender"] = $res["gender"];
							$response["university"] = $res["university"];
							$response["pic"] = $res["pic"];
							$response["last_online"] = $res["last_online"];
							$response["user_status"] = $res["status"];
							$response["education"] = $res["education"];
							$response["start_time"] = $res["start_time"];
							$response["end_time"] = $res["end_time"];
							$response["desig"] = $res["desig"];
							$response["office_room"] = $res["office_room"];
							$response["type"] = $res["type"];
						}
						else {
						// user not found
						$response["status"] = "2";
						$response["error"] = "User not available!";	
						}
	}
	else
	{
	//email exist	
	$response["status"] = "1";
	$response["error"] = "User not exist!";
	}
	echo json_encode($response);
	return;
	
}

if($tag == 'edit_event')
{
	$event_slug=trim($_POST['event_slug']);
	$tagged_user=trim($_POST['username']);	
	$event_name=trim($_POST['event_name']);
	$desc=trim($_POST['desc']);
	$start=date("Y-m-d", strtotime(trim($_POST['start'])));
	$end=date("Y-m-d", strtotime(trim($_POST['end'])));
	$end_time=date("H:i:s", strtotime(trim($_POST['end_time'])));
	$start_time=date("H:i:s", strtotime(trim($_POST['start_time'])));
	$type = $_POST['type'];
	$location = $_POST['location'];
	$recurring = $_POST['recurring'];
	$days = $_POST['days'];
	$notes=mysql_real_escape_string(trim($_POST['notes']));
	
	$sql_check_event="select * from event_meta where event_slug='".$event_slug."' and username='".$tagged_user."' ";
	$rs_check_event=mysql_query($sql_check_event);
	$res_check_event=mysql_fetch_array($rs_check_event);
	if($res_check_event['event_slug'] == $event_slug)
	{
			$sql_update="update event_meta set  event_name='".$event_name."' , descp='".$desc."' , start='".$start."' , end='".$end."' , start_time='".$start_time."' , end_time='".$end_time."' , days='".$days."' , recurring='".$recurring."' , location='".$location."' , notes='".$notes."'  where event_slug='".$event_slug."' and username='".$tagged_user."' ";
			$rs_class= mysql_query($sql_update);	
			if($type == 'class')
			{
				$sql_class_update="update class set class_title='".$event_name."' where event_slug='".$event_slug."' and profasar_id='".$tagged_user."' ";	
				$rs_class_update= mysql_query($sql_class_update);
			}
	$response["status"] = "0";
	echo json_encode($response);
	return;
	}
	else
	{
	$response["status"] = "1";
	echo json_encode($response);
	return;
	}	
}
if($tag == 'notification')
{
	$user_id = $_POST['userid'];
	$sql_notification="select * from notification where user_id='".$user_id."'";
	$rs_notification=mysql_query($sql_notification);
	while($row = mysql_fetch_array($rs_notification))
	{
		$d[]= array( "id" => $row["id"] , "type" => $row["type"] , "title" => $row["title"] , "message" => $row["message"] , "user_id" => $row["user_id"] , "date" => $row["date"] , "event_slug" => $row["event_slug"] );
	}
					$array = array('array' => $d );
				
					header('Content-type: application/json');
							
					echo json_encode( $array );
}
if($tag == 'delete_user_from_class')
{
	$group_code = $_POST['group_code'];
	$prof_id = $_POST['prof_id'];
	$del_user_id = $_POST['del_user_id'];
	$sql_check="select * from class where class_code='".$group_code."' and profasar_id='".$prof_id."' ";
	$rs_check=mysql_query($sql_check);
	$res_check=mysql_fetch_array($rs_check);
	if($res_check['id']!= "")
	{
		$sql_delete_group="delete from class_group_member where class_code='".$group_code."' and user_id='".$del_user_id."'";
		$rs_detele_group=mysql_query($sql_delete_group);
		$sql_delete_user="delete from event_users where class_code='".$group_code."' and tagged_username='".$del_user_id."' and type='class' ";
		$res_delete_user=mysql_query($sql_delete_user);
		$response["status"] = "0";
		echo json_encode($response);
		return;
		
	}
	else
	{
	$response["status"] = "1";
	echo json_encode($response);
	return;
	}
	
}

if($tag == 'update_profile_image')
{
	$userid = $_POST['userid'];
	$profile_photo = trim($_FILES["profile_photo"]["name"]);
				$arr=array();
				$arr=explode(".",$profile_photo);
				$ext=end($arr);
				$allowed =  array('png' ,'jpg','jpeg');
				if(!in_array($ext,$allowed) ) 
				{
						$response["status"] = "11";
						//header("location:product.php?act=faild");
						//exit;
				}
				else {
						$newname=strtotime("now").".".$ext;
						$fsrc=$_FILES["profile_photo"]["tmp_name"];
						$fdst="pics/".$newname;
						copy($fsrc,$fdst); 
						//$response["status"] = "0";
						$sql_pro="update students set pic='".$newname."' where id='".$userid."'";
						$result = mysql_query($sql_pro);
						if (!$result)
						{
								$response["status"] = "1";
						}
						else
						{
								$sql_new="select * from students where id='".$userid."'";
								$rs_new=mysql_query($sql_new);
								$res_new=mysql_fetch_array($rs_new);
								$response["status"] = "0";
								$response["pic"] = $res_new['pic'];	
						}
				}
	echo json_encode($response);
	return;
}

if($tag == 'forgot_pass')
{
	$email = $_POST['email'];
	$sql_email="select * from students where email='".$email."'";
	$rs_email=mysql_query($sql_email);
	$res_email=mysql_fetch_array($rs_email);
	if($res_email['id'] != "")
	{
		
		$to = $email;
		$subject = "forgot password";
		
		$message = "Hi ".$res_email['first_name']."\r\n".
		   "Your password is: ".$res_email['password']."\r\n";
		
		// Always set content-type when sending HTML email
		$headers = "From: webmaster@einfodemolink.com";
		
		if(mail($to,$subject,$message,$headers))
		{
			//Send response: yes
			$response["status"] = "0";
			$response["error"] = "mail sent successfully";	
		}
		else
		{
			//Send response: no
			$response["status"] = "1";
			$response["error"] = "mail sent unsuccessful!";	
		}
	}
	else
	{
		$response["status"] = "1";
		$response["error"] = "email id not exist!";		
	}
	echo json_encode($response);
	return;
}

if($tag == 'get_all_appos')
{
	$userid = $_POST['userid'];
	if($userid!="")
	{
		$sql="select * from event_meta where event_slug in ( select distinct event_slug from event_users where tagged_username='".$userid."') and type='appo' and start BETWEEN '2016-01-01' AND  '2016-12-31'";
		$rs=mysql_query($sql);
		$co=mysql_num_rows($rs);
			if($co>0)
			{
					while($row = mysql_fetch_array($rs))
					{
						$event_slug = $row['event_slug'];
						
						$event = array('event_slug' => $row['event_slug'], 
						'event_name' => $row['event_name'], 
						'start' => $row['start'], 
						'end' => $row['end'], 
						'descp' => $row['descp'], 
						'pic' => $row['pic'], 
						'username' => $row['username'], 
						'start_time' => $row['start_time'], 
						'end_time' => $row['end_time'], 
						'type'=>$row['type'],
						'location'=>$row['location'] ,
						'recurring'=>$row['recurring'] ,
						'days'=>$row['days'] ,
						'notes'=>$row['notes'] );
						
						$d[] = array ( 'event_details' => $event );
					}
					$array = array('array' => $d );
				
					header('Content-type: application/json');
							
					echo json_encode( $array );
			}
			else
			{
				$response["status"] = "1";
				echo json_encode($response);
				return;
			}
			
	}
	else
	{
		$response["status"] = "1";
		echo json_encode($response);
		return;
	}
}



if($tag == 'get_all_students_for_tagging')
{
	$userid=$_POST['userid'];
	$sql_user="select * from event_users where tagged_username='".$userid."' and type='class' ";
	$rs=mysql_query($sql_user);
	$co=mysql_num_rows($rs);
	$member = array();
	//$x=1;
	if($co>0)
	{
		while($res = mysql_fetch_array($rs))
		{
			$sql_class_gorup="select * from class where class_code='".$res['class_code']."'";
			$rs_class_group=mysql_query($sql_class_gorup);
			$res_class_group=mysql_fetch_array($rs_class_group);			
			$sql_class_group_member="select * from class_group_member where class_code='".$res['class_code']."'";
			$rs_class_group_member=mysql_query($sql_class_group_member);
			//$y=1;
			
			while($res_class_group_member = mysql_fetch_array($rs_class_group_member))
			{
				$member[] = getUserDetails($res_class_group_member['user_id']);
				//$member[] = array ('details' => getUserDetails($res_class_group_member['user_id']) ); //, 'x' =>$x , 'y' => $y);	
				//$members[] = array ('members' => $member , 'x' =>$x , 'y' => $y);
				//$y++;if
			}
			//echo $res_class_group['class_title'];
		//$d[] = array ( 'group_name' => $res_class_group['class_title'], 'group_code' => $res['class_code'], 'members' => $member );
		//$x++;
		}
				
		$array = array('array' => $member);
				
		header('Content-type: application/json');
				
		echo json_encode( $array );
	}
	else{
		$response["status"] = "1";
		$response["error"] = "No group present";
		echo json_encode($response);
		return;
	}
}

if($tag == 'delete_event')
{
	$userid=$_POST['userid'];
	$event_slug=$_POST['event_slug'];
	$sql_check="select * from  event_meta where event_slug='".$event_slug."' and username='".$userid."'";
	$rs_check=mysql_query($sql_check);
	$res_check=mysql_fetch_array($rs_check);
	if($res_check["event_slug"] == $event_slug)
	{
		if($res_check["type"] == "class")
		{
			$sql_delete_users="delete from event_users where event_slug='".$event_slug."' ";
			$rs_delete_users=mysql_query($sql_delete_users);
			
			$sql_delete_event="delete from event_meta where event_slug='".$event_slug."' ";
			$rs_delete_event=mysql_query($sql_delete_event);
			
			$sql_delete_class="delete from class where event_slug='".$event_slug."' ";
			$rs_delete_class=mysql_query($sql_delete_class);
			
			$response["status"] = "0";
			
			
		}
		else
		{
			$sql_delete_users="delete from event_users where event_slug='".$event_slug."' ";
			$rs_delete_users=mysql_query($sql_delete_users);
			
			$sql_delete_event="delete from event_meta where event_slug='".$event_slug."' ";
			$rs_delete_event=mysql_query($sql_delete_event);
			
			$response["status"] = "0";
			
			
		}
	}
	else
	{
		$response["status"] = "1";
		$response["error"] = "Un Authorised Event Slug";
	}
	
	echo json_encode($response);
		return;
}

if($tag == 'get_all_office')
{
	$userid=$_POST['userid'];
	$sql_check_off="select * from event_meta where username='".$userid."' and type='Office'";
	$rs_check_off=mysql_query($sql_check_off);
	while($res_check_off = mysql_fetch_array($rs_check_off))
	{
		$event = array('event_slug' => $res_check_off['event_slug'], 
			'event_name' => $res_check_off['event_name'], 
			'start' => $res_check_off['start'], 
			'end' => $res_check_off['end'], 
			'descp' => $res_check_off['descp'], 
			'pic' => $res_check_off['pic'], 
			'username' => $res_check_off['username'], 
			'start_time' => $res_check_off['start_time'], 
			'end_time' => $res_check_off['end_time'], 
			'type'=>$res_check_off['type'],
			'location'=>$res_check_off['location'] ,
			'recurring'=>$res_check_off['recurring'] ,
			'days'=>$res_check_off['days'] ,
			'notes'=>$res_check_off['notes'] );
			
			
			$d[] = array ( 'event_details' => $event );
		
	}
		$array = array('array' => $d);
		
		header('Content-type: application/json');
				
		echo json_encode( $array );
}

if($tag == 'update_token')
{
	$userid=$_POST['userid'];
	$token=$_POST['token'];
	if($userid != "" && $token !="")
	{
		$sql_update="update students set token='".$token."' where id='".$userid."'";
		$rs_update=mysql_query($sql_update);
		$response["status"] = "0";
	}
	else
	{
		$response["status"] = "1";
	}
	header('Content-type: application/json');
				
	echo json_encode( $response );
}
if($tag == 'feedback')
{
	$userid=$_POST['userid'];
	$text=trim(mysql_real_escape_string($_POST['text']));
	if($userid != "" && $text !="")
	{
		$sql_update="insert into feedback set user_id='".$userid."' , feedback='".$text."'";
		$rs_update=mysql_query($sql_update);
		$response["status"] = "0";
	}
	else
	{
		$response["status"] = "1";
	}
	header('Content-type: application/json');
				
	echo json_encode( $response );
}


// Edited by sanjay on 25/10/2016
//Api for extracting the userid based on the class code.

if($tag == 'get_class_members')
{
	$classcode=$_POST['class_code'];
	$sql_get_userid="select * from event_users where class_code='".$classcode."'";
	$rs_get_userid=mysql_query($sql_get_userid);
	$count=mysql_num_rows($rs_get_userid);
	if($count>0)
	{
		$member = array();
		while($res_id = mysql_fetch_array($rs_get_userid))
		{
			$id=$res_id['tagged_username'];
			$member[] = getUserDetails($id);
			$array = array('array' => $member);	
		}
		echo json_encode( $array );
	}
	else
	{
		$response["status"] = "1";
		$response["error"] = "No members present";
		echo json_encode($response);
	}
	return;
	
}

?>