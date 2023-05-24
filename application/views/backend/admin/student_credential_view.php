
<style>
.myerror{color:red!important}.panel-group .panel>.panel-heading{padding:0 0}.panel-title.pull-left{color:#000;font-weight:600}.top{position:relative;top:30px}
</style>
<?php

    if ( $this->session->flashdata( 'club_updated' ) ) {
        echo '<div align="center">
	<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	' . $this->session->flashdata( 'club_updated' ) . '
	</div> 
	</div>';
    }
    
    if ( $this->session->flashdata( 'error_msg' ) ) {
    	echo '<div align="center">
    	<div class="alert alert-danger alert-dismissable">
    	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    	' . $this->session->flashdata( 'error_msg' ) . '
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
            <h3 class="system_name inline" data-step="1" data-position="top" data-intro="Here you can check students login credentials reports">
                <i class="fas fa-user-circle"></i>
                 <?php echo get_phrase('students_credentials');?>
            </h3>
        </div>
    </div>
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#accordion1" href="#collapseOne" class="">
                <i class="fa fa-file-text-o" aria-hidden="true"></i>
                Student Credentials Reports
              </a>
          </h4>
      </div>
        <div id="collapseOne" class="panel-collapse in" style="height: auto;">
            <div class="panel-body">
            <div class="panel-group" id="accordion2">
                    <div class="row">
                        <div class="col-md-6" data-step="2" data-position="top" data-intro="Please select department and press get report button to get report departmentwise">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title pull-left">Departmentwise</div>
                            </div>
                            <div class="panel-body">
            
                                <form action="<?php echo base_url();?>reports/student_credential_list" method="post" >
                        
                                <div class="col-md-8">
                                  <label>Select Department</label>
                                      <select id="department_id_filter" class="selectpicker form-control" name="section_id">
                                            <?php
                                                echo department_list('',$_SESSION['school_id']);
                                            ?>
                                      </select>
                                </div>
                                <div class="col-md-4 top">
                                      <input style="float: right;" type="submit" id="submit" value="Get Report" class="btn btn-primary">
                                </div>
                                </form>
                            </div>
                            </div>
                        </div>
    
                          <div class="col-md-6" data-step="3" data-position="top" data-intro="Please select class and press get report button to get report classwise">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left">Classwise</div>
                            </div>
                            <div class="panel-body">
                            <form action="<?php echo base_url();?>reports/student_credential_list" method="post">
                            <div class="col-md-8">
                              <label>Select Class</label>
                                  <select id="class_id_filter" class="selectpicker form-control" name="section_id">
                                        <?php
                                            echo class_list('',$_SESSION['school_id']);
                                        ?>
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="Get Report" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                          </div>
                    </div>
    
                    <br>
                    <!-- 1st row -->
                    <div class="row">
                        <div class="col-md-6" data-step="4" data-position="top" data-intro="Please select section and press get report button to get report sectionwise">
                            <div class="panel panel-default">
                            <div class="panel-heading">
                            <div class="panel-title pull-left">Sectionwise</div>
                            </div>
                            <div class="panel-body">
                    
                            <form action="<?php echo base_url();?>reports/student_credential_list" method="post">
                    
                            <div class="col-md-8">
                              <label>Select Section</label>
                                  <select id="section_id_filter" class="selectpicker form-control" name="section_id">
                                    <?php
                                        echo section_list('',$_SESSION['school_id']);
                                    ?>
                                  </select>
                            </div>
                            <div class="col-md-4 top">
                                  <input style="float: right;" type="submit" id="submit" value="Get Report" class="btn btn-primary">
                            </div>
                            </form>
                    
                            </div>
                            </div>
                        </div>
                    </div>
                    <br>
    
                    <!-- 2nd row -->
            </div>

        </div>
        </div>
    </div>
    </div>
    <!-- DATA TABLE EXPORT CONFIGURATIONS -->
    <script type="text/javascript">
    $(document).ready(function() {
        $('#table_export1').DataTable();
    });

    </script>
    <style>
    .loader {
        border: 16px solid #f3f3f3;
        /* Light grey */
        border-top: 16px solid #63b7e7;
        /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }
    
    .loader_small {
        border: 7px solid #f3f3f3;
        border-top: 7px solid #63b7e7;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin-right: auto;
        margin-left: auto;
    }
    
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
    </style>