
<?php  
 if($this->session->flashdata('club_updated')){
   echo '<div align="center">
     <div class="alert alert-success alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      '.$this->session->flashdata('club_updated').'
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
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('departments'); ?>
        </h3>
    </div>
</div>
<div class="col-lg-12 col-sm-12">
    <table class="table table-bordered datatable table-hover cursor table_export" data-step="2" data-position='top' data-intro="departments records">
        <thead>
            <tr>
                <th style="width:34px;">
                    <div>
                        <?php echo get_phrase('#');?>
                    </div>
                </th>
                <th style="width:150px;">
                    <div>
                        <?php echo get_phrase('department_detail');?>
                    </div>
                </th>
                <th style="width:93px;">
                    <div>
                        <?php echo get_phrase('options');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php 
            $school_id=$_SESSION['school_id'];
            
            $data_=array('school_id'=>$school_id);
    		$students=$this->db->get_where(get_school_db().'.departments',$data_)->result_array();
    		$j=0;
            foreach($students as $row)
    		{
                $j++;
    		?>
            <tr>
                <td class="td_middle">
                    <?php echo $j;?>
                </td>
                <td>
                    <div class="myttl">
                        <?php echo $row['title'].' ('.$row['short_name'].')';?>
                    </div>
                    <div><strong><?php echo get_phrase('department_head'); ?>: </strong>
                        <?php $head_id= $row['department_head'];
                        $rec_dep=$this->db->query("select s.*,d.title as designation,d.is_teacher as is_teacher from ".get_school_db().".staff s LEFT JOIN ".get_school_db().".designation d ON s.designation_id=d.designation_id where s.staff_id=$head_id and s.school_id=".$_SESSION['school_id']." order by d.designation_id")->result_array();
                        echo  $rec_dep[0]['name'];
            if($rec_dep[0]['designation']!="")
            {
          echo " (".$rec_dep[0]['designation'].") ";  
        }
              
             
             if($rec_dep[0]['is_teacher']==1)
             {
          echo " (Teaching staff) ";
         } 
    ?>
                    </div>
                    <div><strong><?php echo get_phrase('description'); ?>
    : </strong><span class="item"><?php echo $row['discription'];?></span></div>
                </td>
                <td class="td_middle">
                <?php 
                if (right_granted(array('departments_manage', 'departments_delete')))
                {?>
                    <div class="btn-group" data-step="3" data-position='left' data-intro="department delete / edit option">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <?php echo get_phrase('action'); ?>
     <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            <?php 
                            if (right_granted('departments_manage'))
                            {?>
                            <li>
                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/departments_add_edit/<?php echo $row['departments_id'];?>');">
                                    <i class="entypo-pencil"></i>
                                    <?php echo get_phrase('edit');?>
                                </a>
                            </li>
                            <?php
                            } 
                            if (right_granted('departments_delete'))
                            {?>
                            <li class="divider"></li>
                            <!-- STUDENT DELETION LINK -->
                            <li>
                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>departments/departments_listing/delete/<?php echo $row['departments_id']; ?>');">
                                    <i class="entypo-trash"></i>
                                    <?php echo get_phrase('delete');?>
                                </a>
                            </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                <?php
                }
                ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>    
</div>
<script>
function toggle_func(section_id) {
    $.ajax({
        type: 'POST',
        data: {
            section_id: section_id  
        },
        url: "<?php echo base_url(); ?>departments/department_generator",
        dataType: "html",
        success: function(response) {
            $('#naveed' + section_id).show();
            $('#naveed' + section_id).html(response);
        }
    });
}

<!--Datatables Add Button Script-->
<?php if(right_granted('departments_manage')){ ?>

    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/departments_add_edit/")';
    var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new department' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_department');?></a>";    
<?php } ?>

</script>