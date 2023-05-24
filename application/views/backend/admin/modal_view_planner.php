<style>
	.tab-content { min-height:20px !important;}
</style>
<table class="table table-bordered table-responsive table-stripped ">
<?php $d1 = date( "d-M-Y", strtotime($param3)); ?>
	<tr>
		<td>
         <?php echo get_phrase('academic_planner_task_view');?>
        <span style="font-size:11px;">(<?php echo $d1;?>)</span></td> 
	</tr>
	<?php
	    $q = "SELECT a.*,s.name as sub_name, s.subject_id as subject_id, s.code as code FROM " . get_school_db() . ".academic_planner a INNER JOIN " . get_school_db() . ".subject s ON a.subject_id = s.subject_id where a.school_id=" . $_SESSION[ 'school_id' ] . " AND a.planner_id=$param2";
	    $query = $this->db->query( $q )->result_array();
	  //  print_r($query);
	    foreach ( $query as $pln ) {
	?>
	<tr>
		<td>
            <div class="row" style="    padding-left: 2px;">
    			<div class="myttl col-sm-12">
    				<?php
    				echo  $pln[ 'sub_name' ] . "- " . $pln[ 'code' ];
    				//$section_id= subject_section_list($pln['subject_id']);
    				$section_array = subject_section_detail( $pln[ 'subject_id' ] );
    				?>
    			</div>
			</div>
            <br>
            <div class="row" style="padding-left: 2px;">
			    <div class="col-sm-12">
				<strong> <?php echo get_phrase('Title');?>: </strong>
                <?php	echo $pln['title'] ; ?>
                <br>
                <strong> <?php echo get_phrase('Attachement');?>: </strong>
                
				<?php
				if ( $pln['attachment'] != "" ) {
					$file_name = $pln['attachment'];
					$folder_name = 'academic_planner';
					$display_link = display_link( $file_name, $folder_name );
					?>
				<a target="_blank" href="<?php echo $display_link;?>"><i class="glyphicon glyphicon-download-alt" style="color:#0a73b7; padding-left:5px;font-size: 20px;" aria-hidden="true"></i>
				<span class="glyphicon glyphicon-download-alt" data-step="3" data-position="top" data-intro="download attachement file"></span>
		    </a>
				<?php echo "<br/>"; 
 } 
	
?>

			</div>
			</div>
            <br>
            <div class="row" style="    padding-left: 2px;">
                <div class="col-sm-12"><strong> <?php echo get_phrase('teacher');?>:</strong></div> 
			    <div class="col-sm-12">
				<?php
				$teacher_list = subject_teacher( $pln[ 'subject_id' ] );
			
				foreach ( $teacher_list as $teacher ) {
					
					?>
					
			<div class="col-sm-6"><i class="fa fa-dot-circle-o" style="padding-right:0px;"></i>	<?php	echo $teacher[ 'teacher_name' ] ; ?>
				</div>
					
					
					<?php
					
				}
				?>


			</div>
			</div>
            <br>
            <div class="row" style="    padding-left: 2px;">
			    <div class="col-sm-12">
				<strong>  <?php echo get_phrase('class');?> /  <?php echo get_phrase('section');?>: </strong>
			</div>
			    <div class="col-sm-12">
				<?php

				foreach ( $section_array as $row ) {
					?>
				<div class="col-sm-6">
			<i class="fa fa-dot-circle-o" style="padding-right:0px; "></i>	
					
						<?php echo $row['c']; ?>
				
				<span>/</span>
						<?php echo $row['s']; ?>
				

			
				</div>
				<?php
				}
				?>
			</div>
            </div>
            <br>
            <div class="row" style="    padding-left: 2px;">
    			<div class="col-sm-12" >
    				<?php	echo "<strong>Required time:</strong> ".$pln['required_time']; ?>  <?php echo get_phrase('minutes');?>
    			</div>
            </div>
        	<ul class="nav nav-tabs" style="margin-top: 5px !important; ">
        	    <li ><a data-toggle="tab" href="#detail" class="active"> <?php echo get_phrase('detail');?></a></li>
                <li><a data-toggle="tab" href="#Objective"> <?php echo get_phrase('objective');?></a></li>
                <li><a data-toggle="tab" href="#assesment"> <?php echo get_phrase('assesment');?></a></li>
                <li><a data-toggle="tab" href="#requirements"> <?php echo get_phrase('requirements');?></a></li>  
            </ul>
            <div class="tab-content" style="max-height: 200px; overflow: auto;">
                <div id="detail" class="tab-pane active">
                	<?php echo $pln['detail']; ?>
                </div>
                <div id="Objective" class="tab-pane">
                    <?php echo $pln['objective']; ?>
                </div>
                <div id="assesment" class="tab-pane">
                    <?php echo $pln['assesment']; ?>
                </div>
                <div id="requirements" class="tab-pane">
                    <?php echo $pln['requirements']; ?>
                </div>
            </div>
		</td>
		<?php } ?>
	</tr>
</table>