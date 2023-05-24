<style>
/*
.validate-has-error {
    color: red;
}

.panel-default > .panel-heading + .panel-collapse .panel-body {
    border-top-color: #00a1de;
    border-top: 2px solid #006f9c;
}

.fa {
    padding-right: 1px !important;
}

.fa-plus-square {
    color: #507895;
}

.title {
    border: 1px solid #eae7e7;
    min-height: 34px;
    padding-top: 10px;
    background-color: rgba(242, 242, 242, 0.35);
    color: rgb(140, 140, 140);
    height: auto;
}

.adv {
    width: 50px;
}

.tt {
    background-color: rgba(242, 242, 242, 0.35);
    min-height: 26px;
    padding-top: 4px;
}

.tt2 {
    max-height: 400px;
    overflow-y: auto;
    overflow-x: hidden;
}

.panel-default> .panel-heading+ .panel-collapse .panel-body {
    border-top-color: #21a9e1;
    border-top: 2px solid #00a651;
}

.panel-body {
    padding: 9px 22px;
}

.myfsize {
    font-size: 11px !important;
}

.panel-group .panel> .panel-heading> .panel-title> a {
    display: inline;
}

.fa-file-o {
    padding-right: 0px !important;
}

.panel {
    margin-bottom: 20px;
    background-color: #fff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
    box-shadow: 0 1px 1px rgba(43, 43, 43, 0.15);
}

.panel-title {
    width: 100%;
}

.panel-group.joined> .panel> .panel-heading+ .panel-collapse {
    background-color: #fff;
}

.difl {
    display: inline;
    float: left;
}

.bt {
    margin-bottom: -28px;
    padding-top: 15px;
    padding-right: 31px;
}

.panel-heading> .panel-title {
    float: left !important;
    padding: 10px 15px !important;
}

.pdr43 {
    padding-right: 43px;
}

.title_collapse {
    padding: 5px;
    font-size: 18px;
    font-color: #000;
    color: #000;
    padding: 5px 15px !important;
    cursor: pointer;
    border: 1px solid rgba(204, 204, 204, 0.64);
}

.crud a {
    color: #b3b3b3;
    font-size: 12px;
    padding-top: 5px;
}
*/
</style>
<!--<div class="col-sm-12">-->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0px;">
            <?php 
            $q2_where = '';
			if( isset($section_id) && $section_id > 0)
			{
			    $q2_where=" AND sec.section_id=".$section_id;
				
			}
            $q2="SELECT d.title as department,d.departments_id,cls.name as class,cls.class_id,sec.title as section,sec.section_id FROM  ".get_school_db().".class_section  sec  
            	INNER JOIN ".get_school_db().".class  cls on sec.class_id=cls.class_id 
            	INNER JOIN ".get_school_db().".departments d on cls.departments_id=d.departments_id 
            	WHERE 
            	sec.school_id=".$_SESSION['school_id']."  $q2_where 
            	order by d.title, cls.name, sec.title";
            	//exit;

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
	?>
            <div class="title_collapse" style="border-left: 4px solid #8dd0f6; font-size: 16px;">
                <i class="fa fa-university" aria-hidden="true"></i>
                <span style="display:inline;" onclick="myFunction('<?php echo 'dep'.$out_key;?>')"> <?php echo $outer_row['name'];?>    <i class="fa fa-caret-down" style="float:right;"></i> </span>
            </div>
            <div class="panel-group collapse child<?php echo 'dep'.$out_key?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo 'dep'.$out_key;?>">
                <?php
	    	foreach ($section_arr[$out_key] as $h_key => $hierarchy_arr) 
	    	{
	    		
	    	?>
                    <div class="title_collapse" style="border-left: 4px solid #8dd0f6;font-size: 15px;">
                   <!--<i class="fa fa-puzzle-piece" aria-hidden="true" style="color: #35b443;"></i>-->
                        <span style="display:inline;" onclick="myFunction(<?php echo $h_key;?>)"> <?php echo $hierarchy_arr;?> </span><i class="fa fa-caret-down" style="color:#8dd0f6;"></i>
                        <?php
                        if (right_granted('managechallanform_manage'))
	    				{
                        ?>
                        <a class="myterm" style="float:right;" href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/chalan_form_add/add/<?php echo $h_key ?> ');">
                            <i class="fa fa-plus-square" aria-hidden="true"></i>
                            <?php echo get_phrase('add_challan_form');?>
                        </a>

                        <?php
                    	}
                        ?>
                    </div>
                    <?php
				$toggle = true;
				$settings="select distinct ccf.*, c.name as class_title, cs.title as section_name,d.title as dep_title from  ".get_school_db().".class_chalan_form ccf inner join ".get_school_db().".class_section cs on cs.section_id=ccf.section_id inner join ".get_school_db().".class c on c.class_id=cs.class_id inner join ".get_school_db().".departments d on c.departments_id=d.departments_id where  
					ccf.school_id=".$_SESSION['school_id']." 
					and cs.section_id = ".$h_key." and type !=10 and ccf.status !=0 ";

                    
				
				$settingsRes=$this->db->query($settings)->result_array();
				if (count($settingsRes) > 0)
				{
					foreach($settingsRes as $row)
					{
						if($row['status']==1)
							{  $status=get_phrase("active");  }
					 	else
					 		{ $status= get_phrase('inactive');  }
						
						
						
						
						$c_c_f_id=$row['c_c_f_id'];
						
						
						$period_array=array();
						//$hierarchy=section_hierarchy($row['section_id']);
						?>
                        <!--	  <button data-toggle="collapse" data-target="#demo">Collapsible</button>

<div id="demo" class="collapse"> -->
                        <div data-toggle="collapse" data-target="#collapse<?php echo $c_c_f_id;?>" class="panel-group collapse child<?php echo $h_key?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo $h_key;?>">
                            <div class="panel panel-default cursor">
                                <div class="panel-heading">
                                    <h4 class="panel-title" style="border-left:4px solid  #902222;font-size: 14px !important">
                                        <a onclick="toggle_func('<?php echo $c_c_f_id;?>','<?php echo $h_key;?>')" style="float:left;">
                                            <i class="fa fa-print" aria-hidden="true" style="    color: #902222;"></i>
                                            <?php  $title=$row['title'];     ?>
                                            <?php  echo $title ; ?>
                                            
                                            
                                            <span class="fontsizesmall" style="font-size:13px;">  (<?php  echo   $status ;?>) 
					
					
					(<?php  echo  get_phrase("type").":"; ?>
						
						
						<?php
						
			       echo $type = display_class_chalan_type($row['type']);?>)
					</span>
                                        </a><i class="fa fa-caret-down" style="color: color: #902222;"></i>
                                        <span style="float:right;padding-top: 4px;" class="myfsize"> 
                                        <?php
				                        if (right_granted('managechallanform_manage'))
					    				{
				                        ?>
				                            <a href="#" class="text-white" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/chalan_form_setting/<?php echo $h_key; ?>/<?php echo $c_c_f_id; ?>');">


											<i class="fa fa-cog"></i>
											<?php echo get_phrase('manage_fee_type');?>
											</a>

                                            <a href="#" class="text-white" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/chalan_form_discount/<?php echo $h_key; ?>/<?php echo $c_c_f_id; ?>');">


											<i class="fa fa-cog"></i>
                                                <?php echo get_phrase('manage_discount');?>
											</a>
										
											<a href="#" class="text-white" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/chalan_form_add_edit/edit/<?php echo $c_c_f_id; ?>/<?php echo $h_key; ?>');">
				                                <i class="entypo-pencil"></i><?php echo get_phrase('edit');?>
											</a>
										<?php
										}

                                       // if (right_granted('managechallanform_manage'))
                                        {
                                            ?>




                                            <?php
                                        }

                                        /*
										if (right_granted('managechallanform_delete'))
										{
										?>	

											<a href="#" onclick="delete_func('<?php echo $c_c_f_id; ?>','<?php echo $h_key;?>');">
												<i class="entypo-trash"></i><?php echo get_phrase('delete');?>
											</a>
										<?php
										}
                                        */
										?>
											<a href="#" class="text-white" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/chalan_form_listing/<?php echo $row['type'] ?>/<?php echo $row['section_id'] ?>/<?php echo $h_key; ?>');">
                                                <i class="entypo-plus"></i><?php echo get_phrase('view_archive');?>
                                            </a>
										</span>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div id="collapse<?php echo $c_c_f_id;?>" class="panel-collapse collapse" style="margin-left:26px; margin-right:26px; border:1px solid #EEE;">
                        </div>
                        <?php
			        }
			    }
			    else
			    {?>
                            <div class="panel-group collapse child<?php echo $h_key?>" style="margin-bottom: 0px; padding:0px 10px 0px 10px;" id="accordion<?php echo $h_key;?>">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title" style="font-size:14px !important;">
                    <?php echo get_phrase('no_settings_found');?>.
                                        </h4>
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
<!--</div>-->
<script type="text/javascript">
$(document).ready(function() {

});

function myFunction(a) {
    $(".child" + a).slideToggle("slow");
}

function toggle_func(c_c_f_id, section_id) {
    $.ajax({
        type: 'POST',
        data: {
            section_id: section_id,
            c_c_f_id: c_c_f_id
        },
        url: "<?php echo base_url(); ?>class_chalan_form/accordion_generator",

        dataType: "html",
        success: function(response) {
            //alert(response);
            $('#collapse' + c_c_f_id).html(response);
        }
    });
}

function delete_func(c_c_f_id, section_id) {
    var result = confirm("<?php echo get_phrase('are_you_sure_you_want_to_delete');?>");
    if (result) {
        $.ajax({
            type: 'POST',
            data: {
                c_c_f_id: c_c_f_id,
                section_id: section_id,
            },
            url: "<?php echo base_url(); ?>class_chalan_form/delete_bulk_ccf",

            dataType: "html",
            success: function(response) {

                location.reload();
               /*if ($.trim(response) == 1)
               {
                    location.reload();
               }*/

            }
        });
    }
}
</script>
