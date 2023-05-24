
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<style>

    .bs-example {
        padding: 11px;
        background: #f8f9fa;
    }
    
    .my_btn {
        background: #3d86b7;
        color: white !important;
        border-radius: 35px;
        padding: 2px 15px 5px 15px !important;
        transition:all 0.50s ease-in-out;
    }
    
    .my_btn:hover{
        background: rgb(31 109 160 / 98%);
    }
    
    /***********************************************/
    /***************** Login Modal CSS ********************/
    /***********************************************/
    @import url('https://fonts.googleapis.com/css?family=Tajawal');
    
    #LoginModal .modal-content{
    	overflow:hidden;
    }
    a.h2{
        color:#007b5e;
        margin-bottom:0;
        text-decoration:none;
    }
    #LoginModal .form-control {
        height: 56px;
        border-top-left-radius: 30px;
        border-bottom-left-radius: 30px;
    	padding-left:30px;
    }
    #LoginModal .btn {
        border-top-right-radius: 30px;
        border-bottom-right-radius: 30px;
    	padding-right:20px;
    	background:#40a8d2;
    	border-color:#40a8d2;
    }
    #LoginModal .form-control:focus {
        color: #495057;
        background-color: #fff;
        border-color: #007b5e;
        outline: 0;
        box-shadow: none;
    }
    #LoginModal .top-strip{
    	height: 155px;
        background: #40a8d2;
        transform: rotate(141deg);
        margin-top: -94px;
        margin-right: 190px;
        margin-left: -130px;
        border-bottom: 65px solid #0c668a;
        border-top: 10px solid #012b3c;
    }
    #LoginModal .bottom-strip{
    	height: 155px;
        background: #40a8d2;
        transform: rotate(112deg);
        margin-top: -110px;
        margin-right: -215px;
        margin-left: 300px;
        border-bottom: 65px solid #0c668a;
        border-top: 10px solid #012b3c;
    }
    
    /**************************/
    /****** modal-lg stips *********/
    /**************************/
    #LoginModal .modal-lg .top-strip {
        height: 155px;
        background: #007b5e;
        transform: rotate(141deg);
        margin-top: -106px;
        margin-right: 457px;
        margin-left: -130px;
        border-bottom: 65px solid #4CAF50;
        border-top: 10px solid #4caf50;
    }
    #LoginModal .modal-lg .bottom-strip {
        height: 155px;
        background: #007b5e;
        transform: rotate(135deg);
        margin-top: -115px;
        margin-right: -339px;
        margin-left: 421px;
        border-bottom: 65px solid #4CAF50;
        border-top: 10px solid #4caf50;
    }
    
    #login_btn {
        border-top-right-radius: 30px;
        border-bottom-right-radius: 30px;
        padding-right: 20px;
        border-bottom-left-radius: 30px;
        background: #40a8d2 !important;
        border-color: #40a8d2 !important;
        border-top-left-radius: 30px;
        width:100%;
    }
    
    #login_btn:hover {
        background: #0c668a !important;
        border-color: #0c668a !important;
    }
    
    /****** extra *******/
    #Reloadpage{
        cursor:pointer;
    }
</style>

<div class="bs-example">
    <nav class="navbar navbar-expand-md navbar-light bg-light">
        <a href="#" class="navbar-brand">
            <img src="<?php echo base_url(); ?>assets/landing_pages/images/indici-edu-logo-SVG.svg" height="60" alt="Indici Edu" style="position: absolute;top: -3px;">
        </a>
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav">
                <!--<a href="#" class="nav-item nav-link active">Home</a>-->
                <!--<a href="#" class="nav-item nav-link">Profile</a>-->
                <!--<a href="#" class="nav-item nav-link">Messages</a>-->
                <!--<a href="#" class="nav-item nav-link disabled" tabindex="-1">Reports</a>-->
            </div>

            <div class="navbar-nav ml-auto">
                <a href="#" data-toggle="modal" data-target="#LoginModal" class="nav-item nav-link my_btn">Login</a>
            </div>
        </div>
    </nav>
</div>
