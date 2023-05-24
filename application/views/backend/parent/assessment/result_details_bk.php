<script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script>
<script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>
<style>
    .self-text{
    float: right;
    color: blue;
    margin-right: 10px;
    margin-top: -15px;
    }
    .joined
    {
        margin:50px;
    }
</style>
<div class="panel-group joined" id="accordion-test-2">
    <div class="panel panel-default">
        
        <?php
        $i = 0;
        foreach($result_details as $row)
        {
            $i++;
        ?>
        <div class="panel-heading">
              <h4 class="panel-title">  <!-- data-parent="#accordion-test-2"  -->
              <a data-toggle="collapse" style="color:black !important" href="#collapse-<?php echo $i; ?>" aria-expanded="false" class="collapsed">
                <?php echo "Q # ".$i.": ".$row['question_text']; ?>
              </a>
              </h4>
              <?php echo "<span class='self-text'>Obtained Marks : ".$row['obtained_marks']."/" .$row['question_total_marks']. "</span>"; ?>
        </div>
        <div id="collapse-<?php echo $i; ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;"> 
        <div class="panel-body">
            
            
            <p>
               Your Answer : <?php 
               if($row['question_type_id'] == 1)
               {
                   echo get_answer_option($row['question_id']);
                   echo '<div>Right answer was option '.get_correct_option_for_mcqs($row['question_id']).'</div>';
               }
                elseif($row['question_type_id'] == 6){
                   $img_url = get_question_img_url($row['question_id']);
                   echo "<img src='".base_url()."uploads/".$_SESSION['folder_name']."/pictorial_question/".$img_url."'/>";
                   echo $row['answer']; 
                   }
                elseif($row['question_type_id'] == 7){
                    $matching_question_option = get_matching_question_option($row['question_id']);
                    
                    
            ?>
                            <table class="table table-bordered">
                                <thead>
                                    <th>Column A</th>
                                    <th>Column B</th>
                                    <th>Your Answer</th>
                                    <th>Marks</th>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach($matching_question_option as $matching_questions)
                                    {
                                        
                                        $matching_question_option_id = $matching_questions['matching_question_option_id'];
                                        
                                        $qu = "select sol.*, q.right_side_text from ".get_school_db().".assessment_matching_solution sol
                                        inner join ".get_school_db().".matching_question_option q on sol.option_number = q.option_number
                                        where sol.matching_question_option_id = ".$matching_question_option_id." and q.question_id = ".$row['question_id']."";
                                        $solution = $this->db->query($qu)->result_array();
                                    ?>
                                        <tr>
                                           <input type="hidden" value=" <?php echo $matching_questions['right_answer']; ?>">
                                            <td>
                                                <?php echo $matching_questions['left_side_text']; ?>
                                            </td>
                                            
                                            <td>
                                                <?php echo $matching_questions['right_side_text']; ?>
                                            </td>
                                            
                                            <td>
                                                <?php 
                                                    if( $solution[0]['option_number'] == $matching_questions['right_answer']){
                                                        echo "<span style='color:green;'>".$solution[0]['right_side_text']." ( Right Answer ) </span>";
                                                    }else{
                                                        echo "<span style='color:red;'>".$solution[0]['right_side_text']." ( Wrong Answer ) </span>";
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td>
                                                <?php echo $solution[0]['option_marks_obtained']. "/" .$matching_questions['option_marks']; ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                    
            <?php
                   }
                elseif($row['question_type_id'] == 8){
                    echo "<img src='".base_url()."uploads/".$_SESSION['folder_name']."/drawing/".$row['drawing_sheet_url']."'/>";
                   }
                else{
                echo $row['answer']; 
               }
               ?>
            </p>
        </div>
        </div>
        <hr>
        <?php
        }
        ?>
        
    </div>
</div>