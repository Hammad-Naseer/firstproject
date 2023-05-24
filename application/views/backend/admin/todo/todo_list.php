    <style>
        .save_note
        {
            position: relative;
            top: 18px;
            left: -8px;
            z-index: 9999;
            background: #9e8745;
            color: white;
            padding: 7px 15px 7px 15px;
            border: 2px solid #f0ecdb;
        }
    </style>
    <div class="page-container">
        <div class="main-content">
            <div class="col-lg-12 col-sm-12">
                <div class="notes-env">
                <div class="notes-header">
                    <h2><?= ucfirst($page_title); ?></h2>
                    <div class="right"> <a class="btn btn-primary btn-icon icon-left" id="add-note"> <i class="entypo-pencil"></i>&nbsp;&nbsp;&nbsp;&nbsp;New Note</a> </div>
                </div>
                <form action="#" id="save_note_form" method="post">
                    <button type="submit" class="save_note">Save Note</button>
                    <div class="notes-list">
                        <input type="hidden" name="todo_id" class="todo_id">
                        <input type="hidden" name="title" class="t_title">
                        <input type="hidden" name="content" class="t_content">
                        
                        <ul class="list-of-notes">
                            <?php if(count($todo_data) > 0){$i = 1;foreach($todo_data as $data): $i++; ?>
                            <li class="<?php if($i == 1){ echo 'current';} ?>"> 
                                <a href="#" data-todo-id="<?= $data['todo_list_id']; ?>"> 
                                    <strong><?= $data['todo_title'] ?></strong> 
                                    <span><?= substr($data['todo_content'],100) ?></span>
                                </a> 
                                <button type="button" onclick="confirm_modal('<?php echo base_url();?>todo/todo_delete/<?php echo $data['todo_list_id'] ?>');" class="note-close">&times;</button>
                                <div class="content"><?= $data['todo_title'] ?>&#013;&#010;<?= $data['todo_content'] ?></div>
                            </li>
                            <?php endforeach; }else{ ?>
                                <li class="current text-center" style="position:relative;top:150px;"> 
                                    <img src="<?=base_url()?>assets/images/data_not_found.png" width="120px">
                                    <br><br>
                                    <p>
                                        <strong>Not Created Todo Yet</strong>
                                    </p>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="write-pad"> <textarea class="form-control autogrow" placeholder="Title&#013;&#010;


Content"></textarea> </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
    <?php if($this->session->flashdata('error')){ $msg = $this->session->flashdata('error'); ?>
        <script>toastr["error"]('<?= $msg; ?>');</script>
    <?php } ?>
    <script>
        $("#save_note_form").on('submit',function(e){
            e.preventDefault();
            var t_title = $(".current").find('strong').text();
            var t_content = $(".current").find('span').text();
            var t_todo_id = $(".current").find('a').attr('data-todo-id');
            if(t_title == "" || t_content == "")
            {
                $(".save_note").removeAttr("disabled");
                toastr["error"]('Please Add New Todo');
            }else{
                $(".t_title").val(t_title);
                $(".t_content").val(t_content);
                $(".todo_id").val(t_todo_id);
                
                $.ajax({
                    url: '<?= base_url() ?>Todo/todo_insert',
                    method: 'POST',
                    dataType: "JSON",
                    data:new FormData(this),
                    contentType: false,  
                    cache: false,  
                    processData:false,
                    success:function(response)
                    {
                        $(".save_note").removeAttr("disabled");
                        toastr["success"](response.msg);
                        $(".current").find('a').attr('data-todo-id',response.id);
                    }
                    
                });
            }
            setTimeout(function(){ $(".save_note").removeAttr("disabled"); }, 1000);
        });
    </script>