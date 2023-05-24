<style>
    .card{
        background-color:#02658d;
        margin-bottom: 5px;
        cursor:pointer;
    }
    .tab_ui{
        color:white !important;
    }
</style>
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

    <div class="row ">
       <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
           <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name inline capitalize"> 
                <?php echo get_phrase('activity_progress_report'); ?>
            </h3>
        </div>
    </div>
    
    <div>
        <form id="filter" name="filter" method="post" action="<?php echo base_url();?>activitylog/progress_report_filter" class="form-horizontal form-groups-bordered validate " style="background-color:rgba(0, 0, 0, 0) !important;     margin-top: -14px;">
            <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select the filters and press Filter button to get specific records">
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label id="teacher_id_filter">Select Teacher <span class="text-danger">*</span></label>
                    <select id="teacher_id_filter"  class="form-control" name="teacher_id" required="required">
                        <?php echo teacher_option_list($teacher_id);?>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label>Start Date <span class="text-danger">*</span></label>
                    <input type="date" name="start_date" class="form-control start" value="<?php echo isset($start_date) && $start_date != '1970-01-01'  ? $start_date : ''; ?>" required="">
                </div>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <label>End Date <span class="text-danger">*</span></label>
                    <input type="date" name="end_date" class="form-control end" value="<?php echo isset($end_date) && $end_date != '1970-01-01' ? $end_date : ''; ?>" required="">
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 mt-3">
                    <input type="hidden" name="apply_filter" value="1">
                    <input type="submit" value="<?php echo get_phrase('filter'); ?>" class="modal_save_btn" id="btn_submit">
                    <input type="hidden" id="section_name" name="section_name" value="<?php echo isset($section_name) ? $section_name : '';  ?>" />
                    <?php
                    if ($apply_filter == 1)
                    {
                    ?>
                    <a href="<?php echo base_url(); ?>activitylog/filter" class="modal_cancel_btn" id="btn_remove">
                        <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </form>
</div>
<div class="col-lg-12 col-md-12 col-sm-12">
        <?php 
            /*
                "1" => 'diary',
                "2" => 'online_assessment',
                "3" => 'lecture_notes_sharing',
                "4" => 'virtual_class'
            */
        ?>
    
    <?php if ($apply_filter == 1){ ?>    
        <div id="accordion">
          <div class="card">
            <div class="card-header" id="headingOne">
                <h4 class="tab_ui text-white" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Attendance <b>(Total - <?= $attandance->total_attandances; ?>)</b> </h4>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
              <div class="card-body">
                <table class="table table-bordered table_export" style="width: 100% !important;">
                    <?php if(isset($attandance)) {  ?>
                    <thead>
                        <th><?php echo get_phrase('total_present');?></th>
                        <th><?php echo get_phrase('total_absent');?></th>
                        <th><?php echo get_phrase('total_leave');?></th>
                    </thead>
            		<tbody>
            		    <tr>
                		    <td><?= $attandance->total_present; ?></td>
                		    <td><?= $attandance->total_absents; ?></td>
                		    <td><?= $attandance->total_leaves; ?></td>
            		    </tr>
            		</tbody>
            		<?php } ?>
            	</table>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="headingTwo">
              <h4 class="collapsed tab_ui text-white" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Diary <b>(Total - <?= count($diary); ?>)</b> </h4>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
              <div class="card-body">
                <table class="table table-bordered table_export" style="width: 100% !important;">
                    <?php if(isset($diary)) {  ?>
                    <thead>
                        <th><?php echo get_phrase('#');?></th>
                        <th><?php echo get_phrase('date');?></th>
                        <th><?php echo get_phrase('title');?></th>
                        <th><?php echo get_phrase('assigned_to');?></th>
                    </thead>
            		<tbody>
            		    <?php $i = 1; foreach($diary as $data): ?>
                		    <tr>
                    		    <td><?= $i++; ?></td>
                    		    <td><?= date_view($data['assign_date']); ?></td>
                    		    <td><?= $data['diary_title'] ?></td>
                    		    <td><?= $data['department'] ?> / <?= $data['class'] ?> / <?= $data['section'] ?></td>
                		    </tr>
            		    <?php endforeach; ?>
            		</tbody>
            		<?php } ?>
            	</table>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="headingThree">
              <h4 class="tab_ui collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Online Assessment <b>(Total - <?= count($assessment); ?>)</b> </h4>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
              <div class="card-body">
                <table class="table table-bordered table_export" style="width: 100% !important;">
            		<?php if(isset($assessment)) {  ?>
            		<thead>
            		    <tr align="center">
                            <td colspan="4"><h4><b style="color:white !Important">Assessment</b></h4></td>
                        </tr>
                        <tr>
                            <td><?php echo get_phrase('#');?></td>
                            <td><?php echo get_phrase('date');?></td>
                            <td><?php echo get_phrase('title');?></td>
                            <td><?php echo get_phrase('is_assigned');?></td>    
                        </tr>
                        
                    </thead>
            		<tbody>
            		    <?php $i = 1; foreach($assessment as $data): ?>
                		    <tr>
                    		    <td><?= $i++; ?></td>
                    		    <td><?= date_view($data['inserted_at']); ?></td>
                    		    <td>
                        		   <?= $data['assessment_title'] ?>
                    		    </td>
                    		     <td>
                    		        <?php if($data['is_assigned'] == 1){ ?>
                    		            <?php echo get_assessment_time($data['assessment_id']) ?>
                    		        <?php }else{ ?>
                        		        Not Assigned
                    		        <?php } ?>
                    		    </td>
                		    </tr>
            		    <?php endforeach; ?>
            		</tbody>
            		<?php } ?>
            	</table>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="headingThree0">
              <h4 class="tab_ui collapsed text-white" data-toggle="collapse" data-target="#collapseThree0" aria-expanded="false" aria-controls="collapseThree">Lectures Notes <b>(Total - <?= count($notes); ?>)</b> </h4>
            </div>
            <div id="collapseThree0" class="collapse" aria-labelledby="headingThree0" data-parent="#accordion">
              <div class="card-body">
                <table class="table table-bordered table_export" style="width: 100% !important;">
            		<?php if(isset($notes)) {  ?>
            		<thead>
            		    <tr align="center">
                            <td colspan="4"><h4><b style="color:white !Important">Notes</b></h4></td>
                        </tr>
                        <tr>
                            <td><?php echo get_phrase('#');?></td>
                            <td><?php echo get_phrase('date');?></td>
                            <td><?php echo get_phrase('title');?></td>
                            <td><?php echo get_phrase('is_assigned');?></td>
                        </tr>
                        
                    </thead>
            		<tbody>
            		    <?php $i = 1; foreach($notes as $data): ?>
                		    <tr>
                    		    <td><?= $i++; ?></td>
                    		    <td><?= date_view($data['inserted_at']); ?></td>
                    		    <td>
                    		        <?= $data['notes_title'] ?>
                    		    </td>
                    		    <td>
                    		        <?php if($data['is_assigned'] == 1){ ?>
                    		            <?php echo get_notes_assigned_section($data['notes_id']) ?>
                    		        <?php }else{ ?>
                        		        Not Assigned
                    		        <?php } ?>
                    		    </td>
                		    </tr>
            		    <?php endforeach; ?>
            		</tbody>
            		<?php } ?>
            	</table>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header" id="headingThree1">
              <h4 class="tab_ui collapsed text-white" data-toggle="collapse" data-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree">Virtual Class <b>(Total - <?= count($vc); ?>)</b></h4>
            </div>
            <div id="collapseThree1" class="collapse" aria-labelledby="headingThree1" data-parent="#accordion">
              <div class="card-body">
                <table class="table table-bordered table_export" style="width: 100% !important;">
            		<?php if(isset($vc)) {  ?>
            		<thead>
            		    <tr align="center">
                            <td colspan="4"><h4><b style="color:white !Important">Virtual Class</b></h4></td>
                        </tr>
                        <tr>
                            <td><?php echo get_phrase('#');?></td>
                            <td><?php echo get_phrase('date');?></td>
                            <td><?php echo get_phrase('class-section');?></td>
                            <td><?php echo get_phrase('virtual_class_name');?></td>
                        </tr>
                    </thead>
            		<tbody>
            		    <?php $i = 1; foreach($vc as $data): ?>
                		    <tr>
                    		    <td><?= $i++; ?></td>
                    		    <td><?= date_view($data['inserted_at']); ?></td>
                    		    <td><?= $data['department'] ?> / <?= $data['class'] ?> / <?= $data['section'] ?></td>
                    		    <td><?= $data['virtual_class_name'] ?></td>
                		    </tr>
            		    <?php endforeach; ?>
            		</tbody>
            		<?php } ?>
                </table>
              </div>
            </div>
          </div>
        </div>
    <?php } ?>
</div>
