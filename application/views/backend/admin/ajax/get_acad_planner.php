<style>
.month {
    position: relative;
    top: 4px;
}

.acspan {
    padding-left: 20px;
    font-size: 12px;
    color: #645e5e;
    font-weight: normal;
}

.breadcrumb2 {
    display: inline;
    padding: 3px;
    margin-left: 5px;
    color: #428abd;
}

.blue {
    color: #3f73b7;
    font-weight: bold;
    padding-left: 10px;
}

.pdl20 {
    padding-left: 20px;
}

.edt {
    font-size: 11px;
    padding-right: 2px;
}

.del {
    font-size: 11px;
}

.view {
    font-size: 11px;
    padding-right: 2px;
}

.mgb17 {
    margin-bottom: -17px;
}
.panel-heading.holiday_planner {
    background-color: #4caf50 !important;
}

.panel-heading.holiday {
    background-color: #02658d !important;
}

</style>
<?php 
if($_POST['month']){ 
    
    $custom_css   = array(1=>'current-day',2=>'holiday');    
    $current_date = date('Y-m-d');    
    $date_month   = $_POST['month'];
    $date_month   = date("m", strtotime($date_month));
    $date_year    = $_POST['year'];      
    $section_id   = $_POST['section_id'];
    $subject_id   = $_POST['subject_id'];
    
    $total_planner_count = get_specific_acadamic_planner_counts($subject_id , $date_month , $date_year);
    
    if(isset($subject_id) && ($subject_id>0))
    {
        $subj_query=" AND ap.subject_id=$subject_id";
    }
    elseif(isset($section_id) && ($section_id>0))
    {
    	$subj_list=array();
    	$q = "select * from ".get_school_db().".subject s INNER join ".get_school_db().".subject_section ss on s.subject_id=ss.subject_id
    	      where ss.section_id='$section_id' and s.school_id=".$_SESSION['school_id']." ";
    	$s=$this->db->query($q)->result_array();
    	foreach($s as $subj)
    	{
    	 	$subj_list[]=$subj['subject_id'];
    	}
    	$subj_array = implode(",",$subj_list);
    	$subj_query = " AND ap.subject_id IN(".$subj_array.")";
    }

    $school_id=$_SESSION['school_id'];
    $query="SELECT ap.planner_id as planner_id, ap.title as title, ap.start as start, ap.objective as objective,ap.assesment as assesment,
            ap.requirements as requirements,ap.required_time as required_time, ap.attachment as attachment, s.name as subject_name, 
            s.code as code,s.subject_id as subject_id FROM ".get_school_db().".academic_planner ap
            INNER JOIN ".get_school_db().".subject s  ON ap.subject_id = s.subject_id
            Where ap.school_id=$school_id AND MONTH(start) =$date_month  and year(start)=$date_year".$section_query.$subj_query; 

    $qur_red=$this->db->query($query)->result_array();

    $plan=array();

    foreach($qur_red as $red){
         $plan[$red['start']][$red['subject_id']][]=array('planner_id'=>$red['planner_id'], 'title'=>$red['title'],'detail'=>$red['detail'],'objective'=>$red['objective'],'assesment'=>$red['assesment'],'requirements'=>$red['requirements'],'required_time'=>$red['required_time'],'attachment'=>$red['attachment'], 'subject_name'=>$red['subject_name'],'subject_id'=>$red['subject_id'],'code'=>$red['code']);
    }

?>
<?php
   $d          = cal_days_in_month(CAL_GREGORIAN,$date_month,$date_year);
   $statuslist = "";
?>
  <h3 class="text-center text-info">Total Planners : <?php echo $total_planner_count; ?></h3>
<?php


$statuslist=array(1=>'present', 2=>'absent', 3=>'leave',4=>'weekend'); 
for($i=01; $i<=$d; $i++){
    
	$statuslist_css="";
    $current = "";
    $current1="";

    $s=mktime(0,0,0,$date_month, $i, $date_year);
    $today_date= date('Y-m-d',$s);
    $dw = date( "l", strtotime($today_date));
    $d1 = date( "d-M-Y", strtotime($today_date));
    if($today_date==$current_date)
    {
        $current=$custom_css[1];
    }  
    if($dw=='Saturday' or $dw=='Sunday'){
        $statuslist_css=$statuslist[4];  
    }

    $icon="";  
    if(isset($plan[$today_date]))
    {
        foreach($plan[$today_date] as $val )
        {   
            foreach($val as $row)
            {
                if($row['title']!="")
                {
                    //$icon="<i class='fa fa-caret-down' style='float:right;'></i>";
                }
            }
        }
    }

    $q1="select * from ".get_school_db().".holiday where start_date<='$today_date' and end_date>='$today_date' AND school_id=".$_SESSION['school_id']." ";  
    $qurrr=$this->db->query($q1)->result_array();
    if(count($qurrr)>0){
        $current1=$custom_css[2];
    }
    $sdate=date("Y-m-d",strtotime($d1));
   $showcount="";
   $bgcolor="";
   if(count($plan[$sdate])>0)
   {
       $showcount="";
       $bgcolor="#0992c9";
   }
   
 ?>
        <div class="panel-group" id="accordion" style="margin-bottom:2px;width:100%">
                <div class="panel panel-default">
                    <div id="head_<?php echo $today_date;?>" class="panel-heading <?php echo $current.' '.$current1.' '.$statuslist_css;?>" style="background-color:<?= $bgcolor ?>;border-bottom: 1px solid #CCC;">
                        <h4 class="panel-title" style="width:100%">
                            <a class="toggle_div" value="<?php echo $today_date;?>" id="<?php echo $today_date;?>" onclick="toggle_func('<?php echo $today_date;?>')" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo  $today_date; ?>">
                                <span class="myttl" style="color:#ffffff !important;"><?php  echo "$d1"." ". $showcount .""; ?><span class="acspan" style="color:#ffffff !important;">(<?php echo "$dw";  ?>)</span></span>
                                <?php echo $icon;?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse<?php echo $today_date; ?>" class="panel-collapse collapse" style="padding-left:10px;">
                        <div class="panel-body" style="border-top: 2px solid #00a651;">
                        </div>
                    </div>
                </div>
            </div>
 <?php 
    
     } 
   } 
 ?>
 
 
    <script>
        $(document).ready(function() {});
    </script>
    <script>
        $(function() { /* to make sure the script runs after page load */

        $('.item').each(function(event) { /* select all divs with the item class */

            var max_length = 130; /* set the max content length before a read more link will be added */

            if ($(this).html().length > max_length) { /* check for content length */

                var short_content = $(this).html().substr(0, max_length); /* split the content in two parts */
                var long_content = $(this).html().substr(max_length);

                $(this).html(short_content +
                    '<a href="#" class="read_more" style="color:#03bf5f;font-weight:bold;"><br/>Read More</a>' +
                    '<span class="more_text" style="display:none;">' + long_content + '</span>'); /* Alter the html to allow the read more functionality */

                $(this).find('a.read_more').click(function(event) { /* find the a.read_more element within the new html and bind the following code to it */

                    event.preventDefault(); /* prevent the a from changing the url */
                    $(this).hide(); /* hide the read more button */
                    $(this).parents('.item').find('.more_text').show(); /* show the .more_text span */

                });

            }

        });


    });
    </script>
    
    <script>
    $(document).ready(function() {
        $('#planner').DataTable({
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "bStateSave": true
        });
    });
    $(".dataTables_wrapper select").select2({
        minimumResultsForSearch: -1
    });
    </script>
    <script>
    function toggle_func(today_date) {
        
        var month      = '<?php echo $date_month;?>';
        var year       = '<?php echo $date_year;?>';
        var dept_id    = '<?php echo $dept_id;?>';
        var class_id   = '<?php echo $class_id;?>';
        var subject_id = '<?php echo $subject_id;?>';
        $.ajax({
            type: 'POST',
            data: {
                month: month,
                year: year,
                dept_id: dept_id,
                class_id: class_id,
                subject_id: subject_id,
                today_date: today_date
            },
            url: "<?php echo base_url();?>academic_planner/accordion_generator",
            dataType: "html",
            success: function(response) {
                $('#collapse' + today_date).html(response);
            }
        });
        
    }
    </script>
