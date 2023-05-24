
<table class="table table-bordered datatable  table-hover  cursor" id="admin_departments_listing">
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
        $this->db->order_by('order_num');
$students=$this->db->get_where(get_school_db().'.departments',$data_)->result_array();
$j=0;
                foreach($students as $row){
                $j++;?>
        <tr>
            <td>
                <?php echo $j;?>
            </td>
            <td>
                <div class="myttl">
                    <?php echo $row['title'].' ('.$row['short_name'].')';?>
                </div>
                <div><strong>
                <?php echo get_phrase('department_head');?>
                : </strong>
                    <?php $head_id= $row['department_head'];
            
            
$rec_dep=$this->db->query("select s.*,d.title as designation,d.is_teacher as is_teacher from ".get_school_db().".staff s LEFT JOIN ".get_school_db().".designation d ON s.designation_id=d.designation_id where s.staff_id=$head_id and s.school_id=".$_SESSION['school_id']."")->result_array();
       
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
                <div><strong><?php echo get_phrase('description');?>: </strong><span class="item"><?php echo $row['discription'];?></span></div>
            </td>
            <td>
            <?php 
            if (right_granted(array('departments_manage', 'departments_delete')))
            {?>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        <?php echo get_phrase('action');?> <span class="caret"></span>
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



