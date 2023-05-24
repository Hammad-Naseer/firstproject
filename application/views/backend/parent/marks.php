<?php 
$student_id=$_SESSION['student_id'];
$academic_id=intval($_SESSION['academic_year_id']);
$term_id=intval($_SESSION['yearly_term_id']);

?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="noticeboard_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('examination_results'); ?>
        </h3>
    </div>
</div>


  
    <form name="marksheet" id="marksheet" class="form-horizontal validate" data-step="1" data-position='top' data-intro="get examination result">
          
    <div class="row filterContainer">
    
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group">
                <select id="exam_id" name="exam_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('required');?>">
                    <?php echo year_exam_option_list();?>
                </select>
            </div>
        </div>
        
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group" style="padding-left:5px">
                <button type="submit" id="submit_exam" class="modal_save_btn" >filter </button>
                <a style="display:none;" href="<?php echo base_url();?>parents/marks" class="modal_cancel_btn" >
                    <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                </a>
            </div>
        </div>
        
    </div>
        
    </form>

<hr/>
<div id="exam_result" class="text-center">
</div>
<script type="text/javascript">
$(document).ready(function() {
    document.getElementById('marksheet').onsubmit = function() {
        return false;
    };
    $('#yearly_term').on('change', function() {
        var yearly_term = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>parents/get_exam_type",
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
    $('#submit_exam').on('click', function() {

        var exam_id = $('#exam_id').val();
        if (exam_id == '') {
            $('#exam_result').text('');
        }
        if (exam_id != '') 
        {
            $("#exam_result").html("<div class='loader'></div>");
            $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>parents/get_exam_result",

                data: ({
                    exam_id: exam_id,
                    /*term_id: term_id,*/
                    student_id: '<?php echo $student_id;?>'
                }),
                dataType: "html",
                success: function(html) 
                {
                    $('#exam_result').html('');
                    if (html != '') {
                        $('#exam_result').html(html);

                        $('.modal_cancel_btn').css({'display':'inline'});
                    }
                }
            });
        }
    });

});
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


<?php
/*
<div class="row">
	<div class="col-md-12">
    
    	<!--CONTROL TABS START-->
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('Examination_Result');?>
                    	</a></li>
		</ul>
    	<!--CONTROL TABS END-->
        
	
            <!--TABLE LISTING STARTS-->
            <div class="tab-pane  <?php if(!isset($edit_data) && !isset($personal_profile) && !isset($academic_result) )echo 'active';?>" id="list">
				<center>
                <?php echo form_open(base_url().'parents/marks'); ?>
                <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
                	
                	<tr>
                        <td>
                        	<select name="exam_id" class="form-control"  style="float:left;">
                                <option value=""><?php echo get_phrase('select_an_exam');?></option>
                                <?php 
                                $this->db->where('school_id',$_SESSION['school_id']);
                                $exams = $this->db->get(get_school_db().'.exam')->result_array();
                                foreach($exams as $row):
                                ?>
                                    <option value="<?php echo $row['exam_id'];?>"
                                        <?php if($exam_id == $row['exam_id'])echo 'selected';?>>
                                          <?php echo $row['name'];?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </td>
                       
                        <td>
                        
                        	<input type="hidden" name="operation" value="selection" />
                    		<button type="submit" value="<?php echo get_phrase('manage_marks');?>" class="btn btn-primary" />View Marks </button>
    </td>
   </tr>
                </table>
                </form>
                </center>
                <br /><br />
                <?php
           
                 
                 if($exam_id >0 && $_SESSION['class_id'] >0):?>
                <?php 
						////CREATE THE MARK ENTRY ONLY IF NOT EXISTS////
						
//$students=$this->crud_model->get_students($_SESSION['class_id']);


?>
                <table class="table table-bordered" >
                    <thead>
                        <tr>
                            <td><?php echo get_phrase('subject');?></td>
                            <td><?php echo get_phrase('mark_obtained');?></td>
                            <td><?php echo get_phrase('mark_total');?></td>
                            <td><?php echo get_phrase('comment');?></td>
                            
                        </tr>
                    </thead>
                    <tbody>
                    	
                        <?php
                         

//$query = $this->db->get_where('mark' , $verify_data);
$school_id=$_SESSION['school_id'];
$q1="SELECT m.mark_obtained,m.attendance,m.comment,m.mark_total,m.school_id,s.name 
From ".get_school_db().".mark m 
inner join ".get_school_db().".subject s on s.subject_id=m.subject_id
WHERE m.class_id=".$_SESSION['class_id']." AND m.student_id=".$_SESSION['student_id']." AND m.exam_id=$exam_id AND m.school_id=$school_id";
 							 
$marks=$this->db->query($q1)->result_array();
foreach($marks as $row2):

?>
<tr>
<td>
<?php echo $row2['name'];?>
</td>
<td style="text-align:center;">
<?php echo $row2['mark_obtained'];?>
</td>
<td style="text-align:center;">
<?php echo $row2['mark_total'];?>
</td>
<!--<td style="text-align:center;">-->
<?php //echo $row2['attendance'];?>
<!--</td>-->
<td style="width:200px;">
									<?php echo $row2['comment'];?>
								</td>
							 </tr>
                         	<?php 
							endforeach;
						 
						 ?>
                     </tbody>
                  </table>
            
            <?php endif;?>
			</div>
            <!----TABLE LISTING ENDS--->
            
		</div>
	</div>
</div>

<script type="text/javascript">
  function show_subjects(class_id)
  {
      for(i=0;i<=100;i++)
      {

          try
          {
              document.getElementById('subject_id_'+i).style.display = 'none' ;
	  		  document.getElementById('subject_id_'+i).setAttribute("name" , "temp");
          }
          catch(err){}
      }
      document.getElementById('subject_id_'+class_id).style.display = 'block' ;
	  document.getElementById('subject_id_'+class_id).setAttribute("name" , "subject_id");
  }

</script> 
*/?>