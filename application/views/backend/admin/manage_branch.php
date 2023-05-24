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
            <h3 class="system_name inline capitalize">
                <?php echo get_phrase('branches'); ?> 
            </h3>
        </div>
        
    </div>
    <table class="table table-bordered table_export" id="" data-step="2" data-position="top" data-intro="branches record">
        <thead>
            <tr>
                <th style="width:50px;">
                    <div>
                        <?php echo get_phrase('sr#');?>
                    </div>
                </th>
                <th style="width:100px;">
                    <div>
                        <?php echo get_phrase('photo');?>
                    </div>
                </th>
                <th>
                    <div>
                        <?php echo get_phrase('Detail');?>
                    </div>
                </th>
                <th style="width:100px;">
                    <div>
                        <?php echo get_phrase('options');?>
                    </div>
                </th>
            </tr>
        </thead>
        <tbody>
        <?php

        $q="select sys_sch_id, school_db FROM ".get_system_db().".system_school where parent_sys_sch_id=".$_SESSION['sys_sch_id']." AND sys_sch_id!=parent_sys_sch_id";
        $query=$this->db->query($q)->result_array();
        
        $count = 0;
        foreach($query as $query2)
        {
            $count++;
            $sys_sch_id=$query2['sys_sch_id'];
            $school_db = $query2['school_db'];
            if ($query2['school_db'] == "")
                $school_db = get_school_db();
           
            $q2="select sch.school_id as school_id, sch.name, sch.address, sch.phone, sch.logo as logo, sch.url, sch.email as school_email,sch.folder_name as folder_name,sch.sys_sch_id as sys_sch_id
                 FROM ".$school_db.".school sch WHERE sch.sys_sch_id=".$query2['sys_sch_id']." ";
            $result=$this->db->query($q2)->result_array();  

            foreach($result as $row)
            {
                
            ?>
                <tr>
                    <td class="td_middle"><?php echo $count; ?></td>
                    <td>
                    <?php
                    $folder_name=$row['folder_name'];
                    if($row['logo']!="")
                    {
                    ?>
                           <img class="img-responsive" style="max-width:80px;" src="<?php echo display_link($row['logo'],$folder_name,1);?>" />
                    <?php
                    }
                    ?>
                    </td>
                    <td>
                        <strong><?php echo $row['name'];?></strong>
                        <br>
                        <?php echo $row['address'];?>
                        <br>
                        <?php echo $row['phone'];?>
                        <br>
                        <?php echo $row['url'];?>
                        <br>
                        <?php echo $row['school_email'];?>
                    </td>
                    <td class="td_middle">
                    <?php
                    if (right_granted(array('branches_manage','branches_delete')))
                    {
                    ?>
                        <div class="btn-group" data-step="3" data-position="left" data-intro="branch option: edit / delete / dashboard / assign COA / assign fee types / assign discount list">
                            <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                <?php echo get_phrase('action'); ?>  <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                <?php
                                if (right_granted('branches_manage'))
                                {
                                ?>
                                    <li>
                                        <a href="<?php echo base_url();?>dashboard/home/<?php echo $row['school_id'];?>">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('Dashboard');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="<?php echo base_url();?>branch/branch_edit/<?php echo $row['school_id'].'/'.$row['sys_sch_id'];?>">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('edit');?>
                                        </a>
                                        <!--<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_branch_edit/<?php echo $row['school_id'].'/'.$row['sys_sch_id'];?>');">-->
                                        <!--    <i class="entypo-pencil"></i>-->
                                        <!--    <?php echo get_phrase('edit');?>-->
                                        <!--</a>-->
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_coa_config_edit/<?php echo $row['school_id'].'/'.$row['sys_sch_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('assign_coa');?>
                                        </a>
                                    </li>

                                    <li class="divider"></li>
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_fee_type_config_edit/<?php echo $row['school_id'].'/'.$row['sys_sch_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('assign_fee_type');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_discount_list_config_edit/<?php echo $row['school_id'].'/'.$row['sys_sch_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                            <?php echo get_phrase('assign_discount_list');?>
                                        </a>
                                    </li>

                                <?php
                                }
                                if (right_granted('branches_delete'))
                                {
                                ?>

                                    <!-- teacher DELETION LINK -->
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>branch/branches/delete/<?php echo $row['sys_sch_id'].'-'.$row['school_id'].'/'.$row['folder_name'].'/'.$row['logo'];?>');">
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
                <?php 
            }
        }
        ?>
        </tbody>
    </table>
    <script>
    
    <?php if(right_granted('branches_manage')){ ?>
       var datatable_btn_url = "<?php echo base_url();?>branch/branch_add";
       var datatable_btn     = "<a href='"+datatable_btn_url+"' data-step='1' data-position='left' data-intro='Press this button to add new branch' class='modal_open_btn'><i class='entypo-plus-circled'></i><?php echo get_phrase('add_new_branch');?></a>";    
    <?php } ?>
    
    </script>
