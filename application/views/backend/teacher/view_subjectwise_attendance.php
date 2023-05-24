
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('view_subjectwise_attendance');?>
        </h3> 
    </div>
</div>
<div>
    <form action="<?php echo base_url().'teacher/view_subjectwise_attendance'?>" method="post" >    
        <div class="row filterContainer">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <label id="section_id_filter_selection"></label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6" data-step="1" data-position="top" data-intro="select class - section">
                <select id="section_id_filter" class="selectpicker form-control" name="section_id" required>
                    <?php echo section_selector($section_id);?>
                </select>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6" data-step="2" data-position="top" data-intro="press this button to view student attendance record">
                <input type="hidden" name="apply_filter" value="1" />
                <button type="submit" class="modal_save_btn"><?php echo get_phrase('filter');?></button>
                <?php if($apply_filter){?>
                <a href="<?php echo base_url().'teacher/view_subjectwise_attendance'?>"  class="modal_cancel_btn">Remove Filter</a>
                <?php }?>
                
            </div>
        </div>
    </form>
</div>
    <!--<div id="get_attendance" style="width:100%"></div>-->
<div class="col-lg-12 col-md-12 col-sm-12">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered table_export">
    <thead>
        <tr>
            <th style="width:34px;"><?php echo get_phrase('picture');?></th>
            <th><?php echo get_phrase('roll_no');?></th>
            <th><?php echo get_phrase('student_name');?></th>
            <th style="width:150px;"><?php echo get_phrase('view_attendance');?></th>
        </tr>
    </thead>
    <?php
    foreach($students as $row)
    {
    ?>
        <tr>
            <td class="td_middle">
            		<img src="<?php
            		if($row['image']==''){
            		 echo  base_url().'/uploads/default.png'; 
            		}else{
            		echo  display_link($row['image'],'student');
            		}
            		 ?>" class="img-circle" width="30" />
            </td>	
            <td><?php echo $row['roll'];?></td>	
            <td><?php echo $row['name'];?></td>
            <td class="td_middle">
            <a href="<?php echo base_url()."teacher/student_subjectwise_attendance/".str_encode($row['student_id'])?>"  class="btn btn-primary"><?php echo get_phrase('view_subject_wise_attendance');?></a>
            
            </td>
        </tr>
    <?php
    }
    ?>
    </table>
</div>

