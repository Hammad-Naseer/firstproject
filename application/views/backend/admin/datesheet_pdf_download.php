<style>

    table {  
    color: #333;
    font-family: Helvetica, Arial, sans-serif;
    border-collapse: 
    collapse; border-spacing: 0;
    width: 100%;
    border: 1px solid black;
    font-size: 12px;
    }
    td, th { /* No more visible border height: 25px; */
    transition: all 0.3s;  /* Simple transition for hover effect 
    */
    padding-left: 3px;
    }
    th {  
    background: #DFDFDF;  /* Darken header a bit */
    font-weight: bold;
    }
    td {  
    background: #FAFAFA;
    }
    /* Cells in even rows (2,4,6...) are one color */        
    tr:nth-child(even) td { background: #F1F1F1; }         
    tr:nth-child(odd) td { background: #FEFEFE; }  
    tr td:hover { background: #666; color: #FFF; }  
    
</style>
<div id="header" style="height: 100px; width: 100%;">
    <div style="float: left; margin-top: -15px; height: 100px; width: 150px;"> 
        <?php
        
        if($exam_id!=''){
        	$exam_check=" and exam_id=".$exam_id."";
        }
        
        $d_school_id = $_SESSION['school_id'];
        {
            $logo=system_path($_SESSION['school_logo']);
            if($_SESSION['school_logo']=="" || !is_file($logo))
            {
            ?>
            <a href="">
                <img style="width: 150px; height: 100px; margin-top: -15px;" src="<?php //echo base_url();?>assets/images/gsims_logo.png">
            </a>
            <?php
            }
            else
            {
                $img_size = getimagesize("uploads/".$_SESSION['folder_name']."/".$_SESSION['school_logo']."");
                $img_width = $img_size[0];
                $img_height = $img_size[1];

            ?>
            <a href="">
            <img style="margin-top: -15px;
                width:
                <?php
                    if ($img_width>150) {
                        $img_width = 150;
                    }
                    echo $img_width."px;";
                ?>
                height:
                <?php
                    if ($img_height>100) {
                        $img_height = 100;
                    }
                    echo $img_height."px;";
                ?>
                " src="<?php //echo base_url();?>uploads/<?php echo $_SESSION['folder_name'].'/'.$_SESSION['school_logo']; ?>">
            </a>
            <?php
            }
        }
        
        ?>
    </div>
    <?php
        $q = "select e.* from ".get_school_db().".exam e inner join ".get_school_db().".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id where e.school_id=".$_SESSION['school_id']." ".$termCheck." ".$yearCheck." ".$exam_check ." order by e.start_date DESC";
		$exams = $this->db->query($q)->result_array();
    ?>
    <div style="float: left; margin-top: -15px; margin-left: 100px;"> 
        <h2 style="text-transform: uppercase; letter-spacing: 5px;">
            <b><?php echo $_SESSION['school_name']; ?></b>
        </h2>
        <h4 style="margin-top: -25px;margin-left: -50px !important; text-transform: uppercase; letter-spacing: 5px;">
            <?php echo get_phrase('datesheet_pdf');?><br>
            <?php echo "From : " .date('d M Y',strtotime($exams[0]['start_date']))."  To : ".date('d M Y',strtotime($exams[0]['end_date'])); ?>
        </h4>
    </div>
</div>

<div class="panel-group joined" id="accordion-test-2">
    <div class="panel panel-default">
        <div id="collapse<?php echo $row['exam_id'];?>" class="panel-collapse collapse in">
            <div class="panel-body" style="margin-top: 20px;">
                <table border="1px">
                    <thead>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Subject</th>
                    </thead>
                    <tbody>
                        <?php foreach($exams as $row){ ?>
                        <tr>
                            <?php 
                                $custom_css=array(1=>'current-day',2=>'holiday');      
                                $current_date=date('d-M-Y');                           
                                  
                                $date_from = strtotime($row['start_date']);
                                $date_to = strtotime($row['end_date']);
                                
                                $oneDay = 60*60*24;
                                
                                for($i=$date_from; $i<=$date_to; $i=$i+$oneDay)
                                {
                                	$current = "";
                                    $day=date("l", $i);
                                	$date1=convert_date(date("l F j, Y", $i));
                                if($date1==$current_date)
                                {
                                	$current=$custom_css[1];
                                } 
                                echo '<tr class="gradeA '.$current.'">'; 	
                            ?>
                            <td width="100">
                                <?php echo $date1;?>
                            </td>
                            <td width="100">
                                <?php echo $day;?>
                            </td>
                            <td>
                                <?php
    	                        $q="select er.* from ".get_school_db().".exam_routine er where er.school_id=".$_SESSION['school_id']." and er.exam_id=".$row['exam_id']." and section_id=".$section_id."";
                                $routines=$this->db->query($q)->result_array();
    
    					        foreach($routines as $row2){?>
                                <div class="btn-group border-div" id="er<?php echo $row2['exam_routine_id'];?>">
                                    <?php 
        						        $exam_date=$row2['exam_date'];
        							    if(strtotime($row2['exam_date'])==$i){
        							?>
                                    <?php 
        							    echo get_subject_name($row2['subject_id']);
        							    echo '('.$row2['time_start'].'-'.$row2['time_end'].')'; 						
        							?>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>

<div style="margin-top: 15px;">
	<table>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
			<?php 
				echo get_phrase('Date');?> :
				<?php
				    echo date("Y-m-d h:i:s a");
				?>
			</td>
		</tr>
	</table>
</div>


