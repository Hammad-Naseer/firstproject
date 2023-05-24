<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>

.box{
    width: 100%;
    /*height:100vh;*/
    background-color:#f7f9fa;
    
}

.box .box-content{
    width:100%;
   
    margin:auto;
    display:flex;
    justify-content:center;
}
.panel-primary{
    width:100%;
    margin:auto;
    text-align:center;
    background-color:#fdfdfd;
      box-shadow: 0 0 2px;
}

p{
    font-size:70px;
    text-align:center;
}
h2{
     font-size:60px;
    text-align:center;
}
#sy-data{
    font-size:55px;

}
</style>

<?php 
// echo $folder_name;exit;
    // $array = explode('-',$param3);
    // $urlArr = explode('/',$_SERVER['REQUEST_URI']);
    // $yearly_id = $this->uri->segment(4);
    $arr = array('subject_sallybus_id' => $subject_syllabus_id,'subject_id' => $subject_id, 'school_id' => $school_id, 'academic_year_id' => $academic_year_id);

    // $get_sallybus = $this->db->where($arr)->get($school_db.".subject_sallybus")->row();
    
    $arr = " SELECT * FROM ".$school_db.".subject_sallybus WHERE subject_sallybus_id = $subject_syllabus_id AND subject_id = $subject_id AND school_id = $school_id AND academic_year_id = $academic_year_id ";
    $get_sallybus = $this->db->query($arr)->row();
    // print_r($get_sallybus);exit;
    
?>

<div class="tab-pane box " id="add">
    <div class="box-content subj-clr">
	    <div class="panel panel-primary" data-collapsed="0">
	            <div class="panel-heading">
	                <div class="panel-title black2">
	                    <i class="entypo-plus-circled"></i>
                        <h2>Description</h2>
	                </div>
	            </div>
	            <div class="panel-body">
	                <div class="form-group text-center">
	                    <?php if($get_sallybus->sallybus_type == '1'){ ?>
	                    <p id="sy-data">
	                        <?= $get_sallybus->sallybus_data ?></p>
	                    <?php }else if($get_sallybus->sallybus_type == '2'){ ?>
	                        <a href="<?= base_url() ?>/uploads/<?= $folder_name ?>/subject_sallybus/<?= $get_sallybus->sallybus_data ?>" download>
	                            <i class="fa-regular fa-file" style="font-size: 110px;"></i>
	                            <p>Download File</p>
	                        </a>
	                    <?php }else if($get_sallybus->sallybus_type == '3'){ ?>
	                        <iframe src="<?= $get_sallybus->sallybus_data ?>" width="900" height="400"   title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	                    <?php }else if($get_sallybus->sallybus_type == '4'){ ?>
	                        <iframe src="<?= $get_sallybus->sallybus_data ?>" width="100%></iframe>
	                    <?php } ?>
	                </div> 
				    <div class="form-group">	
				        <div class="float-right">

                        </div>
	                </div> 
	            </div>
	        </div>
    </div>
</div>

<div id="list_new"></div>
<div class="tab-pane box active" id="list1">
</div>


