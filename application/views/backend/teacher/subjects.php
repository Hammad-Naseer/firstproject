

<div class="row lev-reqst-tpbr">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive Tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('subjects'); ?>
        </h3>
    </div>
</div>

    
<div class="col-md-12 table-responsive">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable" id="table_export" data-step="2" data-position='top' data-intro="Subjects record">
            <thead>
                <tr>
                    <th style="width: 34px;!important;">
                        <div><?php echo get_phrase('s_no');?></div>
                    </th>
                    <th>
                        <div>
                            <?php echo get_phrase('subject_name');?>
                        </div>
                    </th>
                    <th>
                        <div>
                            <?php echo get_phrase('syllabus');?>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php $count = 1;foreach($data as $row):?>
                <tr>
                    <td class="td_moddle">
                        <?php echo $count++;?>
                    </td>
                    <td>
                        <?php echo $row['name'] . ' (' . $row['code']. ')'; ?>
                    </td>
                    <td>
                        <?php echo get_subject_sallybus($row['subject_id']); ?>
                    </td>
                </tr>
                <?php endforeach;?>
            </tbody>
        </table>
    </div>

<script>
// var datatable_btn = "<a href='javascript:;' data-toggle='modal' data-target='#myModal' data-step='1' data-position='left' data-intro='press this button open popup then submit your leave request' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('request_a_leave');?></a>";
</script>