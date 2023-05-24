<style>
    .entypo-plus-circled:before
    {
        color: #000 !important;
    }
</style>

<?php
if($this->session->flashdata('club_updated'))
{
    echo '<div align="center">
            <div class="alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                '.$this->session->flashdata('club_updated').'
            </div> 
	     </div>';
}
?>

<script>
    $(window).on("load",function()
    {
        setTimeout(function()
        {
           $('.alert').fadeOut();
        }, 3000);
        
    });
</script>

<style>
    .fld {
        position: absolute;
        right: 30px;
    }
    .fle {
        position: absolute;
        right: 80px;
    }
    .fla {
        position: absolute;
        right: 115px;
    }
    @media (max-width:400px) {
        .fle {
            position: static;
        }
        .fla {
            position: static;
        }
        .fld {
            position: static;
        }
    }

    .inli:hover {
        background: #b50000;
    }
    .tree,
    .tree ul {
        margin: 0;
        padding: 0;
        list-style: none;
        cursor: pointer;
    }
    .tree ul {
        margin-left: 1em;
        position: relative
    }
    .tree ul ul {
        margin-left: .5em
    }
    .tree ul:before {
        content: "";
        display: block;
        width: 0;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        border-left: 1px solid
    }
    .tree li {
        margin: 0;
        padding: 0px 0px 0px 1em;
        line-height: 2em;
        color: #a2a4a8;
        position: relative;
    }
    .tree li:hover {
        background-color: rgba(204, 204, 204, 0.08);
    }

    .cospan:hover {
        border-right: 3px solid #ad0707;
    }

    .tree li a {
        text-decoration: none;
        color: #7d8086;
    }

    .tree ul li:before {
        content: "";
        display: block;
        width: 10px;
        height: 0;
        border-top: 1px solid;
        margin-top: -1px;
        position: absolute;
        top: 1em;
        left: 0
    }

    .tree ul li:last-child:before {
        background: #fff;
        height: auto;
        top: 1em;
        bottom: 0
    }

    .indicator {
        margin-right: 5px;
    }

    .tree li button,
    .tree li button:active,
    .tree li button:focus {
        text-decoration: none;
        color: #369;
        border: none;
        background: transparent;
        margin: 0px 0px 0px 0px;
        padding: 0px 0px 0px 0px;
        outline: 0;
    }

    .coact {
        text-transform: capitalize;
        padding-left: 7px;
        background-color: #EEE;
        margin-left: 10px;
        padding-bottom: 6px;
        color: #000;
        margin-right: 18px;
        padding-top: 5px;
        font-size: 12px;
    }

    .myarrow {
        font-size: 20px;
        padding-left: 7px;
        padding-right: 6px;
        position: relative;
        top: 3px;
    }

    .coabg:hover {
        color: red;
    }

    .active
    {
        color: red !important;
    }
    .panel-title.black2.hdng-clr {
    color: #fff !important;
    }
    .panel-title.black2.hdng-clr .entypo-plus-circled:before {
    color: #fff !important;
    }
</style>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline" data-step="1" data-position='left' data-intro="financial reports settings">
            <?php echo get_phrase('financial_reports_settings');?>
        </h3>
        <?php
        /*
        if (right_granted('chartofaccount_manage'))
        {

        ?>
        <a style="margin-right: 10px;" class="btn btn-primary pull-right" id="myBtn" href="#" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/chart_of_accounts/');">
                <i class=" white entypo-plus-circled" style="color:#FFF !important"></i>
                <?php echo get_phrase('Add Chart of Account Title');?>
        </a>
        <?php
        }
        */
        ?>
    </div>
</div>

<?php
$account_type = 1;
?>

