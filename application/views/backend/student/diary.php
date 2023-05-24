<style>
.myfst{font-size:15px;color:#0a73b7;font-weight:700}.due{color:#972d2d}.mygrey{color:#a6a6a6}.fa-remove{color:#fff!important}audio,canvas,progress,video{height:28px}.diary-tree li{list-style-type:none;margin:0;padding:10px 5px 0 5px;position:relative}.diary-tree li::after,.diary-tree li::before{content:'';left:-20px;position:absolute;right:auto}.diary-tree li::before{border-left:2px solid #1a7866;bottom:50px;height:100%;top:0;width:1px}.diary-tree li::after{border-top:2px solid #1a7866;height:20px;top:25px;width:25px}.diary-tree li span{-webkit-border-radius:5px;border-radius:2px;color:#fff;display:inline-block;padding:6px 8px;text-decoration:none;cursor:pointer;width:100%;background:#1a7866}.diary-tree>ul>li::after,.diary-tree>ul>li::before{border:0}.diary-tree li:last-child::before{height:27px}.diary-tree li span:hover{background:#586e06;color:#fff!important}.diary-tree li span a{font-size:16px;color:#fff!important}.diary-tree [aria-expanded=false]>.expanded,.diary-tree [aria-expanded=true]>.collapsed{display:none}.diary-tree [data-toggle=collapse]:after{margin-top:4px}.diary-tree{margin-left:-44px}.diary-tree i{font-size:18px!important}.row.dairy-row-set{min-height:562px}.springss{position:absolute;display:flex;left:24.7%;flex-direction:column;z-index:99}.spring1{margin-top:5px}.diary-tree li span{-webkit-border-radius:5px;border-radius:2px;display:inline-block;padding:12px 8px;text-decoration:none;cursor:pointer;width:100%;border:1px solid #cccccca8;font-weight:700;background:#fff;box-shadow:1px 2px 8px 3px #cccccc80}.diary-tree li span a{color:#000!important}.diary-ul>li span{color:#000!important}.diary-ul>li i{color:#000!important}.dairy-row-set td,.dairy-row-set th{font-size:16px;padding:14px 6px!important}.diary-tree li span:hover a{color:#fff!important}.diary-tree li span:hover i{color:#fff!important}
</style>

<div class="dairyfilter">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('class_diary'); ?>  
        </h3>
    </div>
    <?php
        if ($this->session->flashdata('solve_assign')) {
            echo '<div align="center">
            <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            ' . $this->session->flashdata('solve_assign') . '
            </div> 
            </div>';
        }
    ?>
    <script>
        $(window).on("load",function() {
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 3000);
        });
    </script>
    <?php
    $sub_arr = $this->db->query("SELECT sub.* from ".get_school_db().".subject sub
    	                         INNER JOIN ".get_school_db().".subject_section ss on sub.subject_id=ss.subject_id 
    	                         WHERE ss.section_id = ".$_SESSION['section_id']."
     	                         AND ss.school_id = ".$_SESSION['school_id']." ")->result_array();
    ?>
    
    <div class="thisrown pd102 " data-step="1" data-position='top' data-intro="Please use this filter to get specific diary records">
        <form id="subject_filter" method="post" action="<?php echo base_url();?>student_p/diary" class="form-horizontal validate" novalidate>
            <div class="row filterContainer px-2">    
                <div class="col-md-6 col-lg-6 col-sm-6 mt-4" data-step="2" data-position='top' data-intro="type: diary title name">
                 <input type="text" name="std_search" class="form-control" value="<?php if(!empty($std_search)){echo $std_search;}?>">
                </div>       
                <div class="col-md-6 col-lg-6 col-sm-6 mt-4" data-step="3" data-position='top' data-intro="Select subject">
                    <select id="subject_id" name="subject_id" class="form-control" data-message-required="Required">
                        <option value=""><?php echo get_phrase('select_subject'); ?></option>
                        <?php
                        foreach ($sub_arr as $key => $value) 
                        {
                        	$sub_sel = '';
                        	if ($value['subject_id'] == $sub_filter)
                        	{
        						$sub_sel = 'selected';
        					}	
                        	echo "<option value='".$value['subject_id']."' $sub_sel>".$value['name'].' - '.$value['code']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-6 mt-4" data-step="4" data-position='top' data-intro="Select start date">
                    <input type="text" name="starting" autocomplete="off"  id="starting" placeholder="Select Start Date" value="<?php if($start_date > 0){ echo date_dash($start_date); } ?>" class="form-control datepicker" data-format="dd/mm/yyyy">
                    <label style="color: red;" id="sd"></label>
                </div> 
                <div class="col-md-6 col-lg-6 col-sm-6 mt-4" data-step="5" data-position='top' data-intro="Select end date"> 
                    <input type="text" name="ending" autocomplete="off"  id="ending" placeholder="Select End Date" value="<?php if($end_date > 0){ echo date_dash($end_date); } ?>" class="form-control datepicker" data-format="dd/mm/yyyy">
                    <label style="color: red;" id="ed"></label> 
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12  mb-4">
                    <input type="submit" class="btn btn-primary" value="Filter">
                    <?php if($filter) { ?>
                        <a href="<?php echo base_url()?>student_p/diary" class="btn btn-danger">
                            <i class="fa fa-remove"></i><?php echo get_phrase('remove_filters'); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>    
        </form>
    </div>
</div>
<div class="row dairy-row-set">
        <div class="col-sm-12 col-md-12 col-lg-3  diary-left pr-5">
            <h3>Subjects Diary</h3>
            <div class="diary-subjects">
                <?php foreach ($subjects as $row){ ?>
                <div class="diary-tree">
                    <ul class="diary-ul"> 
                        <li onclick="getsubdiary('<?php echo $row['subject_id'];?>')"><span><a style="color:#000; text-decoration:none;" data-toggle="collapse" href="#subjects<?php echo $row['subject_id'];?>" aria-expanded="true" aria-controls="subjects"><i class="collapsed"><i class="fas fa-folder"></i></i>
                            <i class="expanded"><i class="far fa-folder-open"></i></i><?php echo $row['name']; ?> (<?php echo $row['code'];?>) </a></span>
                            <div id="subjects<?php echo $row['subject_id'];?>" class="collapse">
                                <input type="text" class="form-control" style="margin-top: 10px;" id="myInput" onkeyup="search_diary(<?php echo $row['subject_id'] ?>)" placeholder="Search for diary..">
                                <ul class="subjects<?php echo $row['subject_id'];?>" id="subjects<?php echo $row['subject_id'];?>">
                                    
                                </ul>
                            </div>
                        </li>
                    </ul>  
                </div> 
                <?php } ?>
            </div> 
        </div> 
        <div class="springss">
            <?php echo diary_springs(43); ?>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-9 bg-light diary-right  pl-5">
            <h3>Diary Notes</h3>
            <div class="diary-notes">
                <div class="row pl-3 contentdiary"></div> 
            </div>
        </div>
    </div> 

<script>
    $(document).ready(function(){
      $(".page-container").addClass("sidebar-collapsed");
    });
    $("#starting").change(function () {
        var startDate = s_d($("#starting").val());
        var endDate = s_d($("#ending").val());
       
        if ((Date.parse(endDate) < Date.parse(startDate)))
        {
            Command: toastr["warning"]("<?php echo get_phrase('start_date_should_be_less_then_end_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("starting").value = "";
        }
        // else if ((Date.parse(startDate) < Date.parse("<?php echo $_SESSION['session_start_date']; ?>"))) 
        // {
        //     Command: toastr["warning"]("<?php echo get_phrase('please_select_start_date_within_academic_session');?>", "Alert")
               toastr.options.positionClass = 'toast-bottom-right';
        //     document.getElementById("start_date").value = "";      
        // }
    });
    
    $("#ending").change(function () {
        var startDate = s_d($("#starting").val());
        var endDate = s_d($("#ending").val());
        if ((Date.parse(startDate) > Date.parse(endDate))) {
            Command: toastr["warning"]("<?php echo get_phrase('end_date_should_be_greater_than_start_date');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
             document.getElementById("ending").value = "";      
        }else if ((Date.parse(endDate) > Date.parse("<?php echo $_SESSION['session_end_date']; ?>"))) {
            Command: toastr["warning"]("<?php echo get_phrase('please_select_end_date_within_academic_session');?>", "Alert")
            toastr.options.positionClass = 'toast-bottom-right';
            document.getElementById("ending").value = "";    
        }
    });

    function s_d(date){
        var date_ary=date.split("/");
        return date_ary[2]+"-"+date_ary[1]+'-'+date_ary[0]; 
    }
    
    function getsubdiary(sub_id) {
        $.ajax ({ 
           url:"<?= base_url() ?>student_p/getsubdiary",
           type:"POST",
           data: {
               sub_id : sub_id
           },
           cache: false,
           success: function (response) {
               $(".subjects"+sub_id).html(response);
           }
        })
    }
    
    function getdiarycontent(diaryb_id) {
        $.ajax ({ 
           url:"<?= base_url() ?>student_p/getdiarycontent",
           type:"POST",
           data: {
               diaryb_id : diaryb_id
           },
           cache: false,
           success: function (response) {
               $(".contentdiary").html(response);
           }
        });
    }
    
    function search_diary(subject_id) {
      var input, filter, ul, li, a, i, txtValue;
      input = document.getElementById('myInput');
      filter = input.value.toUpperCase();
      ul = document.getElementById("subjects"+subject_id);
      li = ul.getElementsByTagName('li');
    
        // Loop through all list items, and hide those who don't match the search query
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("span")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            }else{
                li[i].style.display = "none";
            }
        }
    } 
</script>