<style>
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
    .modal-content {
        width: 800px;
        left: -28%;
    }
    .template_faq {
        background: #edf3fe none repeat scroll 0 0;
    }
    .panel-group {
        background: #fff none repeat scroll 0 0;
        border-radius: 3px;
        box-shadow: 0 5px 30px 0 rgba(0, 0, 0, 0.04);
        margin-bottom: 0;
        padding: 0px;
    }
    #accordion .panel {
        border: medium none;
        border-radius: 0;
        box-shadow: none !important;
        margin: 0 0 -20px 10px;
    }
    #accordion .panel-heading {
        border-radius: 30px;
        padding: 15px;
    }
    #accordion .panel-title a {
        background: #ffb900 none repeat scroll 0 0;
        border: 1px solid transparent;
        border-radius: 30px;
        color: #fff;
        display: block;
        font-size: 18px;
        font-weight: 600;
        padding: 12px 20px 12px 50px;
        position: relative;
        transition: all 0.3s ease 0s;
    }
    #accordion .panel-title a.collapsed {
        background: #fff none repeat scroll 0 0;
        border: 1px solid #ddd;
        color: #333 !important;
        box-shadow: 0px 0px 6px 1px #0000002e;
    }
    #accordion .panel-title a::after, #accordion .panel-title a.collapsed::after {
        background: #ffb900 none repeat scroll 0 0;
        border: 1px solid transparent;
        border-radius: 50%;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.58);
        color: #fff;
        content: "";
        font-family: fontawesome;
        font-size: 25px;
        height: 55px;
        left: -20px;
        line-height: 55px;
        position: absolute;
        text-align: center;
        top: -5px;
        transition: all 0.3s ease 0s;
        width: 55px;
    }
    #accordion .panel-title a.collapsed::after {
        background: #fff none repeat scroll 0 0;
        border: 1px solid #ddd;
        box-shadow: none;
        color: #333;
        content: "";
    }
    #accordion .panel-body {
        background: transparent none repeat scroll 0 0;
        border-top: medium none;
        padding: 20px 25px 10px 9px;
        position: relative;
    }
    #accordion .panel-body p {
        border-left: 1px dashed #8c8c8c;
        padding-left: 25px;
    }

    .searchbar{
        margin-bottom: auto;
        margin-top: auto;
        height: 60px;
        background-color: #353b48;
        border-radius: 30px;
        padding: 10px;
        font-size: 26px;
    }

    .search_input{
        padding: 0 10px;
        width: 450px;
        caret-color:red;
        color: white;
        border: 0;
        outline: 0;
        background: none;
        caret-color:transparent;
        line-height: 40px;
        transition: width 0.4s linear;
    }

    .searchbar:hover > .search_input{
        padding: 0 10px;
        width: 450px;
        caret-color:red;
        transition: width 0.4s linear;
    }

    .searchbar:hover > .search_icon{
        background: white;
        color: #e74c3c;
    }

    .search_icon{
        height: 40px;
        width: 40px;
        float: right;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        color:white;
        text-decoration:none;
    }
 
</style>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('frequently_asked_question');?>
            	</div>
            </div>
            <div class="panel-body">
                <div class="box-content">
                    <?php echo form_open(base_url().'' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        <div class="form-group">
                            <label class="control-label"><?php echo get_phrase('write_your_question');?> <span class="star">*</span></label>
                            <div class="searchbar">
                              <input class="search-query search_input" type="text" name="" placeholder="Search...">
                              <a href="#" class="search_icon"><i class="fas fa-search"></i></a>
                            </div>
                        </div>
                    </form>
        			<div id="result"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
/*
Author       : Zeeshan Arain.
Template Name: Indici Edu
Version      : 1.0
*/
(function($) {
	'use strict';
	jQuery(document).on('ready', function(){
    	$('a.page-scroll').on('click', function(e){
    		var anchor = $(this);
    		$('html, body').stop().animate({
    			scrollTop: $(anchor.attr('href')).offset().top - 50
    		}, 1500);
    		e.preventDefault();
    	});		
	}); 	
})(jQuery);

$(".search-query").on('keyup',function(){
    var search = $(this).val();
    if(search !== ""){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>login/asked_question",
            data: {search:search},
            dataType : "html",
            success: function(response)
            {
                $("#result").html(response);
            }
        });
    }else{
        $("#result").html("");
    }
});

</script>