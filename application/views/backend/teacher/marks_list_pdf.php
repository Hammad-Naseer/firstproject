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
        transition: all 0.3s;  /* Simple transition for hover effect */
        padding-left: 3px;
    }
    th {  
        background: #DFDFDF;  /* Darken header a bit */
        font-weight: bold;
    }
    td {  
        background: #FAFAFA;
    }
</style>

<div id="header" style="height: 100px; width: 100%;">
    <div style="float: left; margin-top: -15px; height: 100px; width: 150px;"> 
        <?php
        $d_school_id = $_SESSION['school_id'];
        {
            $logo=system_path($_SESSION['school_logo']);
            if($_SESSION['school_logo']=="" || !is_file($logo)){
        ?>
            <a href="">
                <img style="width: 150px; height: 100px; margin-top: -15px;" src="<?php //echo base_url();?>assets/images/gsims_logo.png">
            </a>
        <?php
            }else{
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
    <div style="float: left; margin-top: -15px; margin-left: 100px;"> 
        <h2 style="text-transform: uppercase; letter-spacing: 5px;">
            <b><?php echo $_SESSION['school_name']; ?></b>
        </h2>
        <h4 style="margin-top: -25px;margin-left: -50px !important; text-transform: uppercase; letter-spacing: 5px;">
            <?php echo get_phrase('student_marks_sheet_report');?>
        </h4>
    </div>
</div>




<div id="table_data" style="margin-top: 20px;">
    <?php
    $p = "SELECT * from ".get_school_db().".exam_routine WHERE exam_id=$exam_id AND section_id=$section_id AND subject_id=$subject_id AND school_id=".$_SESSION['school_id']." AND (is_submitted=1 OR is_approved=1)";
    $query = $this->db->query($p)->result_array(); 
    
    $subject_title=get_subject_name($subject_id);
    $q="select distinct e.*,y.yearly_terms_id,y.title 
	 	from ".get_school_db().".exam e 
	 	inner join ".get_school_db().".yearly_terms y on y.yearly_terms_id=e.yearly_terms_id 
	 	inner join ".get_school_db().".exam_routine er on er.exam_id=e.exam_id 
	 	where 
	 	e.school_id=".$_SESSION['school_id']." 
	 	".$termStr." 
	 	and y.status in (1,2) 
	 	and y.is_closed = 0 and e.exam_id=".$exam_id."";
    $query=$this->db->query($q);
    $result = $query->row();
    $data_section['0'] = $section_id;
    if ($section_id > 0)
    {
        $studentArr=$this->db->query("select s.name,s.student_id,s.roll  from ".get_school_db().".student s  where s.school_id =".$_SESSION['school_id']."  and s.section_id=".$section_id."  and student_status in (".student_query_status().") ")->result_array();
        if(sizeof($studentArr)>0)
        {
            echo "<h4 class='system_name inline'><b>".$subject_title."</b></h4><hr>";
            echo "<h4 class='system_name inline'><b>".$result->name.' ( '.date('d-M-Y',strtotime($result->start_date)).' to '.date('d-M-Y',strtotime($result->end_date)).' ) '."</b></h4><hr>";
            echo "<h4 class='system_name inline'><b>".get_teacher_dep_class_section_list_name_pdf($data_section, $section_id)."</b></h4><hr>";
            
    ?>
        <div class="row">
            <div id="session" style="display:none">
                <?php
               echo '<div align="center">
                 <div class="alert alert-success alert-dismissable">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  Record Saved
                 </div> 
                </div>';
                ?>
            </div>
            <div class="col-md-12" data-step="2" data-position='top' data-intro="Assign marks">
                <div class="tab-content">
                    <div class="" id="list">
                        <div id="error"></div>
                        <form name="marks" id="filter" method="post">
                            <table border="1">
                                <tr style="background:black !important;">
                                    <th style="width:50px; color:#ffffff;background:black !important;">
                                        <?php echo get_phrase('roll ');?>
                                    </th>
                                    <th style="width:70px; color:#ffffff;background:black !important;">
                                        <?php echo get_phrase('student');?>
                                    </th>
                                    <th style="color:#ffffff;background:black !important;">
                                        <?php echo get_phrase('component_wise_marks');?>
                                    </th>
                                </tr>
                                <?php foreach($studentArr as $st){ ?>
                                <tr>
                                    <td class="td_middle">
                                        <?php echo $st['roll'];?>
                                    </td>
                                    <td>
                                        <?php echo $st['name'];?>
                                    </td>
                                    <td>
                                        <table class="table table-bordered ">
                                            <tr style="background:#dcdcdc; font-weight:bold;">
                                                <td style="width:200px"><?php echo get_phrase('subject');?> / <?php echo get_phrase('component');?></td>
                                                <td style="width:100px"><?php echo get_phrase('total_marks');?></td>
                                                <td style="width:150px"><?php echo get_phrase('marks_obtained');?></td>
                                                <td style="width:150px"><?php echo get_phrase('garde');?></td>
                                                <!--<td><?php echo get_phrase('comment');?></td>-->
                                            </tr>
                                            <?php  
                                            $q2="select subject_component_id,subject_id,title,school_id,percentage  from ".get_school_db().".subject_components  where  subject_id=".$subject_id."  and school_id=".$_SESSION['school_id']."";
                                            $compArr=$this->db->query($q2)->result_array();
                                            if(sizeof($compArr)>0)
                                            {
                                                $total=0;
                                                $sum=0;
                                                foreach($compArr as $comp)
                                                {
                                                    $q3="SELECT m.*,marks_obtained FROM ".get_school_db().".marks m inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id where m.subject_id=".$subject_id." AND m.student_id=".$st['student_id']." AND m.exam_id=".$exam_id." and m.school_id=".$_SESSION['school_id']." AND mc.subject_component_id=".$comp['subject_component_id']."";
                                                    $marksArr=$this->db->query($q3)->result_array();
                                                    $total+=$comp['percentage'];
                                                    $sum+=$marksArr[0]['marks_obtained'];
                                                    $comments[$st['student_id']][]=$marksArr[0]['comment'];?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $comp['title']?>
                                                        </td>
                                                        <td class="marks_tot_<?php echo $st['student_id']?>" id="<?php echo $comp['subject_component_id']?>">
                                                            <?php echo $comp['percentage']?>
                                                        </td>
                                                        <td id="">
                                                            <input id="comp_<?php echo $comp['subject_component_id']?>" type="number" min="0" max="<?php echo $comp['percentage']?>" style="width:50px !important" value="<?php echo $marksArr[0]['marks_obtained']?>" name="marks_obt" class="marks_obt_<?php echo $st['student_id']?>" <?php echo $disable_val;?>>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                    <?php 
                                                }
                                            }
                                            else
                                            {
                                                $q3="SELECT m.*,marks_obtained FROM ".get_school_db().".marks m inner join ".get_school_db().".marks_components mc on mc.marks_id=m.marks_id where m.subject_id=".$subject_id." AND m.student_id=".$st['student_id']." 
                                                    AND m.exam_id=".$exam_id." 
                                                    and m.school_id=".$_SESSION['school_id']." 
                                                    AND mc.subject_component_id=0";
                                                $marksArr=$this->db->query($q3)->result_array();
                                                ?>
                                                <tr>
                                                    <td><?php echo $subject_title; ?></td>
                                                    <td>
                                                        <?php echo get_total_marks($exam_id,$section_id,$subject_id)?>
                                                    </td>
                                                    <td>
                                                        <?php echo $marksArr[0]['marks_obtained'];?>
                                                    </td>
                                                    <td>
                                                        <?php echo get_grade($marksArr[0]['marks_obtained']);?>
                                                    </td>
                                                    <!--<td>-->
                                                    <!--    <?php //echo $marksArr[0]['comment']?>-->
                                                    <!--</td>-->
                                                </tr>
                                                <?php 
                                            }
                                            
                                            if(sizeof($compArr)>0)
                                            {?>
                                                <tr>
                                                    <td><?php echo get_phrase('total_marks');?>:</td>
                                                    <td>
                                                        <input type="text" disabled="" style="width:50px !important" value="<?php echo $total;?>" id="total_marks<?php echo $st['student_id']?>" placeholder="" name="marks_obtained">
                                                    </td>
                                                    <td>
                                                        <input type="text" style="width:50px !important" value="<?php echo $sum;?>" placeholder="<?php echo get_phrase('total_obtained');?>" name="total_obtained" id="total_obtained<?php echo $st['student_id']?>" <?php echo $disable_val;?>>
                                                        <input type="hidden" name="student_id" class="student_id" id="student_id" value="<?php echo $st['student_id']?>">
                                                        <div style="float: right;  width: 75px;margin-top: 5px;" name="grade" id="grade<?php echo $st['student_id']?>"><?php echo get_phrase('grade');?>:
                                                            <?php echo get_grade($sum);?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <textarea name="comments" rows="3" cols="40" id="comment<?php echo $st['student_id']?>" <?php echo $disable_val;?>><?php echo $comments[$st['student_id']][0];?></textarea>
                                                    </td>
                                                </tr>
                                                <?php 
                                            }
                                            ?>

                                        </table>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <?php } } ?>    
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


