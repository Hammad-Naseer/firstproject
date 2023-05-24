<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <!--  <i class="entypo-right-circled carrow">
                        </i>-->
            <!--<img class="img-responsive mynavimg" src="<?php echo base_url();?>assets/images/student-add.png"> -->
            <?php echo get_phrase('student_admission_form');?>       
        </h3>
    </div>
</div>
<?php
if ($success=='success')
{
?>
<div class="row text-center">
	<h4 class="well">
		<i class="fa fa-check" aria-hidden="true"></i>
		<?php echo get_phrase('student_adminssion_form_saved_successfully');?>
	</h4>
</div>
<?php
}
?>
</br>
</br>

<div class="row">
	<div class="col-md-12">
		<ul class="fa-ul">
			<li>
				<i class="fa-li fa fa-check-square"></i>
				<a href="<?php echo base_url();?>c_student/student_add"><?php echo get_phrase('add_new_student');?></a>
			</li>
			<li>
				<i class="fa-li fa fa-check-square"></i>
				<a href="<?php echo base_url();?>c_student/student_pending"><?php echo get_phrase('goto_candidate_list');?></a>
			</li>
			<li>
				<i class="fa-li fa fa-check-square"></i>
				<a href="<?php echo base_url();?>c_student/student_information"><?php echo get_phrase('goto_student_list');?></a>
			</li>
		</ul>
	</div>
</div>