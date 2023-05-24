<footer class="pb-3 pt-4 bg-purple">
    <div class="container-fluid map p-0 m-0 text-center text-light">
     <a href="https://indiciedu.com.pk"><img src="<?=base_url()?>assets/landing_pages/indiciedulogo.png" class=" mx-auto d-block img-fluid" alt="Indici Edu"></a>
     <hr class="hr-ftr-set  mb-5">  
     <span>All Rights Reserved. Powerd by indici-edu</span>
    </div>
    <!--general Inquery Model--> 

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">General Inquery Form</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
                <div class="modal-body">
                    <form id="general_inquiry_form" class="row g-3" method="post"> 
                        <div class="col-md-6"> 
                            <input type="text" class="form-control" id="inputFullname" name="name" placeholder="Full Name" required>
                        </div> 
                        <div class="col-md-6"> 
                            <input type="email" class="form-control" id="inputEmail4" name="email" placeholder="Email Address" required>
                        </div>
                        <div class="col-md-6"> 
                            <input type="number" class="form-control" id="inputPhonenumber" name="mobile_no" placeholder="Mobile Number" required>
                        </div>
                        <div class="col-md-6"> 
                            <select id="inputState" class="form-select" name="inquiry_type" required>
                              <?= inquiries_option_list(); ?>
                            </select>
                        </div>  
                        <div class="col-md-12"> 
                            <textarea class="form-control" name="decsription" id="textarea" placeholder="Description" required></textarea>
                        </div> 
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-ornage">Send Inquiry</button>
                        </div>
                    </form>
          </div>
            </div>
        </div>
    </div>
</footer>