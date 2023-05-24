<?php
    if($this->session->flashdata('club_updated'))
    {
        echo "<script>sweet_message('".$this->session->flashdata('club_updated')."','success')</script>";
    }
?>

<style>
    self_class{
        float: right;
        background-color: #3498c6;
        padding: 10px 5px 0px 5px;
        border-radius: 5px;
    }
    .text-color{
        color:white;
    } 
    .mcq_info {
    margin-bottom: 5px;
}
</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('assessments');?>
        </h3>
    </div> 
</div>

<?php if(count($assessment) > 0) { ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <div class="alert alert-info" role="alert">
                <p style="font-size:0.8em">Dear Student ! Please Note if you click on solve assessment button your attempt will be counted and your time will start.</p>
            </div>
        </div> 
    </div>
<?php } ?>
    
<!--<div class="row">-->
    <div class="col-md-12" data-step="1" data-position='top' data-intro="Assessments record">
        <div class="tab-content">            
            <div class="tab-pane box active" id="list">
                <div class="table-responsive">
    			    <table class="table table-striped table-bordered table_export table-responsive" id="assessment_table">
                        <thead>
                            <tr>
                                <th style="width:50px;"><div><?php echo get_phrase('s_no');?></div></th>
                                <th class="assessment_th_mobile"><div><?php echo get_phrase('Assessment Details');?></div></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            if(count($assessment) > 0)
                            {
                                foreach($assessment as $row):
                                $j++;
                                
                                ?>
                                <tr>
                                    <td class="td_middle"><?= $j; ?></td>
                                    <td style="padding: 0px 0px;">
                                        <div class="member-entry" style="margin-top:0px;margin-bottom:0px;"> 
                                        <a> 
                                           <i class="fas fa-file-alt" style="font-size:90px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                                        </a> 
                                        <div class="member-details"> 
                                            <h4> 
                                                <a href="#"><?php echo $row['name'];?> </a> 
                                            </h4> 
                                            <div class="row info-list p-0" style="color: black;"> 
                                                <div class="col-sm-4 col-xs-4"> 
                                                    <strong>Title:</strong>
                                                    <?php echo ucfirst($row['assessment_title']);?>
                                                    <br>
                                                    <strong>Teacher Name:</strong>
                                                    <?php echo $row['teacher_name'];?>
                                                    <br>
                                                    <strong>Subject:</strong>
                                                    <?php echo get_subject_name($row['subject_id']); ?>
                                                    <br>
                                                    <strong>Yearly Terms:</strong>
                                                    <?php
                                                        $yearly_terms =  get_yearly_terms($_SESSION['academic_year_id'] , $row['yearly_term_id'] );
                                                        echo $yearly_terms[0]['title'];
                                                    ?>
                                                    <br>
                                                    <strong>Total Marks:</strong>
                                                    <?php echo $row['total_marks']; ?>
                                                    <br>
                                                    <strong>Allowed Attempts:</strong>
                                                    <?php echo $row['total_attempts']; ?>
                                                </div> 
                                                <div class="col-sm-4 col-xs-4"> 
                                                    <strong>Date:</strong>
                                                    <?php echo date_view($row['assessment_date']); ?>
                                                    <br>
                                                    <strong>Start Time:</strong>
                                                    <?php echo $row['start_time'];?>
                                                    <br>
                                                    <strong>End Time:</strong>
                                                    <?php echo $row['end_time'];?>
                                                </div> 
                                                <div class="col-sm-4 col-xs-4 col-md-4 col-lg-3"> 
                                                    <?php 
                                                        if($row['mcq_questions'] > 0 ){
                                                            echo "<div class='mcq_info'><strong class='min-space'>MCQ's : </strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['mcq_questions']."</span></div>";
                                                        }
                                                        if($row['true_false_questions'] > 0 ){
                                                            echo "<div class='mcq_info'><strong class='min-space'>True/False : </strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['true_false_questions']."</span></div>";
                                                        }
                                                        if($row['fill_in_the_blanks_questions'] > 0 ){ 
                                                            echo "<div class='mcq_info'><strong class='min-space'>Blanks : </strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['fill_in_the_blanks_questions']."</span></div>";
                                                        }
                                                        if($row['short_questions'] > 0 ){   
                                                            echo "<div class='mcq_info'><strong class='min-space'>Short Q's : </strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['short_questions']."</span></div>";
                                                        }
                                                        if($row['long_questions'] > 0 ){   
                                                            echo "<div class='mcq_info'><strong class='min-space'>Long Q's </strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['long_questions']."</span></div>";
                                                        }
                                                        if($row['pictorial_questions'] > 0 ){   
                                                            echo "<div class='mcq_info'><strong class='min-space'>Pictorial Q's</strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['pictorial_questions']."</span></div>";
                                                        }
                                                        if($row['match_questions'] > 0 ){   
                                                            echo "<div class='mcq_info'><strong class='min-space'>Column Matching Q's</strong><span class='badge badge-info badge-roundless pull-right no_q'>".$row['match_questions']."</span></div>";
                                                        }
                                                        if($row['drawing_questions'] > 0 ){   
                                                            echo "<div class='mcq_info'><strong class='min-space'>Drawing Q's</strong><span class='badge badge-success badge-roundless pull-right no_q'>".$row['drawing_questions']."</span></div>";
                                                        }
                                                    ?>
                                                </div> 
                                                <div class="clear"></div> 
                                                <div class="col-sm-12 col-xs-12 ststs-asesmnt"> 
                                                            <b>Assessment Status :</b> 
                                                    <ul class="memeber_entry_buttons"> 
                                                        <?php
                                                        if(date('Y-m-d',strtotime($row['assessment_date'])) == date('Y-m-d'))
                                                        {
                                                            $start_t = date("H:i", (strtotime($row['start_time'])-(60)));
                                                            $end_t = date("H:i", (strtotime($row['end_time'])-(60)));
                                                            $current_t = date("H:i", strtotime("now"));
                                                            
                                                            if($current_t >= $start_t And $current_t <= $end_t)
                                                            {
                                                                if($row['number_of_attempts'] < $row['total_attempts'])
                                                                {
                                                         ?>     <li>
                                                                    <a class="modal_save_btn" id="assessment_id_<?php echo $row['assessment_id'];?>" href="javascript:void(0)" onclick="check_validation(this)">
                                                                        Solve Assessment
                                                                    </a>
                                                                </li>    
                                                         <?php
                                                                }else{
                        	                                        echo "<li><b class='badge badge-danger'>You Have No More Attempts</b></li>";
                        	                                    }
                                                            }
                                                            elseif($current_t > $end_t){
                                                                echo "<td><span>Time Over</span></td>";
                                                            }elseif($current_t < $start_t){
                                                                echo "<li><span class='badge badge-info'>Time Not Started</span></li>";
                                                            }
                                                            
                                                        }elseif(date('Y-m-d',strtotime($row['assessment_date'])) < date('Y-m-d'))
                                                        {
                                                            echo "<li><span class='badge badge-info'>Date Over</span></li>";
                                                        }else{
                                                            echo "<li><span class='badge badge-info'>Date Not Started</span></li>";
                                                        }
                                                    ?>  
                                                    </ul> 
                                                </div>
                                            </div> 
                                        </div> 
                                    </div>
                                    </td>
                                </tr>
                                <?php endforeach;
                            }
                            else
                            {
                            ?>
                                <tr>
                                    <td colspan="2" style="text-align: center;"> 
                                        <div class="text-center">
                                            <i class="fas fa-poll-h" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                                            <h2><b>No Assessment</b></h2>
                                            <a href="<?php echo base_url();?>assessment_student/view_assessment" style="color:black;"> <b><?php echo get_phrase('go_to_assessment_page');?> <i class="fas fa-long-arrow-alt-right"></i></b></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                     </tbody>
                    </table>               
                </div>
			</div>
    	</div>
    </div>
<!--</div>-->

<div class="modal fade eduModal" id="modal-4" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:100px;">
                <div class="modal-header">
                    <h4 class="modal-title" style="text-align:center;">Are you sure to Continue ?</h4>
                    <button type="button" class="close" data-dismiss="modal"aria-hidden="true">&times;</button>
                </div>
                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <a href="#" class="btn btn-danger" data-id="1" id="delete_link">Yes</a>
                    &nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-danger" data-dismiss="modal" style="background-color:#F44336!important;border:#f34336 1px solid!important">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<script>
    function check_validation(a)
    {
        var id = a.id;
        var res = id.split("_");
        var assessment_id = res[2];
        $('#modal-4').modal('show',{backdrop: 'static', keyboard: false}); //Added By tm
    
        document.getElementById('delete_link').onclick = function(){
           var url = "<?php echo base_url().'assessment_student/assessment_details/';?>"+assessment_id;
            window.location.replace(url);
        }
        
        
    }
</script>
