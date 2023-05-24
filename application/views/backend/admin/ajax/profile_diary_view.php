<style>
        .row.diary-headr{padding:16px 5px;color:#fff;font-weight:500;font-size:18px}
        .row.diary-foter{padding:16px 5px;color:#fff;font-weight:500;font-size:18px}hr.hr-seting{width:100%;margin:0;margin-top:7px;background:#fff;height:1px}
        .row.diary-headr i{color:#ffeb3b}button.btn{background:#02658d;color:#fff;font-weight:600}table{border-collapse:collapse;border-spacing:0;width:100%;border:1px solid #ddd}td,th{text-align:left;padding:16px}tr:nth-child(even){background-color:#f2f2f2}
        .diary-duedate p{padding:10px 0;font-weight:500;font-size:18px;color:#fff}.row.memocard1{padding:26px 0}h5.diar-detail,h5.diar-title{min-width:88px}.col-sm-12.diary-note-cl{box-shadow:0 0 29px -16px #02658d;padding:34px;margin:39px 0;position:relative}i.fas.fa-lightbulb{position:absolute;top:-6px;left:-6px;background:#1a7866;padding:7px;color:#fde300}
        .diary-inner{margin:0 18px}
        .row.diary-atachmnt{display:flex;align-items:center;padding:24px 0 0}.diary-teacherAVA{width:51px;margin-right:8px}
</style>

<?php
if(count($arr) > 0){
?>
<div class="container my-3 diary-roww"> 
    <div class="row diary-headr">
        <div class="col-sm-6 d-flex justify-content-start align-items-center">
            <h3 class="text-center m-0 text-white">Subject Diary</h3>
        </div> 
        <hr class="hr-seting">
    </div>

    <div class="row diary-duedate">
        <div class="col-sm-6 d-flex justify-content-start align-items-center">
            <p class="text-center m-0"><i class="far fa-clock"></i> Assign Date: <?php echo date_view($arr->assign_date); ?></p>
        </div>
        <div class="col-sm-6 d-flex justify-content-md-end justify-content-sm-start"> 
            <p class="text-center m-0"><i class="far fa-clock"></i> Due Date: <?php echo date_view($arr->due_date); ?></p>
        </div> 
    </div>

    <div class="row memocard memocard1">
        <div class="col-sm-12 d-flex">
            <h5 class="diar-title m-0">Title:</h5> 
            <p class=" m-0"><b> <?php echo $arr->title; ?></b></p>
        </div>  
    </div> 
    <div class="row memocard memocard2  pb-5"> 
        <div class="col-sm-12 d-flex  pb-5">  
            <h5 class="diar-detail ">Detail: </h5>    
            <p class="m-0"><?php echo $arr->task; ?></p> 
        </div>
    </div>
    <div class="row diary-atachmnt pb-5">
        <?php
        if ($arr->audio != ""){
        ?>
        <div class="col-sm-4 py-1">   
            <p class="m-0">
                <b>Audio:</b>
                <audio controls>
                  <source src="<?php echo $audio_path; ?>" type="audio/ogg">
                  <source src="<?php echo $audio_path; ?>" type="audio/mpeg">
                </audio></p>  
        </div>
        <?php
        }
        ?>
        <div class="col-sm-4 py-1">   
            <p class="m-0  text-center"><b class="pr-2">Teacher:</b><?php echo get_teacher_name($arr->teacher_id);?></p>  
        </div>
        <?php
            if ($arr->attachment != ""){
            $attachment_path = base_url()."uploads/".$_SESSION['folder_name']."/diary/".$arr->attachment;
        ?>
        <div class="col-sm-4 py-1">   
            <p class="m-0"><b>Attachment:<a target="_blank" href=" <?php echo $attachment_path ?>"><i class="fas fa-paperclip"></i> Click to open the Link</a></b> </p>  
        </div>
        <?php
        }
        ?>
    </div> 
    
    <div class="row diary-foter"> 
        <div class="col-sm-12 d-flex justify-content-end">
            <?php
            if($arr->is_submitted_by == 1){
                $text = "<strong>Submission Date : </strong>".date_view($arr->submission_date);
            }elseif($arr->due_date < date('Y-m-d') ) {
                $text = "<strong>Diary Status : </strong>Submission Time Expired";
            }else{
                $text = "<strong>Diary Status : </strong>Pending assignment";
            }
            ?>
            <span class="btn text-center m-0"><?php echo $text;?></span>
        </div> 
    </div>
</div>
<?php
}else{
?>
<div class="container my-5 diary-roww"> 
<div class="row diary-headr">
    <div class="col-sm-6 d-flex justify-content-start align-items-center">
        <p class="text-center m-0">No Data Found</p>
    </div> 
    <hr class="hr-seting">
</div>
<?php
}
?>