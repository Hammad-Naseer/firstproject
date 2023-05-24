
<?php
if ( $this->session->flashdata( 'candidate_delete' ) ) {
    echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	' . $this->session->flashdata( 'candidate_delete' ) . '
	</div> 
	</div>';
}

if ( $this->session->flashdata( 'delete_challan_form' ) ) {
    echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	' . $this->session->flashdata( 'delete_challan_form' ) . '
	</div> 
	</div>';
}

?>
<div class="row">
    
    <form name="marksheet" id="marksheet" class="form-horizontal  validate">
        <div class="col-sm-12">
            <h3 class="black black2 myttl">
               <?php echo get_phrase('mark_sheet');?>
            </h3>
        </div>
        <div class="myrow" style="background-color: #f0f0f0;height: 80px;padding: 9px 0px;">
            
            <div class="col-md-4 col-lg-4 col-sm-6">
                <!--<label id="exams_filter_selection"></label> -->
                <select id="yearly_term" name="yearly_term" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                    <?php echo yearly_terms_option_list($_SESSION['academic_year_id']);?>
                </select>
            </div>
            
            <div class="col-md-4 col-lg-4 col-sm-6">
                <!--<label id="exams_filter_selection"></label>-->
                <select id="exams_filter" name="yearly_terms1" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">
                    <?php
                     $status_year=array(3);
                     $status_term=array(3); 
                     
                     echo yearly_term_selector_exam('',$status_year,$status_term);
                    ?>
                </select>
            </div>
            
            <div class="col-md-4 col-lg-4 col-sm-6">
                <button type="button" class="btn btn-success" id="filterBtn">Filter Result</button>
            </div>
            
            
        </div>    
    </form>
<hr/>
<div id="exam_result">
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.selectpicker').on('change', function() {
        var id = $(this).attr('id');
        var selected = $('#' + id + ' :selected');

        var group = selected.parent().attr('label');
        $('#' + id + '_selection').text(group);

    });
    
    
    $('#filterBtn').click(function(){
           var yearly_term_id = $('#yearly_term').val();
           var exam_id        = $('#exams_filter').val();
           
           
           if (exam_id != '' && yearly_term_id != '') {


                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>exams/get_exam_result",
    
                    data: ({
                        exam_id: exam_id,
                        student_id: '<?php echo $student_id;?>',
                        yearly_term_id : yearly_term_id
                    }),
                    dataType: "html",
                    success: function(html) {
                        if (html == 'False' || html == 'false') {
                            var html;
                           
                            html='<div align="center"><div class="alert alert-success alert-dismissable"><button type="button" class="close" value="result_not_found" data-dismiss="alert" aria-hidden="true">×</button>Result Not Found</div></div>';
		                    
                            $('#exam_result').html(html);
                             setTimeout(function() {
                                $('#exam_result').html("");
                            }, 3000);
                            
                        }else{
                             $('#exam_result').html(html);
                        }
                    }
                });
        }
           
           
           
    });
    // $('#exams_filter').prop('disabled', true);
    $('#yearly_term').click(function(){
           var yearly_term_id = $('#yearly_term').val();
        //   $('#exams_filter').prop('disabled', true);
           
           
           
          if (yearly_term_id != '') {


                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>exams/get_exams_by_yearly_term",
    
                    data: ({
                        
                        yearly_term_id : yearly_term_id
                    }),
                    dataType: "html",
                    success: function(data) {
                        console.log(data)
                        
                    }
                });
        }
           
           
           
    });
    

    document.getElementById('marksheet').onsubmit = function() {
        return false;
    };
    
    
    /*
    $('#yearly_term').on('change', function() {
        var yearly_term = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>exams/get_exam_type",
            data: ({
                yearly_term: yearly_term
            }),
            dataType: "html",
            success: function(html) {
                if (html != '') {
                    $('#exam_id').html(html);
                }
            }
        });
    });
    */
    
    /*
    $('#exams_filter').on('change', function() {

        var exam_id = $(this).val();
        if (exam_id == '') {
            $('#exam_result').text('');
        }

        if (exam_id != '') {


            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>exams/get_exam_result",

                data: ({
                    exam_id: exam_id,
                    student_id: '<?php echo $student_id;?>'
                }),
                dataType: "html",
                success: function(html) {
                    if (html != '') {
                        $('#exam_result').html(html);
                    }
                }
            });
        }
    });
    */
    
});
</script>
