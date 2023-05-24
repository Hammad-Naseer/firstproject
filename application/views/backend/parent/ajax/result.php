<?php 
$q="select s.student_id,s.name,s.roll,s.section_id,s.academic_year_id,s.school_id 
from ".get_school_db().".student s 
where 
s.student_id=".$student_id."
";
$student_arr = $this->db->query($q)->result_array();
if(count($result) > 0){
?>

<style>
     @page {
        size: A4;
        margin: 0;
    }
    .page {
        width: 297mm;
        height: 209mm;
        padding: 10mm;
        margin: 0 auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        page-break-after: always;
    }
    
    .marksheet{
        box-shadow: 0px 0px 7px 0px #cccc;
        background:white !important;
    }
    .school_heading{
        margin-top:42px;
        font-weight:bold;
    }
    .footer_td{
        color: black !important;
        font-weight: 600;
    }
    .signature_area{
        text-align:center;
        margin-top: 50px;
        margin-bottom: 50px;
    }
    .signature_area b{
        text-decoration: overline;
        color:black;
    }
    #print_btn{
        position: relative;
        width: 200px
    }
    
    @media print {
        table {
            border-collapse: collapse;
            border-spacing: 0px;
        }
        th,td {
            border: 1px solid #c3c3c3 !important;
            padding: 5px;
            font-size: 10px;
            font-family: arial;
            border-style: dotted;
        }
        
        html, body {
            width: 297mm;
            height: 209mm;
        }
        .page {
            margin-top:3px !important;
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
        
    @page {size: A4 landscape; }
    }
    
</style>


<div class="container">
    <div class="row p-0">
        <div class="col-md-9 col-sm-9 pl-3">
            <div class="text-center"> 
                <div id="print_btn" class="modal_save_btn"><?php echo get_phrase('print_marksheet');?></div>
            </div>
        </div>
    </div>   
</div>   
                
                 
<div class="container marksheet page" id="print_form">
    <div class="row">
        <div class="col-md-9 col-sm-9">
            <div class="text-center">
                <h1 class="school_heading">Indici Edu School System</h1>
                <p>School Tag Line Write Here</p>
            </div>
            <?php
                $student_data = get_student_details($student_id);
                $get_exam = get_exam_term_name($exam_id); 
                $get_parent = get_parent_details($student_data[0]['parent_id']);
            ?>
            <p class="text-left"><b>Roll No:</b> <?= $student_data[0]['roll'] ?></p>
            <p class="text-left"><b>Class:</b> <?php $class_section = section_hierarchy($student_data[0]['section_id'],$_SESSION['school_id']); echo $class_section['c']. ' - ' . $class_section['s'] ?></p>
            <p class="text-left"><b>Student Name:</b> <?= ucfirst($student_data[0]['name']); ?></p>
            <p class="text-left"><b>Father Name:</b> <?= ucfirst($get_parent->p_name) ?> </p>
            <p class="text-left"><b>Exam:</b> <?= $get_exam[0]['exam_name'] ?> (<?= $get_exam[0]['term'] ?>)</p>
        </div>
        <div class="col-md-3 col-sm-3">
            <img src="https://indiciedu.com.pk/uploads/sch214-214/setting_1622775652.png" width="150">
            <p><b>Date:</b> <?= date_view($get_exam[0]['exam_end_date']); ?></p>
        </div>
        <div class="col-md-12 col-sm-12">
            <h4 class="text-left"><b>Subjects</b></h4>
            <table class="table table-bordered">
                <thead>
                    <th>S.No</th>
                    <th>Subject</th>
                    <th>Total Marks</th>
                    <th>Obtain Marks</th>
                    <th>Percentage</th>
                    <th>Grade</th>
                </thead>
                <tbody>
                     <?php 
                        $i = 1;
                        $obt = 0; 
                        $total = 0;

                        foreach($result as $arr):
                        	$obt += get_total_obtained($exam_id,$arr['marks_id'],$arr['subject_id']);
                        	$total += get_total_marks($exam_id,$student_data[0]['section_id'],$arr['subject_id']);
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?= get_subject_name($arr['subject_id']); ?></td>
                        <td><?= $total_marks =  get_total_marks($exam_id,$student_data[0]['section_id'],$arr['subject_id']); ?></td>
                        <td><?= $marks_obtained = get_total_obtained($exam_id,$arr['marks_id'],$arr['subject_id']); ?></td>
                        <td><?= $percent_obtained = ($marks_obtained/$total_marks*100); ?>%</td>
                        <td><?= get_grade($percent_obtained); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <th class="footer_td text-center" colspan="2">GRAND TOTAL</th>
                    <td class="footer_td"><?= $total ?></td>
                    <td class="footer_td"><?= $obt ?></td>
                    <td class="footer_td"><?= $total_percentage = ($obt/$total*100); ?>%</td>
                    <td class="footer_td"><?= get_grade($total_percentage); ?></td>
                </tfoot>
            </table>
            <br>
            <h4 class="text-left"><b>Perfomance Indicators</b></h4>
            <table width="100%" border="1" class="table table-bordered">
                <tr>
                    <td class="text-center">
                        Academic Grade: &nbsp;&nbsp;&nbsp;
                        <?= grade_indicators(); ?> 
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-12 signature_area">
            <div class="col-md-6 col-sm-6">
                <b>Class Teacher Signature</b>
            </div>
            <div class="col-md-6 col-sm-6">
                <b>Principle Signature</b>
            </div>
        </div>
    </div>
</div>

 

<?php }else{ ?>
    <div clss="container text-center">
        <h1>Marksheet Not Found</h1>
    </div>
<?php } ?>

<script>
    $( document ).ready( function ()
    {
        $( '#print_btn' ).click( function ()
        {
            var printContents = document.getElementById( 'print_form' ).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        });
    } );
</script>