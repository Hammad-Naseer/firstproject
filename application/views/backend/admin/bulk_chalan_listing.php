<link href="<?=base_url()?>assets/intro.js/introjs.css" rel="stylesheet">
<script type="text/javascript" src="<?=base_url()?>assets/intro.js/intro.js"></script>
<?php
if (right_granted(array('bulkmonthlychallan')))
{

    if($this->session->flashdata('club_updated')){
    	echo "<script>
    	    Swal.fire({
              position: 'top-end',
              icon: 'success',
              title: '".$this->session->flashdata('club_updated')."',
              showConfirmButton: false,
              timer: 3000
            });
    	</script>";
    }
?>
<style>
#error_message {
    width: 100%;
    background: tomato;
    display: block;
    padding: 10px;
    margin-bottom: 15px;
    color: white;
    font-weight: bold;
    text-align: center;
    font-size: 16px;
}

#success_message{
    width: 100%;
    background: #39a70b;
    display: block;
    padding: 10px;
    margin-bottom: 15px;
    color: white;
    font-weight: bold;
    text-align: center;
    font-size: 16px;
}

#error_message {
    width: 100%;
    background: tomato;
    display: block;
    padding: 10px;
    margin-bottom: 15px;
    color: white;
    font-weight: bold;
    text-align: center;
    font-size: 16px;
}



.mgt20 {
    margin-top: 20px;
}

#d1 {
    color: red;
}

#d2 {
    color: red;
}

#d3 {
    color: red;
}
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('monthly_challan'); ?>
        </h3>
    </div>
</div>
<div id="main_form">
    <div class="row filterContainer">
        <div class="col-md-4 col-lg-4 col-sm-4" data-step="1" data-position='top' data-intro="Please select class-section">
            <label id="section_id_filter_selection">Select Class-Section</label>
            <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                <?php echo section_selector();?>
            </select>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-4" data-step="2" data-position='top' data-intro="Please select year">
            <label id="yearly_terms_filter_selection">Select Month</label>
            <select id="month" name="yearly_terms1" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                <?php echo month_option_list(); ?>
            </select>
        </div>
        <div class="col-md-4 col-lg-4 col-sm-4" data-step="3" data-position='top' data-intro="Please select month">
            <label>Select Year</label>
            <select id="year" name="month_year" class="form-control" required>
                <?php echo year_option_list(date('Y')-1,date('Y')+1); ?>
            </select>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12 mgt10 ">
            <button data-step="4" data-position='right' data-intro="Click on this button search previous monthly challans records" type="button" id="filter" class="modal_save_btn"> <?php echo get_phrase('search_chalan'); ?></button>
            <span style="font-weight: 700;color: white;padding-left: 10px;padding-right: 10px;">OR</span>
            <a data-step="5" data-position='right' data-intro="Click on this button to generate monthly challans" id="select" class="modal_cancel_btn"><?php echo get_phrase('generate_monthly_challan'); ?></a>
            <a data-step="6" data-position='right' data-intro="Click on this button view all monthly challan records" href="<?=base_url()?>monthly_fee/monthly_bulk_listing" class="modal_save_btn"><?php echo get_phrase('view_all_chalan'); ?></a>
        </div>
    </div>
</div>

<div class="col-md-12 col-lg-12 col-sm-12">
    <h4 class="text-danger text-center" style="margin-top:20px;"><b id="not_found"></b></h4>    
    <div id="table" style="width:100%"></div>
</div>

