<style>
    .container-new{
        width:75%;
        height:850px;
        margin-left:auto;
        margin-right:auto;
        border:1px solid #000;
        
    }
    .con2{
         width:100% ;
         overflow:hidden;
          height:auto;
          word-wrap: break-word;
    }
    .col{
       padding:0px 0px 15px 0px;
       font-weight:700;
    }
    
</style>
<div class="container">
     <h1 class="text-center"> <?php echo $notes[0]['notes_title']; ?></h1>
    <div class="row">
        <div class="col-lg-4 col-md-4">
        <h4> <b>Teacher Name :</b> <?php echo get_teacher_name($notes[0]['teacher_id']);?> </h4>
        </div>
        
        <div class="col-lg-4 col-md-4">  
            <h4> <b>Created Date :</b>  <?php echo date_view($notes[0]['inserted_at']);?> </h4>
        </div>
        <div class="col-lg-4 col-md-4">
             <h4 class="new_date"> <b>Attachments :</b>
        <?php 
            $docs = explode(',', $notes[0]['urls']);
            foreach($docs as $doc){
        ?>         
            <a target="_blank" download="" title="<?php echo $doc; ?>" href="<?php echo base_url().$doc; ?>"><span class="glyphicon glyphicon-download-alt" data-step="3" data-position='top' data-intro="download attachement file"></span></a>
        <? } ?>
        </h4>
        </div>
        </div>
        <div class="row ">
        <div class="col-lg-6 col-md-4 tag_att">
        <?php
        if($notes[0]['is_assigned'] == 1)
        {
            echo get_notes_assigned_section($notes[0]['notes_id']);
        } else{ ?>
        <h4>Status:</h4>&nbsp<span style='color:red;'>Not Assigned</span>
          <?php  }?>
        </div>
        </div>
        
    <div class="container con2">
    <div class="row mt-5">
        <div class="col">
        <h4>Description :</h4><p><?php echo $notes[0]['description']; ?></p>
        <h4>Remarks :</h4><p><?php echo $notes[0]['remarks']; ?></p>
        </div>
    </div>
    </div>
    
</div>

