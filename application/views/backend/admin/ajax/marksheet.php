<?php
if (right_granted('examresult_viewmarksheet'))
{
?>
    <table class="table table-bordered table_export">
        <thead>
            <tr>
                <th style="width:34px;">
                    <div>
                        <?php echo get_phrase('s_no');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('image');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('details');?>
                    </div>
                </th>
                <th style="width:134px;">
                    <div>
                        <?php echo get_phrase('options');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php 
            
            $q="select * from ".get_school_db().".student where school_id=".$_SESSION['school_id']." AND student_status IN (".student_query_status().")  AND section_id=".$section_id." ";
            $students=$this->db->query($q)->result_array();
            $r = 0;
            foreach($students as $row){
                $r++;
            ?>
            <tr>
                <td class="td_middle">
                    <?php echo $r; ?>
                </td>
                <td class="td_middle">
                    <div>
                        <img src="<?php if($row['image']==''){
                          echo  base_url().'/uploads/default.png'; 
                        }else{
                         echo  display_link($row['image'],'student');
                        }
                        ?>" class="img-circle" width="30" />
                    </div>
                </td>
                <td>
                    <div class="myttl">
                        <?php echo $row['name'];?>
                    </div>
                    <div><strong><?php echo get_phrase('roll');?>#: </strong>
                        <?php echo $row['roll'];?>
                    </div>
                </td>
                <td class="td_middle">
                    <a href="<?php echo base_url();?>exams/get_exam_result/<?php echo $row['student_id']."/".$yearly_term_id."/".$exam_id;?>" id="view_market_sheet" class="btn btn-default">
                        <?php echo get_phrase('view_marksheet');?>
                    </a>
                </td>
            </tr>
            <?php 
            }
            ?>
        </tbody>
    </table>
    <?php } ?>
<script>
    $(".table_export").DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
</script>


<!--
    // <a href="#" data-sid="<?= $row['student_id']; ?>" data-tid="<?= $yearly_term_id; ?>" data-eid="<?= $exam_id; ?>" id="view_market_sheet" class="btn btn-default">
    //     <?php echo get_phrase('view_marksheet');?>
    // </a>
    // $('#view_market_sheet').click(function(){
    //   var student_id = $(this).attr('data-sid');
    //   var yearly_term_id = $(this).attr('data-tid');
    //   var exam_id        = $(this).attr('data-eid');
    //   if (exam_id != '' && yearly_term_id != '') {
    //         $.ajax({
    //             type: "POST",
    //             url: "<?php echo base_url(); ?>exams/get_exam_result",
    //             data: ({
    //                 exam_id:    exam_id,
    //                 student_id: student_id,
    //                 yearly_term_id : yearly_term_id
    //             }),
    //             dataType: "html",
    //             success: function(html) {
    //                 if (html == 'False' || html == 'false') {
    //                     var html;
    //                     html='<div align="center"><div class="alert alert-success alert-dismissable"><button type="button" class="close" value="result_not_found" data-dismiss="alert" aria-hidden="true">×</button>Result Not Found</div></div>';
    //                     $('#exam_result').html(html);
    //                      setTimeout(function() {
    //                         $('#exam_result').html("");
    //                     }, 3000);
                        
    //                 }else{
    //                      $('#exam_result').html(html);
    //                 }
    //             }
    //         });
    //     }    
    // });
    
    -->