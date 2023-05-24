<style>
    .sepration1 {
        font-size: 11px;
        color: #000;
        font-weight: normal;
    }

    .sepration2 {
        font-size: 11px;
        color: #000;
        font-weight: normal;
    }

    .sep {
        color: #4685b6;
        padding-right: 15px;
    }

    .validate-has-error {
        color: red;
    }

    .sepr {
        margin-bottom: 10px;
        margin-top: 10px;
    }
</style>
<?php if(sizeof($timetable)>0){?>
<table class="table table-bordered datatable" id="table_export">
    <thead>
        <tr>
            <th style="width:20px;">
                <div>
                    <?php echo get_phrase('s_no');?>
                </div>
            </th>
            <th>
                <div>
                    <?php echo get_phrase('timetable_details');?>
                </div>
            </th>
            <th style="width:95px;">
                <div>
                    <?php echo get_phrase('options');?>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php 
                      $i=1;
          foreach($timetable as $timetable_detail)
          {
            ?>
        <tr>
            <td>
                <?php echo $i++;?>
            </td>
            <td>
                <div class="myttl col-sm-12">
                    <?php echo $timetable_detail['academic_year'] ; ?>
                    <span class="sepration1">
              
            <?php echo  '('.convert_date($timetable_detail['academic_year_start_date']).' to '.convert_date($timetable_detail['academic_year_end_date']).')' 
              ; ?>
               </span>
                    <?php echo  $timetable_detail['term'] ; ?>
                    <span class="sepration2">
               <?php echo '('.convert_date($timetable_detail['term_start_date']).' to '.convert_date($timetable_detail['term_end_date']).')'  ;?>
                           
                           </span>
                </div>
                <div class="col-sm-12 sepr">
                    <strong><?php echo get_phrase('department');?> / <?php echo get_phrase('class');?> / <?php echo get_phrase('section');?>: </strong>
                    <ul class="breadcrumb" style="    display: inline;  padding: 3px;    margin-left: 5px;    color: #428abd;">
                        <li>
                            <?php echo $timetable_detail['department'] ;?>
                        </li>
                        <li>
                            <?php echo $timetable_detail['class'] ;?>
                        </li>
                        <li>
                            <?php echo $timetable_detail['section'] ;?>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-4"> <span class="sep">  <?php echo get_phrase('start_time');?>: </span>
                    <?php echo $timetable_detail['start_time']?> </div>
                <div class="col-sm-4"> <span class="sep">  <?php echo get_phrase('assembly_duration');?>: </span>
                    <?php echo $timetable_detail['assembly_duration'];?> <?php echo get_phrase('minutes');?></div>
                <div class="col-sm-4"> <span class="sep"> <?php echo get_phrase('total_number_of_periods');?>: </span>
                    <?php echo $timetable_detail['no_of_periods'];?>
                </div>
                <div class="col-sm-4"> <span class="sep">  <?php echo get_phrase('period_duration');?>: </span>
                    <?php echo $timetable_detail['period_duration'];?> <?php echo get_phrase('minutes');?> </div>
                <?php /*/End Time: .$timetable_detail['end_time']; */?>
                <div class="col-sm-4">
                    <span class="sep">    <?php echo get_phrase('break_after_period_number');?>: </span>
                    <?php echo $timetable_detail['break_after_period'];?>
                </div>
                <div class="col-sm-4"> <span class="sep">  <?php echo get_phrase('break_duration');?>:</span>
                    <?php echo $timetable_detail['break_duration'];?> <?php echo get_phrase('minutes');?></div>
            </td>
            <td>
                <?php if(get_term_status($timetable_detail['yearly_terms_id'])!=1){?>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                        <?php echo get_phrase('action');?> <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                        <!-- STUDENT EDITING LINK -->
                        <?php if(right_granted('time_table_settings_edit')){ ?>
                        <li>
                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_class_routine_edit/<?php echo $timetable_detail['c_rout_sett_id']; ?>');">
                                <i class="entypo-pencil"></i>
                                <?php echo get_phrase('edit');?>
                            </a>
                        </li>
                        <?php }?>
                        <?php if(right_granted('time_table_settings_delete')){ ?>
                        <li class="divider"></li>
                        <!-- STUDENT DELETION LINK -->
                        <li>
                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>time_table/class_routine_settings/delete/<?php echo $timetable_detail['c_rout_sett_id']; ?>');">
                                <i class="entypo-trash"></i>
                                <?php echo get_phrase('delete');?>
                            </a>
                        </li>
                        <?php }?>
                    </ul>
                </div>
                <?php }?>
            </td>
        </tr>
        <?php }
                        ?>
    </tbody>
</table>
<?php }else{ echo get_phrase('no_records_to_display');
}?>
<script>
$(document).ready(function() {
    $('#table_export').DataTable({

        "aLengthMenu": [
            [10, 25, 50, -1],
            [10, 25, 50, "All"]
        ],
        "bStateSave": true
    });


});

$(".dataTables_wrapper select").select2({


    minimumResultsForSearch: -1


});
</script>
