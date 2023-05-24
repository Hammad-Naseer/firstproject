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
            <?php echo get_phrase('assign_discount_list');?>
        </div>
    </div>
    <?php

        $id = $this->uri->segment('4');
        $schl_qur="select * from ".get_school_db().".school where school_id = ".$id."";
        $schl_data = $this->db->query($schl_qur)->result_array();
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
        <?php echo form_open(base_url().'branch/discount_list_assign/'.$row['school_id'].'/'.$row['user_login_id'] , array('id'=>'branch_edit','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));
        ?>

    <div class="form-group">

<table class="table table-bordered datatable table-condensed table-hover cursor" id="admin_discount_list">
    <thead>
        <tr>
            <th style="width:34px;">
                <div>
                    <?php echo get_phrase('#');?>
                </div>
            </th>
            <td style="width:34px;">
                <!-- <div>
                    <input type="checkbox" id="checkAll" name="">
                </div> -->
            </td>
            <th>
                <div>
                    <?php echo get_phrase('discount_type_detail');?>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php

        $school_id=$_SESSION['school_id'];

        $dis_qur = "SELECT dl.*, s.name as school_name FROM ".get_school_db().".discount_list dl
        INNER join ".get_school_db().".school_discount_list sdl on sdl.discount_id = dl.discount_id
        INNER join ".get_school_db().".school s on 
            dl.school_id = s.school_id
        WHERE sdl.school_id = ".$school_id." 
        ORDER BY dl.status desc, dl.title ASC";
        $discount = $this->db->query($dis_qur)->result_array();

        $is_checked_str = "SELECT school_id,discount_id FROM ".get_school_db().".school_discount_list WHERE school_id = ".$id."";
        $is_checked_arr =$this->db->query($is_checked_str)->result_array();
        
        $discount_id_arr = array();
        foreach ($is_checked_arr as $key => $value)
        {
            $discount_id_arr[]= $value['discount_id'];
        }

        $str = 0;
        if (count($discount_id_arr)>0)
        {
            $str =  implode(',',$discount_id_arr);
        }

        $discount_used_arr = $this->db->query("select * from ".get_school_db().".class_chalan_discount where discount_id in(".$str.") and school_id=".$id)->result_array();
        //echo $this->db->last_query();

        $discount_used_id = array();
        if(count($discount_used_arr)>0)
        {
            foreach ($discount_used_arr as $key1 => $value1)
            {
                $discount_used_id[] = $value1['discount_id'];
            }
        }

        
        $checked="";
        $i= 1 ;
        foreach($discount as $row)
        {
            $read_only = "";
            ?>
        <tr>
            <td>
                <?php echo $i++ ; ?>
            </td>
            <td>
                <div>
                    <input type="checkbox" name="discount_id[]" value="<?php echo $row['discount_id'];?>" <?php 
                            if (in_array($row['discount_id'], $discount_id_arr)){
                                $checked = "checked";
                                echo $checked;
                            }
                            ?> <?php if (in_array($row['discount_id'], $discount_used_id)){
                                $read_only = "onclick='return false'";
                                echo $read_only;
                            } ?>
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
        <?php }?>
    </tbody>
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
    <p style="color: red;"><?php echo get_phrase('note')." : ".get_phrase('discounts_used_by_branch_cannot_be_unchecked');?></p>
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