<!-- DATA TABLE EXPORT CONFIGURATIONS -->
<script type="text/javascript">
    $(document).ready(function() {
        $('.selectpicker').on('change', function() {
            var id = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });
    
        get_all_rec();
        $("#departments_id").change(function() {
            var dep_id = $(this).val();
            $(".loader_small").remove();
            $(this).after('<div class="loader_small"></div>');
            $('#class_id').html('<option value=""><option>');
            $('#section_id').html('<option value=""><option>');
            get_class(dep_id, '#class_id');
        });
        $("#class_id").change(function() {
            var class_id = $(this).val();
            $(".loader_small").remove();
            $(this).after('<div class="loader_small"></div>');
            $('#section_id').html('<option value=""><option>');
            get_section(class_id, '#section_id');
        });
        $("#academic_year_id").change(function() {
            var academic_year_id = $(this).val();
            if (academic_year_id == "") {
                $("#loader").remove();
                $("#yearly_term_id").html("<option value=''><?php echo get_phrase('select_term'); ?></option>");
                $("#month_year").html("<option value=''><?php echo get_phrase('select_month'); ?> - <?php echo get_phrase('year'); ?></option>");
            }else {
                $("#loader").remove();
                $("#academic_year_id").after("<div id='loader' class='loader_small'></div>");
                $.ajax({
                    type: 'POST',
                    data: {
                        academic_year_id: academic_year_id
                    },
                    url: "<?php echo base_url();?>monthly_fee/get_yearly_term",
                    dataType: "html",
                    success: function(response) {
                        $("#loader").remove();
                        $("#yearly_term_id").html(response);
                    }
                });
            }
        });
        $("#yearly_terms_filter").change(function() {
            var academic_year_id = $(this).val();
            $("#loader").remove();
            $("#yearly_terms_filter").after("<div id='loader' class='loader_small'></div>");
            if (academic_year_id == "") {
                $("#loader").remove();
                $("#month_year").html("<option value=''><?php echo get_phrase('selecct_year_month'); ?></option>");
            } else {}
            $.ajax({
                type: 'POST',
                data: {
                    academic_year_id: academic_year_id
                },
    			url: "<?php echo base_url();?>monthly_fee/month_year_option",
                dataType: "html",
                success: function(response) {
                    $("#loader").remove();
                    $("#month_year").html(response);
                }
            });
        });
    
        
        $("#departments_id_p").change(function() {
            var dep_id = $(this).val();
            $(".loader_small").remove();
            $(this).after('<div class="loader_small"></div>');
            get_class(dep_id, '#class_id_p');
            
        });
    
        $("#class_id_p").change(function() {
            var class_id = $(this).val();
            $(".loader_small").remove();
            $(this).after('<div class="loader_small"></div>');
            get_section(class_id, '#section_id_p');
            
        });
        
    
        $("#select").click(function()
        {
            Swal.fire({
              title: 'Are you sure?',
              text: "You want to generate class chalan!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes'
            }).then((result) => {
              if (result.isConfirmed) {
                
                    $("#btn_show").show();
                    $('#loading').remove();
                    $('#msg').remove();
                    $("#promotion").before("<div id='loading' class='loader_small'></div>");
            
                    $('#promotion').before('<span id="msg"><?php echo get_phrase('request_is_already_submitted') ?></span>');
                    $('#msg').remove();
                    var section_id = $('#section_id_filter').val();
            
                    var month = $('#month').val();
                    var year  = $('#year').val();
            
                    if (section_id == "" || (month == "" && year == "") )
                    {
                        $('#error_message').remove();
                        $('#main_form').before("<span id='error_message'> <?php echo get_phrase('no_option_selected') ?></span>");
                        $("#btn_show").hide();
                    }
                    else
                    {
                       $.ajax({
                            type: 'POST',
                            data: { section_id: section_id,month: month,year: year },
                            url: "<?php echo base_url();?>monthly_fee/check_promotion_req",
                            dataType: "html",
                            beforeSend: function() {
                                $("#select").text("Wait");
                                $("#select").css("background-color","#dc2d2dab !important");
                            },
                            success: function(response) {
            
                                if ($.trim(response) =="yes") {
            
                                    save_chalan(section_id, month, year);
                                    $("#select").text("Generate Monthly Chalan");
                                    $("#select").css("background-color","#dc2d2d !important");
                                    // setTimeout(function(){ location.reload(); }, 2000);
                                    get_all_rec();
                                } else {
                                    $('#error_message').remove();
                                    $('#main_form').before("<span id='error_message'><?php echo get_phrase('request_is_already_submitted') ?></span>");
            
                                }
                                $('#loading').remove();
                            }
                        });
                    }
                }    
            });
        });    
    
    
        $('#filter').click(function()
        {
            $("#d1").html("");
            $("#d2").html("");
            $("#d3").html("");
    
            if ($('#month').val() == "")
            {
                $("#d2").html("<?php echo get_phrase('value_required'); ?>");
            } else if ($('#year').val() == "")
            {
                $("#d3").html("<?php echo get_phrase('value_required'); ?>");
            } else
            {
                $("#btn_show").show();
                get_all_rec();
            }
        });
    });




    function get_section(class_id, retun_id)
    {
        $.ajax(
            {
            type: 'POST',
            data: {
                class_id: class_id
            },
            url: "<?php echo  base_url();?>monthly_fee/get_section",
            dataType: "html",
            success: function(response)
            {
                $(".loader_small").remove();
                $(retun_id).html(response);
            }
        });
    
    }
    
    function get_class(dep_id, retun_id) {
    
        $.ajax({
            type: 'POST',
            data: {
                dep_id: dep_id
            },
            url: "<?php echo base_url();?>monthly_fee/get_class",
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $(retun_id).html("<option value=''><?php echo get_phrase('select_class'); ?></option>");
                $(retun_id).html(response);
            }
        });
    
    
    
    }
    
    function save_chalan(section_id, month, year) {
    
    
      //  alert('save_promotion_req');
        $.ajax({
            type: 'POST',
            data: {
                section_id: section_id,
                month: month,
                year: year
            },
            url: "<?php echo base_url();?>monthly_fee/save_promotion_req",
            dataType: "html",
            success: function(response) {
                get_all_rec();
                $('#error_message').remove();
                if(response == "Challan Generated")
                {
                    Swal.fire({
                      position: 'top-end',
                      icon: 'success',
                      title: 'Chalan Form Generated Successfully, Issue Chalan Forms & Print',
                      showConfirmButton: false,
                      timer: 3000
                    });
                    get_all_rec();
                }else{
                    Swal.fire({
                      position: 'top-end',
                      icon: 'error',
                      title: response,
                      showConfirmButton: false,
                      timer: 3000
                    });
                }
                
            }
        });
    }
    
    function get_all_rec()  
    {
        var section_id = $("#section_id_filter").val();
        var yearly_term_id = $("#yearly_terms_filter").val();
        if($("#month").val()!=="" && $("#year").val()!==""){
	         var month_year = $("#month").val()+'-'+$("#year").val();
    	}else{
	        var month_year ="";
	    }
   
        $("#loading").remove();
        $("#table").html("<div id='loading' class='loader'></div>");

        $.ajax({
            type: 'POST',
            data: {
                section_id: section_id,
                yearly_term_id: yearly_term_id,
                month_year: month_year
            },
            url: "<?php echo base_url();?>monthly_fee/get_student_info",
            dataType: "html",
            success: function(response) {
                if(response == "0")
                {
                    $("#loading").remove();
                    $("#not_found").text("Record Not Found");
                }else{
                    $("#table").html(response);
                    $("#not_found").text("");
                }
            }
        });

    }
    
    // This default onbeforeunload event
    window.onbeforeunload = function(){
        return "Do you want to leave?"
    }
</script>

<?php } ?>
<?php
    $tour_q = $this->db->query("SELECT COUNT(b_m_c_id) AS 'Check' FROM ".get_school_db().".`bulk_monthly_chalan` WHERE school_id = '".$_SESSION['school_id']."'")->row();
    if($tour_q->Check == 0)
    {
?>
    <script>
        setTimeout(
          function() 
          {
            $('#chalan_tour').trigger('click');
          }, 2000);
          $("#chalan_tour").hide();
    </script>
<?php }else{ ?>
    <script>
          $("#chalan_tour").show();
    </script>
<?php } ?>
