<style>
.pd0 {
    padding: 0px !important;
}

.system_name {
    margin: 0px !important;
}
</style>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
            <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
                <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
            </a>
            <h3 class="system_name"> <?php echo get_phrase('attendance_app_creds');?> </h3>
        </div>
    </div>
    <div class="row" >
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-body" data-step="1" data-position='bottom' data-intro="create attendance app credetials for attendance app">
                    <?php
                    if(count($attendance_creds) > 0){
                        $action = base_url()."user/attenndance_app_creds/update";
                        $btn_text = "update";
                        $info = "credentials already created";
                    }else{
                        $action = base_url()."user/attenndance_app_creds/save";
                        $btn_text = "save";
                        $info = "";
                    }
                    ?>
                    <span style="color:red; float:right;"><?php echo $info;?></span>
                    <form action="<?php echo $action;?>" id="disable_submit_btn" class="form-horizontal form-groups-bordered validate" method="post" accept-charset="utf-8" novalidate="novalidate">
                    
                        <div class="form-group">
                            <label for="field-1" class="control-label">User Name<span class="star">*</span></label>
                            <input type="hidden" name="id" value="<?php echo $attendance_creds->id; ?>">
                            <input type="text" class="form-control" name="username" value="<?php echo $attendance_creds->username; ?>" required="">
                        </div>
                        <div class="form-group">
                            <label for="field-2" class="control-label">Password<span class="star">*</span></label>
                            <input type="password" class="form-control" name="password" required="">
                        </div>
                            <div class="form-group">
                            <label for="field-1" class="control-label">Status</label>
                            <select name="status" id="status" class="form-control" required="">
                            	<option value="1">Active</option>
                            	<option value="0">Incactive</option>
                            </select>
                        </div>	
                        <div class="form-group">
                            <div class="float-right">
                            	<button type="submit" class="modal_save_btn"><?php echo $btn_text; ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

