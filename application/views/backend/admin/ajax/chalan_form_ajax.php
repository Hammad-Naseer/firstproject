<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/chalan_form_add_edit/add/<?php echo $section_id; ?>');" 
    class="btn btn-primary pull-right">
 
 <i class="entypo-plus-circled"></i>

<?php echo get_phrase('add_chalan_form');?>
  
</a> 
<br><br>
<table class="table table-bordered datatable" id="admin_challan_form_ajax">
<thead>
<tr>
<th style="min-width:20px;"><div><?php echo get_phrase('s_no');?></div></th>

<th><div><?php echo get_phrase('chalan_form_detail');?></div></th>

<th style="width:80px;"><div><?php echo get_phrase('options');?></div></th>
</tr>
    </thead>
    <tbody>
        <?php 
          $school_id=$_SESSION['school_id'];
          $where="";
        if($section_id!=""){
			$where=" And cs.section_id=$section_id ";
		}
		elseif($class_id!=""){
			$where=" And c.class_id=$class_id ";
		}
 elseif($departments_id!=""){
			$where=" And d.departments_id=$departments_id ";
		}
 
 $qre_s="select ccf.*, c.name as class_title, cs.title as section_name,d.title as dep_title from  ".get_school_db().".class_chalan_form ccf inner join ".get_school_db().".class_section cs on cs.section_id=ccf.section_id inner join ".get_school_db().".class c on c.class_id=cs.class_id inner join ".get_school_db().".departments d on c.departments_id=d.departments_id where ccf.school_id=$school_id  $where  ";
        
$students=$this->db->query($qre_s)->result_array();
$i=1;
 foreach($students as $row):?>
  <tr>
    <td> <?php echo $i++; ?></td>
          <td>
            <div class="myttl"> <?php echo $row['title'];?></div>
            <div><strong><?php echo get_phrase('chalan_form_type');?>: </strong><?php echo display_class_chalan_type($row['type']); ?> </div>
             <div><strong><?php echo get_phrase('department');?> / <?php echo get_phrase('class');?> / <?php echo get_phrase('section');?>: </strong>
            <ul class="breadcrumb" style="    display: inline;  padding: 3px;    margin-left: 5px;    color: #428abd;">
          	<li><?php echo $row['dep_title'];?></li>
          	<li><?php echo $row['class_title'];?></li>
          	<li><?php echo $row['section_name'];?></li>
          </ul>
             </div>
         <div><strong><?php echo get_phrase('status');?>: </strong>
                  <?php $stt = array(0=>"red" , 1=>"green");?>
        <span class="<?php echo $stt[$row['status']];  ?>">
                 <?php  if($row['status']==1)
							{  echo get_phrase("active");  }
					 	else
					 		{ echo get_phrase('inactive');  }
				?>
                 </span>
                 </div>
          <div><strong><?php echo get_phrase('detail');?>: </strong><span class="item"><?php echo $row['detail'];?></span></div>
           </td>
 
<td>
<div class="btn-group">

<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><?php echo get_phrase('action');?> <span class="caret"></span>
                    </button>
                    
<ul class="dropdown-menu dropdown-default pull-right" role="menu" >

<!-- STUDENT EDITING LINK -->
                        <li>
                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/chalan_form_add_edit/edit/<?php echo $row['c_c_f_id'];?>');">

<i class="entypo-pencil"></i>
 <?php echo get_phrase('edit');?>
</a>
</li>
<!-- view  chalan  LINK -->
<li>                      
<a href="<?php echo base_url();?>class_chalan_form/chalan_view/<?php echo $row['section_id']; ?>/<?php echo $row['c_c_f_id'];?>" >
<i class="fa fa-table"></i>
<?php echo get_phrase('chalan_view');?>
</a>
</li>
<!-- settings LINK -->
<li>
                        
<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/chalan_form_setting/<?php echo $row['section_id']; ?>/<?php echo $row['c_c_f_id']; ?>');">
<i class="fa fa-cog"></i>
<?php echo get_phrase('setting');?>
</a>
</li>
<li class="divider"></li>
 <!-- STUDENT DELETION LINK -->
 <li>
<a href="#" 
onclick="confirm_modal_chalan('<?php echo $row['c_c_f_id']; ?>');" >
<i class="entypo-trash"></i>
<?php echo get_phrase('delete');?>
 </a>
</li>
</ul>
</div>
 </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
