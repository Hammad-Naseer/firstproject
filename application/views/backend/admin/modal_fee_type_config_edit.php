<?php
$login_type_id = get_login_type_id('branch_admin');

$query="select sch.school_id as school_id, sch.name, sch.address, sch.phone, sch.logo as logo, sch.url, sch.email as school_email,sch.contact_person, sch.designation, sch.slogan, sch.detail, sch.city_id as city_id, sch.school_regist_no,sch.folder_name,sch.country_id as country_id,sch.province_id as province_id,sch.location_id as location_id, ul.user_login_id as user_login_id, ul.name as admin_name, ul.email as admin_email, ul.password, uld.status as status, uld.sys_sch_id
        FROM ".get_school_db().".school sch
        INNER JOIN ".get_system_db().".user_login_details uld ON uld.sys_sch_id=sch.sys_sch_id
        INNER JOIN ".get_system_db().".user_login ul ON ul.user_login_id = uld.user_login_id
        where 
        uld.sys_sch_id = $param3
        and uld.login_type = $login_type_id
        and sch.school_id=".$param2." 
        and uld.user_login_id != ".$_SESSION['user_login_id']."
        ";
$edit_data = $this->db->query($query)->result_array();
//echo $this->db->last_query();
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">

<?php
foreach ($edit_data as $row)
{
    ?>             
   <div class="panel-heading">
        <div class="panel-title">
            <i class="entypo-plus-circled"></i>
            <?php echo get_phrase('assign_fee_types');?>
        </div>
    </div>

    <?php
        $id = $this->uri->segment('4');
        $schl_qur="select * from ".get_school_db().".school where school_id = ".$id."";
        $schl_data = $this->db->query($schl_qur)->result_array();
        //echo "<pre>";
        //print_r($schl_data);
    ?>
    <div class="row">
        <div class="col-md-6 pull-left">
        <?php
            if($schl_data[0]['logo'] != ""){
        ?>
            <img style="max-width:100px;height:100;"
        src="<?php echo base_url();?>uploads/<?php echo $schl_data[0]['folder_name']; ?>/<?php echo $schl_data[0]['logo'];?>"
        class=" img-responsive sch_logo">
        <?php
        }
        ?>
        </div>
        <div class="col-md-6">
        <h4 style="float: left; padding-top: 63px; margin-left: -150;">
            <?php echo $schl_data[0]['name']; ?>
        </h4>
        </div>
    </div>
            
    <div class="panel-body">
    <?php
        echo form_open(base_url().'branch/fee_type_assign/'.$row['school_id'].'/'.$row['user_login_id'] , array('id'=>'branch_edit','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));
    ?>

    <div class="form-group">

    <table class="table table-bordered datatable table-striped dataTable table-condensed" id="admin_fee_types">
    <thead>
        <tr>
            <td style="width:34px;">#</td>
            <td style="width:34px;"><!-- <input type="checkbox" id="checkAll" name=""> --></td>
            <td><?php echo get_phrase('title'); ?></td>
        </tr>
    </thead>
    <?php

        $school_id=$_SESSION['school_id'];

        $fee_qur = "SELECT ft.*, s.name as school_name FROM ".get_school_db().".fee_types ft
        INNER JOIN ".get_school_db().".school_fee_types sft on sft.fee_type_id = ft.fee_type_id
        INNER join ".get_school_db().".school s on 
            ft.school_id = s.school_id
        WHERE sft.school_id = ".$school_id." 
        ORDER BY ft.status desc, ft.title ASC";
        $fee_query = $this->db->query($fee_qur)->result_array();
        
        $is_checked_str = "SELECT school_id,fee_type_id FROM ".get_school_db().".school_fee_types WHERE school_id = ".$id."";
        $is_checked_arr =$this->db->query($is_checked_str)->result_array();
        
        $fee_type_id_arr = array();
        foreach ($is_checked_arr as $key => $value)
        {
            $fee_type_id_arr[]= $value['fee_type_id'];
        }

        $str = 0;
        if (count($fee_type_id_arr)>0)
        {
            $str =  implode(',',$fee_type_id_arr);
        } 

        $fee_type_used_arr = $this->db->query("select * from ".get_school_db().".class_chalan_fee where fee_type_id in(".$str.") and school_id=".$id)->result_array();
        //echo $this->db->last_query();

        
        $fee_type_used_id = array();
        if(count($fee_type_used_arr)>0)
        {
            foreach ($fee_type_used_arr as $key1 => $value1)
            {
                $fee_type_used_id[] = $value1['fee_type_id'];
            }
        }


        $read_only = "";
        $checked="";
        $n=0;
        foreach($fee_query as $row)
        { 
            $read_only = "";
        $n++;
        ?>
        <tr>
            <td>
                <div>
                    <?php echo $n; ?>
                </div>
            </td>
            <td>
                <div>
                    <input type="checkbox" name="fee_type_id[]" value="<?php echo $row['fee_type_id'];?>" <?php 
                            if (in_array($row['fee_type_id'], $fee_type_id_arr)){
                                $checked = "checked";
                                echo $checked;
                            }?> <?php if(in_array($row['fee_type_id'], $fee_type_used_id)){
                                $read_only = "onclick='return false'";
                                echo $read_only;
                                }?>

                            >
                </div>
            </td>
            <td>
                <div class="">
                    <?php echo $row['title'];?>
                        <?php 
                        $stclass = '' ;   
                        if($row['status'] == 1)
                        {
                            $stclass = 'green';
                        }elseif($row['status'] == 0)
                        {
                            $stclass = 'red';
                        }  
                        ?>
                        <span class="<?php echo $stclass; ?>">
                        <?php  echo "(".status_active($row['status']).")";  ?>
                        </span>
                </div>
                <?php
                    $login_type = $_SESSION['login_type'];
                    if($login_type == 1)
                    {
                        if($row['school_id']!= $_SESSION['school_id'])
                        {
                            echo "<div><strong>" .get_phrase('added_by').":</strong> ".$row['school_name']."</div>";
                        }
                    }
                ?>
            </td>
        </tr>
        <?php } ?>
</table>


</div>

<div class="form-group">
    <div class="float-right">
		<button type="submit" id="btn1" class="modal_save_btn">
			<?php echo get_phrase('save');?>
		</button>
		<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
			<?php echo get_phrase('cancel');?>
		</button>
	</div>
</div>
    <?php echo form_close();?>
    <p style="color: red;"><?php echo get_phrase('note')." : ".get_phrase('fee_types_used_by_branch_cannot_be_unchecked');?></p>
</div>    
        
<?php
}
?>
        </div>
    </div>
</div>

<!-- <script>
    $(document).ready(function(){
        $('#checkAll').click(function(){    
            $('input:checkbox').prop('checked', this.checked);    
        });
    });
</script> -->

