<?php //session_start(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title">
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_form');?>
            	</div>
            </div>
			<div class="panel-body">
				
<?php echo form_open(base_url().'admin/parent/create/'.$param2.'/'.$param3 , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
	
<div class="form-group">
<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('parent_of');?></label>
<div class="col-sm-8">
<input type="text" class="form-control" readonly
                            	value="<?php echo $this->db->get_where('student', array('student_id'=>$param2))->row()->name;?>">
						</div>
					</div>
                   
                   
             <!-- CNIC   -->      
                   	<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('id_no');?></label>
                        
						<div class="col-sm-8">
	<input id="cnic" onchange="get_cnic()" type="text" class="form-control" name="cnic" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
                            	value="">
						</div>
					</div>
                   
       <!-- end CNIC   -->                
                    
					<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('name');?></label>
                        
						<div class="col-sm-8">
							<input id="name" type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"  autofocus
                            	value="">
						</div>
					</div>
                    
					<div class="form-group">
						<label for="field-1" class="col-sm-4 control-label"><?php echo get_phrase('email');?></label>
						<div class="col-sm-8">
			<span style="color: red;" id="message"></span>			
<input  onkeypress="get_email()" type="text" class="form-control" name="email" id="email" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('password');?></label>
                        
						<div class="col-sm-8">
							<input type="password" class="form-control" name="password" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('relation_with_student');?></label>
                        
						<div class="col-sm-8">
<input id="relation_with_student" type="text" class="form-control" name="relation_with_student" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('phone');?></label>
                        
						<div class="col-sm-8">
<input id="phone" type="text" class="form-control" name="phone" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('address');?></label>
                        
						<div class="col-sm-8">
<input id="address" type="text" class="form-control" name="address" value="">
<input id="parent_id" type="hidden" class="form-control" name="parent_id" value="">
</div>
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('profession');?></label>
                        
						<div class="col-sm-8">
<input id="profession" type="text" class="form-control" name="profession" value="">
						</div>
					</div>
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button id="btn1" type="submit" class="btn btn-default"><?php echo get_phrase('add_parent');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>