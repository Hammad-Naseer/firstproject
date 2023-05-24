<?php 
if (right_granted(array('managedatesheet_manage')))
{
    $yearCheck='';
    $termCheck='';
    $exam_check='';
    $class="";
    if($exam_id!=''){
    	$exam_check=" and exam_id=".$exam_id."";
    }
    $class="collapse in";
?>

<style type="text/css">
    .current-days
    {
        color:white !important;
    }
    .btn-add{
        padding:5px;
        margin:5px;
        background: aliceblue;
    }
    .btn-edit{
        padding:5px;
        margin:5px;
        background: aliceblue;
    }
    .btn-delete{
        padding:5px;
        margin:5px;
        background: aliceblue;
    }
    .current-day > .datesheet_btn{
        background: white !important;
        color: black !important;
        padding: 4px 9px 4px 9px !important;
        border-radius:15px;
    }
    .datesheet_btn
    {
        background: #0a7ead !important;
        color: white !important;
        padding: 4px 9px 4px 9px !important;
        border-radius:15px;
    }
</style>

<form action="<?=base_url()?>exams/datesheet_pdf_download" method="post">
    <input type="hidden" value="<?=$section_id?>" name="section_id">
    <input type="hidden" value="<?=$exam_id?>" name="exam_id">
    <button type="submit" class="modal_save_btn">Download PDF</button>    
</form>
<div class="panel-group joined" id="accordion-test-2">
    <?php 
		$toggle = true;
		$q="select e.* from ".get_school_db().".exam e inner join ".get_school_db().".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id where e.school_id=".$_SESSION['school_id']." ".$termCheck." ".$yearCheck." ".$exam_check ." order by e.start_date DESC";
		$exams = $this->db->query($q)->result_array();
		foreach($exams as $row){
	?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapse<?php echo $exam_id=$row['exam_id'];?>" >
                    <span id="pdftitle" style="font-size:12px;"> <?php echo $row['name'];?>(<?php echo get_phrase('starts_on');?> : <b><?php echo date('d M Y',strtotime($row['start_date']));?></b> - <?php echo get_phrase('ends_on');?> : <b><?php echo date('d M Y',strtotime($row['end_date']));?></b>)
                    </span> 
                </a>
            </h4>
        </div>
        
        <div id="collapse<?php echo $row['exam_id'];?>" class="panel-collapse collapse in <?php if($toggle){echo '';$toggle=false;}?>" >
            <div class="panel-body">
                <div class="table-responsive">
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered" id="Mypdf">
                    <tbody>
                        <?php 
                            $custom_css = array(1=>'current-day',2=>'holiday');      
                            $current_date = date('d-M-Y');                           
                              
                            $date_from = strtotime($row['start_date']);
                            $date_to = strtotime($row['end_date']);
                            
                            $oneDay = 60*60*24;
                            
                            for($i=$date_from; $i<=$date_to; $i=$i+$oneDay)
                            {
                            	$current = "";
                                $day=date("l", $i);
                            	$date1=convert_date(date("l F j, Y", $i));
                            	$query_date = date("Y-m-d",strtotime($date1));
                            if($date1==$current_date)
                            {
                            	$current=$custom_css[1];
                            } 
                            echo '<tr class="gradeA '.$current.'">'; 	
                        ?>
                        <td width="100">
                            <?php echo $date1;?>
                        </td>
                        <td width="100">
                            <?php echo $day;?>
                        </td>
                        <td>
                            <?php
	                        $q="select er.* from ".get_school_db().".exam_routine er where er.school_id=".$_SESSION['school_id']." and er.exam_id=".$row['exam_id']." and section_id=".$section_id." and er.exam_date = '$query_date' ";
                            $routines=$this->db->query($q)->result_array();

					        foreach($routines as $row2){?>
                            <div class="btn-group border-div" style="width: 100%;" id="er<?php echo $row2['exam_routine_id'];?>">
                            <?php 
						        $exam_date=$row2['exam_date'];
							    if(strtotime($row2['exam_date'])==$i){
							?>
                                <?php 
    							    echo get_subject_name($row2['subject_id']);
    							    echo '('.$row2['time_start'].'-'.$row2['time_end'].')'; 						
    							?>
                                    <a class="datesheet_btn float-right" style="position: relative;left: -5px;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_exam_routine/<?php echo $row2['exam_routine_id'].'-'.$department_id.'-'.$class_id.'-'.$section_id.'-'.$row['yearly_terms_id'].'-'.$_SESSION['academic_year_id'];?>');" href="#">
                                        <i class="fas fa-edit"></i>edit
                                    </a>
                                    &nbsp;&nbsp;
                                    <a class="datesheet_btn float-right" style="position: relative;left: -10px;" id="delete<?php echo $row2['exam_routine_id'];?>">
                                        <i class="fas fa-trash" style="color:#ffffff!important;"></i>delete
                                    </a>
                                <?php } ?>
                                </div><br><br>
                            <?php }?>
                        </td>
                        <td id="pdfaction">
                            <a class="datesheet_btn" href="javascript:;" id="add-link" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_exam_routine/<?php echo $section_id.'-'.$row['exam_id'].'-'.$i.'-'.$date1;?>');" class="<?= $current.'s' ?> pull-right">
                                <i class="fas fa-plus"></i><?php echo get_phrase('add');?>
                            </a>
                        </td>
                        </tr>
                        <?php }?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<script>
    $(document).ready(function() {
        $("a[id^='delete']").on('click', function() {	
            Swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    str = $(this).attr('id');
                    exam_routine_id = str.replace('delete', '');
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>exams/exam_routine/delete/" + exam_routine_id,
                        data: ({
                            exam_routine_id: exam_routine_id
                        }),
                        success: function(response) {
                            if($.trim(response) == 'failed'){
                                Swal.fire({
                                  position: 'top-end',
                                  icon: 'danger',
                                  title: 'Failed To Delete',
                                  showConfirmButton: false,
                                  timer: 1500
                                });
                            }else{
                                Swal.fire({
                                  position: 'top-end',
                                  icon: 'success',
                                  title: 'Exam Deleted Successfully',
                                  showConfirmButton: false,
                                  timer: 1500
                                });
                                $("#select").trigger('click');
                            }
                        }
                    });
                }
            });    
        });
    });
</script>
<?php } ?>
