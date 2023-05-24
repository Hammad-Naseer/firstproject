<?php 
$subject_id='';
$academic_id='';

$urlArr=explode('/',$_SERVER['REQUEST_URI']);


	if(sizeof($urlArr)>0)
	{ 
		$urlVal=explode('-',end($urlArr));
		if(sizeof($urlVal)>0){
    		$subject_id=$urlVal[0];
    		$academic_id=$urlVal[1];
		}	
	}
	
	
?>
<style>
body {
    padding: 0px;
    margin: 0px;
}


/*.panel-heading a{
	display:block;
}*/

.panel-title a {
    display: inline-block;
    width: 85%;
}

.options {
    float: right;
}

.options a {
    padding-left: 15px;
    display: inline;
}

.options span {
    padding-left: 15px;
}

.teacher {
    text-align: left;
    border: 1px solid #ccc;
    border-radius: 5px;
    display: inline-block;
    width: 100%;
    margin-bottom: 30px;
    padding: 15px 0px;
}

.teacher .image {
    float: left;
}

.teacher-detail {
    padding-top: 20px;
}

.components {
    margin-top: 20px;
    display: inline-block;
    padding-left: 0px;
    width: 100%;
}

.components li {
    float: left;
    width: 100%;
    /*border:1px solid #eeeeee;*/
    padding: 20px;
    list-style: none;
}

.components li strong {
    border: 1px solid #507895;
    padding: 10px 15px;
    border-radius: 5px;
    color: #507895;
    display: block;
}

.components li.active {
    background: #ffffff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    border-radius: 5px;
}

.components li.active strong {
    background: #507895;
    color: #ffffff;
}

.components li:hover strong,
.components li:focus strong {
    opacity: 0.9;
    cursor: pointer;
}

.components li a {
    padding: 0px 0px 0px 8px;
}

.components ul {
    padding-left: 0px;
    margin-top: 10px;
}

.components ul li {
    padding: 0px;
    float: none;
    border: none;
    margin: 0;
    font-weight: normal;
}

.tab-content {
    margin-top: 30px;
}


/*#year-selection{
	float:right;
}*/


/*#year-selection select{
	height: 35px;
    border-radius: 5px;
    border-color: #ccc;
    padding: 0px 15px;
    box-shadow: 0px 0px 6px rgba(0, 0, 0, 0.3);
	color:#507895;
}*/


/*#year-selection select:focus{
	outline:none;
	box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
}*/

.fa-pencil:before,
.fa-trash:before,
.fa-users:before,
.fa-th-large:before {
    font-size: 12px;
}

.fa-dot-circle-o:before {
    font-size: 10px;
    margin-left: 10px;
}
</style>
<form action="#" id="year-selection">
    <div class="col-sm-12" style="margin-bottom:20px; font-weight:bold">
        <div class="panel-title black2">
            <?php echo get_phrase('view_component_of');?>
            <span><?php echo get_subject_name($subject_id); ?></span>
        </div>
    </div>
    <!--<div class="col-lg-12 col-md-9 col-sm-9">-->
    <!--    <select id="academic_id<?php echo $subject_id;?>" name="academic_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">-->
    <!--    </select>-->
    <!--</div>-->
</form>
<ul class="components" id="component_list<?php echo $subject_id;?>">
</ul>
<script type="text/javascript">
jQuery(document).ready(function($) {

    <?php //if($academic_id!=''){
    ?>
    var academic_year = '<?php echo $academic_id?>';
    
    var subject_id = '<?php echo $subject_id;?>';

    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>subject/get_components",

        data: ({
            subject_id: '<?php echo $subject_id;?>',
            academic_id: academic_year
        }),
        dataType: "html",
        success: function(html) {
            //console.log(html);
            if (html != '') {
                $('#component_list' + subject_id).html(html);					

            }

        }


    });
    <?php
    //}
    ?>
    $("select[id^='academic_id']").on('change', function() {

        var academic_year = $(this).val();
        $(this).after('<div id="icon" class="loader_small"></div>');
        str = $(this).attr('id');
        subject_id = str.replace('academic_id', '');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>subject/get_components",
            data: ({
                subject_id: '<?php echo $subject_id;?>',
                academic_id: academic_year
            }),
            dataType: "html",
            success: function(html) {
                $("#icon").remove();
                //alert(html);
                //console.log(html);
                if (html != '') {

                    $('#component_list' + subject_id).html(html);
                    //$('.components li').first().addClass("active");		

                }

            }


        });

    });


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
