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
        <h3 class="system_name inline">
            <?php echo get_phrase('sections');?>
        </h3>
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
    </div>
</div>
<form action="<?php echo base_url(); ?>departments/section_listing" class="validate" method="post" data-step="2" data-position='top' data-intro="select department & class to get record specific sections record">
    <div class="row filterContainer">
        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
            <select onchange="get_class()" name="department_id" id="loc_country" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                <?php echo department_option_list($this->input->post('department_id'));?>
            </select>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
            <select name="class_id" id="class_id" class="form-control">
                <option value=''> <?php echo get_phrase('select_class');?></option>
            </select>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <input type="submit" value="<?php echo get_phrase('filter');?>" class="btn btn-primary" id="btn_submit">
            <a href="<?php echo base_url(); ?>departments/section_listing" <?php $val_val=$this->input->post('department_id');
                $val_class=$this->input->post('department_id');
                if((isset($val_val) && $val_val!="") || (isset($val_class) && $val_class!="")){
                }else{ ?> style="display:none;" <?php } ?> class="btn btn-danger" id="btn_remove"><i class="fa fa-remove"></i><?php echo get_phrase('remove_filter');?>
            </a>
        </div>
    </div>
</form>
<div class="col-lg-12 col-sm-12">
    <table class="table table-bordered cursor table_export table-responsive" data-step="3" data-position='top' data-intro="sections records">
        <thead>
            <tr>
                <th style="width:10px !important;">
                    <div>S#</div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('section_detail');?>
                    </div>
                </th>
                <th style="width:94px;">
                    <div>
                        <?php echo get_phrase('options');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
                $j=0;
                foreach($students as $row):
                    $j++;
            ?>
            <tr>
                <td style="width:44px;" class="td_middle">
                    <?php echo $j;?>
                </td>
                <td>
                    <ul class="breadcrumb myttl" style="    display: inline;  padding: 3px;    margin-left: 0px;    color: #428abd;">
                        <li>
                            <?php echo $row['dpp_title']; ?>
                        </li>
                        <li>
                            <?php echo $row['class_name']; ?>
                        </li>
                        <li>
                           <?php echo $row['title'];?>
                        </li>
                         
                    </ul>
                    <div>
                        <strong><?php echo get_phrase('teacher');?>: </strong>
                        <?php
                            echo $row['teacher_name'];
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
                    <div>
                        <?php   
                            $q2="select s.name as subject, s.code as subject_code, ss.subject_id,ss.section_id,ss.periods_per_week,ss.periods_per_day from ".get_school_db().".subject_section ss inner join ".get_school_db().".subject s on s.subject_id=ss.subject_id where ss.school_id=".$_SESSION['school_id']." AND section_id=".$row['section_id']." order by s.subject_id ASC ";
                            $selected=$this->db->query($q2)->result_array();
                        ?>
                        <!--  <ul  class="sub_list">-->
                        <div> <strong><?php echo get_phrase('subject');?>: </strong><span data-toggle="collapse" data-target="#demo<?php echo $row['section_id']; ?>" data-step="4" data-position='top' data-intro="press this button to see all subjects that are assigned to this section">
                            <a class="btn-sm"><i class="fa fa-eye" aria-hidden="true"></i><?php echo get_phrase('view_asigned_subjects');?>   </a> </span>
                            <a href="#" data-step="5" data-position='top' data-intro="press this button to assign subjects this section" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_section_subject/<?php echo $row['section_id'];?>');">
                                <i class="fas fa-plus"></i>
                                <?php echo get_phrase('Assign_subjects');?>
                            </a>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <table class="table table-bordered table-responsive mytable2 collapse" id="demo<?php echo $row['section_id']; ?>">
                                <tr style="text-align: center; font-weight:bold;">
                                    <td> <?php echo get_phrase('subject');?></td>
                                    <td colspan="2"><?php echo get_phrase('periods_per_week');?></td>
                                    <td colspan="2"><?php echo get_phrase('periods_per_day');?></td>
                                </tr>
                                <tr style="text-align: center; font-weight:bold;">
                                    <td> </td>
                                    <td><?php echo get_phrase('assigned');?></td>
                                    <td><?php echo get_phrase('total');?></td>
                                    <td><?php echo get_phrase('assigned');?></td>
                                    <td><?php echo get_phrase('total');?></td>
                                </tr>
                                <?php foreach($selected as $sel){?>
                                <tr>
                                    <td>
                                        <?php echo $sel['subject'].' - '.$sel['subject_code']; ?> </td>
                                    <td class="text-center">
                                        <?php echo  (subject_count_class_routine_week($sel['subject_id'],$sel['section_id'])?subject_count_class_routine_day($sel['subject_id'],$sel['section_id']):0) ; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $sel['periods_per_week'] ; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo (subject_count_class_routine_day($sel['subject_id'],$sel['section_id'])?subject_count_class_routine_day($sel['subject_id'],$sel['section_id']):0) ;  ?> </td>
                                    <td class="text-center">
                                        <?php echo $sel['periods_per_day'] ; ?> </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                        <!--  </ul>-->
                    </div>
                    <div>
                        <span>
                        <?php echo '<span class="item">'.$row['discription'].'</span'; ?>
                        </span>
                    </div>
                </td>
                <td class="td_middle">
                <?php 
                if (right_granted(array('sections_delete', 'sections_manage')))
                {?>
                    <div class="btn-group" data-step="6" data-position='left' data-intro="section delete / edit option">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                            <?php echo get_phrase('action');?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                            <?php 
                            if (right_granted('sections_manage'))
                            {?>
                                <li>
                                    <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/section_add_edit/<?php echo $row['section_id'];?>');">
                                        <i class="entypo-pencil"></i>
                                        <?php echo get_phrase('edit');?>
                                    </a>
                                </li>
                            <?php 
                            }
                            if (right_granted('sections_delete'))
                            {?>
                                <li class="divider"></li>
                                <li>
                                    <a href="#" onclick="confirm_modal('<?php echo base_url();?>departments/section_listing/delete/<?php echo $row['section_id']; ?>');">
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

<script>
function get_class() {
    $('#r_load').remove();
    var department_id = $('#loc_country').val();    var class_id = "<?php echo $class_id; ?>";
    $('#loc_country').after("<div id='r_load' class='loader_small'></div>")
    if (department_id == "") {
        $('#class_id').html("<option value=''><?php echo get_phrase('select_class'); ?></option>");
        $('#r_load').remove();
    } else {
        $.ajax({
            type: 'POST',
            data: {
                department_id: department_id,
                class_id: class_id
            },
            url: "<?php echo base_url();?>departments/get_class_r",
            dataType: "html",
            success: function(response) {
                $('#r_load').remove();
                $('#class_id').html(response);
            }
        });
    }
}

$(document).ready(function() {
    get_class();
});

<!--Datatables Add Button Script-->
<?php if(right_granted('sections_manage')){ ?>
    var datatable_btn_url = 'showAjaxModal("<?php echo base_url();?>modal/popup/section_add_edit/")';
    var datatable_btn = "<a href='javascript:;' onclick="+datatable_btn_url+" data-step='1' data-position='left' data-intro='Press this button to add new section' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_section');?></a>";    
<?php } ?>
</script>
