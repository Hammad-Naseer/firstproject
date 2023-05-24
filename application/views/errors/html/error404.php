<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>404 Error</title>
  </head>
  <body>
    <div class="container-fluid" style="height: 100vh;background:#00bcd42e;"> 
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-sm-12" style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
                
            <div style="    box-shadow: 0px 0px 50px -38px; padding: 66px; display: flex; flex-direction: column; justify-content: center; align-items: center;background: #fff;">
             <img src="https://indiciedu.com.pk/frontend/wp-content/uploads/2021/01/indici-edu-logo-SVG.svg" class="img-fluid w-50" alt="404 Error">
                 <div class="d-flex py-3" style="align-items: center;">
                    <img src="<?php echo base_url(); ?>assets/404-Errorg.gif" class="img-fluid" alt="404 Error"><h1 style="font-size: 50px;">Page Not Found</h1>
                 </div> 
             <p style="font-size: 21px; text-align: center;">The page you are looking for might have been removed<br>had its name changed or is temporarily removed</p>
             <button type="button" onclick="window.history.back();" class="btn btn-primary  mt-3">Click To Go Back </button>               
            </div>
            
            </div>
        </div>
    </div> 
    </div>
  </body>
</html>


