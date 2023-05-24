
<div class="panel panel-primary" data-collapsed="0">
    <div class="panel-heading">
    	<div class="panel-title black2">
    		<i class="entypo-plus-circled"></i>
            <?php echo get_phrase('rate_teacher');?>
        </div>
    </div>
    <div class="panel-body">
    	<?php echo form_open(base_url().'evaluation/teacher_rating/create' , array('id'=>'disable_submit_btn','class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                
            <!-- Rating Stars Box -->
            <div class='rating-stars text-center'>
                <ul id='stars'>
                    <li class='modal-star star-item' title='Poor' data-value='1'>
                        <i class='fa fa-star fa-fw'></i>
                        <br>
                        <span>Poor</span>
                    </li>
                    <li class='modal-star star-item' title='Fair' data-value='2'>
                        <i class='fa fa-star fa-fw'></i>
                        <br>
                        <span>Fair</span>
                    </li>
                    <li class='modal-star star-item' title='Good' data-value='3'>
                        <i class='fa fa-star fa-fw'></i>
                        <br>
                        <span>Good</span>
                    </li>
                    <li class='modal-star star-item' title='Excellent' data-value='4'>
                        <i class='fa fa-star fa-fw'></i>
                        <br>
                        <span>Excellent</span>
                    </li>
                    <li class='modal-star star-item' title='Awesome' data-value='5'>
                        <i class='fa fa-star fa-fw'></i>
                        <br>
                        <span>Awesome</span>
                    </li>
                </ul>
            </div>
                <input type="hidden" name="teacher_id" id="teacher_id" value="<?php echo $param2;?>" />
                <input type="hidden" name="rating" id="rating" value="" />
               	<div class="form-group">
                  <div class="float-right">
                <p id="missing-rating" style="color:red;font-weight: 600;"></p>
                      <button type="submit" class="modal_save_btn" id="submit-rating">
    					<?php echo get_phrase('submit');?>
    				</button>
    				<button type="button" class="modal_cancel_btn" data-dismiss="modal" aria-label="Close">
    					<?php echo get_phrase('cancel');?>
    				</button>
                  </div>
    			</div>
        </form>             
    </div>    
</div> 

<script>
    $(document).ready(function(){
      $('#stars li').on('mouseover', function(){
        var onStar = parseInt($(this).data('value'), 10);
        $(this).parent().children('li.star-item').each(function(e){
          if (e < onStar) {
            $(this).addClass('hover');
          }
          else {
            $(this).removeClass('hover');
          }
        });
        
      }).on('mouseout', function(){
        $(this).parent().children('li.star-item').each(function(e){
          $(this).removeClass('hover');
        });
      });
      $('#stars li').on('click', function(){
        var onStar = parseInt($(this).data('value'), 10);
        var stars = $(this).parent().children('li.star-item');
        for (i = 0; i < stars.length; i++) {
          $(stars[i]).removeClass('selected');
        }
        for (i = 0; i < onStar; i++) {
          $(stars[i]).addClass('selected');
        }
        var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
        $('#rating').val(ratingValue);
      });
      $('#submit-rating').on('click', function(e){
          var rating = $("#rating").val();
          if(rating == ''){
              e.preventDefault();
              $("#missing-rating").html("Please choose a star");
          }
      });
      
      
    });
</script>