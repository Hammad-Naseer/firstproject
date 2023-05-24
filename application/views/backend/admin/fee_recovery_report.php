
<style>
.myerror {
    color: red !important;
}
.text-success{
    color:green;
}
.text-danger{
    color:red;
}

</style>
    <script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 3000);
    });
    </script>
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
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 myt topbar" style="margin-left: -25px;">
            <h3 class="system_name inline">
                 <?php echo get_phrase('fee_recovery_report');?>
            </h3>
        </div>
    </div>
    <?php 
        $paid_collapse_status;
        $unpaid_collapse_status;
        $segment = $this->uri->segment('2');
        if($segment == "paid_students")
        {
            $paid_collapse_status = "";    
        }else{
            $paid_collapse_status = "collapse";
        }
        
        if($segment == "unpaid_students")
        {
            $unpaid_collapse_status = "";    
        }else{
            $unpaid_collapse_status = "collapse";
        }
        
    ?>
    <div class="col-lg-12 col-sm-12">
        <div class="panel-group" id="accordion-test-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseTwo-22" class="collapsed" style="color:red;" aria-expanded="false">
                            <!--<i class="fas fa-angle-double-down"></i>-->
                            <?php echo get_phrase('paid_students_reports');?>
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo-22" class="panel-collapse <?=$paid_collapse_status?>" aria-expanded="false">
                    <div class="panel-body" style="padding: 5px;">
                        <?php $this->load->view('backend/admin/paid_students'); ?>
                    </div>
                    <div class="panel-footer">
                    
                    </div>
                </div>
            </div>
            <div class="panel panel-default" style="margin-top: 15px;">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapseTwo-2" class="collapsed" style="color:red;" aria-expanded="false">
                            <!--<i class="fas fa-angle-double-down"></i>-->
                            <?php echo get_phrase('unpaid_students_reports');?>
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo-2" class="panel-collapse <?=$unpaid_collapse_status?>" aria-expanded="false">
                    <div class="panel-body" style="padding: 5px;">
                        <?php $this->load->view('backend/admin/unpaid_students'); ?>
                    </div>
                    <div class="panel-footer">
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
