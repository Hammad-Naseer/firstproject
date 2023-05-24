
 <?php 
$q="SELECT c.*,c.order_by as order_by,d.title as designation,d.is_teacher as is_teacher,s.name as staff_name FROM ".get_school_db().".class c 
LEFT JOIN ".get_school_db().".staff s ON c.teacher_id=s.staff_id
LEFT JOIN ".get_school_db().".designation d ON (s.designation_id=d.designation_id and d.is_teacher=1) 
where c.school_id=".$_SESSION['school_id']." $search_ary";
$class=$this->db->query($q)->result_array(); ?>  

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
$(window).load(function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
<?php

//print_r($class);
//exit;
?>


    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered datatable cursor table-hover" id="admin_class">
                <thead>
                    <tr>
                        <th style="width:34px;">
                            <div>#</div>
                        </th>
                        <th>
                            <div>
                         <?php echo get_phrase('class_details');?>
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
                        
                        $j=0;
                        
                        
                        foreach($class as $row):
                       $j++;?>
                    <tr>
                        <td>
                            <?php echo $j; ?>
                        </td>
                        <td>
                            <div class="myttl">
                                <?php echo $row['name'];
                            if($row['name_numeric']!="")
                            {
                                echo ' ('.$row['name_numeric'].')';
                            }?>
                            </div>
                            <div><strong><?php echo get_phrase('teacher'); ?>: </strong>
                                <?php
                            echo $row['staff_name'];
                            // echo $this->crud_model->get_type_name_by_id('staff',$row['teacher_id']);
                            if($row['designation']!="")
                            {
                                echo " (".$row['designation'].") ";  
                            }
                            if($row['is_teacher']==1)
                            {
                                echo " (Teaching staff) ";
                            } 
                            
                            ?>
                            </div>
                            <div><strong>
						<?php echo get_phrase('department'); ?> : </strong>
                                <?php

$ary_data=array('departments_id'=>$row['departments_id'],'school_id'=>$_SESSION['school_id']);

  $rec_data=$this->db->get_where(get_school_db().'.departments',$ary_data)->result_array();
  
  echo $rec_data[0]['title']; 
  
  ?>
                            </div>
                            <div><strong><?php echo get_phrase('detail'); ?> : </strong>
                                <?php echo $row['description'];?> </div>
                        </td>
                        <td>
                        <?php 
                        if (right_granted(array('classes_manage', 'classes_delete')))
                        {?>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <?php echo get_phrase('action'); ?><span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    <!-- EDITING LINK -->
                                    <?php
                                    if (right_granted('classes_manage'))
                                    {
                                    ?>
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_class/<?php echo $row['class_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                    <?php
                                    }
                                    if (right_granted('classes_delete'))
                                    {?>
                                    <li class="divider"></li>
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>departments/classes/delete/<?php echo $row['class_id'];?>');">
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
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content panel panel-primary" data-collapsed="0">
                <div class="panel-heading modal-header" style="border-bottom:1px solid #EEE !important;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="panel-title black2">
                        <i class="entypo-plus-circled"></i>
                        <?php echo get_phrase('add_class');?>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="box-content">
                        <?php echo form_open(base_url().'departments/classes/create' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    <?php echo get_phrase('departments');?> <span class="star">*</span></label>
                                <div class="col-sm-8">
                                    <select name="departments_id" class="form-control" style="width:100%;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                        <option value="">
                                        <?php echo get_phrase('select_department'); ?>
                                        </option>
                                        <?php 

$this->db->where('school_id',$_SESSION['school_id']);               
$teachers = $this->db->get(get_school_db().'.departments')->result_array();
foreach($teachers as $row):
?>
                                        <option value="<?php echo $row['departments_id']; ?>">
                                            <?php echo $row['title'];?>
                                        </option>
                                        <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    <?php echo get_phrase('name');?> <span class="star">*</span></label>
                                <div class="col-sm-8">
                                    <input maxlength="100" type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                   <?php echo get_phrase('short_name');?>
                                </label>
                                <div class="col-sm-8">
                                    <input maxlength="20" type="text" class="form-control" name="name_numeric" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    <?php echo get_phrase('teacher');?>
                                </label>
                                <div class="col-sm-8">
                                    <select name="teacher_id" class="form-control" style="width:100%;">
                                        <option><?php echo get_phrase('select_teacher'); ?></option>
                                        <?php 

$this->db->where('school_id',$_SESSION['school_id']);               
$teachers = $this->db->get(get_school_db().'.staff')->result_array();
print_r($teachers);
foreach($teachers as $row):
?>
                                        <option value="<?php echo $row['staff_id']; ?>">
                                            <?php echo $row['name'];?>
                                        </option>
                                        <?php
                                        endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">
                                    <?php echo get_phrase('detail');?>
                                </label>
                                <div class="col-sm-8">
                                    <textarea id="description1" maxlength="1000" oninput="count_value('description1','description_count1','1000')" class="form-control" name="description"></textarea>
                                    <div id="description_count1" class="col-sm-12"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                <input type="submit" class="btn btn-info" value="<?php echo get_phrase('add_class');?>"></input>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $("#btn_submit").click(function(e) {



        $(".err_div").html("");
        if ($("#loc_country").val() == "") {
            e.preventDefault();
            $(".err_div").html("<?php echo get_phrase('please_select_department'); ?>");

        }



    });






    $("#loc_country").change(function() {


        $(".err_div").html("");


    });
    </script>
    <script>
    $(document).ready(function() {
        //$('#admin_class').DataTable();
        tableContainer = $("#admin_class");
        tableContainer.dataTable({
            "sPaginationType": "bootstrap",
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "bStateSave": true,
            // Responsive Settings
            bAutoWidth: false,
            fnPreDrawCallback: function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper) {
                    responsiveHelper = new ResponsiveDatatablesHelper(tableContainer, breakpointDefinition);
                }
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                responsiveHelper.createExpandIcon(nRow);
            },
            fnDrawCallback: function(oSettings) {
                responsiveHelper.respond();
            }
        });

        $(".dataTables_wrapper select").select2({
            minimumResultsForSearch: -1
        });
    });
    </script>
