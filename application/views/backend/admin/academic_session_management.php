<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
     <script>
    function validatesession(){
        var endDate=$('#end-date').val();
        var a=endDate.split('/');
        var startDate=$('#start-date').val();
        var b=startDate.split('/');
        var d1=new Date(b[2],b[0]-1,b[1]);
        var d2=new Date(a[2],a[0]-1,a[1]);
        if(d2<=d1){
            if($('#session p').empty()){
            $('#session').append('<p style="color:#80003B">Invalid Date!</p>');
        }
            return false;
        }
    }
    </script>
    	<thead>
        	<tr>
            	<th><?php echo get_phrase('session_start_date');?></th>
                <th><?php echo get_phrase('session_end_date');?></th>
                <th><?php echo get_phrase('Class(es');?></th>
            	<th><?php echo get_phrase('submit');?></th>
           </tr>
       </thead>
       		<tbody>
                <form method="post" id="holiday" action="<?php echo base_url();?>admin/add_session" onsubmit="return validatesession();" class="form">
                    <tr id="session" class="gradeA">
                    <td>
                        <input class="form-control datepicker readonly" id="start-date" type="text" name="start-date" required/>
                    </td>
                    <td>
                        <input class="form-control datepicker readonly" id="end-date" type="text" name="end-date" required/>
                    </td>
                    <td>
                    	<select name="class_id" class="form-control">
                        	<option value="0"><?php echo get_phrase('all_classes'); ?></option>
                        	<?php 
							$classes	=	$this->db->get('class')->result_array();
							foreach($classes as $row):?>
                        	<option value="<?php echo $row['class_id'];?>"
                            	<?php if(isset($class_id) && $class_id==$row['class_id'])echo 'selected="selected"';?>>
									<?php echo $row['name'];?>
                              			</option>
                            <?php endforeach;?>
                        </select>

                    </td>
                    
                    <td align="center">
                    <input type="submit" value="<?php echo get_phrase('add_session');?>" class="btn btn-info"/></td>
                </tr>
            </form>
		</tbody>
        </table>


<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
    	<thead>
        	<tr>
            	<th><?php echo get_phrase('select_holiday_date');?></th>
                <th><?php echo get_phrase('enter_holiday_name');?></th>
            	<th><?php echo get_phrase('submit');?></th>
           </tr>
       </thead>
       		<tbody>
                <form method="post" id="holiday" action="<?php echo base_url();?>admin/add_holidays" class="form">
            	<tr class="gradeA">
                    <td>
                        <input class="form-control datepicker readonly" type="text" name="holiday-date" required/>
                    </td>
                    <td>
                        <input type="text" name="holiday-name" required/>
                    </td>
                    <td align="center"><input type="submit" value="<?php echo get_phrase('add_holiday');?>" class="btn btn-info"/></td>
                </tr>
            </form>
		</tbody>
        </table>


<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
     <script>
    function validateholiday(){
        var endDate=$('#end-date').val();
        var a=endDate.split('/');
        var startDate=$('#start-date').val();
        var b=startDate.split('/');
        var d1=new Date(b[2],b[0]-1,b[1]);
        var d2=new Date(a[2],a[0]-1,a[1]);
        if(d2<=d1){
            if($('#vacation p').empty()){
            $('#vacation').append('<p style="color:#80003B">Invalid Date!</p>');
        }
            return false;
        }
    }
    </script>
    	<thead>
        	<tr>
            	<th><?php echo get_phrase('vacation_start_date');?></th>
                <th><?php echo get_phrase('vacation_end_date');?></th>
                <th><?php echo get_phrase('vacation_name');?></th>
            	<th><?php echo get_phrase('submit');?></th>
           </tr>
       </thead>
       		<tbody>
 <form method="post" id="holiday" action="<?php echo base_url();?>admin/add_vacation" onsubmit="return validateholiday();" class="form">
                    <tr id="vacation" class="gradeA">
                    <td>
                        <input class="form-control datepicker readonly" id="start-date" type="text" name="start-date" required/>
                    </td>
                    <td>
                        <input class="form-control datepicker readonly" id="end-date" type="text" name="end-date" required/>
                    </td>
                    <td>
                        <input type="text" name="vacation-name" required/>
                    </td>
                    <td align="center"><input type="submit" value="<?php echo get_phrase('add_vacation');?>" class="btn btn-info"/></td>
                </tr>
            </form>
		</tbody>
        </table>

