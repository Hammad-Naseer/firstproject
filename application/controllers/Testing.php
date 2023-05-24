<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();


//exit;
class Testing extends CI_Controller
{
    
    
    function __construct()
    {
        parent::__construct();
		$this->load->database();
        /*cache control*/
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        //$_SESSION['school_id']=101;
    }
    function test(){
		
		
	$this->load->view("test");	
		
		

		
		
	}
    function ajax_form(){
	$this->load->view('backend/admin/testing');	
	}   
	function ajax_form1(){
		
//print_r($_FILES);

//print_r($_POST);
		
	}
	function sheraz_testing(){
	
	$this->load->view('backend/admin/test_view');		
		
	}
	
	function sheraz_ajax(){
		
		print_r($_GET);
		
	}
	
	function rep_test(){
		
	
		
	}
	
	function bulksms()
	{
		/*
		$username = "923315740289";
		$password = "4675";
		$mobile = "923315740289";
		$sender = "923315740289";
		*/
		
		$username = "923074446694";
		$password = "8563";
		$mobile = "923165134988";
		$sender = "Indici Edu";
		
		$message = "Test SMS From SendPK.com";
		echo $url = "http://sendpk.com/api/sms.php?username=".$username."&password=".$password."&mobile=".$mobile."&sender=".urlencode($sender)."&message=".urlencode($message)."";
		
		$ch = curl_init();
		$timeout = 30;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$responce = curl_exec($ch);
		curl_close($ch);
		/*Print Responce*/
		echo "<br>";
		echo $responce;
	}
	
	function jazzsms()
	{
		
		$username = "03028502823";
		$password = "Pakistan123";
		$mobile = "923315740289";
		$sender = "GSIMS";
		
		$message = "Test SMS From JAZZ - GSIMS";
		//http://119.160.92.2:7700/sendsms_url.html?Username=03028502823&Password=Pakistan123&From=GSIMS&To=923076666914&Message=[MESSAGE HERE................]
		
		echo $url = "http://119.160.92.2:7700/sendsms_url.html?Username=".$username."&Password=".$password."&From=".urlencode($sender)."&To=".$mobile."&Message=".urlencode($message)."";
		
		$ch = curl_init();
		$timeout = 30;
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		$responce = curl_exec($ch);
		curl_close($ch);
		/*Print Responce*/
		echo "<br>";
		echo $responce;
	}

function php_sc(){
	
$list = array ();

$list=$this->db->query("select * from ".get_school_db().".student s inner join ".get_school_db().".student_relation sr on sr.student_id=s.student_id inner join ".get_school_db().".student_parent sp on sp.s_p_id=sr.s_p_id")->result_array();


$fp = fopen('abc.csv', 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}



$file='khan.csv';

 //$file="http://192.168.0.10/gsims/khan.csv";

if (file_exists($file)) {
	
	echo "yes";
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
  
}

//echo "yes";

fclose($fp);




}

 public function student_information_csv()
   { 
   	$file_name='student_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	
 $column_names=array('Name', 'Date of Birth','Gender','Religion','location','Phone', 'Email','Section name','Roll no','Form no','Admission date','Form b','Mobile no','Emergency no','Alternate no','Blood group','Disability','Registration Number','Permanent address','Father name','Father id_no','Father Contact','Father Occupation','Mother name','Mother id_no','Mother Contact','Mother Occupation','Guardian name','Guardian id_no','Guardian Contact','Guardian Occupation');
fputcsv($file,$column_names);	
 $p="SELECT s.*,cl.title  FROM ".get_school_db().".student s left join ".get_school_db().".city_location cl on cl.location_id=s.location_id  WHERE s.school_id=".$_SESSION['school_id']." and s.student_status in ('".student_query_status()."')";
$query=$this->db->query($p)->result_array();
$student_array=array();
foreach($query as $row)
{	
$religion=	religion($row['religion']);
	$student_id=$row['student_id'];
$section_hr=section_hierarchy($row['section_id']);
	$section_c_name=$section_hr['d'].'-'.$section_hr['c'].'-'.$section_hr['s'];
	
	$student_array=array($row['name'],$row['birthday'],$row['sex'],$religion,$row['title'],$row['phone'],$row['email'],$section_c_name,$row['roll'],$row['form_num'],$row['adm_date'],$row['id_no'],$row['mob_num'],$row['emg_num'],$row['alternative_num'],$row['bd_group'],$row['disability'],$row['reg_num'],$row['p_address']);
	//fputcsv($file,$student_array);

	$query1=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='f' ")->result_array();
	  
//	print_r($query1);
	  
if(count($query1) > 0)
{

   $student_array[]=$query1[0]['p_name'];
	$student_array[]=$query1[0]['id_no'];
	$student_array[]=$query1[0]['contact'];
	$student_array[]=$query1[0]['occupation'];
	
	}
else
{
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" "; 

}

	$query2=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='m' ")->result_array();
if(count($query2) > 0)
{
	
			$student_array[]=$query2[0]['p_name'];
			$student_array[]=$query2[0]['id_no'];
			$student_array[]=$query2[0]['contact'];
			$student_array[]=$query2[0]['occupation'];
	
	
}
else
{
	$student_array[]="";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";

}


	$query3=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='g' ")->result_array();
if(count($query3) > 0)
{

			 $student_array[]=$query3[0]['p_name'];
			 
			
			
			
			$student_array[]=$query3[0]['id_no'];
			$student_array[]=$query3[0]['contact'];
			$student_array[]=$query3[0]['occupation'];
	
	
}
else
{
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";

}

	$result_array=$student_array;
	
fputcsv($file, $result_array);
	
	//print_r($result_array);
} 


$file=$file_name;



if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
   header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);

