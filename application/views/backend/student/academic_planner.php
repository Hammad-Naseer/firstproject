<?php if(isset($classes)&&isset($subjects)){
    ?>
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
    	<thead>
        	<tr>

            	<th><?php echo get_phrase('subject');?></th>
                <th><?php echo get_phrase('planner');?></th>
           </tr>
       </thead>
       		<tbody>
                    <?php         foreach ($subjects as $subject):  ?>             

            	<tr class="gradeA">
                    <td><?php echo $subject['name'];?></td>
                    <td><form><input type="hidden" required name="subject" value="<?php echo $subject['subject_id'];?>">
                            <input type="hidden" required name="class" value="<?php echo $class['class_id'];?>">
                            <input type="hidden" required name="tid" value="<?php echo $subject['teacher_id'];?>">
                            <button class="btn" formmethod="post" formaction="<?php echo base_url();?>parents/academic_planner/<?php echo $subject['name'];?>"><?php echo get_phrase('view_academic_planner');?></button></form></td>
                </tr>
                <?php  
                        endforeach;
                        ?>
		</tbody>
        </table>
<?php 
}
 elseif (isset($calendarTitle)){
     ?>
  <center>
    <div class="row">
        <div class="col-sm-offset-4 col-sm-4">
        
            <div class="tile-stats tile-white-gray">
                <div class="icon"><i class="entypo-calendar" style="opacity: 0.5"></i></div>
                <h2><?php echo $student .'</h2><h2>'. $calendarTitle;?></h2>
               
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
    right: 'month,agendaWeek,agendaDay'
   },
   

            events:[
						<?php 
						$this->db->query("SET SESSION time_zone='+5:00'");
						$sql = "SELECT id,title,unix_timestamp(start) as start,unix_timestamp(end) as end FROM "get_school_db().".evenement WHERE (class=? AND subject=?) OR teacher=-1"; 
                        $resultat = $this->db->query($sql,array($class,$subject))->result_array();
						foreach($resultat as $row):
						?>
                   {
                                                        id: <?php echo $row['id'];?>,
							title: "<?php echo $row['title'];?>",
							start: new Date(<?php echo $row['start']*1000;?>),
							end:	new Date(<?php echo $row['end']*1000;?>) 
						},
						<?php 
						endforeach
						?>
						
					],
lazyFetching:true,
selectable:false,
editable:false,

  
 });
 });
</script>
<div id="calendar"></div>
<?php
 }