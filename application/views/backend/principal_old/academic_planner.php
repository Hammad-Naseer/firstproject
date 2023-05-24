   <script>
        function edit(){
                var title=$('#title').val();
                var id=$('#id').val();
                $.ajax({
                        url: '<?php echo base_url();?>principal/edit_event',
                        data: 'title='+title+'&id='+ id ,
                        type: "POST",
                        success: function(json) {
                                  $('button.btn-default').trigger('click');
                                  location.reload(true);
                        }   
      });
                return false;
                }
        
        </script>
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
    	<thead>
        	<tr>
            	<th><?php echo get_phrase('select_teacher');?></th>
            	<th><?php echo get_phrase('submit');?></th>
           </tr>
       </thead>
       		<tbody>
                <form method="post" id="holiday" action="<?php echo base_url();?>principal/academic_planner" class="form">
            	<tr class="gradeA">
                    <td>
                        <select name="teacher" class="form-control" required>
                        	<option value="">Select a teacher</option>
                        	<?php 
							$teachers	=	$this->db->get('teacher')->result_array();
							foreach($teachers as $row):?>
                        	<option value="<?php echo $row['teacher_id'];?>"
                            	<?php if(isset($teacher_id) && $teacher_id==$row['teacher_id'])echo 'selected="selected"';?>>
									<?php echo $row['name'];?>
                              			</option>
                            <?php endforeach;?>
                        </select>

                    </td>
                    <td align="center"><input type="submit" value="<?php echo get_phrase('select_teacher');?>" class="btn btn-info"/></td>
                </tr>
            </form>
		</tbody>
        </table>

<?php if(isset($classes)&&isset($subjects)){
    ?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
    	<thead>
        	<tr>
            	<th><?php echo get_phrase('class_name');?></th>
            	<th><?php echo get_phrase('subject');?></th>
                <th><?php echo get_phrase('planner');?></th>
           </tr>
       </thead>
       		<tbody>
                    <?php         foreach ($subjects as $subject):               
                    foreach ($classes as $class):

?>

            	<tr class="gradeA">
                    <td><?php echo $class['name'];?></td>
                    <td><?php echo $subject['name'];?></td>
                    <td><form><input type="hidden" required name="subject" value="<?php echo $subject['subject_id'];?>">
                            <input type="hidden" required name="class" value="<?php echo $class['class_id'];?>">
                            <input type="hidden" required name="tid" value="<?php echo $subject['teacher_id'];?>">
                            <button class="btn" formmethod="post" formaction="<?php echo base_url();?>principal/academic_planner/<?php echo $class['name'].'/'.$subject['name'];?>">View Academic Planner</button></form></td>
                </tr>
                <?php  
                        endforeach;
                        endforeach;
                        ?>
		</tbody>
        </table>
<?php 
}
 elseif (isset($calendarTitle)){
     ?>
<?php

/* Values received via ajax */
$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];

    $this->db->where('id', $id);
    $this->db->update('evenement',array('title'=>$title,'start'=>$start,'end'=>$end));

?>
                <center>
    <div class="row">
        <div class="col-sm-offset-4 col-sm-4">
        
            <div class="tile-stats tile-white-gray">
                <div class="icon"><i class="entypo-calendar" style="opacity: 0.5"></i></div>
                <h2><?php echo $calendarTitle;?></h2>
               
            </div>
        </div>
    </div>
</center>
<script>


 $(document).ready(function() {
     var teacher='<?php echo $teacher_id;?>';
     var c='<?php echo $class;?>';
     var subject='<?php echo $subject;?>';
     var getid=0;
  var date = new Date();
  var d = date.getDate();
  var m = date.getMonth();
  var y = date.getFullYear();

  var calendar = $('#calendar').fullCalendar({
   header: {
    left: 'prev,next today',
    center: 'title',
    right: 'month'
   },
   

            events:[
						<?php 
						$this->db->query("SET SESSION time_zone='+5:00'");
						$sql = "SELECT id,title,unix_timestamp(start) as start,unix_timestamp(end) as end,teacher,allDay FROM evenement WHERE (class=? AND teacher=? AND subject=?) OR teacher=?"; 
                                                $resultat=  $this->db->query($sql,array($class,$teacher_id,$subject,-1))->result_array();
						foreach($resultat as $row):
						?>
                   {
                                                        id: <?php echo $row['id'];?>,
							title: "<?php echo $row['title'];?>",
							start: new Date(<?php echo $row['start']*1000;?>),
							end:	new Date(<?php echo $row['end']*1000;?>) 
                                                <?php if($row['teacher']>=0){
                                                    ?>
                                                                        , editable:true
                                                <?php }?>
						},
						<?php 
						endforeach
						?>
						
					],
lazyFetching:true,
// Convert the allDay from string to boolean
   eventRender: function(event, element, view) {
    if (event.allDay === 'true') {
     event.allDay = true;
    } else {
     event.allDay = false;
    }
   },
   selectable: true,
   selectHelper: true,
   selectOverlap: function(event) {
        return event.editable;
    },
        eventOverlap: function(stillEvent, movingEvent) {
        return stillEvent.editable;
    },
   select: function(start, end, allDay) {
   var title = prompt('Event Title:');
   if (title) {
   var start = moment(new Date(start)).format("YYYY-MM-DD HH:mm:ss");
   var end = moment(new Date(end)).format("YYYY-MM-DD HH:mm:ss");
   $.ajax({
   url: '<?php echo base_url();?>principal/addevents',
   data: 'title='+ title+'&start='+ start +'&end='+ end +'&class='+c+'&subject='+subject+'&teacher='+teacher ,
   type: "POST",
   success: function(json) {
   alert('Added Successfully!');
   getid=Number(json);
   }
   });
   
   calendar.fullCalendar('renderEvent',
   {
   id:getid,
   title: title,
   start: start,
   end: end,
   allDay: allDay,
   editable:true
   },
   true // make the event "stick"
   );
   }         
   location.reload(true);
   calendar.fullCalendar('unselect');
   },
   
   editable: false,
   weekends:false,
   eventDrop: function(event, delta) {
   var start = moment(new Date(event.start)).format("YYYY-MM-DD HH:mm:ss");
   var end = moment(new Date(event.end)).format("YYYY-MM-DD HH:mm:ss");
   $.ajax({
   url: '<?php echo base_url();?>principal/updateevents',
   data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
   type: "POST",
   success: function(json) {
    alert("Updated Successfully");
   }
   });
   },
   eventResize: function(event) {
   var start = moment(new Date(event.start)).format("YYYY-MM-DD HH:mm:ss");
   var end = moment(new Date(event.end)).format("YYYY-MM-DD HH:mm:ss");
   $.ajax({
    url: '<?php echo base_url();?>principal/updateevents',
    data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
    type: "POST",
    success: function(json) {
     alert("Updated Successfully");
    }
   });

},
eventClick: function(event) {
if(event.editable==true){
        var id=event.id;
        var title=event.title;
        showAjaxModal('<?php echo base_url();?>modal/popup/model_edit_event'+'/'+id);
    }
    }
  });
  
 });

</script>
<script>
       
                function deleteevent(){
                var did=$('#id').val();
                $.ajax({
                        url: '<?php echo base_url();?>principal/deleteevents',
                        data: 'id='+ did ,
                        type: "POST",
                        success: function(json) {
                                  $('button.btn-default').trigger('click');
                                  location.reload(true);
                        }   
      });
                return false;
                }
</script>

<div id="calendar"></div>
<?php
 }