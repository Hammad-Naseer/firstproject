<?php

$arr = $this->db->query("select * from ".get_school_db().".user_group where user_group_id = $user_group_id ")->result_array();
?>
<h3>
   <?php echo get_phrase('assign_rights_to');?> (<?php echo $arr[0]['title']; ?>)
</h3>
<hr>
<div class="tab-pane box" id="add" style="padding: 5px">
    <?php 
	$query=$this->db->query("select distinct m.module_id,m.title as module 
     from ".get_system_db().".package_rights p 
     inner join ".get_system_db().".action a on a.action_id=p.action_id 
     inner join ".get_system_db().".module m on m.module_id=a.module_id 
     where 
     package_id=".$_SESSION['package_id']." order by module asc");
	
	$modules=$query->result_array();
	?>
    <form name="assign-rights" id="assign-rights">
        <ul>
            <?php foreach($modules as $mod)
	       {?>
            <li style="list-style: none">
                <input type="checkbox" id="<?php echo $mod['module_id']?>" name="module[]" class="module" value="<?php echo $mod['module_id']?>"><b><?php echo ucfirst($mod['module'])?></b></input>
                <ul>
                    <?php  
                	$query=$this->db->query("select p.*,a.title as action 
                        from ".get_system_db().".package_rights  p 
                        inner join ".get_system_db().".action a on a.action_id=p.action_id 
                        inner join ".get_system_db().".module m on m.module_id=a.module_id 
                        where 
                        p.package_id=".$_SESSION['package_id']." 
                        and a.module_id=".$mod['module_id']);
                	$action=$query->result_array();
                	$selected="select action_id from ".get_school_db().".group_rights where user_group_id=". $user_group_id;
                	$query=$this->db->query($selected);
                	$selectedAct=$query->result_array();
                	
                	foreach($selectedAct as $sel)
                	{
                		$sel2[]=$sel['action_id'];
                	}
                	
                	foreach($action as $act)
                    {?>
                        <li style="list-style: none">
                            <input name="actions[]" type="checkbox" <?php if(in_array($act[ 'action_id'],$sel2)){echo "checked=checked";}?> class="act<?php echo $mod['module_id'];?>" value="<?php echo $act['action_id']?>" id="actions">
                                <?php echo $act['action'] ?>
                            </input>
                        </li>
                    <?php 
                    }?>
                </ul>
            </li>
            <?php }?>
        </ul>
        <button type="button" id="submit-btn" class="btn btn-info">
            <?php echo get_phrase('Save');?>
        </button>
    </form>
</div>

<script>
$(document).ready(function() {

    $('input[class^="module"]').click(function() {
        var str = $(this).attr('id');
        var m_id = $(this).attr('value');
        $('.act' + m_id).prop('checked', true);
        if ($('.' + m_id).attr('checked') == true) {
            $('.act' + m_id).removeAttr('checked', true);
            $('.' + m_id).removeAttr('checked', false);
        }

    });
    $('#submit-btn').click(function() {

        var user_type_id = '<?php echo $user_group_id?>';
        var module_id = $('.module:checked').serializeArray();
        var action_id = $('#actions:checked').serializeArray();

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>user/assign_right",

            data: ({
                user_type_id: user_type_id,
                module_id: module_id,
                action_id: action_id
            }),
            success: function(response) {
                console.log(response);
                window.location = "<?php echo base_url(); ?>user/user_groups";

            }

        });
    });
});
</script>
