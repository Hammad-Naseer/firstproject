<?php
if (right_granted(array('examlist_manage')))
{
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
                <?php echo get_phrase('examination_list'); ?>
            </h3>
        </div>
    </div>
    <form action="" method="post" id="filter" class="form-horizontal form-groups-bordered validate" style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
        <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select yearly term and click on filter button to get specific records">
            <div class="col-md-6 col-lg-6 col-sm-6">
              <label id="yearly_terms_filter_selection"></label>
                    <select id="yearly_terms_filter" name="yearly_terms1" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                        <?php
                            $status_year=array(3);
                            echo yearly_term_selector('',$status_year);
                         ?>
                    </select>   
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6">
            <button type="submit" id="select" class="modal_save_btn"><?php echo get_phrase('filter'); ?></button>
            
                <a href="<?php echo base_url(); ?>exams/exam" style="display: none;" class="modal_cancel_btn" id="btn_remove"> 
                <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?></a>
                </div>
            
        </div>
    </form>
    <div class="row">
        <div id="exam-list" style="width: 100%;"></div>
    </div>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
    	 $('.selectpicker').on('change', function() {
            var id = $(this).attr('id');
            var selected = $('#' + id + ' :selected');
            var group = selected.parent().attr('label');
            $('#' + id + '_selection').text(group);
        });
        document.getElementById('filter').onsubmit = function() {
            return false;
        };
        var datatable = $("#table_export").dataTable();

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
        $('#exam-list').load("<?php echo base_url(); ?>exams/get_exam_list");
        $('#academic_id').on('change', function() {
            var academic_year = $(this).val();
            //alert(academic_year)
            if (academic_year == '') {
                $('#term_id').html('<select><option><?php echo get_phrase('select_term'); ?></option></select>');
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>exams/get_terms",
                    data: ({
                        academic_year: academic_year
                    }),
                    dataType: "html",
                    success: function(html) {
                        //console.log(html);
                        if (html != '') {
                            $('#term_id').html(html);
                        }
                    }
                });
            }
        });

        $('#select').click(function() {
            //var academic_id = $('#academic_id').val();
            var term_id = $('#yearly_terms_filter').val();
            ///t(academic_id);
            if (term_id != '') {
                $('#btn_remove').show();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>exams/get_exam_list",

                    data: ({
                        term_id: term_id
                    }),
                    dataType: "html",
                    success: function(html) {
                        //alert(html);
                        if (html != '') {
                            $('#exam-list').html(html);
                        } else {
                            $('#exam-list').text('<?php echo get_phrase('no_records_found'); ?>');
                        }
                    }
                });
            }
        });
    });
    </script>

<?php } ?>