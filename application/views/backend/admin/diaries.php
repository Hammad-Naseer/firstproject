<style>
audio, canvas, progress, video {
    height: 28px;
}
.diary-tree li{list-style-type:none;margin:0;padding:10px 5px 0 5px;position:relative}.diary-tree li::after,.diary-tree li::before{content:'';left:-20px;position:absolute;right:auto}
.diary-tree li::before{border-left:2px solid #1a7866;bottom:50px;height:100%;top:0;width:1px}.diary-tree li::after{border-top:2px solid #1a7866;height:20px;top:25px;width:25px}
.diary-tree li span{-webkit-border-radius:5px;border-radius:2px;color: white;display:inline-block;padding:6px 8px;text-decoration:none;cursor:pointer;width:100%;background:#1a7866}.diary-tree>ul>li::after,.diary-tree>ul>li::before{border:0}
.diary-tree li:last-child::before{height:27px}.diary-tree li span:hover{background:#586e06;color:white !important;}.diary-tree li span a{font-size:16px;color:#fff!important}.diary-tree [aria-expanded=false]>.expanded,.diary-tree [aria-expanded=true]>
.collapsed{display:none}.diary-tree [data-toggle=collapse]:after{margin-top:4px}.diary-tree{margin-left:-44px}.diary-tree i{font-size:18px!important}.row.dairy-row-set{min-height:700px}
.springss{position:absolute;display:flex;left:24.7%;flex-direction:column;z-index:99}.spring1{margin-top:5px}
.diary-tree li span {
    -webkit-border-radius: 5px;
    border-radius: 2px;
    display: inline-block;
    padding: 12px 8px;
    text-decoration: none;
    cursor: pointer;
    width: 100%;
    border: 1px solid #cccccca8;
    font-weight: 700;
    background: #ffffff;
    box-shadow: 1px 2px 8px 3px #cccccc80;
}
.diary-tree li span a{
    color:black !important;
}.diary-tree li span {
    color: #000;
}
.collapse a.collapsed i.far.fa-file {
    display: none;
}.diary-tree li span:hover a {
    color: #fff !important;
}
.diary-tree li span:hover i.far.fa-file {
    color: white !important;
}
</style>
<?php
    if($this->session->flashdata('club_updated'))
    {
       echo '<div align="center">
         <div class="alert alert-success alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          '.$this->session->flashdata('club_updated').'
         </div> 
        </div>';
    }
?>
<script>
    $(window).on("load",function() 
    {
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
               <?php echo get_phrase('diary'); ?>
        </h3>
    </div>
</div>

<?php
    /*
<form method="post" action="<?php echo base_url();?>teacher/diary" class="form" data-step="2" data-position='top' data-intro="Please select the filters and press Filter button to get specific records">
    <div class="row filterContainer">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                
                <!-- <label id="select_selection"></label>   -->
                <select name="dep_c_s_id" id="dep_c_s_id" class="dcs_list form-control" >
                    <?php echo get_teacher_dep_class_section_list($teacher_section, $section);
                    ?>
                </select>
    		</div>
    	</div>
    			
    	<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <select name="subject" id="subject_list" class="form-control" >
                    <option value=""><?php echo get_phrase('select_subject');?></option>
                </select>	
    		</div>
    	</div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <input type="text" name="std_search" class="form-control" placeholder="Search Diary Title" value="<?php 
                if(!empty($std_search))
                {
        			echo $std_search;
        		}
                ?>">
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <!--<label for="loc_country"><b>Select Country</b></label>-->
                <select id="month_year" name="month_year" class="form-control" >
                <?php
                    $academic_year_id= intval($_SESSION['academic_year_id']);
                    $qur_rr=$this->db->query("select *  from ".get_school_db().".acadmic_year  where school_id=".$_SESSION['school_id']."  and academic_year_id=$academic_year_id ")->result_array();
                    $start_date=$qur_rr[0]['start_date'];
                    $end_date=$qur_rr[0]['end_date'];
                    echo month_year_option($start_date,$end_date,$subject_month_year);
                ?>
                </select>
            </div>
        </div>
    	<div class="col-lg-4 col-md-4 col-sm-4">
            <div class="form-group">
                <input type="submit" name="submit" id="submit_filter" value="<?php echo get_phrase('filter');?>" class="btn btn-primary"/>
                    <?php if($filter) {?>
                        <a href="<?php echo base_url();?>teacher/diary" class="btn btn-danger" >
                            <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters');?>
                        </a>
                    <?php } ?>
                <div id="error_end1" style="color:red"></div>
    		</div>
    	</div>
	
	</div>
</form>
 */ ?>

 <div class="row dairy-row-set">
    <div class="col-sm-12 col-md-12 col-lg-3 diary-left pr-5">
            <h3>Subjects Diary</h3>
            <div class="diary-subjects"> 
                <?php
                foreach ($sections as $row){
                    $section_detail = section_hierarchy($row['section_id']);
                ?>
                <div class="diary-tree">
                    <?php echo $row['section_id'];?>
                    <ul class="diary-ul"> 
                        <li>
                            <span onclick="get_section_subjects('<?php echo $row['section_id'];?>')">
                                <a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#subjects<?php echo $row['section_id'];?>" aria-expanded="true" aria-controls="subjects">
                                    <i class="collapsed">
                                        <i class="fas fa-folder"></i>
                                    </i>
                                    <i class="expanded">
                                        <i class="far fa-folder-open"></i>
                                    </i>
                                    <?php echo $section_detail['d']." / ".$section_detail['c']." / ".$section_detail['s'];?> 
                                </a>
                            </span>
                            <div id="subjects<?php echo $row['section_id'];?>" class="collapse">
                                <ul class="subjects<?php echo $row['section_id'];?>"></ul>
                            </div>
                        </li>
                    </ul>  
                </div> 
                <?php } ?>
            </div> 
        </div> 
    <div class="springss">
            <?php echo diary_springs(54); ?>
        </div>
    <div class="col-sm-12 col-md-12 col-lg-9 bg-light diary-right  pl-5">
            <h3>Diary Notes</h3>
            <div class="diary-notes">
                <div class="row pl-3 contentdiary"> 
                
                </div> 
            </div>
        </div> 
</div> 


                
<script type="text/javascript">
	$(document).ready(function()
	{
    	$('#subject_id').change(function(){
    	    
    	    var	subject_id=$(this).val();
    	    var url = '<?php echo base_url(); ?>teacher/get_subject_student';
    	    $.ajax({
                type: 'POST',url: url,
                data:{subject_id:subject_id},
                dataType : "html",
                success: function(html) {
        	        $('#student_id').html(html);
                }
    	    });
    			
    	});	
    	
    	$('.dcs_list').on('change', function (){
            var id=this.id;
            var selected = jQuery('#'+ id +' :selected');
            var group = selected.parent().attr('label');
            jQuery(this).siblings('label').text(group);
        });
        
        
        $("#dep_c_s_id").change(function()
        {
            var section_id=$(this).val();
            var url = '<?php echo base_url();?>teacher/get_time_table_subject_list';
            $(".loader_small").remove();
            $(this).after('<div class="loader_small"></div>');
            $.ajax({
                type: 'POST',
                data: {section_id:section_id},
                url: url,/*get_diary_subject_list*/
                dataType: "html",
                success: function(response) {
                    $(".loader_small").remove();
                    $("#subject_list").html(response);      
                }
            });
        });
        
        var section_id=$('#dep_c_s_id').val();
        var url = '<?php echo base_url();?>teacher/get_time_table_subject_list/<?php echo $subject_id_selected ?>';
        $.ajax({
            type: 'POST',
            data: {section_id:section_id},
            url: url,
            dataType: "html",
            success: function(response) {
                $(".loader_small").remove();
                $("#subject_list").html(response);      
            }
        });
        
        
	});
	
	
    var datatable_btn_url = '<?php echo base_url();?>teacher/add_edit_diary/';
    var datatable_btn     = "<a href="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new diary' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_diary');?></a>";    

    function get_section_subjects(section_id) {
      $.ajax ({ 
          url:"<?= base_url() ?>c_student/get_section_subjects",
          type:"POST",
          data: {
              section_id : section_id
          },
          cache: false,
          success: function (response) {
              $(".subjects"+section_id).html(response);
          }
      })
    }
    
    function getsubjectdiary(subject_id,section_id) {
       $.ajax ({ 
           url:"<?= base_url() ?>c_student/getsubjectdiary",
           type:"POST",
           data: {
               subject_id : subject_id,
               section_id: section_id
           },
           cache: false,
           success: function (response) {
              $(".subjects_diary"+subject_id).html(response);
           }
       })
    }
    
    function get_diary_data(diary_id) {
    
       $.ajax ({ 
           url:"<?= base_url() ?>c_student/get_diary_data",
           type:"POST",
           data: {
               diary_id : diary_id,
           },
           cache: false,
           success: function (response) {
              $(".contentdiary").html(response);
           }
       })
    }

    function search_diary(subject_id) {
      // Declare variables
      var input, filter, ul, li, a, i, txtValue;
      input = document.getElementById('myInput');
      filter = input.value.toUpperCase();
      ul = document.getElementById("subjects_diary"+subject_id);
      li = ul.getElementsByTagName('li');
    
      // Loop through all list items, and hide those who don't match the search query
      for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByTagName("span")[0];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          li[i].style.display = "";
        } else {
          li[i].style.display = "none";
        }
      }
    }
    
    $(document).ready(function(){
        $(".page-container").addClass("sidebar-collapsed");
    });
</script>
