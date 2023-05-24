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
    
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <form class="validate" id="filter1">
                <div class="panel panel-primary" data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title black2">
                            <i class="entypo-plus-circled"></i>
                            <?php echo get_phrase('subject_categories');?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="field-2"><?php echo get_phrase('title');?><span class="star">*</span></label>
                            <input  maxlength="250" id="title_add" type="text" class="form-control" name="title_add" data-validate="required" placeholder="Please Enter Category Title" data-message-required="<?php echo get_phrase('value_required');?>">
                            <span style="color:red;" id="error_msg"></span>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
            					<button id="btn_submit1"  type="submit" class="modal_save_btn">
            						<?php echo get_phrase('save');?>
            					</button>
            					<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
            						<?php echo get_phrase('cancel');?>
            					</button>
            				</div>
                             <input type="hidden" name="id_hidden" id="id_hidden" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="list_new">
    </div>
    <div class="tab-pane box active icn-clr" id="list1">
    </div>
    <script>
    $(document).ready(function() {
        document.getElementById('filter1').onsubmit = function() {
            return false;
        };
        $('#list1').load("<?php echo base_url(); ?>Category/category_generator");
        
        $('#btn_submit1').click(function() 
        {
            var proceed = true;
            var title = $('#title_add').val();
            
            if (title.length < 1) {
                proceed = false;
                
            } else {
                $("#error_msg").html("");
                proceed = true;
            }
            
            if(proceed){
                var id = $('#id_hidden').val();
    
                if (id != "")
                {
                    $('#list_new').html('<div id="message" class="loader"></div>');
                    $.ajax({
                        type: 'POST',
                        data: {
                            title: title,
                            id: id
                        },
                        url: "<?php echo base_url();?>Category/edit_category",
                        dataType: "html",
                        success: function(response) {
                           // $('#btn_submit1').attr('disabled','disabled');
                            $('#title_add').val('');
                            $('#id_hidden').val('');
                            $('#list_new').html(response);
                            $('#list1').load("<?php echo base_url(); ?>Category/category_generator");
                        }
                    });
                } 
                else 
                {
                    $('#list_new').html('<div id="message" class="loader"></div>');
                    if (title != "") {
                        $.ajax({
                            type: 'POST',
                            data: {
                                title: title
                            },
                            url: "<?php echo base_url();?>Category/categories",
                            dataType: "html",
                            success: function(response) {
                                $('#title_add').val('');
                                $('#list_new').html(response);
                                $('#list1').load("<?php echo base_url(); ?>Category/category_generator");
                            }
                        });
                    }
                }
            }
        });
    });


    function edit_func(id, title) {
        document.getElementById("title_add").value = title;
        document.getElementById("id_hidden").value = id;
    }

    function delete_func(id) {
        var result = confirm("Are you sure you want to delete?");
        if (result) {

            $('#list_new').html('<div id="message" class="loader"></div>');

            $.ajax({
                type: 'POST',
                data: {
                    id: id
                },
                url: "<?php echo base_url();?>Category/delete_category",
                dataType: "html",
                success: function(response) {
                    $('#list_new').html(response);
                    $('#list1').load("<?php echo base_url();?>Category/category_generator");
                }
            });
        }
    }
    </script>
    <script>
    $("#title_add").keypress(function (e) {
        
        var key = e.keyCode || e.which;       
        $("#error_msg").html("");
        //Regular Expression
        var reg_exp = /^[A-Za-z0-9 ]+$/;
        //Validate Text Field value against the Regex.
        var is_valid = reg_exp.test(String.fromCharCode(key));
        if (!is_valid) {
          $("#error_msg").html("No special characters Please!");
        }
        return is_valid;
      });
    </script>
