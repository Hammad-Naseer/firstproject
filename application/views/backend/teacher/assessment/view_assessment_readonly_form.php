<?php  $assessment_row = get_assessment_row($assessments[0]['assessment_id']); ?>
<script src="https://cdn.ckeditor.com/4.14.1/standard-all/ckeditor.js"></script>
<script src="https://www.wiris.net/demo/plugins/app/WIRISplugins.js?viewer=image"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
    
    @page {
      size: A4;
      margin: 0;
    }
    @media print {
        #questionPaper  {
        background-color: lightgreen !important;
      }
       .headHeadings{
            font-family: fantasy;
            color:red !important;
        }
    }
    
    @media screen and (max-width: 1020px) {
      #questionPaper  {
        background-color: lightgreen !important;
      }
    }
    
    /*@media print {*/
    /*  #questionPaper {*/
    /*    margin: 0;*/
    /*    border: initial;*/
    /*    border-radius: initial;*/
    /*    width: initial;*/
    /*    min-height: initial;*/
    /*    box-shadow: initial;*/
    /*    background: initial;*/
    /*    page-break-after: always;*/
    /*  }*/
    /*    .headHeadings{*/
    /*        font-family: fantasy;*/
    /*        color:red !important;*/
    /*    }*/
    /*}*/

    body{font-family:ABeeZee}.page-container .main-content{background:#dee2e6!important}
    .self-text{float:right;color:#fff;margin-right:10px;margin-top:-15px}.joined{margin:30px}
    /*.container_self{width:95%;margin-top:30px}*/
    .panel_heading_self{height:50px;padding:10px 0 5px 10px;font-size:18px}
    .panel_self{color:#000}.timer_heading{font-size:30px!important;font-weight:600;letter-spacing:6px}#demo{font-size:60px!important;color:#000;font-variant:petite-caps;font-weight:900}
    .card{box-shadow:0 4px 8px 0 rgba(0,0,0,.2);transition:.3s;width:100%}.card:hover{box-shadow:0 8px 16px 0 rgba(0,0,0,.2)}
    .container_after_timer{padding:2px 16px}.detail_card_p{font-size:14px!important;margin:0 0 0 .5px}#sidebars{position:fixed;margin:3px 0 0 10px;box-shadow:1px 2px 4px 2px #cccccc94}.sidenav_assessment{z-index:1;top:4%;left:65%;background:#eee;overflow-x:hidden;padding:15px 15px}
    /*.header_assess{padding: 20px;text-align: center;background: #465050;margin: 82px}*/
    .Q{font-size:20px}.panel{border:3px solid transparent;box-shadow:1px 2px 10px 1px #ccc}
    .panel-info{border-color:#000}#zwibbler_11{width:100%!important}.times{background:#000;color:#fff;width:119px!important;border-radius:10px;font-size:80px!important;text-align:-webkit-center;padding:7px;font-family:'Allerta Stencil'}.times_text{font-size:18px!important;position:relative;top:0;text-align:-webkit-center;font-family:Orbitron}.voice_over{width:50px;height:50px;border-radius:30px;background:#f44336;border:1px solid red;color:#fff;font-size:20px;text-align:center;outline:0;float:left;position:absolute;left:-24px;top:-18px;border:8px solid #fff}
    .voice_over:focus{outline:0}.wrs_tickContainer{display:none}.wrs_modal_dialogContainer{z-index:999999999!important;top:30%;left:35%}zwibbler{position:absolute;left:0;right:0;top:0;bottom:0;display:flex;flex-flow:row nowrap}.tools{background:#f5f5f5;flex:0 0 203px;display:flex;flex-flow:column nowrap;overflow-y:scroll;padding:10px;font-family:Ubuntu}[z-canvas]{flex:1 1 auto}.tools button{font-family:inherit;font-size:100%;padding:5px;display:block;background-color:#fff;border:none;border-radius:2px;border-bottom:2px solid #ddd;width:100%}.tools button[tool]{display:inline-block;width:60px;height:60px;font-size:30px}.tools button.option{border:0;padding:10px;border-radius:3px;background:0 0;text-align:left}
    .tools button.selected{background:#dbe6d7}.tools button.hover{background:#ddd}.tools hr{border:none;border-top:1px solid #ccc}.tools select{width:100%}[swatch]{border:1px solid #000;display:inline-block;height:2em;width:4em;vertical-align:middle;margin-right:10px}.colour-picker{padding:10px 0}.pages{flex:0 0 100px;background:#ccc;display:flex;flex-flow:row nowrap;overflow-x:scroll;overflow-y:hidden;align-items:center}.page{border:3px solid transparent;margin:5px;display:inline-block;box-shadow:2px 2px 2px rgba(0,0,0,.2)}.page.selected{border:3px solid orange}[z-popup]{background:#ccc;padding:10px;box-shadow:2px 2px 2px rgba(0,0,.2)}.panel-title>p{display:inline-block}.panel-body{padding:5px!important}
    hr.line1 {
        border: 0;
        height: 1px;
        background: #333;
        margin-bottom: 7.1mm;
    }
    hr.style-eight {
    overflow: visible; /* For IE */
    padding: 0;
    border: none;
    border-top: medium double #333;
    color: #333;
    text-align: center;
    }
    hr.style-eight:after {
        content: "§";
        display: inline-block;
        position: relative;
        top: -0.7em;
        font-size: 1.5em;
        padding: 0 0.25em;
        background: white;
    }
    .examHeadContainer{
        /*width:100%;*/
        margin-top:-30px;
    }
    .headHeadings{
        font-family: fantasy;
    }
    .pictorialImg{
        object-fit: contain !important;
        width: 500px;
    }
    h4.panel-title p {
        color: black !important;
        font-size: 16px !important;
        display: inline-block;
    }
    
    .printButton{
        background-size: 400% 400%;
        position: relative;
        left: 252px;
        top: 32px;
        border: 3px solid white !important;
        background: #ac1818 !important;
        /*background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab) !important;*/
        /*animation: gradient 15s ease infinite;*/
    }
    
    /*@keyframes gradient {*/
    /*	0% {*/
    /*		background-position: 0% 50%;*/
    /*	}*/
    /*	50% {*/
    /*		background-position: 100% 50%;*/
    /*	}*/
    /*	100% {*/
    /*		background-position: 0% 50%;*/
    /*	}*/
    /*}*/
    
</style>


    
      
      
<!--<div class="header_assess">-->
    
<!--</div>-->
<button onclick="printContent('questionPaper')" class="btn btn-primary printButton">Print Question Paper</button>
<div class="container mt-4">
   <?php
        $assessment_details = get_assessment_details($assessment_row->assessment_id);
        
        $assessment_date =  date("Y-m-d",strtotime($assessment_details->assessment_date));
        $time1 = date("Y-m-d h:i",strtotime($assessment_date.' '.$assessment_details->start_time));
        $time2 = date("Y-m-d h:i",strtotime($assessment_date.' '.$assessment_details->end_time));
        $examTime = round(abs($time1 - $time2) / 60,2). " minutes";
        
        $start_datetime = new DateTime($time1); 
        $diffExamTime = $start_datetime->diff(new DateTime($time2)); 
   ?>
    <div class="panel panel-info panel_self" id="questionPaper">
        <div class="row">
            <div class="col-lg-12 text-center headingBox">
                 <h1 class="headHeadings"><u><?php echo $assessment_row->assessment_title; ?></u></h1>
                 <h4 class="headHeadings"><u><?php echo $assessment_details->subject_name ." ( ".$assessment_details->subject_code." ) "; ?></u></h4>
                 <h4 class="headHeadings"><u><?php echo $assessment_details->department_name ." - ".$assessment_details->class_name. " - ".$assessment_details->section_name; ?></u></h4>
            </div>
        </div>
        <div class="examHeadContainer">
            <div class="row">
                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-6 text-left">
                    <p><b>Time:</b> 
                    <?php 
                        echo $diffExamTime->h != "" ? $diffExamTime->h.' Hours ' : "";
                        echo $diffExamTime->i != "" ? $diffExamTime->i.' Minutes ' : "";
                    ?>
                    </p>   
                  <p><b>No of Questions:</b> <?php echo $assessment_row->total_attempts; ?></p> 
                  <p><b>Student Name:</b> __________________</p> 
                </div>
                <div class="col-xs-6 col-sm-8 col-md-8 col-lg-6 text-right">
                  <p><b>Exam Date:</b> <?php echo $assessment_row->assessment_date != "" ? date('d-m-Y',strtotime($assessment_row->assessment_date)) : date('d-m-Y') ; ?> </p>
                  <p><b>Total Marks:</b> <?php echo $assessment_row->total_marks; ?> </p>   
                  <p><b>Roll No:</b> _______</p> 
                </div>
            </div>
            <hr class="style-eight" style="width:95%;margin-top: 0px;">
            <?php if($assessment_row->remarks != ""): ?>
            <div class="row">
                <div class="col-lg-12">
                    <b>Remarks: </b>
                    <p>
                        <?= $assessment_row->remarks ?>
                    </p>
                </div>
            </div>
            <hr style="width:95%;margin-top: 0px;">
            <?php endif; ?>
        </div>
    
        <div class="panel-group joined" id="accordion-test-2">
            <div class="">    
         
                <?php
                 
                $i = 0;
                if(count($assessments) > 0){
                    
                foreach($assessments as $row)
                {
                    $i++;
                    
                    if($row['question_type_id'] == 3)
                    {
                ?>
                    <h4 class="panel-title"> <?php echo "Q.".$i.": ".str_replace("_","___________",$row['question_text']) ?></h4>
                <?php
                    }else{
                ?>
                    
                    <h4 class="panel-title"> <?php echo "Q.".$i.": ".$row['question_text']; ?></h4>
                    <?php } echo "<span style='float:right;position:relative;top:-28px;font-size:12px;'>Total Marks : ".$row['question_total_marks']. "</span>"; ?>
                <?php if($row['question_type_id'] != 3){ ?>
                <div class="panel-body">
                <?php } ?>
                    <?php 
                       
                        if($row['question_type_id'] == 1)
                        {
                            $question_options = get_question_options($row['question_id']);
                            $j = 0;
                            foreach($question_options as $options)
                            {
                            $j++;
                        ?>
                            <label class="radio-inline" style="line-height: 1.2;display:initial;">
                            <input type="radio" id="answer_<?php echo $count.'_'.$j ?>" value="<?php echo $options['option_number']?>" name="answer_<?php echo $count; ?>"><?php echo $options['option_text']; ?>
                            </label>
                        <?php
                            }
                        }elseif($row['question_type_id'] == 2){
                        ?>
                            <label class="radio-inline" style="line-height: 1.2;display:initial;">
                            <input type="radio" id="answer_<?php echo $count.'_'.$j ?>" value="true" name="answer_<?php echo $count; ?>">True
                            </label>
                            
                            <label class="radio-inline" style="line-height: 1.2;display:initial;">
                            <input type="radio" id="answer_<?php echo $count.'_'.$j ?>" value="false" name="answer_<?php echo $count; ?>">False
                            </label>
                        <?php
                        }elseif($row['question_type_id'] == 6){
                            $img_url = get_question_img_url($row['question_id']);
                            echo  "<div class='text-center'><img height='350' class='pictorialImg' width='350' src='".base_url()."uploads/".$_SESSION['folder_name']."/pictorial_question/".$img_url."'/></div>";
                            echo "<br>";
                            echo "<h4>Answer : </h4><br>";
                            
                            for ($x = 0; $x <= $row['required_lines']; $x++) {
                              echo "<hr class='line1'>";
                            }
                        }elseif($row['question_type_id'] == 4 || $row['question_type_id'] == 5 || $row['question_type_id'] == 8){
                            echo "<h4>Answer : </h4><br>";
                            for ($x = 0; $x <= $row['required_lines']; $x++) {
                              echo "<hr class='line1'>";
                            }
                        }elseif($row['question_type_id'] == 7){
                    ?>
                            <table class="table">
                                <thead>
                                    <th><b>Column A</b></th>
                                    <th><b>Column B</b></th>
                                </thead>
                                <tbody>
                                <?php
                                $matching_question_option =  get_matching_question_option($row['question_id']);
                                $total_option             =  count($matching_question_option);
                                
                                foreach($matching_question_option as $matching_questions)
                                {
                                ?>
                                    <tr>
                                        <td>
                                            <?php echo "<span>".$matching_questions['left_side_text']. "</span>"; ?>
                                        </td>
                                        <td>
                                            <?php echo "<span>".$matching_questions['right_side_text']. "</span>"; ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>   
                                </tbody>
                            </table>
                    <?php } ?>
                <?php if($row['question_type_id'] != 3){ ?>
                </div>
                <?php } ?>
                
                <!--</div>-->
                <!--<hr>-->
                <?php } }else{ ?>
                    <div class="text-center p-4">
                        <i class="fas fa-poll-h" style="font-size:250px;color:#0073b7;text-shadow:2px 11px 8px #ccc;"></i>
                        <h2><b>No Result Found</b></h2>
                        <a href="<?php echo base_url(); ?>student/assessment/assessment_result" style="color:blue;"> <b>Go To Results Page <i class="fas fa-long-arrow-alt-right"></i></b></a>
                    </div>
                <?php } ?>
                <hr class="style-eight">
                <p class="text-center">
                    <b>THE END</b>
                </p>
            </div>
        </div>
    </div>
</div>



<script>
    // function printContent(el){
    //     var restorepage = $('body').html();
    //     var printcontent = $('#' + el).clone();
    //     $('body').empty().html(printcontent);
    //     window.print();
    //     $('body').html(restorepage);
    // }
    
    function printContent(divName) {
        var panel = document.getElementById(divName);
        var printWindow = window.open('', '', '');
        printWindow.document.write('<html><head><title>Print Invoice</title>');
        
        // Make sure the relative URL to the stylesheet works:
        // printWindow.document.write('<base href="' + location.origin + location.pathname + '">');
        
        // Add the stylesheet link and inline styles to the new document:
        printWindow.document.write('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">');
        printWindow.document.write('<style type="text/css">#questionPaper {margin: 0;border: initial;border-radius: initial;width: initial;min-height: initial;box-shadow: initial;background: initial;page-break-after: always;}.headHeadings{font-family: fantasy;color:red !important;}.panel-body {padding: 5px!important;}h4.panel-title p {color: black !important;font-size: 16px !important;display: inline-block;}.headingBox{margin-top:-15px;}.panel-title{margin-top:10px;}hr.line1 {border: 0;height: 1px;background: #333 !important;margin-bottom: 7.1mm;}hr.style-eight {overflow: visible;padding: 0;border: none;border-top: medium double #333;color: #333;text-align: center; width: 100% !important;}hr.style-eight:after {content: "§";display: inline-block;position: relative;top: -0.7em;font-size: 1.5em;padding: 0 0.25em;background: white;}</style>');
        
        printWindow.document.write('</head><body >');
        printWindow.document.write(panel.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        setTimeout(function () {
            printWindow.print();
        }, 500);
        return false;
    }
    
//     function printContent(divName) {
//      var printContents = document.getElementById(divName).innerHTML;
//      var originalContents = document.body.innerHTML;

//      document.body.innerHTML = printContents;

//      window.print();

//      document.body.innerHTML = originalContents;
// }
</script>