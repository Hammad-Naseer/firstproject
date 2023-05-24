<?php 
    $dept_id;
    $section;
    $c_rout_id;
?>

<style>
    span.myfsize {
        position: relative;
        left: -10px;
        top: -3px;
    }
	.validate-has-error{color:red}.panel-default>.panel-heading+.panel-collapse .panel-body{border-top-color:#00a1de;border-top:2px solid #006f9c}.myterm{padding:3px}.fa{padding-right:1px!important}.fa-plus-square{color:#507895}.title{border:1px solid #eae7e7;min-height:34px;padding-top:10px;background-color:rgba(242,242,242,.35);color:#8c8c8c;height:auto}.adv{width:50px}.tt{background-color:rgba(242,242,242,.35);min-height:26px;padding-top:4px}.tt2{max-height:400px;overflow-y:auto;overflow-x:hidden}.panel-default>.panel-heading+.panel-collapse .panel-body{border-top-color:#21a9e1;border-top:2px solid #00a651}.panel-body{padding:9px 22px}.myfsize{font-size:11px!important}.panel-group .panel>.panel-heading>.panel-title>a{display:inline}.fa-file-o{padding-right:0!important}.panel{margin-bottom:20px;background-color:#fff;border:1px solid rgba(0,0,0,.08);border-radius:4px;-webkit-box-shadow:0 1px 1px rgba(0,0,0,.05);box-shadow:0 1px 1px rgba(43,43,43,.15)}.panel-title{width:100%}.panel-group.joined>.panel>.panel-heading+.panel-collapse{background-color:#fff}.difl{display:inline;float:left}.bt{margin-bottom:-28px;padding-top:15px;padding-right:31px}.panel-heading>.panel-title{float:left!important;padding:10px 15px!important;background-color:#fff}.pdr43{padding-right:43px}.title_collapse{padding:5px;font-size:18px;font-color:#000;color:#000;padding:5px 15px!important;cursor:pointer;border:1px solid rgba(204,204,204,.64)}.crud a{color:#b3b3b3;font-size:12px;padding-top:5px}.entypo-trash{color:#f1181b!important}
</style>

<div class="col-sm-12">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12" data-step="2" data-position='top' data-intro="collapse this department tab and collapse class-section then see timetable" style="    padding: 0px;">
            <?php 
            $q2_where = '';
			if( isset($section_id_filter) && $section_id_filter > 0)
			{
			    $q2_where=" AND sec.section_id=".$section_id_filter;	
			}
            $q2="SELECT d.title as department,d.departments_id,cls.name as class,cls.class_id,sec.title as section,sec.section_id FROM  ".get_school_db().".class_section  sec  
            	INNER JOIN ".get_school_db().".class  cls on sec.class_id=cls.class_id 
            	INNER JOIN ".get_school_db().".departments d on cls.departments_id=d.departments_id 
            	WHERE 
            	sec.school_id=".$_SESSION['school_id']."  $q2_where 
            	order by d.departments_id, cls.class_id, sec.section_id";
            	
            $result=$this->db->query($q2)->result_array();
			$dcs_arr = array();
            $section_arr = array();
			foreach ($result as $key => $value) 
			{
				$dcs_arr[$value['departments_id']]['name'] = $value['department'];
				$section_arr[$value['departments_id']][$value['section_id']] = $value['class'].' - '.$value['section'];
			}

            if(sizeof($result)>0)
            {
            	foreach($dcs_arr as $out_key => $outer_row)
            	{
            		$depart_id=$out_key;
            		
            		//$section_hierarchy=section_hierarchy($row1['section_id']);?>
            		<div class="title_collapse" style="border-left:4px solid #4a8cbb;font-size: 16px;">
            	        <span style="display:block;" onclick="myFunction('<?php echo 'dep'.$out_key;?>')" > 	   
            	            <i class="fa fa-university" aria-hidden="true"  style="padding-right:10px !important;"></i><?php echo $outer_row['name'];?> 
            	        </span>
            	    </div>
            	    <div class="panel-group collapse child<?php echo 'dep'.$out_key?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo 'dep'.$out_key;?>">
            	        <?php
            	    	    foreach ($section_arr[$out_key] as $h_key => $hierarchy_arr) 
            	    	    {
            	    		    $sect_id=$h_key;
            	        ?>   
                        <div class="title_collapse" style="border-left:4px solid #35b443;font-size: 15px;">
                            <i class="fa fa-puzzle-piece" aria-hidden="true" style="padding-right:10px !important; color: #35b443;"></i>     
                			 <span style="display:inline;" onclick="myFunction(<?php echo $h_key;?>)" >         
                			     <?php echo $hierarchy_arr;?>       
                			 </span>
                			 
                			 <?php
                			    if (right_granted('managetimetable_manage')){
                			 ?>
                	            <a class="myterm" style="float:right;" href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_class_routine_settings/<?php echo $h_key; ?>/<?php echo $depart_id;?>');">
                		            <i class="fa fa-plus-square" aria-hidden="true"></i>
                		            <?php echo get_phrase('add_time_table');?>
                		        </a>
                			 <?php } ?>
                		</div>
            		<?php
            			$toggle = true;
            			$settings = "select distinct cs.*,cls.class_id from ".get_school_db().".class_routine_settings cs  INNER JOIN ".get_school_db().".class_section  sec on sec.section_id=cs.section_id INNER JOIN ".get_school_db().".class  cls on sec.class_id=cls.class_id INNER JOIN ".get_school_db().".departments  d on cls.departments_id=d.departments_id 
            					where  
            					cs.school_id=".$_SESSION['school_id']." 
            					and sec.section_id = ".$h_key."
            					order by cs.start_date desc
            					";
            				
            				$settingsRes=$this->db->query($settings)->result_array();
            				if (count($settingsRes) > 0)
            				{
            					foreach($settingsRes as $row)
            					{
            						$no_of_periods=$row['no_of_periods'];
            						$period_duration=$row['period_duration'];
            						$start_time=strpos($row['start_time'],':')?$row['start_time']:($row['start_time'].':00');
            						$end_time=strpos($row['end_time'],':')?$row['end_time']:($row['end_time'].':00');
            						$assembly_duration=$row['assembly_duration'];
            						$break_duration=$row['break_duration'];
            						$break_after_period=$row['break_after_period'];
            						$c_rout_setting_id=$row['c_rout_sett_id'];
            						$start_date=$row['start_date'];
            						$end_date=$row['end_date'];
            						$is_active=$row['is_active'];
            						$period_array=array();
            						//$hierarchy=section_hierarchy($row['section_id']);
            						
            						?>
            						
            			            <div class="panel-group collapse child<?php echo $h_key?> class_routine_panel" id="accordion" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo $h_key;?>">
            			            	<div class="panel panel-default" style="border-radius: 0px;">
            			                    <div class="panel-heading" style="border-left: 4px solid #6b0303;">
            			                        <h4 class="panel-title">
            			                            <!--data-parent="#accordion"-->
            			                            <a data-toggle="collapse" href="#collapse<?php echo $c_rout_setting_id;?>"  onclick="load_table(<?php echo "'".$c_rout_setting_id."','".$h_key."'" ?>)" >
            			                                <i class="fa fa-table" aria-hidden="true" style="    color: #6b0303;"></i>
            			                                <?php echo convert_date($start_date).' - '.convert_date($end_date);?>
            			                            </a>
            			                            <span style="font-size:11px !important;">
                			                            <?php if($is_active==1) {?>
                									    <span class="green"><?php echo '('.get_phrase('active').')'; }else {?></span>
                    									<span class="red"><?php echo '('.get_phrase('inactive').')'; }?></span>
            									    </span>
            			                            <span style="float:right;padding-top: 4px;position:relative;top:-3px;left:-10px;" class="myfsize"></span>
            			                            <i class="fa fa-caret-down" aria-hidden="true" style="color: #6b0303;"></i>
            			                            <span style="float:right;padding-top: 4px;" class="myfsize"> 
            			                            	
            			                            <?php if (right_granted('managetimetable_manage')) {?>
        												<a href="#" style="color: #fff;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_class_routine_edit/<?php echo $c_rout_setting_id; ?>/<?php echo $depart_id;?>/<?php echo $sect_id;?>');">
        					                                <i class="entypo-pencil"></i><?php echo get_phrase('edit');?>
        												</a>
            										<?php }if (right_granted('managetimetable_delete')) { ?>
        												<a href="#" style="color: #fff;" onclick="confirm_modal('<?php echo base_url();?>time_table/class_routine_settings/delete/<?php echo $c_rout_setting_id; ?>/<?php echo $depart_id;?>/<?php echo $sect_id;?>');">
        													<i class="entypo-trash"></i><?php echo get_phrase('delete');?>
        												</a>
            										<?php } ?>
            										</span>
            			                        </h4>
            			                    </div>
            			                    
            	
            			                    <div id="collapse<?php echo $c_rout_setting_id;?>" class="panel-collapse collapse ">
            			                        <div class="panel-body">
            
            			     						<div class="table-mobile tbl-nw-set" id="loaded_table_<?php echo $c_rout_setting_id; ?>"></div>
            			                        </div>
            			                    </div>
            			                    
            			                    
            			                    
            			                </div>
            			            </div>
            			            <?php
            			        }
            			    }
            			    else
            			    {?>
            					<div class="panel-group collapse child<?php echo $h_key?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo $h_key;?>">
            		                <div class="panel panel-default">
            		                    <div class="panel-heading">
            		                        <p class="panel-title" style="color: black !important;">
            		                            <?php echo get_phrase('no_record_found');?>
            		                        </p>
            		                    </div>
            		                </div>
            			        </div>    
            			    	
            			    <?php
            			    }
            			}
            			?>
            		</div>
            
            <?php
                }
            }
            else
            echo get_phrase('no_data');
?>
        </div>
    </div>
</div>
<script type="text/javascript">

function load_table(c_rout_sett_id=0, section_id=0, load = false)
{
	//$('#loader_div'+c_rout_sett_id).html("3333333333");
	if ($('#collapse'+c_rout_sett_id).hasClass('collapse') || load == true)//call ajax on show
	{	
		$.ajax({
			url:'<?php echo base_url();?>time_table/load_table',
			method: 'post',
			dataType:'html',
			data:{c_rout_sett_id:c_rout_sett_id, section_id:section_id},
			async:false,
			success:function(result)
			{
				//$("#loading").remove();
				$('#loaded_table_'+c_rout_sett_id).html(result);
				
			}
		});
	}
}

$(document).ready(function() 
{
	var dept_id='<?php echo $dept_id;?>'; 
	var sect_id='<?php echo $section;?>';
	var c_rout_id='<?php echo $c_rout_id;?>';
	//alert(c_rout_id);
	/*if(dept_id!="")
	{
		
		
		myFunction('dep'+dept_id);
	}
	if(sect_id!="")
	{
		myFunction(sect_id);
		
		
	}
	if(c_rout_id!="" && sect_id!="")
	{
		load_table(c_rout_id,sect_id);
		alert(c_rout_id);
		alert(sect_id);
	}*/
	if(dept_id!="" && sect_id!="" && c_rout_id!="")
	{
		
		
		myFunction('dep'+dept_id);
		myFunction(sect_id);
		load_table(c_rout_id,sect_id);
	}
	
	
	/*$("a[class='routine_del']").on('click', function(e) {
        $(this).attr('href', '#');
        str = $(this).attr('id');
        var period = $('#period' + str).val();
        var day = $('#day' + str).val();
        var setting_id = $('#setting_id' + str).val();
        cr_id = str.replace('delete_', '');
        delete_url = '#';
        // $('#delete_link').attr('href','#');
        if (confirm('Are you sure?')) {

            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>time_table/class_routine/delete/" + cr_id,
                data: ({
                    class_routine_id: cr_id
                }),
                success: function(response) {
                    $('#cr' + cr_id).hide();
                    $('#select').trigger('click');
                }
            });
        }
    });*/


    function call_ajax() {

        var class_id = $('#class_id').val();
        $('#class_id').after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>admin/get_student",

            data: ({
                class_id: class_id
            }),
            dataType: "html",
            success: function(html) {
                //alert(html);
                $('#subject_id').html(html);
                $('#icon').remove();
                crt_btn();
            }

        });
        $('#student_id').after('<span id="icon" class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');
    }


});

function myFunction(a) 
{
    $(".child" + a).slideToggle("slow");
   
}
</script>