    file_delete($file);
}

}



 public function candidate_information_csv()
   { 
   	$file_name='student_'.time().".csv";
 	$file = fopen($file_name, 'w');
 	
 $column_names=array('Name', 'Date of Birth','Gender','Religion','location','Phone', 'Email','Section name','Roll no','Form no','Admission date','Form b','Mobile no','Emergency no','Alternate no','Blood group','Disability','Registration Number','Permanent address','Father name','Father id_no','Father Contact','Father Occupation','Mother name','Mother id_no','Mother Contact','Mother Occupation','Guardian name','Guardian id_no','Guardian Contact','Guardian Occupation');
fputcsv($file,$column_names);	
 $p="SELECT s.*,cl.title  FROM ".get_school_db().".student s left join ".get_school_db().".city_location cl on cl.location_id=s.location_id  WHERE s.school_id=".$_SESSION['school_id']." and s.student_status<10";
$query=$this->db->query($p)->result_array();
$student_array=array();
foreach($query as $row)
{	
$religion=	religion($row['religion']);
	$student_id=$row['student_id'];
$section_hr=section_hierarchy($row['section_id']);
	$section_c_name=$section_hr['d'].'-'.$section_hr['c'].'-'.$section_hr['s'];
	
	$student_array=array($row['name'],$row['birthday'],$row['sex'],$religion,$row['title'],$row['phone'],$row['email'],$section_c_name,$row['roll'],$row['form_num'],$row['adm_date'],$row['id_no'],$row['mob_num'],$row['emg_num'],$row['alternative_num'],$row['bd_group'],$row['disability'],$row['reg_num'],$row['p_address']);
	//fputcsv($file,$student_array);

	$query1=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='f' ")->result_array();
	  
//	print_r($query1);
	  
if(count($query1) > 0)
{

   $student_array[]=$query1[0]['p_name'];
	$student_array[]=$query1[0]['id_no'];
	$student_array[]=$query1[0]['contact'];
	$student_array[]=$query1[0]['occupation'];
	
	}
else
{
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" "; 

}

	$query2=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='m' ")->result_array();
if(count($query2) > 0)
{
	
			$student_array[]=$query2[0]['p_name'];
			$student_array[]=$query2[0]['id_no'];
			$student_array[]=$query2[0]['contact'];
			$student_array[]=$query2[0]['occupation'];
	
	
}
else
{
	$student_array[]="";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";

}


	$query3=$this->db->query("SELECT sp.p_name,sp.id_no,sp.contact,sp.occupation FROM ".get_school_db().".student_relation sr INNER JOIN ".get_school_db().".student_parent sp
ON sr.s_p_id=sp.s_p_id	
	  WHERE sr.school_id=".$_SESSION['school_id']." AND sr.student_id=".$student_id." AND sr.relation='g' ")->result_array();
if(count($query3) > 0)
{

			 $student_array[]=$query3[0]['p_name'];
			 
			
			
			
			$student_array[]=$query3[0]['id_no'];
			$student_array[]=$query3[0]['contact'];
			$student_array[]=$query3[0]['occupation'];
	
	
}
else
{
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";
			$student_array[]=" ";

}

	$result_array=$student_array;
	
fputcsv($file, $result_array);
	
	//print_r($result_array);
} 


$file=$file_name;



if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
   header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);

    file_delete($file);
}

}

