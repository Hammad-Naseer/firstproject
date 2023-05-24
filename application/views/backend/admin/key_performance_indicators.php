<style>.no-print{position:relative;top:15px;z-index:99999;border:2px solid #fff;border-radius:20px 20px 20px 0;box-shadow:1px 0 5px 1px #ccc;outline:0;padding-left: 30px;padding-right: 30px;}</style>
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
        <h3 class="system_name inline">
            <?php echo get_phrase('key_performance_indicators'); ?>
        </h3>
    </div>
</div>

<div class="row">
    <form action="<?php echo base_url().'reports/key_performance_indicators/'?>" style="display: inline;" method="post" name="key_section_wise_paid_form" id="key_section_wise_paid_form" data-step="1" data-position="top" data-intro="use this filter to get specific Fee Summary records">
        <button class="modal_save_btn no-print" type="submit" id="key_section_wise_paid_pdf"><?php echo get_phrase('get_pdf');?></button>
    </form>
    <form action="<?php echo base_url().'reports/key_performance_indicators/'?>" style="display: inline;" method="post" name="key_section_wise_paid_form" id="key_section_wise_paid_form" data-step="1" data-position="top" data-intro="use this filter to get specific Fee Summary records">
        &nbsp;&nbsp;
        <button class="modal_save_btn no-print" style="background:#008000e3 !important;" type="button" id="btnExport"><?php echo get_phrase('get_excel');?></button>
    </form>
    <div class="col-lg-12 col-sm-12 topbar thisrow" style="border: 1px solid #cccccc9c !important;">
        <table class="table table-bordered table-hover" data-step="1" data-position='top' id="table_export_exp" data-intro="Key Performance Indicators ">
            <tbody>
                <tr>
                    <td colspan="2" align="center">
                        <h4><b>Key Performance Indicators Report</b></h4>
                    </td>
                </tr>
                <tr>
                    <td><strong>Capacity</strong></td>
                    <td><?php echo school_capacity($_SESSION['school_id']);?></td>
                </tr>
                <tr>
                    <td><strong>No of Students</strong></td>
                    <td>
                        <?php echo $total_std = total_school_studnets();  ?>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>New Admissions</strong></td>
                    <td>
                        <?php   
                            echo $total_addmission = total_school_new_admission(); 
                        ?>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>Withdrawals</strong></td>
                    <td>
                        <?php   
                            echo $total_withdrawal = total_school_withdrawal(); 
                        ?>
                    </td>
                </tr>
                
                <tr>
                    <td><strong>Current Students Strengths</strong></td>
                    <td><?php echo $total_current_strength = $total_std+$total_addmission-$total_withdrawal; ?></td>
                </tr>
                
                <tr>
                    <td colspan="2">&nbsp;</td>
                    
                </tr>
                
                <tr>
                    <td><strong>Net Gain/Loss</strong></td>
                    <td><?php echo $total_current_strength-$total_std; ?></td>
                </tr>
            </tbody>
        </table>    
    </div>
</div>    
</script>
<script>
$(document).ready(function(){
        $('#key_section_wise_paid_pdf').click(function(){
            $('#key_section_wise_paid_form').attr('action', '<?php echo base_url(); ?>reports/key_performance_indicators_pdf');
            $('#key_section_wise_paid_form').submit();
        });
        
    });
    
</script>

<script src="<?=base_url()?>assets/table2excel.js"></script>
<script>
    $(function () {
        $("#btnExport").click(function () {
            $("#table_export_exp").table2excel({
                filename: "Key Performance Indicators.xls"
            });
        });
    });
</script>