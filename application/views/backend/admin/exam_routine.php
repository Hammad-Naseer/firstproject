
<?php 
if (right_granted(array('managedatesheet_manage')))
{
$resArr='';
$dept_id='';
$class_id='';
$section_id='';
$year='';
$term='';
$urlArr=explode('/',$_SERVER['REQUEST_URI']);
$resArr=explode('-',end($urlArr));

if(sizeof($resArr)>1){
	$dept_id=$resArr[0];
	$class_id=$resArr[1];
	$section_id=$resArr[2];
	$year=$resArr[3];
	$term=$resArr[4];
}

?>
<script>
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('manage_datesheet'); ?>
        </h3>
        <?php if(right_granted('managedatesheet_add')){?>
        <!--<a  href="#" id="add-link" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_add_exam_routine');" class="btn btn-primary pull-right">
			<i class="entypo-plus-circled"></i>
			<?php echo get_phrase('Add exam Schedule');?>
		</a>
-->
        <?php }?>
    </div>
</div>
<div>
    <form id="marks__filter" method="post" class="form-horizontal validate">
        <div class="row filterContainer" data-step="1" data-position="top" data-intro="Please select filters and press filter button to get specific records">
            <div class="col-md-6 col-lg-6 col-sm-6 ">
                <label id="exams_filter_selection"></label>                            
                <select id="exams_filter" name="yearly_terms1" class="selectpicker form-control" data-validate="required" data-message-required="Value Required">	                                              
                <?php
                    $select_year=array(3);
                    echo yearly_term_selector_exam('',$select_year); 
                ?> 
                </select>
                <div class="error-message-exam"></div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-6 ">
                <label id="section_id1_selection"></label>
                <select id="section_id1" class="selectpicker form-control" name="section_id" data-validate="required" data-message-required="Value Required">                          	
                    <?php echo section_selector();?>                            
                </select>
                <div class="error-message-class"></div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12 mt-3">
                <button type="button" id="filter__btn" class="modal_save_btn"><?php echo get_phrase('filter'); ?></button>
                <a href="<?php echo base_url(); ?>exams/exam_routine" style="display: none;" class="modal_cancel_btn" id="btn_remove"> <i class="fa fa-remove"></i><?php echo get_phrase('remove_filter'); ?></a>
            </div>
        </div>
    </form> 
        
  <div class="col-lg-12 col-sm-12 mt-4">
      <div id="exam-routine"></div>
      
  </div>

<script type="text/javascript">
$(document).ready(function() {

    document.getElementById('marks__filter').onsubmit = function() {
        return false;
    };


    $("#filter__btn").click(function() {
       
        if ($("#exams_filter").val() !== "" && $("#section_id1").val() !== "") {
            $('#btn_remove').show();
        }
       
        var exam_id = $("#exams_filter").val();
        var section_id=$("#section_id1").val();
       
        // if (exam_id != '' && section_id != '') {
        //     $("#exam-routine").html("<div id='loading' class='loader'></div>");

        //     $.ajax({
        //         type: 'POST',
        //         data: {
        //             section_id: section_id,
        //             exam_id: exam_id
        //         },
        //         url: "<?php echo base_url();?>exams/get_routine",
        //         dataType: "html",
        //         success: function(response) {
        //             $("#loading").remove();
        //             $("#exam-routine").html(response);
        //         }
        //     });
        //       $(".error-message-exam").html("");
        //       $(".error-message-class").html("");
        // }else if(exam_id == '' ){
            
        //      $(".error-message-exam").html("<span class='red'>Select Exam is Required</span>");
            
        // }
        // else if(section_id == ''){
        //      $(".error-message-class").html("<span class='red'>Select Dep/Class/Sec is Required</span>");
        // }
        
        
        if(exam_id != '')
        {
             $("#exam-routine").html("<div id='loading' class='loader'></div>");
             $(".error-message-exam").html("<span class=''></span>");
            if(section_id != '')
            {
                 $("#exam-routine").html("<div id='loading' class='loader'></div>");
                  $(".error-message-class").html("<span class=''></span>");
                  $.ajax({
                type: 'POST',
                data: {
                    section_id: section_id,
                    exam_id: exam_id
                },
                url: "<?php echo base_url();?>exams/get_routine",
                dataType: "html",
                success: function(response) {
                    $("#loading").remove();
                    $("#exam-routine").html(response);
                }
            });
            }
            
            else{
                $(".error-message-class").html("<span class='red'>Select Dep/Class/Sec is Required</span>");
            }
        }
        else
        {
             $(".error-message-exam").html("<span class='red'>Select Exam is Required</span>");
        }
       
        
     
    });
});

function downloadpdf()
{
    $('#pdfaction').hide();
    var doc = new jsPDF('p', 'pt', 'letter');  
    var htmlstring = '';  
    var tempVarToCheckPageHeight = 0;  
    var pageHeight = 0;  
    pageHeight = doc.internal.pageSize.height;  
    specialElementHandlers = {  
        // element with id of "bypass" - jQuery style selector  
        '#bypassme': function(element, renderer) {  
            // true = "handled elsewhere, bypass text extraction"  
            return true  
        }  
    };  
    margins = {  
        top: 150,  
        bottom: 60,  
        left: 40,  
        right: 40,  
        width: 600  
    };  
    var y = 20;  
    doc.setLineWidth(2);  
    doc.setFontSize(14);
    doc.text(50, 20, '<?php echo $_SESSION['school_name'];?>'+ $('#pdftitle').text());  
    doc.autoTable({  
        html: '#Mypdf',  
        startY: 70,  
        theme: 'grid',  
        columnStyles: {  
            0: {  
                cellWidth: 180,  
            },  
            1: {  
                cellWidth: 180,  
            },  
            2: {  
                cellWidth: 180,  
            }
            
        },  
        styles: {  
            minCellHeight: 40  
        }  
    })  
    doc.save('Mid Term '+ $('#pdftitle').text()+'.pdf');
     $('#pdfaction').show();
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>  

<?php } ?>