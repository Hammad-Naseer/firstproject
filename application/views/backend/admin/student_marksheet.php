<?php
if (right_granted('examresult_viewmarksheet'))
{
?>

    <div class="row">
        <div class="col-md-12 mt-4">
        <?php
            if ( $this->session->flashdata( 'not_found' ) ) {
                echo '<div align="center">
                	<div class="alert alert-danger alert-dismissable">
                	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                	' . $this->session->flashdata( 'not_found' ) . '
                	</div> 
                </div>';
            }
        ?>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <script>
                $(window).on("load",function() {
                    setTimeout(function() {
                        $('.alert').fadeOut();
                    }, 3000);
                });
            </script>
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline">
                <?php echo get_phrase('exams_results');?>
              
            </h3>
        </div>
    </div>
    <form name="marksfilter" id="marksfilter" method="post" class="form-horizontal  validate">
        <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filter and press the select button to get specific records">    
            <div class="col-md-4 col-lg-4 col-sm-4 mt-3">
                <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                    <?php echo section_selector();?>
                </select>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 mt-3">
                <!--<label id="exams_filter_selection"></label> -->
                <select id="yearly_term" name="yearly_term" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                    <?php echo yearly_terms_option_list($_SESSION['academic_year_id']);?>
                </select>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 mt-3">
                <!--<label id="exams_filter_selection"></label>-->
                <select id="exams_filter" name="yearly_terms1" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                    <option value="">Select Exam</option>
                </select>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
                <button type="submit" id="select" class="modal_save_btn"><?php echo get_phrase('filter');?></button>
                <a id="btn_show" href="<?php echo base_url();?>exams/student_marksheet" class="modal_cancel_btn" style="padding: 5px 8px !important; display: none;">Remove Filter</a>
            </div>
            
        </div>
    </form>
    <div class="row">
        <div id="marksheet" style="width:100%"></div>
    </div>
    <script type="text/javascript">
    $(document).ready(function() {
        
        $("#yearly_term").on('click',function(){
            var yearly_term_id = $(this).val();
            if(yearly_term_id != ''){
                $("#icon").remove();
                $(this).after('<div id="icon" class="loader_small"></div>');
                $.ajax({
                    type: 'POST',
                    data: {
                        yearly_term_id: yearly_term_id
                    },
                    url: "<?php echo base_url();?>exams/get_exams_by_yearly_term",
                    dataType: "html",
                    success: function(response) {
                        $("#icon").remove();
                        $("#exams_filter").html(response);
                    }
                });
            }else{
                $("#exams_filter").html('<option value="">Select Exam</option>');
            }
        });
        
        $('.selectpicker').on('change', function() {
            var id       = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
            var group    = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });
        var datatable = $("#table_export").dataTable();

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
        document.getElementById('marksfilter').onsubmit = function() {
            return false;
        };
        $("#departments_id").change(function() {
            var dep_id = $(this).val();
            $("#icon").remove();
            $(this).after('<div id="icon" class="loader_small"></div>');
            $.ajax({
                type: 'POST',
                data: {
                    department_id: dep_id
                },
                url: "<?php echo base_url();?>exams/get_class",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $("#class_id").html(response);
                    $("#section_id").html('<select><option><?php echo get_phrase('select_section'); ?></option></select>');
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
                url: "<?php echo base_url();?>exams/get_class_section",
                dataType: "html",
                success: function(response) {
                    $("#icon").remove();
                    $("#section_id").html(response);
                }
            });
        });

        $("#select").click(function() {
            $('#marks-filter').validate();
            var section_id = $("#section_id_filter").val();
             var yearly_term_id = $('#yearly_term').val();
           var exam_id        = $('#exams_filter').val();
            if (section_id != '') {

                $('#btn_show').show();
                $.ajax({
                    type: 'POST',
                    data: {
                        section_id: section_id,
                        exam_id: exam_id,
                        yearly_term_id : yearly_term_id
                    },
                    url: "<?php echo base_url();?>exams/get_marksheet",
                    dataType: "html",
                    success: function(response) {
                        $("#marksheet").html(response);
                    }
                });
            }
        });
        
    //     $('#view_market_sheet').click(function(){
    //       var yearly_term_id = $('#yearly_term').val();
    //       var exam_id        = $('#exams_filter').val();
           
           
    //       if (exam_id != '' && yearly_term_id != '') {


    //             $.ajax({
    //                 type: "POST",
    //                 url: "<?php echo base_url(); ?>exams/get_exam_result",
    
    //                 data: ({
    //                     student_id: '<?php echo $student_id;?>',
    //                     exam_id: exam_id,
    //                     yearly_term_id : yearly_term_id
    //                 }),
    //                 dataType: "html",
    //                 success: function(html) {
    //                     if (html == 'False' || html == 'false') {
    //                         var html;
                           
    //                         html='<div align="center"><div class="alert alert-success alert-dismissable"><button type="button" class="close" value="result_not_found" data-dismiss="alert" aria-hidden="true">×</button>Result Not Found</div></div>';
		                    
    //                         $('#btn_show').hide();
    //                         //  setTimeout(function() {
    //                         //     $('#exam_result').html("");
    //                         // }, 3000);
                            
    //                     }else{
    //                         $('#btn_show').show();
    //                          $('#exam_result').html(html);
    //                     }
    //                 }
    //             });
    //     }
           
           
           
    // });
        
    //     document.getElementById('marksfilter').onsubmit = function() {
    //     return false;
    // };
    });
    
    
    </script>

    <?php
}
?>
