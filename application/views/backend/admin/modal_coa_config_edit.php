<style>
    input.form-check-input {
        margin: 0px -20px 0;
    }
</style>
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
$used_coa_ids_temp = array();
$unused_coa_ids_temp = array();

$brance_school_id =  $param2;//$row['school_id'];
$branch_assign_coa_str = "SELECT coa_id FROM ".get_school_db().".school_coa where school_id = $brance_school_id";
$branch_assign_coa_query = $this->db->query($branch_assign_coa_str)->result_array();

//____________branch assigned fee types_____________
$is_checked_str = "SELECT school_id,fee_type_id FROM ".get_school_db().".school_fee_types WHERE school_id = ".$brance_school_id."";
$is_checked_arr =$this->db->query($is_checked_str)->result_array();

$fee_type_id_arr = array();
foreach ($is_checked_arr as $key => $value)
{
    $fee_type_id_arr[]= $value['fee_type_id'];
}
//__________________________________________________

//____________branch assigned discount list_________
$discunt_str = "SELECT school_id,discount_id FROM ".get_school_db().".school_discount_list WHERE school_id = ".$brance_school_id."";
$discunt_arr =$this->db->query($discunt_str)->result_array();
        
$discount_id_arr = array();
foreach ($discunt_arr as $key => $value)
{
    $discount_id_arr[]= $value['discount_id'];
}
//__________________________________________________

foreach ($branch_assign_coa_query as $b_value)
{
  foreach ($fee_type_id_arr as $key => $value)
  {
    $fee_type_qur = "SELECT fee_type_id FROM ".get_school_db().".fee_types where fee_type_id = ".$value." and ( (issue_dr_coa_id = ".$b_value['coa_id'].") or (issue_cr_coa_id = ".$b_value['coa_id'].") or (receive_dr_coa_id =".$b_value['coa_id'].") or (receive_cr_coa_id = ".$b_value['coa_id'].") )";
    $fee_type_arr =$this->db->query($fee_type_qur)->result_array();
    if (count($fee_type_arr)>0)
    {
       $used_coa_ids_temp[] = $b_value['coa_id'];
    }
    else
    {
      $unused_coa_ids_temp[] = $b_value['coa_id'];
    }
  }

  foreach ($discount_id_arr as $key => $d_value)
  {
    $discount_qur = "SELECT discount_id FROM ".get_school_db().".discount_list where discount_id = ".$d_value." and ( (issue_dr_coa_id = ".$b_value['coa_id'].") or (issue_cr_coa_id = ".$b_value['coa_id'].") or (receive_dr_coa_id =".$b_value['coa_id'].") or (receive_cr_coa_id = ".$b_value['coa_id'].") )";
    $discount_arr =$this->db->query($discount_qur)->result_array();
    if (count($discount_arr)>0)
    {
       $used_coa_ids_temp[] = $b_value['coa_id'];
    }
    else
    {
      $unused_coa_ids_temp[] = $b_value['coa_id'];
    }
  }
   
}

$used_coa_ids_temp = array_unique($used_coa_ids_temp);
$parent_coa_id_arr = array();
$parent_coa_id_arr_tmp = array();
foreach ($used_coa_ids_temp as $key => $value)
{
  $parent_coa_id_arr_tmp =  get_coa_parent_list($value);
  $parent_coa_id_arr = array_merge($parent_coa_id_arr,$parent_coa_id_arr_tmp);
}

$used_coa_ids_temp = array_merge($used_coa_ids_temp,$parent_coa_id_arr);
$unused_coa_ids_temp = array_unique($unused_coa_ids_temp);
$common_coa_ids_temp = array_intersect($used_coa_ids_temp,$unused_coa_ids_temp);
$unused_coa_ids_temp = array_diff($unused_coa_ids_temp,$common_coa_ids_temp);
?>
<style>
.form-group{margin:5px;}
.form-control{    margin-bottom: 16px;
margin-top: -6px;}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <?php foreach ($edit_data as $row) { ?>     
           <div class="panel-heading">
                <div class="panel-heading">
                    <div class="panel-title">
                        <i class="entypo-plus-circled"></i>
                        <?php echo get_phrase('assign_chart_of_accounts'); ?>
                    </div>
                </div>
            </div>
           <div class="row">
                <div class="col-md-6 pull-left">
                    <?php if($row['logo'] != ""){ ?>
                        <img style="max-width:100px;height:70px;margin-top:6px;" src="<?php echo base_url();?>uploads/<?php echo $row['folder_name']; ?>/<?php echo $row['logo'];?>" class="img-responsive sch_logo">
                    <?php } ?>
                </div>
                <div class="col-md-6">
                   <h4 style="text-align: center;line-height: 4;"> <?php echo $row['name']; ?> </h4>
                </div>
            </div>
        </div>
        <div class="panel-body">
      <?php echo form_open(base_url().'branch/coa_assign/'.$row['school_id'].'/'.$row['user_login_id'] , array('id'=>'branch_edit','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
        <div class="form-group">
            <?php coa_list_assign(0,0,0,0,0,0, $brance_school_id , $used_coa_ids_temp,$unused_coa_ids_temp); ?>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-5">
                <input type="hidden" name="brach_school_id" value="<?php echo $row['school_id']; ?>" >
                <input id="btn1" type="submit" value="<?php echo get_phrase('save');?>" class="btn btn-info">
            </div>
        </div>
        <?php echo form_close();?>
        <p style="color: red;"><?php echo get_phrase('note')." : ".get_phrase('coa_used_in_fees_or_discounts_by_branch_cannot_be_unchecked');?></p>
    </div>    
    </div>
</div>
<?php } ?>
<script>
    $(document).ready(function(){
        // $('.all_check').click(function(){
        //      $('input:checkbox').prop('checked', this.checked);
        // });
        $('input:checkbox').click(function(){
            if(this.checked){
                $(this).parents().prevUntil('ul').prop('checked',true);
            }
        });
    });
</script>