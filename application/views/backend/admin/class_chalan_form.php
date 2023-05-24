<?php
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
 
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                   <?php echo get_phrase('manage_challan_form'); ?>
            </h3>
        </div>
    </div>
    
    <form name="chalan-filter" id="chalan-filter" class="validate">
        <div class="row filterContainer" data-step="1" data-position='right' data-intro="Please select the filters and press Filter button to get specific records">
            <div class="col-lg-4 col-md-4 col-sm-4">
            	<div class="form-group">
                    <select id="section_id_filter" class="selectpicker form-control" name="section_id">                            
                        <?php echo section_selector();?>
                    </select> 
            	</div>	
            </div>
            
            <div class="col-lg-4 col-md-4 col-sm-4">
            	<div class="form-group">
                    <button id="select" class="btn btn-primary">
                        <?php echo get_phrase('filter'); ?>
                    </button>
                    <a href="<?php echo base_url(); ?>class_chalan_form/class_chalan_f" style="display: none;" class="modal_cancel_btn" id="btn_remove">
                    <i class="fa fa-remove"></i>
                    <?php echo get_phrase('remove_filter'); ?></a> 
            	</div>	
            </div>
            
        </div>
    </form>
    <div id="dept_div" data-step="2" data-position='top' data-intro="Collapse the department then you can add new challan form & collapse class then you can view challan form , manage fee types , manage discounts, edit challan form"></div>
    <div id="table_data">
    </div>
    </div>
    <script>
    $(document).ready(function() {
        document.getElementById('chalan-filter').onsubmit = function() {
            return false;
        };

        $('#dept_div').load("<?php echo base_url(); ?>class_chalan_form/class_challan_generator");
    });

    $("#select").click(function() {
       
        var section_id = $("#section_id_filter").val();
        if (section_id != '') {
            $("#table").html("<div id='loading' class='loader'></div>");
            $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id
                },
                url: "<?php echo base_url();?>class_chalan_form/class_challan_generator",
                dataType: "html",
                success: function(response) {
                    $('#btn_remove').show();

                    //alert(response);

                    $('#academic-error').hide();

                    $("#loading").remove();
                    $("#dept_div").html(response);

                }
            });
        } else {

            $('#academic-error').show();
        }
    });

    $("#departments_id").change(function() {
        var dep_id = $(this).val();

        $("#icon").remove();
        $(this).after('<div id="icon" class="loader_small"></div>');

        $.ajax({
            type: 'POST',
            data: {
                dep_id: dep_id
            },
            url: "<?php echo base_url();?>c_student/get_class",
            dataType: "html",
            success: function(response) {



                $("#icon").remove();

                $("#class_id").html(response);
                $("#section_id").html("<option value=''><?php echo get_phrase('select_section'); ?></option>");


            }
        });



    });

    $("#class_id").change(function() {
        var class_id = $(this).val();

        $("#icon").remove();
        $(this).after('<div id="icon" class="loader_small"></div>');

        $.ajax({
            type: 'POST',
            data: {
                class_id: class_id
            },
            url: "<?php echo base_url();?>c_student/get_section",
            dataType: "html",
            success: function(response) {



                $("#icon").remove();

                $("#section_id").html(response);



            }
        });



    });
    </script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var datatable = $("#table_export").dataTable({
            "sPaginationType": "bootstrap",
            "sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
            "oTableTools": {
                "aButtons": [

                    {
                        "sExtends": "xls",
                        "mColumns": [0, 2, 3, 4]
                    }, {
                        "sExtends": "pdf",
                        "mColumns": [0, 2, 3, 4]
                    }, {
                        "sExtends": "print",
                        "fnSetText": "Press 'esc' to return",
                        "fnClick": function(nButton, oConfig) {
                            datatable.fnSetColumnVis(1, false);
                            datatable.fnSetColumnVis(5, false);

                            this.fnPrint(true, oConfig);

                            window.print();

                            $(window).keyup(function(e) {
                                if (e.which == 27) {
                                    datatable.fnSetColumnVis(1, true);
                                    datatable.fnSetColumnVis(5, true);
                                }
                            });
                        },

                    },
                ]
            },

        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
    });
    </script>
