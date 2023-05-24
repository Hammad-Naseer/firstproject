<style>
.entypo-plus-circled:before {
    color: #000 !important;
}
</style>

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
        margin-left: 15px;
    margin-right: 24px;
    padding: 0px 0px 0px 1em;
    line-height: 2em;
    color: #000000;
    position: relative;
    font-family: 'Circular-Loom';
    font-weight: 500;
    font-size: 13px;
}
.tree li:hover {
    background-color: rgb(204 204 204 / 48%);
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

.active {
    color: red !important;
}

.acc .nav-list.tree .fld, .acc .nav-list.tree .fle {
    border-left: 1px solid #fff;
}
</style>




<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <a style="float:right;color:blue;font-size: 15px;" href="javascript:void(0);" id="chalan_tour" onclick="javascript:introJs().start();">
            <b><i class="fas fa-info-circle"></i> Interactive tutorial</b>
        </a>
        <h3 class="system_name inline">
               <?php echo get_phrase('chart_of_accounts'); ?>
        </h3>
    </div>
     <div class="col-lg-12 col-md-12 col-sm-12 myt topbar">
        <?php
	    if (right_granted('chartofaccount_manage'))
    	{
    	?>
    	<a data-step="1" data-position='left' data-intro="Step 1: press this button open popup fill this form add chart of account title" style="margin-right: 10px;" class="btn btn-primary pull-right" id="myBtn" href="#" onclick="showAjaxModal('<?php echo base_url(); ?>modal/popup/add_chart_of_account/');">
                <i class=" white entypo-plus-circled" style="color:#FFF !important"></i>
                <?php echo get_phrase('add_chart_of_account_title');?>
        </a>
        <?php
		}
		?>
    </div>
</div>



<div class="row">
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog" style="z-index: 15 !important; margin-top: 20%;">
            <div class="modal-content">
                <?php echo get_phrase('this_is_my_modal'); ?>
            </div>
        </div>
    </div>
</div>


<div class="row acc" data-step="2" data-position="left" data-intro="Step 2: Press add child button to add new chart of account!!   Step 3 : Press edit button to edit chart of account!!   Step 4 : Press delete button to delete chart of account">
    <div class='coact' style="width:100%"><span class="act"><?php echo get_phrase('account_title'); ?></span><i class="fa myarrow fa-arrows-h" aria-hidden="true"></i><span class="acn"><?php echo get_phrase('account_number'); ?>  / <?php echo get_phrase('code'); ?></span></div>
    <?php
    	foreach($menu as $mnu){
    		echo $mnu;
    	}
	?>
</div>
<?php
/*<div class="row mgt35">
	<!--Arrears Drop down start-->
	<div class="col-sm-6" >
   <div style="        padding: 20px 20px 30px 20px;
    border: 1px solid #EEE;">
	<?php
    if (right_granted('chartofaccount_manage'))
    {
		$get_val=$this->db->query("select * from ".get_school_db().".misc_challan_coa_settings where school_id=".$_SESSION['school_id']." and type='arrears_coa'")->result_array();
	 ?>
        <div class="box-content">
            <div class="panel-heading" style="    background-color: #eee;">
                <div class="panel-title black2">
                    <i class="entypo-plus-circled">
					</i>  Chart of Account title for Arrears</div>
            </div>
            
            <div id="alert-success-arrears" class="alert alert-success alert-dismissable" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            		Arrears saved Successfully
			</div>
            
            <form class="form-horizontal form-groups-bordered validate">


                <!--COA while generating challan form Start-->
                <fieldset class="custom_legend">
                    <legend class="custom_legend">COA while Arrears generation:</legend>
                    <div class="form-group">
                        <label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('Debit');?><span class="star">*</span></label>
                        <div class="col-sm-8">
                            <!--<input class="form-control" value="<?php echo $get_val[0]['generate_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                            <select class="form-control" name="generate_dr_coa_id" id="generate_dr_coa_id">
                                <option value=""><?php echo get_phrase('select');?></option>
                                <?php
                                echo coa_list_h(0,$get_val[0]['generate_dr_coa_id']);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                            <!-- <input class="form-control" value="<?php echo $get_val[0]['generate_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                            <select class="form-control" name="generate_cr_coa_id" id="generate_cr_coa_id">
                                <option value=""><?php echo get_phrase('select');?></option>
                                <?php
                                echo coa_list_h($parent_id=0,$get_val[0]['generate_cr_coa_id']);
                                ?>
                            </select>
                        </div>
                    </div>
                </fieldset>
                <!--COA while generating challan form End-->

                <!--COA while issuing challan form Start-->
                    <fieldset class="custom_legend">
  					<legend class="custom_legend">COA while issuing challan form:</legend>
                    <div class="form-group">
						<label for="field-2" class="col-sm-4 control-label"><?php echo get_phrase('Debit');?><span class="star">*</span></label>
						<div class="col-sm-8">
                        <!--<input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                        <select class="form-control" name="issue_dr_coa_id" id="issue_dr_coa_id">
                            <option value=""><?php echo get_phrase('select');?></option>
                            <?php 
echo coa_list_h(0,$get_val[0]['issue_dr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    
                   <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                       <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                        <select class="form-control" name="issue_cr_coa_id" id="issue_cr_coa_id">
                            <option value=""><?php echo get_phrase('select');?></option>
							<?php 
echo coa_list_h($parent_id=0,$get_val[0]['issue_cr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    </fieldset>
                     <!--COA while issuing challan form End-->	
                    
                     <!--COA while reciving challan form Start-->
                    <fieldset class="custom_legend">
  					<legend class="custom_legend">COA while receiving challan form:</legend>
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
						<div class="col-sm-8">
                       <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                        <select class="form-control" name="receive_dr_coa_id" id="receive_dr_coa_id">
                            <option value=""><?php echo get_phrase('select');?></option>
							<?php 
echo coa_list_h($parent_id=0,$get_val[0]['receive_dr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    
                   <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                        <!--<input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                        <select class="form-control" name="receive_cr_coa_id" id="receive_cr_coa_id">
                            <option value=""><?php echo get_phrase('select');?></option>
							<?php
echo coa_list_h($parent_id=0,$get_val[0]['receive_cr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    </fieldset>
                     <!--COA while reciving challan form End-->	
                    
                       <!--COA while cancelling challan form Start-->
                    <fieldset class="custom_legend">
  					<legend class="custom_legend">COA while Cancelling challan form:</legend>
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
						<div class="col-sm-8">
                       <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                        <select class="form-control" name="cancel_dr_coa_id" id="cancel_dr_coa_id">
                           <option value=""><?php echo get_phrase('select');?></option>
						   <?php 
echo coa_list_h($parent_id=0,$get_val[0]['cancel_dr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    
                   <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                        <!--<input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                        <select class="form-control" name="cancel_cr_coa_id" id="cancel_cr_coa_id">
                            <option value=""><?php echo get_phrase('select');?></option>
							<?php 
echo coa_list_h($parent_id=0,$get_val[0]['cancel_cr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    </fieldset>
                     <!--COA while cancelling challan form End-->	

                    <div class="col-sm-12 mgt10">
                       <button id="btn_save" class="btn btn-primary pull-right" type="submit">Save</button>
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
    <div class="col-sm-6" >
   <div style="padding: 20px 20px 30px 20px;
    border: 1px solid #EEE;">
    <?php
$get_val1=$this->db->query("select * from ".get_school_db().".misc_challan_coa_settings where school_id=".$_SESSION['school_id']." and type='other_coa'")->result_array();
	
	    if (right_granted('chartofaccount_manage'))
    	{
    	?>
        <div class="box-content">
           <div class="panel-heading" style="    background-color: #eee;">
                <div class="panel-title black2">
                    <i class="entypo-plus-circled">
					</i> Chart of Account title for other fee (eg. Fines )</div>
            </div>
            
            <div id="alert-success-fee" class="alert alert-success alert-dismissable" style="display: none;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            		Fee saved Successfully
			</div>
            
            <form class="form-horizontal form-groups-bordered validate">
                      <!--COA while issuing challan form Start-->
                    <fieldset class="custom_legend">
  					<legend class="custom_legend">COA while issuing challan form:</legend>
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
						<div class="col-sm-8">
                        <!--<input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                        <select class="form-control" name="issue_dr_coa_id_1" id="issue_dr_coa_id_1">
                          <option value=""><?php echo get_phrase('select');?></option>
                           <?php 
echo coa_list_h($parent_id=0,$get_val1[0]['issue_dr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    
                   <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                       <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                        <select class="form-control" name="issue_cr_coa_id_1" id="issue_cr_coa_id_1">
                         <option value=""><?php echo get_phrase('select');?></option>
						 <?php 
echo coa_list_h($parent_id=0,$get_val1[0]['issue_cr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    </fieldset>
                     <!--COA while issuing challan form End-->	
                     
                     <!--COA while reciving challan form Start-->
                    <fieldset class="custom_legend">
  					<legend class="custom_legend">COA while receiving challan form:</legend>
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
						<div class="col-sm-8">
                       <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                        <select class="form-control" name="receive_dr_coa_id_1" id="receive_dr_coa_id_1">
                           <option value=""><?php echo get_phrase('select');?></option>
                            <?php 
echo coa_list_h($parent_id=0,$get_val1[0]['receive_dr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    
                   <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                        <!--<input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                        <select class="form-control" name="receive_cr_coa_id_1" id="receive_cr_coa_id_1">
                           <option value=""><?php echo get_phrase('select');?></option>
						   <?php 
echo coa_list_h($parent_id=0,$get_val1[0]['receive_cr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    </fieldset>
                     <!--COA while reciving challan form End-->	
                    
                       <!--COA while cancelling challan form Start-->
                    <fieldset class="custom_legend">
  					<legend class="custom_legend">COA while Cancelling challan form:</legend>
                    <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Debit');?>  <span class="star">*</span>   </label>
						<div class="col-sm-8">
                       <!-- <input class="form-control" value="<?php echo $get_val[0]['issue_dr_coa_id']; ?>" name="misc_id" id="misc_id" type="hidden">-->
                        <select class="form-control" name="cancel_dr_coa_id_1" id="cancel_dr_coa_id_1">
                           <option value=""><?php echo get_phrase('select');?></option>
						   <?php 
echo coa_list_h($parent_id=0,$get_val1[0]['cancel_dr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    
                   <div class="form-group">
						<label for="field-2" class="col-sm-4  control-label"><?php echo get_phrase('Credit');?>  <span class="star">*</span>   </label><div class="col-sm-8">
                        <!--<input class="form-control" value="<?php echo $get_val[0]['issue_cr_coa_id']; ?>" name="misc_id_1" id="misc_id_1" type="hidden">-->
                        <select class="form-control" name="cancel_cr_coa_id_1" id="cancel_cr_coa_id_1">
                           <option value=""><?php echo get_phrase('select');?></option>
						   <?php 
echo coa_list_h($parent_id=0,$get_val1[0]['cancel_cr_coa_id']);
?>
                        </select>
                    </div>
                    </div>
                    </fieldset>
                     <!--COA while cancelling challan form End-->	
           
                    <div class="col-sm-12 mgt10">
                            <button id="btn_save1" class="btn btn-primary pull-right" type="submit">Save</button>
                    </div>
              
            </form>
        </div>
        <script type="text/javascript">
        $(document).ready(function() {
	        $("#btn_save1").click(function(e) {
	            e.preventDefault();
	            var issue_dr_coa_id_1 = $('#issue_dr_coa_id_1').val();
        		var issue_cr_coa_id_1 = $('#issue_cr_coa_id_1').val();
		
				var receive_dr_coa_id_1 = $('#receive_dr_coa_id_1').val();
       			var receive_cr_coa_id_1 = $('#receive_cr_coa_id_1').val();
		
				var cancel_dr_coa_id_1 = $('#cancel_dr_coa_id_1').val();
       			var cancel_cr_coa_id_1 = $('#cancel_cr_coa_id_1').val();
				var coa_id = 4;
				
				
	            $.ajax({
	                type: 'POST',
	                data: {
	                   issue_dr_coa_id_1: issue_dr_coa_id_1,
					   issue_cr_coa_id_1: issue_cr_coa_id_1,
					   receive_dr_coa_id_1: receive_dr_coa_id_1,
					   receive_cr_coa_id_1: receive_cr_coa_id_1,
					   cancel_dr_coa_id_1: cancel_dr_coa_id_1,
					   cancel_cr_coa_id_1: cancel_cr_coa_id_1
					  },
	                url: "<?php echo  base_url(); ?>/chart_of_account/save_arrears1",
	                dataType: "html",
	                success: function(response) {
						$("#alert-success-fee").show().delay(5000).fadeOut();
	                }
	            });
	        });
	     });

        </script>
        <?php
    	}
    	?>



		</div>
		
		
	</div>
	<!--Fee Drop down End-->
	
</div>

?>













<script type="text/javascript">
   $(document).ready(function() {
    $("#btn_save").click(function(e) {
        e.preventDefault();


        var generate_dr_coa_id = $('#generate_dr_coa_id').val();
        var generate_cr_coa_id = $('#generate_cr_coa_id').val();

        var issue_dr_coa_id = $('#issue_dr_coa_id').val();
        var issue_cr_coa_id = $('#issue_cr_coa_id').val();

        var receive_dr_coa_id = $('#receive_dr_coa_id').val();
        var receive_cr_coa_id = $('#receive_cr_coa_id').val();

        var cancel_dr_coa_id = $('#cancel_dr_coa_id').val();
        var cancel_cr_coa_id = $('#cancel_cr_coa_id').val();
		
		$.ajax({
            type: 'POST',
            data: {
                generate_dr_coa_id: generate_dr_coa_id,
                generate_cr_coa_id: generate_cr_coa_id,
                issue_dr_coa_id: issue_dr_coa_id,
               	issue_cr_coa_id: issue_cr_coa_id,
				receive_dr_coa_id: receive_dr_coa_id,
				receive_cr_coa_id: receive_cr_coa_id,
				cancel_dr_coa_id: cancel_dr_coa_id,
				cancel_cr_coa_id: cancel_cr_coa_id
				},
            url: "<?php echo  base_url(); ?>/chart_of_account/save_arrears",
            dataType: "html",
            success: function(response) {
				$("#alert-success-arrears").show().delay(5000).fadeOut();
            }
        });
   });





});
        </script>
<script>

/*


$(document).ready(function(){
	
	
	$(".nav-list").each(function(index){
$(this).on("click", function(){
	
	
	//$(this).children().removeClass('active');
//$(this).closest('li').addClass('active');
	
	
	
});
	
	
	
	
});

});*/

?>





<script>
$(document).ready(function(){
	
    });

    //$(".dataTables_wrapper select").select2({
       // minimumResultsForSearch: -1
    //});
//});
</script>
<script>
$.fn.extend({
    treed: function(o) {

        var openedClass = 'glyphicon-minus-sign';
        var closedClass = 'glyphicon-plus-sign';

        if (typeof o != 'undefined') {
            if (typeof o.openedClass != 'undefined') {
                openedClass = o.openedClass;
            }
            if (typeof o.closedClass != 'undefined') {
                closedClass = o.closedClass;
            }
        };

        //initialize each of the top levels
        var tree = $(this);
        tree.addClass("tree");
        tree.find('li').has("ul").each(function() {
            var branch = $(this); //li with children ul
            branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
            branch.addClass('branch');
            branch.on('click', function(e) {
                if (this == e.target) {
                    var icon = $(this).children('i:first');
                    icon.toggleClass(openedClass + " " + closedClass);
                    $(this).children().children().toggle();
                }
            })
            branch.children().children().toggle();
        });
        //fire event from the dynamically added icon
        tree.find('.branch .indicator').each(function() {
            $(this).on('click', function() {
                $(this).closest('li').click();
            });
        });
        //fire event to open branch if the li contains an anchor instead of text
        tree.find('.branch>a').each(function() {
            $(this).on('click', function(e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
        //fire event to open branch if the li contains a button instead of text
        tree.find('.branch>button').each(function() {
            $(this).on('click', function(e) {
                $(this).closest('li').click();
                e.preventDefault();
            });
        });
    }
});

//Initialization of treeviews

$('#tree1').treed();

$('#tree2').treed({
    openedClass: 'glyphicon-folder-open',
    closedClass: 'glyphicon-folder-close'
});

$('#tree3').treed({
    openedClass: 'glyphicon-chevron-right',
    closedClass: 'glyphicon-chevron-down'
});

$('#tree4').treed({
    openedClass: 'glyphicon-chevron-right',
    closedClass: 'glyphicon-chevron-down'
});
$('#tree5').treed({
    openedClass: 'glyphicon-chevron-right',
    closedClass: 'glyphicon-chevron-down'
});
$('#tree6').treed({
    openedClass: 'glyphicon-chevron-right',
    closedClass: 'glyphicon-chevron-down'
});
$('#tree7').treed({
    openedClass: 'glyphicon-chevron-right',
    closedClass: 'glyphicon-chevron-down'
});






function active(indexValue){
    //alert('HI');
}



</script>
