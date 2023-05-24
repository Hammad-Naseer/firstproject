<style>
    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
    @page {
        size: A4;
        margin: 0;
    }
    .page_letter {
        width: 210mm;
        /*min-height: 297mm;*/
        padding: 10mm;
        margin: 10mm auto;
        border: 1px #D3D3D3 solid;
        border-radius: 5px;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    @media print {
        html, body {
            width: 210mm;
            height: 297mm;        
        }
        .page_letter {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a class="modal_save_btn" id="print_btn" style="float:right;font-size:12px;color:white !important;">
            <b><i class="fas fa-print"></i> Print</b>
        </a>
        <h3 class="system_name inline">
            <?php echo get_phrase('salaries_payable_letters'); ?>
        </h3>
    </div>
</div>
<div class="container" id="printDiv">
    <?php
    $total_salary_payable = 0;
        foreach($salary_data as $data){
    ?>
    <div class="col-lg-12 col-md-12 page_letter" style="border:1px dashed black">
        
        <h5>Date: <?= date("D-M-Y"); ?></h5>
        <h5>The Manager of Bank</h5>
        <h5>Allied Bank Limited</h5>
        <h5>Main Branch Islamabad</h5>
        <br>
        <p>
            Dear sir, <br><br>
            I am writing this letter as a request to transfer salary of amount RS : <?php echo number_format($data['net_salary'],2); ?> to the respective account as mentioned below: The details are as follows:
        </p>
        <table class="table table-bordered">
            <tr>
                <th>Employee Name</th>
                <th>Account Details</th>
                <th>Amount Payable</th>
            </tr>
            <tr>
                <td><?= $data['name'] ?></td>
                <td>BAFL28372382783728</td>
                <td>
                    <?php 
                        echo number_format($data['net_salary'],2);
                        $total_salary_payable += $data['net_salary'];
                    ?>
                </td>
            </tr>
        </table>
        <br><br>
        <table class="table">
            <tr>
                <td><b>Yours sincerely,</b></td>
                <td><b>Authorized signatory</b></td>
            </tr>
        </table>
        <br><br>
    </div>
    <?php } ?>
    
    <div col-lg-12 col-md-12 style="float: right;margin-right: 150px;">
        <h3>Total Payable Amount : <?php echo number_format($total_salary_payable,2); ?></h3>
    </div>
</div>

<script>
    $(".page-container").addClass("sidebar-collapsed");
    
    $( document ).ready( function ()
    {
        $( '#print_btn' ).click( function ()
        {
            var printContents = document.getElementById( 'printDiv' ).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        });
    } );
    
    
</script>