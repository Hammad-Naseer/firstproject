<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>

<?php
if (right_granted('staff_manage'))
{
$school_id=$_SESSION['school_id'];
$param2=$this->uri->segment(3);
$edit_data=$this->db->get_where(get_school_db().'.deposit' , array('deposit_id' => $param2,'school_id'=>$school_id))->result_array();

    $account_type = 0;
    $location_id = 0;
    $country_id = 0;
    $province_id = 0;
    $city_id = 0;

   /* if(count($edit_data)>0) {
        $location_id = $edit_data[0]['location_id'];
        $array_address = get_country_edit($location_id);
        $country_id = $array_address[0]['country_id'];
        $province_id = $array_address[0]['province_id'];
        $city_id = $array_address[0]['city_id'];
    }*/
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <h3 class="system_name inline">
            <?php if(empty($param2))echo get_phrase('Add_deposit');
			else echo get_phrase('Edit_deposit');?>
        </h3>
    </div>
</div>
<a href="<?php echo base_url();?>deposit/deposit_listing" class="btn btn-primary"><?php echo get_phrase('back'); ?></a>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body">
                <?php echo form_open(base_url().'deposit/deposit_listing/add_edit/' , array('class' => 'form-horizontal form-groups-bordered validate',
                    'enctype' => 'multipart/form-data' , 'id'=>'deposit'));?>
                <input type="hidden" class="form-control" name="deposit_id" value="<?php echo $edit_data[0]['deposit_id'] ?>">


                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label">
                        <?php echo get_phrase('depositors');?><span class="star">*</span></label>
                    <div class="col-sm-5">


                        <?php
                        depositors('depositor_id' ,$edit_data[0]['depositor_id']);
                        ?>


                    </div>
                </div>

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label">
                        <?php echo get_phrase('date');?><span class="star">*</span></label>
                    <div class="col-sm-5">

                        <input id="deposit_date" name="deposit_date" class="form-control datepicker"
                               value="<?php echo date_dash($edit_data[0]['deposit_date']);?>" placeholder="Select Deposit Date" data-validate="required"
                               data-format="dd/mm/yyyy" />


                    </div>
                </div>





                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label">
                        <?php echo get_phrase('amount');?><span class="star">*</span></label>
                    <div class="col-sm-5">
                        <input maxlength="15" type="text" class="form-control" id="amount" name="amount"
                               data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"
                               value="<?php echo $edit_data[0]['amount'] ?>" autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label">
                        <?php echo get_phrase('title');?><span class="star">*</span></label>
                    <div class="col-sm-5">
                        <input maxlength="15" type="text" class="form-control" id="title" name="title"
                               data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data[0]['title'] ?>" autofocus>
                    </div>
                </div>













                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label">
                        <?php echo get_phrase('description');?></label>
                    <div class="col-sm-5">
                        <textarea cols="20" rows="7" class="form-control" name="description" id="description" autofocus><?php echo $edit_data[0]['description'] ?></textarea>

                    </div>
                </div>







                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label">
                        <?php echo get_phrase('status'); ?><span class="star">*</span></label>
                    <div class="col-sm-5">
                        <?php $status_array = array(''=>get_phrase('select') , '1'=>get_phrase('submit') , '0'=>get_phrase('save'));
                             $class = "class = form-control data-validate=required id = status";
                         echo form_dropdown('status', $status_array, $edit_data[0]['status'], $class);
                        ?>

                    </div>
                </div>







                <div class="form-group">
                    <label for="field-2" class="col-sm-3 control-label">
                       
                    </label>
                    <div class="col-sm-5">
                        <button id="btn-dsb" type="submit" class="btn btn-default">
                        <?php echo get_phrase('save'); ?>
                        </button>
                    </div>
                </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<script>
$(window).on("load",function() {
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 3000);
});
</script>
<?php
}
?>
<script>

    $(document).ready(function(){
        $("#deposit").submit(function()
        {
            var check = false;
            var status  = $("#status").val();


          if(status == '1')
          {
              if (confirm('Once submitted, deposit detail can not be modified. Are you sure to continue?'))
              {
                  true;
              }
              else
              {
                  return false;
              }
          }
         /* else
          {
              return true;
          }*/


            $('input[type=text],select').not("input[type='hidden']").each(function(key, element)
            {

                var v =  $.trim(this.value);
                // alert(v.length);

                if( $.trim($(this).val()).length == 0)
                {
                    check = true;
                }
            });

            if(check == false)
            {

                $(this).find(':submit').attr('disabled','disabled');
               // alert('Add Depositor form submitted');
            }



        });
    });

</script>
