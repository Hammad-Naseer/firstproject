<style>
.fa-hand-o-right {
    color: #217ee1;
    font-size: 15px !important;
}

.fa-times {
    color: #DB0003 !important;
}

.fa-pencil {
    color: #00c01c !important;
}
</style>

<?php 
	$q2="select sc.subject_component_id, sc.title AS component,sc.percentage,sc.subject_id from ".get_school_db().".subject_components sc where sc.subject_id=".$subject_id." AND sc.school_id=".$_SESSION['school_id']." 
		order by sc.subject_component_id desc
	";

	$compArr=$this->db->query($q2)->result_array();
	if(sizeof($compArr)>0)
	{?>
		<table class="table table-bordered table-hover table-striped table-responsive">
	    <tr>
	        <th><?php echo get_phrase('title');?></th>
	        <th><?php echo get_phrase('marks');?></th>
	        <th style="width:40px;"><?php echo get_phrase('action');?></th>
	    </tr>
		<?php
		foreach($compArr as $comp)
		{
		?>
	        <tr id="<?php echo $comp['subject_component_id'];?>">
	            <td>
	                <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
	                <?php echo $comp['component'];?>
	            </td>
	            <td>
	                <?php echo $comp['percentage'] ?>
	            </td>
	            <td>
                	<a href="#"  onclick="edit_func(<?php echo "'".$comp['component']."'".','.$comp['percentage'].','.$subject_id.','.$comp['subject_component_id'];?>);">
                    	<i class="fa fa-pencil"></i>
                    </a>

                    <a href="#" onclick="delete_func('<?php echo $comp['subject_component_id'];?>')">
                        <i class="fa fa-trash"></i>
                    </a>
	            </td>
	            
	        </tr>
        <?php 	
        } 
        ?>
		</table>
	<?php 
	}else{
	    echo "<span>No component found</span>";
	}
	?>
<script type="text/javascript">

subject_id = <?php echo $subject_id ?>;
function edit_func(title, marks, subject_id, component_id) 
{
    $("#title_add").val(title);
    $("#percentage").val(marks);
    $("#hidden_component_id").val(component_id);
}

function delete_func(id) 
{
	if (confirm("<?php echo get_phrase('are_you_sure_you_want_to_delete');?>")) 
	{
	    $('#list_new').html('<div id="message" class="loader"></div>');

	    $.ajax({
	        type: 'POST',
	        data: {
	            comp_id: id
	        },
	        url: "<?php echo base_url();?>subject/components/delete",
	        dataType: "html",
	        success: function(response) 
	        {
	            $('#list_new').html(response);
	            $('#list1').load("<?php echo base_url(); ?>subject/get_components/"+subject_id);
	        }
	    });
	}
}

jQuery(document).ready(function($) {

    $("a[id^='delete']").on('click', function() {
        //alert('clicked');
        var subject_id = $("#subject_id").val();
        var academic_id = "<?php echo $academic_id;?>";
        //var del_id="<?php echo $comp['subject_component_id'];?>";
        str = $(this).attr('id');
        comp_id = str.replace('delete', '');
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>subject/components/delete/",

            data: ({
                comp_id: comp_id
            }),
            dataType: "html",
            success: function(html) {
                console.log(html);
                if (html == "deleted") {
                    $('.modal-body').load("<?php echo base_url(); ?>modal/popup/modal_all_components/" + subject_id + '-' + academic_id).append('<?php echo get_phrase("component_deleted");?>');
                    //$(#"<?php echo $comp['subject_component_id'];?>").remove();
                    //$("#"+del_id).remove();
                    //$(this).parent().remove();
                    //$('.modal-body').append('Component deleted');
                }

            }


        });
        //$('.comp-element').siblings().removeClass('active');
        //$("#"+del_id).remove();
        //$(this).parent().remove();
    });


});
</script>
