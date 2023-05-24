<?php
if(count($assessment_arr) > 0){
?>
<div class="container my-3"> 
    
    <table class="table">
    <?php
        $count = 1;
        foreach($assessment_arr as $assessment_arr){
    ?>
        <tr>
            <td><?php echo $count++; ?></td>
            <td><?php echo $assessment_arr['assessment_title']; ?></td>
            <td><?php echo $assessment_arr['assessment_date']; ?></td>
            <td><?php echo $assessment_arr['teacher_name']; ?></td>
            <td><?php echo $assessment_arr['subject_name']; ?></td>
            <td><?php echo $assessment_arr['subject_code']; ?></td>
            <td><?php echo $assessment_arr['total_marks']; ?></td>
            <td><?php echo $assessment_arr['obtained_marks']; ?></td>
            <td>
                <?php
                if($assessment_arr['grade_id'] > 0 ){
                    $assessment_grade = get_assessment_grade($assessment_arr['grade_id']);
                    echo $assessment_grade[0]['name'];
                }
                ?>
            </td>
            <td><a class="btn btn-primary" href="<?php echo base_url()."adm_assessment/view_assessment_details/".$assessment_arr['assessment_id'] ?>" target="_blank">Details</a></td>
        </tr>
    <?php            
        }
    ?>
    </table>
</div>
<?php
}else{
?>
<div class="container my-5 diary-roww"> 
<div class="row diary-headr">
    <div class="col-sm-6 d-flex justify-content-start align-items-center">
        <p class="text-center m-0">No Data Found</p>
    </div> 
    <hr class="hr-seting">
</div>
<?php
}
?>