<script type="text/javascript">
	function confirm_modal_chalan(c_c_f_id)
	{
		
		jQuery('#modal-4').modal('show', {backdrop: 'static'});
		/*document.getElementById('delete_link').setAttribute('href' , c_c_f_id);*/
		
	}
	</script>
    
    <!-- (Normal Modal)-->
    <div class="modal fade in" id="modal-4">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-top:100px;">
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" style="text-align:center;"><?php echo get_phrase('are_you_sure_to_delete_this_information');?> ?</h4>
                </div>
                
                
                <div class="modal-footer" style="margin:0px; border-top:0px; text-align:center;">
                    <div href="#" onclick="delete_func();" class="btn btn-danger" id="delete_link"><?php echo get_phrase('delete');?></div>
                    <button type="button" class="btn btn-info" data-dismiss="modal"><?php echo get_phrase('cancel');?></button>
                </div>
            </div>
        </div>
    </div>


 <script>
    	$(document).ready(function() {
    $('#admin_challan_form_ajax').DataTable(
    {
    	
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
		"bStateSave": true
	}
    );
    
    
} );

  $(".dataTables_wrapper select").select2({
            
            
            minimumResultsForSearch: -1
            
            
        });

    	
    </script>
<style>

/** The Magic **/
.btn-breadcrumb .btn:not(:last-child):after {
  content: " ";
  display: block;
  width: 0;
  height: 0;
  border-top: 17px solid transparent;
  border-bottom: 17px solid transparent;
  border-left: 10px solid white;
  position: absolute;
  top: 50%;
  margin-top: -17px;
  left: 100%;
  z-index: 3;
}
.btn-breadcrumb .btn:not(:last-child):before {
  content: " ";
  display: block;
  width: 0;
  height: 0;
  border-top: 17px solid transparent;
  border-bottom: 17px solid transparent;
  border-left: 10px solid rgb(173, 173, 173);
  position: absolute;
  top: 50%;
  margin-top: -17px;
  margin-left: 1px;
  left: 100%;
  z-index: 3;
}

/** The Spacing **/
.btn-breadcrumb .btn {
  padding:6px 12px 6px 24px;
}
.btn-breadcrumb .btn:first-child {
  padding:6px 6px 6px 10px;
  margin-right:1px;
}
.btn-breadcrumb .btn:last-child {
  padding:6px 18px 6px 24px;
  margin-left:0px;
}

/** Default button **/
.btn-breadcrumb .btn.btn-default:not(:last-child):after {
  border-left: 10px solid #fff;
}
.btn-breadcrumb .btn.btn-default:not(:last-child):before {
  border-left: 10px solid #ccc;
}
.btn-breadcrumb .btn.btn-default:hover:not(:last-child):after {
  border-left: 10px solid #ebebeb;
}
.btn-breadcrumb .btn.btn-default:hover:not(:last-child):before {
  border-left: 10px solid #adadad;
}

/** Primary button **/
.btn-breadcrumb .btn.btn-primary:not(:last-child):after {
  border-left: 10px solid #428bca;
}
.btn-breadcrumb .btn.btn-primary:not(:last-child):before {
  border-left: 10px solid #357ebd;
}
.btn-breadcrumb .btn.btn-primary:hover:not(:last-child):after {
  border-left: 10px solid #3276b1;
}
.btn-breadcrumb .btn.btn-primary:hover:not(:last-child):before {
  border-left: 10px solid #285e8e;
}

/** Success button **/
.btn-breadcrumb .btn.btn-success:not(:last-child):after {
  border-left: 10px solid #5cb85c;
}
.btn-breadcrumb .btn.btn-success:not(:last-child):before {
  border-left: 10px solid #4cae4c;
}
.btn-breadcrumb .btn.btn-success:hover:not(:last-child):after {
  border-left: 10px solid #47a447;
}
.btn-breadcrumb .btn.btn-success:hover:not(:last-child):before {
  border-left: 10px solid #398439;
}

/** Danger button **/
.btn-breadcrumb .btn.btn-danger:not(:last-child):after {
  border-left: 10px solid #d9534f;
}
.btn-breadcrumb .btn.btn-danger:not(:last-child):before {
  border-left: 10px solid #d43f3a;
}
.btn-breadcrumb .btn.btn-danger:hover:not(:last-child):after {
  border-left: 10px solid #d2322d;
}
.btn-breadcrumb .btn.btn-danger:hover:not(:last-child):before {
  border-left: 10px solid #ac2925;
}

/** Warning button **/
.btn-breadcrumb .btn.btn-warning:not(:last-child):after {
  border-left: 10px solid #f0ad4e;
}
.btn-breadcrumb .btn.btn-warning:not(:last-child):before {
  border-left: 10px solid #eea236;
}
.btn-breadcrumb .btn.btn-warning:hover:not(:last-child):after {
  border-left: 10px solid #ed9c28;
}
.btn-breadcrumb .btn.btn-warning:hover:not(:last-child):before {
  border-left: 10px solid #d58512;
}

/** Info button **/
.btn-breadcrumb .btn.btn-info:not(:last-child):after {
  border-left: 10px solid #5bc0de;
}
.btn-breadcrumb .btn.btn-info:not(:last-child):before {
  border-left: 10px solid #46b8da;
}
.btn-breadcrumb .btn.btn-info:hover:not(:last-child):after {
  border-left: 10px solid #39b3d7;
}
.btn-breadcrumb .btn.btn-info:hover:not(:last-child):before {
  border-left: 10px solid #269abc;
}
</style>

