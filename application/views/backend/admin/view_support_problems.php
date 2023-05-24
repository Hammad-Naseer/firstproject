<?php
    if($this->session->flashdata('club_updated')){
        echo '<div align="center">
        <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        '.$this->session->flashdata('club_updated').'
        </div> 
        </div>';
    }
    
    if($this->session->flashdata('error_msg')){
        echo '<div align="center">
        <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        '.$this->session->flashdata('error_msg').'
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
    <script>
    $(window).on("load",function() {
        setTimeout(function() {
            $('.mydiv').fadeOut();
        }, 3000);
    });
</script>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('system_support'); ?>
        </h3>
    </div>
</div>

    <div class="col-lg-12 col-md-12 col-sm-12">
        <table class="table table-bordered table_export" width="100%" data-step="1" data-position="top" data-intro="Support tickets records">
            <thead>
                <tr>
                    <td width="40">
                        <?php echo get_phrase('#');?>
                    </td>
                    <td>
                        <?php echo get_phrase('details');?>
                    </td>
                    <td width="94">
                        <?php echo get_phrase('status');?>
                    </td>
                    <td>
                        <?php echo get_phrase('Support Details');?>
                    </td>
                </tr>
            </thead>
            <tbody>
            <?php
            $a=0;
            foreach($problems_arr as $row)
            {
                $a++;
            ?>
                    <tr>
                        <td class="td_middle">
                            <?php echo $a; ?>
                        </td>
                        <td>
                            <?php 
                                echo "<strong>Title : </strong>".$row['title']."<br>";
                                echo "<strong>Date : </strong>".date('d-M-Y H:i:s A' , strtotime($row['problem_date']))."<br>";
                                echo "<strong>URL : </strong>".$row['url']."<br>";
                                echo "<strong>Description : </strong>".$row['description']."<br>";
                            ?>
                        </td>
                        <td>
                            <?php 
                            if($row['problem_status'] == 1){
                                echo "<span style='color:blue;'>New</span>";
                            }elseif($row['problem_status'] == 2){
                                echo "<span style='color:red;'>Open</span>";
                            }elseif($row['problem_status'] == 3){
                                echo "<span style='color:green;'>Closed</span>";
                            }
                             ?>
                        </td>
                        <td class="td_middle">
                            <?php
                                if($row['problem_status'] == 3){
                                    
                                    echo "<strong>Closing Date : </strong>".date('d-M-Y H:i:s A' , strtotime($row['closing_date']))."<br>";
                                    echo "<strong>Comments : </strong>".$row['comments']."<br>";
                                }elseif($row['problem_status'] == 2){
                                    echo "Work in progress";
                                }elseif($row['problem_status'] == 1){
                                    echo "Not Open";
                                }
                            ?>
                        </td>
                    </tr>
            <?php 
            }
            ?>
            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() 
        {
            $('#support_table').DataTable({
                "aLengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                "bStateSave": true
            });

            $(".dataTables_wrapper select").select2({
                minimumResultsForSearch: -1
            });
        });
    </script>