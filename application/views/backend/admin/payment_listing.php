<?php
if($this->session->flashdata('club_updated'))
{
    echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">�</button>
	'.$this->session->flashdata('club_updated').'
	</div> 
	</div>';
}
?>
<script>
    $( window ).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
</script>


<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <img class="img-responsive mynavimg2" src="<?php echo base_url();?>assets/images/challan-form-icon.png">
            <?php echo get_phrase('student_chalan_listing');?>
        </h3>
    </div> 
</div>

<div class="row">
    <a class="btn btn-success" href="<?php echo base_url(); ?>c_student/student_information/<?php echo $section_id;?>"><?php echo get_phrase('back_to_listing');?></a>
</div>

<?php

$ret_val     = student_details($student_id);

$section_ary = section_hierarchy($ret_val[0]['section_id']);

if($ret_val[0]['image']!="")
{
    $img_dis = display_link($ret_val[0]['image'],'student');
}
else
{
    $img_dis = base_url().'/uploads/default.png';
}

?>


<div class="row" style="margin-top:20px;">
    <div class="col-sm-4 ">
        <div class="row std_three" style="padding:8px;">
            <div class=" col-sm-3 std_one">
                <span class="std_img">
                    <img  class="img-responsive"  src="<?php echo $img_dis ; ?>" style="max-height: 85px;max-width: 85px;min-height: 85px;min-width: 85px;padding-right: 10px;">
                </span>
            </div>
            <div class=" col-sm-9  std_two">
                <p class="std_name"><?php echo get_phrase('name');?> : <?php echo $ret_val[0]['name']; ?></p>
                <p class="std_class"><?php echo get_phrase('department');?> : <?php echo $section_ary['d']; ?></p>
                <p class="std_class"><?php echo get_phrase('class');?> : <?php echo $section_ary['c']; ?></p>
                <p class="std_class"><?php echo get_phrase('section');?> : <?php echo $section_ary['s']; ?></p>
                <p class="std_roll"><?php echo get_phrase('roll');?> : <?php echo $ret_val[0]['roll']; ?></p>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div id="table" class="col-md-12 col-lg-12 col-sm-12">

    </div>
</div>


<input type="hidden" id="section_id" name="section_id" value="<?php echo $ret_val[0]['section_id']; ?>">
<input type="hidden" id="departments_id" name="departments_id" value="<?php echo $section_ary['d_id']; ?>">
<input type="hidden" id="class_id" name="class_id" value="<?php echo $section_ary['c_id']; ?>">
<input type="hidden" id="academic_year" name="academic_year" value="<?php echo $ret_val[0]['academic_year_id']; ?>">


<!-- DATA TABLE EXPORT CONFIGURATIONS -->
<script type="text/javascript">


    $(document).ready(function()
    {
        get_all_rec();
        $("#departments_id").change(function()
        {
            var dep_id=$(this).val();

            $(".loader_small").remove();
            $(this).after('<div class="loader_small"></div>');

            $.ajax
            ({
                type: 'POST',
                data: {dep_id:dep_id},
                url: "<?php echo base_url();?>c_student/get_class",
                dataType: "html",
                success: function(response)
                {
                    $(".loader_small").remove();
                    $("#section_id").html("<option value=''><?php echo get_phrase('select_class'); ?></option>");
                    $("#class_id").html(response);
                }
            }
            );
        });

        $("#class_id").change(function()
        {
            var class_id = $(this).val();

            $(".loader_small").remove();
            $(this).after('<div class="loader_small"></div>');

            $.ajax({
                type: 'POST',
                data: {class_id:class_id},
                url: "<?php echo base_url();?>c_student/get_section",
                dataType: "html",
                success: function(response) 
                {
                    $(".loader_small").remove();
                    $("#section_id").html(response);
                }
            });

        });

        $("#select").click(function(){
            $("#btn_show").show();
            get_all_rec();
        });

    });


    function get_all_rec(){
        
        var section_id     = $("#section_id").val();
        var departments_id = $("#departments_id").val();
        var class_id       = $("#class_id").val();
        var academic_year  = $("#academic_year").val();
        
        
        //alert(section_id + '||' + departments_id + '||' + class_id + '||' + academic_year);
        

        $("#loading").remove();
        $("#table").html("<div id='loading' class='loader'></div>");
        $.ajax({
            type: 'POST',
            data: {section_id:section_id,departments_id:departments_id,class_id:class_id,academic_year:academic_year},
            url: "<?php echo base_url();?>payments/get_chalan_listing/<?php echo $student_id; ?>",
            dataType: "html",
            success: function(response) {

                $("#table").html(response);

            }
        });


    }

</script>

<style>


    .loader {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #63b7e7; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }

    .loader_small {
        border: 7px solid #f3f3f3;
        border-top: 7px solid #63b7e7;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }



</style>