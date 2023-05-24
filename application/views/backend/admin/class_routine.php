<?php
$department_id;
$sect_id;
$c_rout_set_id;
if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
     </div> 
    </div>';
  }
  ?>
<script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>
<?php 
    $resArr='';
    $dept_id='';
    $class_id='';
    $section_id='';
    $year='';
    $urlArr=explode('/',$_SERVER['REQUEST_URI']);
    $resArr=explode('-',end($urlArr));
    if(sizeof($resArr)>1)
    {
        $dept_id=$resArr[0];
        $class_id=$resArr[1];
        $section_id=$resArr[2];
        $year=$resArr[3];
    }
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('manage_time_table'); ?>
        </h3>
    </div>
</div>



<div class="col-lg-12 col-sm-12">
    <form data-step="1" data-position='top' data-intro="select class -> section to get specific class timetable" id="filter" name="filter" method="post" class="form-horizontal form-groups-bordered validate" style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
        <div class="row filterContainer" style="padding-top: 14px;margin:0px;">
            <div class="col-lg-6 col-md-6 col-sm-6 form-group ml-2">
                <select id="section_id_filter" class="selectpicker form-control" name="section_id">                            
                        <?php echo section_selector();?>                              
     		    </select>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 form-group ml-2">
                <input type="submit" id="select" class="btn btn-primary" value="<?php echo get_phrase('filter'); ?>">
                <a href="<?php echo base_url(); ?>time_table/class_routine" style="display: none;" class="btn btn-danger" id="btn_remove"> 
                      <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?>
                </a>
            </div>
        </div>
    </form>
</div>


<div id="table">
</div>
<script type="text/javascript">
$(document).ready(function() 
{
	$(".page-container").addClass("sidebar-collapsed");
    document.getElementById('filter').onsubmit = function() {
        return false;
    };
    url = '<?php echo base_url()?>time_table/class_routine';


    var datatable = $("#table_export").dataTable();
    $(".dataTables_wrapper select").select2({
        minimumResultsForSearch: -1
    });
    

   
 

    $("#select").click(function(e) 
    {
        var section_id_filter = $("#section_id_filter").val();
        if (section_id_filter != '') {
			
			$("#btn_remove").show();
            $("#table").html("<div id='loading' class='loader'></div>");
            $.ajax({
                type: 'POST',
                data: {
                    section_id_filter: section_id_filter
                },
                url: "<?php echo base_url();?>time_table/get_class_routine",
                dataType: "html",
                success: function(response) {

                    $('#academic-error').hide();

                    $("#loading").remove();
                    $("#table").html(response);

                }
            });
        } else {

            $('#academic-error').show();
        }
    });
    
    

    $("#table").html("<div id='loading' class='loader'></div>");
    var department_id='<?php echo $department_id;?>';
    var sect_id='<?php echo $sect_id;?>';
    var c_rout_set_id='<?php echo $c_rout_set_id;?>';
    var departments_id="";
	if(department_id!="")
	{
		
		departments_id=department_id;
		
	}
	section_id="";
	if(sect_id!="")
	{
		section_id=sect_id;
	}
	c_rout_id="";
	if(c_rout_set_id!="")
	{
		c_rout_id=c_rout_set_id;
	}
	
    $.ajax({
        type: 'POST',
        data: {
            departments_id: departments_id,
            class_id: '',
            section_id:section_id,
            c_rout_set_id:c_rout_set_id
             
        },
        url: "<?php echo base_url();?>time_table/get_class_routine",
        dataType: "html",
        success: function(response) {

            $('#academic-error').hide();

            $("#loading").remove();
            $("#table").html(response);

        }
    });

});
</script>