<div class="row mgt35">
    <!--Arrears Drop down start-->
    <div class="col-sm-6" data-step="2" data-position='right' data-intro="you can set income statements settings sales debit & credit chart of account and expanse debit & credit chart of account, select account then press save button">
        <div style="padding: 20px 20px 30px 20px;border: 1px solid #EEE;">
            <?php
            if (right_granted('chartofaccount_manage'))
            {
                $get_val=$this->db->query("select * from ".get_school_db().".misc_challan_coa_settings where school_id=".$_SESSION['school_id']." and type='arrears_coa'")->result_array();
                ?>
                <div class="box-content">
                    <div class="panel-heading" style="    background-color: #02658d;">
                        <div class="panel-title black2 hdng-clr">
                            <i class="entypo-plus-circled"></i>
                            <?php echo get_phrase('income_statements_settings');?>
                        </div>
                    </div>

                    <div id="alert-success-arrears" class="alert alert-success alert-dismissable" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo get_phrase('income_statements_settings_saved!'); ?>
                    </div>

                    <form class="form-horizontal form-groups-bordered validate">
                        <!--COA while sales Start-->
                        <fieldset class="custom_legend">
                            <legend class="custom_legend"><?php echo get_phrase('sales_coa'); ?> :</legend>
                            <div class="form-group  px-4">
                                <label for="field-2" class=" control-label"><?php echo get_phrase('coa_title');?><span class="star">*</span></label>
                                 
                                    <!--<input class="form-control" value="<?php echo $get_val[0]['generate_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->

                                    <?php
                                    $coa_id = 0;
                                    $income_stmt_sales_str = "select coa_id from ".get_school_db().".financial_reports_settings 
                                                                    where school_id=".$_SESSION['school_id']." 
                                                                            and settings_type='income_stmt_sales'";
                                    $income_stmt_sales_str_query=$this->db->query($income_stmt_sales_str)->row();
                                    if(count($income_stmt_sales_str_query)>0)
                                    {
                                        $coa_id = $income_stmt_sales_str_query->coa_id;
                                    }

                                    ?>
                                    <input type="hidden" name="income_stmt_sales" id="income_stmt_sales" value="income_stmt_sales">
                                    <select class="form-control select2" name="income_id_sales" id="income_id_sales">
                                        <option value="0"><?php echo get_phrase('select');?></option>
                                       <?php echo child_coa_list(0 , $coa_id); ?>
                                    </select> 
                            </div>

                        </fieldset>
                        <!--COA while sales End-->



                        <!--COA while income/revenue Start-->
                       <?php /* <fieldset class="custom_legend">
                            <legend class="custom_legend"> <?php echo get_phrase('income_coa');?> :</legend>
                            <div class="form-group">
                                <label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('coa_title');?>  <span class="star">*</span>   </label>
                                <div class="col-sm-8">
                                    <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                    <input type="hidden" name="income_stmt_income" id="income_stmt_income" value="income_stmt_income">

                                    <?php
                                    $income_stmt_income_coa_id = 0;
                                    $income_stmt_income_str = "select coa_id from ".get_school_db().".financial_reports_settings 
                                                                    where school_id=".$_SESSION['school_id']." 
                                                                            and income_stmt_income='income_stmt_income'";
                                    $income_stmt_income_query=$this->db->query($income_stmt_income_str)->row();
                                    if(count($income_stmt_income_query)>0)
                                    {
                                        $income_stmt_income_coa_id = $income_stmt_income_query->coa_id;
                                    }

                                    ?>
                                    <select class="form-control" name="income_id_income" id="income_id_income">
                                        <option value="0"><?php echo get_phrase('select');?></option>
                                        <?php echo child_coa_list(0 , $income_stmt_income_coa_id); ?>
                                    </select>
                                </div>
                            </div>
                        </fieldset> */ ?>
                        <!--COA income/revenue End-->
                        <!--COA while expenses Start-->
                        <fieldset class="custom_legend">
                            <legend class="custom_legend"> <?php echo get_phrase('expense_coa');?> :</legend>
                            <div class="form-group  px-4">
                                <label for="field-2" class=" control-label"><?php echo get_phrase('coa_title');?>  <span class="star">*</span>   </label>
                                
                                    <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                    <input type="hidden" id="income_stmt_expense" name="income_stmt_expense" value="income_stmt_expense">

                                    <?php
                                    $income_stmt_expense_coa_id = 0;
                                    $income_stmt_expense_str = "select coa_id from ".get_school_db().".financial_reports_settings 
                                                                    where school_id=".$_SESSION['school_id']." 
                                                                            and settings_type='income_stmt_expense'";
                                    $income_stmt_expense_query=$this->db->query($income_stmt_expense_str)->row();
                                    if(count($income_stmt_expense_query)>0)
                                    {
                                       $income_stmt_income_coa_id = $income_stmt_expense_query->coa_id;
                                    }

                                    ?>

                                    <select class="form-control select2" name="income_id_expense" id="income_id_expense">
                                        <option value="0"><?php echo get_phrase('select');?></option>
                                        <?php echo child_coa_list(0 , $income_stmt_income_coa_id); ?>
                                    </select> 
                            </div>


                        </fieldset>
                        <!--COA while expenses form End-->

                        <div class="col-sm-12 mgt10">
                            <button id="btn_income_statement" class="btn btn-primary pull-right modal_save_btn" type="submit"><?php echo get_phrase('save');?></button>
                        </div>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>

    </div>
    <!--Arrears Drop down End-->
    <!--Fee Drop down start-->
    <div class="col-sm-6" data-step="3" data-position='left' data-intro="you can set balance sheet settings assets debit & credit chart of account and liabilities debit & credit chart of account and capital debit & credit chart of account , select account then press save button">
        <div style="padding: 20px 20px 30px 20px;
    border: 1px solid #EEE;">
            <?php
            $get_val1=$this->db->query("select * from ".get_school_db().".misc_challan_coa_settings where school_id=".$_SESSION['school_id']." and type='late_fee_fine_coa'")->result_array();

            if (right_granted('chartofaccount_manage'))
            {
                ?>
                <div class="box-content">
                    <div class="panel-heading" style="    background-color: #02658d;">
                        <div class="panel-title black2 hdng-clr">
                            <i class="entypo-plus-circled">
                            </i> 
                            <?php echo get_phrase('Balance_sheet_settings');?>
                            
                            
                            </div>
                    </div>

                    <div id="alert-success-fee" class="alert alert-success alert-dismissable" style="display: none;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo get_phrase('balance_sheet_settings_saved!'); ?>
                    </div>

                    <form class="form-horizontal form-groups-bordered validate">


                        <!--COA while assets Start-->
                        <fieldset class="custom_legend">
                            <legend class="custom_legend"><?php echo get_phrase('assets_coa');?> :</legend>
                            <div class="form-group  px-4">
                                <label for="field-2" class=" control-label"><?php echo get_phrase('coa_title');?>  <span class="star">*</span>   </label>
                                 
                                    <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                    <input type="hidden"  name="balance_sheet_assets" id="balance_sheet_assets" value="balance_sheet_assets">

                                    <?php
                                    $balance_sheet_assets_coa_id = 0;
                                    $balance_sheet_assets_str = "select coa_id from ".get_school_db().".financial_reports_settings 
                                                                    where school_id=".$_SESSION['school_id']." 
                                                                            and settings_type='balance_sheet_assets'";
                                    $balance_sheet_assets_query=$this->db->query($balance_sheet_assets_str)->row();
                                    if(count($balance_sheet_assets_query)>0)
                                    {
                                        $balance_sheet_assets_coa_id = $balance_sheet_assets_query->coa_id;
                                    }

                                    ?>


                                    <select class="form-control select2" name="balance_sheet_id_assets" id="balance_sheet_id_assets">
                                        <option value="0"><?php echo get_phrase('select');?></option>
                                        <?php echo child_coa_list(0 , $balance_sheet_assets_coa_id); ?>
                                    </select> 
                            </div>

                        </fieldset>
                        <!--COA while assets End-->


                        <!--COA while liabilities Start-->
                        <fieldset class="custom_legend">
                         <legend class="custom_legend"><?php echo get_phrase('liabilities_coa');?> :</legend>
                            <div class="form-group  px-4">
                                <label for="field-2" class=" control-label"><?php echo get_phrase('coa_title');?>  <span class="star">*</span>   </label>
                                
                                    <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                    <input type="hidden" name="balance_sheet_liabilities" id="balance_sheet_liabilities" value="balance_sheet_liabilities">

                                    <?php
                                    $balance_sheet_liabilities_coa_id = 0;
                                    $balance_sheet_liabilities_str = "select coa_id from ".get_school_db().".financial_reports_settings 
                                                                    where school_id=".$_SESSION['school_id']." 
                                                                            and settings_type='balance_sheet_liabilities'";
                                    $balance_sheet_liabilities_query=$this->db->query($balance_sheet_liabilities_str)->row();
                                    if(count($balance_sheet_liabilities_query)>0)
                                    {
                                        $balance_sheet_liabilities_coa_id = $balance_sheet_liabilities_query->coa_id;
                                    }

                                    ?>

                                    <select class="form-control select2" name="balance_sheet_id_liabilities" id="balance_sheet_id_liabilities">
                                        <option value="0"><?php echo get_phrase('select');?></option>
                                        <?php echo child_coa_list(0 , $balance_sheet_liabilities_coa_id); ?>
                                    </select> 
                            </div>

                        </fieldset>
                        <!--COA while liabilities End-->


                        <!--COA while Capital Start-->
                        <fieldset class="custom_legend">
                            <legend class="custom_legend"><?php echo get_phrase('capital_coa');?> :</legend>
                            <div class="form-group  px-4">
                                <label for="field-2" class=" control-label"><?php echo get_phrase('coa_title');?>  <span class="star">*</span>   </label>
                                 
                                    <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                                    <input type="hidden" name="balance_sheet_capital" id="balance_sheet_capital" value="balance_sheet_capital">

                                    <?php
                                    $balance_sheet_capital_coa_id = 0;
                                    $balance_sheet_capital_str = "select coa_id from ".get_school_db().".financial_reports_settings 
                                                                    where school_id=".$_SESSION['school_id']." 
                                                                            and settings_type='balance_sheet_capital'";
                                    $balance_sheet_capital_query=$this->db->query($balance_sheet_capital_str)->row();
                                    if(count($balance_sheet_capital_query)>0)
                                    {
                                        $balance_sheet_capital_coa_id = $balance_sheet_capital_query->coa_id;
                                    }

                                    ?>
                                    <select class="form-control select2" name="balance_sheet_id_capital" id="balance_sheet_id_capital">
                                        <option value="0"><?php echo get_phrase('select');?></option>
                                        <?php echo child_coa_list(0 , $balance_sheet_capital_coa_id); ?>
                                    </select> 
                            </div>

                        </fieldset>
                        <!--COA while liabilities End-->



                        <div class="col-sm-12 mgt10">
                            <button id="btn_balance_sheet" class="btn btn-primary pull-right modal_save_btn" type="submit"><?php echo get_phrase('save');?></button>
                        </div>

                    </form>
                </div>

                <?php
            }
            ?>



        </div>


    </div>
    <!--Fee Drop down End-->

</div>


<script type="text/javascript">
    $(document).ready(function()
    {
        $("#btn_income_statement").click(function(e)
        {


            e.preventDefault();
           // alert('hi');
          /*  var income_stmt_sales = $('#income_stmt_sales').val();
            var income_stmt_income = $('#income_stmt_income').val();
            var income_stmt_expense = $('#income_stmt_expense').val();*/

            var income_id_sales = $('#income_id_sales').val();
            var income_id_expense = $('#income_id_expense').val();
           /* var income_id_income = $('#income_id_income').val();*/
            $.ajax({
                type: 'POST',
                data: {
                    income_id_sales: income_id_sales,
                    income_id_expense: income_id_expense
                },
                url: "<?php echo base_url();?>chart_of_account/save_income_statement",
                dataType: "html",
                success: function(response) {
                    $("#alert-success-arrears").show().delay(5000).fadeOut();
                }
            });

        });


        $("#btn_balance_sheet").click(function(e)
        {

            e.preventDefault();

            /*var balance_sheet_assets = $('#balance_sheet_assets').val();
             var balance_sheet_liabilities = $('#balance_sheet_liabilities').val();
             var balance_sheet_capital = $('#balance_sheet_capital').val();*/

            var balance_sheet_id_assets = $('#balance_sheet_id_assets').val();
            var balance_sheet_id_liabilities = $('#balance_sheet_id_liabilities').val();
            var balance_sheet_id_capital = $('#balance_sheet_id_capital').val();

           /*var cancel_dr_coa_id_1 = $('#cancel_dr_coa_id_1').val();
            var cancel_cr_coa_id_1 = $('#cancel_cr_coa_id_1').val();*/
            //var coa_id = 4;


            $.ajax({
                type: 'POST',
                data: {
                    balance_sheet_id_assets: balance_sheet_id_assets,
                    balance_sheet_id_liabilities: balance_sheet_id_liabilities,
                    balance_sheet_id_capital: balance_sheet_id_capital
                },
                url: "<?php echo base_url(); ?>chart_of_account/save_balance_sheet",
                dataType: "html",
                success: function(response) {
                    $("#alert-success-fee").show().delay(5000).fadeOut();
                }
            });

        });



    });
</script>

