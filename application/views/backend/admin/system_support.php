<style>
    .validate-has-error {
        color: red;
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
    $(window).load(function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script> 
    <?php echo form_open(base_url().'support/supports/create',array('id'=>'system_supports','class' => 'pd0 form-horizontal form-groups-bordered validate','target'=>'_top')); ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name">
                <?php echo get_phrase('system_support');?>
            </h3>
        </div>
    </div>
    <div>
    <div class="col-lg-12 col-sm-12">    
        <h2><?php echo get_phrase('we_are_here_to_help_you');?></h2>
        <p><?php echo get_phrase('below_are_the_priorities_levels defined_for_GSIMS_support');?>:</p>
    	 <table class="table-bordered table" data-step="1" data-position="top" data-intro="Below Are The Priorities Levels Defined For GSIMS Support">
            	<thead>
                	<tr>
                		<th> <?php echo get_phrase('priority');?></th>
                        <th> <?php echo get_phrase('color_code');?></th>
                        <th> <?php echo get_phrase('risk_factor');?></th>
                      
                 <th> <?php echo get_phrase('expected_response_time');?></th>
                    </tr>	
                </thead>
                <tbody>
                	<tr>
                    	<td> <?php echo get_phrase('priority');?>1 </td>
                        <td style="color:red; font-weight:bold;">
                        <?php echo get_phrase('red');?>
                        </td>
                        <td> <?php echo get_phrase('V_high');?></td>
                        <td> <?php echo get_phrase('Hot'); ?>/ 
						<?php echo get_phrase('equick_fix_from_5_working_hours_to_less_than_a_working_day');?></td>
                    </tr>
                    <tr>
                    	<td> <?php echo get_phrase('priority ');?>2</td>
                        <td style="color:orange; font-weight:bold;"><?php echo get_phrase('orange');?></td>
                        <td> <?php echo get_phrase('high');?></td>
                        <td> <?php echo get_phrase('1_working_day');?>  </td>
                    </tr>
                    <tr>
                    	<td> <?php echo get_phrase('priority');?> 3</td>
                        <td style="color:#ffca28; font-weight:bold;"><?php echo get_phrase('yellow');?></td>
                        <td> <?php echo get_phrase('medium');?></td>
                        <td> <?php echo get_phrase('2_working_days');?>  </td>
                    </tr>
                    <tr>
                    	<td> <?php echo get_phrase('priority');?> 4</td>
                        <td style="color:green; font-weight:bold;"><?php echo get_phrase('green');?></td>
                        <td> <?php echo get_phrase('low');?></td>
           <td> <?php echo get_phrase('4_to_5_working_days');?>  </td>
                    </tr>
                </tbody>
            </table>
    </div>
    </div>
    
    <?php $school_id=$_SESSION['school_id'];?>
    <div class="col-md-12" data-step="2" data-position="top" data-intro="fill this form and describe your problem and press save button">
        <div >
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="row p-0">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">
                            <?php echo get_phrase('problem_title');?><span class="star">*</span></label>
                            <input maxlength="500" type="text" class="form-control" name="problem_title" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label for="field-2" class="control-label">
                                <?php echo get_phrase('problem_type');?><span class="star">*</span>
                            </label>
                             <label id="problem_priority_selection"></label>
                            <select name="problem_priority" id="problem_priority" class="form-control selectpicker">
    						    <?php echo priority_selector();?>
    						</select>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label class="control-label">
                                <?php echo get_phrase('url');?>
                            </label>
                            <input maxlength="1000" type="text" class="form-control" name="url" value="">
                        </div>
                    </div>
                    <div class="row p-0">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label">
                                <?php echo get_phrase('description');?><span class="star">*</span>
                            </label>
                            <textarea maxlength="3000" id="description" oninput="count_value('description','description_count','3000')" class="form-control" name="description" rows="5" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"></textarea>
                            <div id="description_count" class="col-sm-12 "></div>
                        </div>
                    </div>
                </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <!--<div class="col-xs-offset-3 col-sm-5">-->
                            <button type="submit" class="btn btn-info submit_btn" style="margin-bottom:25px !important;">
                                <?php echo get_phrase('save');?>
                            </button>
                            <br>
                        <!--</div>-->
                    </div>
                </div>
            </div>
    </div>
    
<script> 
$(document).ready(function(){
//$('.selectpicker').selectpicker();
	$('.selectpicker').on('change', function (){
		var id=$(this).attr('id');
		var selected = $('#'+ id +' :selected');
		var group = selected.parent().attr('label');
		$('#'+ id + '_selection').text(group);
    }); 
});      
</script>     