function telenor_key(){	
 $url = "https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn=923458501954&password=03458501954";

$ch = curl_init();
$timeout = 30;
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
 echo $responce = curl_exec($ch);

print_r($responce);
$xml = simplexml_load_string($responce);

print_r($xml);


curl_close($ch);
	
	
}


function telenor_key2(){

echo $url="https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp?msisdn=923458501954&password=03458501954";
echo "<br>";
$curl_connection = curl_init();
/*
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents
*/
	//echo $url="https://telenorcsms.com.pk:27677/corporate_sms2/api/auth.jsp";
	//echo "<br>";
	//echo $post_string2 = "msisdn=923458501954&password=03458501954";
	//echo "<br>";
	//set options
	curl_setopt($curl_connection, CURLOPT_URL, $url);
	curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 0);
	curl_setopt($curl_connection, CURLOPT_TIMEOUT, 0);
	curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_connection, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
	//set data to be posted
	//curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string2);


$data = curl_exec($curl_connection); // execute curl request
if ($data === FALSE)
{
	echo "Error:- ".curl_error($curl_connection);
}
else
{
	echo "Data:- ".$data;
}
curl_close($curl_connection);
echo "<br>";
$xml = simplexml_load_string($data);
print_r($xml);
echo "<br>";
echo "xml->response:- ".$xml->response;
echo "<br>";
echo "xml->data:- ".$xml->data;
}



function telenor_code(){

$smstext = "Hello World!";
$mask = "IOT";
$api_session = "67414bed13e24517a95a92837917bacf";
$cell = "923315740289";
//$url="https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp?session_id=67414bed13e24517a95a92837917bacf&to=923315740289&text=".$msg."&mask=IOT";

$curl_connection = curl_init();
/*
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $url);    // get the url contents
*/
	echo $url = "https://telenorcsms.com.pk:27677/corporate_sms2/api/sendsms.jsp";
	echo "<br>";
	echo $post_string2 = "session_id=".$api_session."&to=".$cell."&text=".$smstext."&mask=".$mask;
	echo "<br>";

	curl_setopt($curl_connection, CURLOPT_URL, $url);
	curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 0);
	curl_setopt($curl_connection, CURLOPT_TIMEOUT, 0);
	//curl_setopt($curl_connection, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl_connection, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
	//set data to be posted
	curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string2);

$data = curl_exec($curl_connection); // execute curl request
if ($data === FALSE)
{
	echo curl_error($curl_connection);
}
else
{
	echo $data;
}
curl_close($curl_connection);

$xml = simplexml_load_string($data);
print_r($xml);
}

function telenor_testing(){
	$this->load->view('backend/admin/testing');
}

function hexa(){
	echo 0xff;
}

function barcode()
{
	$this->load->library('zend');
	$this->zend->load('Zend/Barcode');
	$barcode = new Zend_Barcode();
	
	//$text = "new barcode";
	//$text = time().'|'.'112';
	$bar_code_type = 112;
	
	$school_id = 1581;
	$school_id = sprintf("%'06d",$school_id);
	
	$student_id= 2394;
	$student_id = sprintf("%'07d",$student_id);
	
	//echo sprintf("%'.09d\n", 123);
	echo $text = $bar_code_type.''.$school_id.''.$student_id;
	//$text = 'bar code type (3 digits 999)'.'school id (6 digits, max 999999)'.'student id (7 digits, max 9999999)';
	echo "<br>";
	echo "bar_code = ".$b_c_text = $text;
	echo "<br>";
	echo "type = ".$b_c_type = substr($text, 0, 3);
	echo "<br>";
	echo "sch_id = ".$sch_id = substr($text, 3, 6)*1;
	echo "<br>";
	echo "std_id = ".$std_id = substr($text, 9, 7)*1;
	echo "<br>";
	$file = $barcode->draw('Code128', 
							'image', 
							array('text' => $text,'barHeight'=>20,'drawText'=>TRUE,'withQuietZones'=>FALSE,'orientation'=>0), 
							array());
	
	$path='uploads/testing';
	$file_name = "barcode_testing.png";
	echo "<br>";
	echo $store_image = imagepng($file,$path.'/'.$file_name);
}

}
    
    