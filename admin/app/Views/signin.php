<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>/public/css/styles.css" rel="stylesheet">
    <link href="public/images/favicon.png" rel="icon">
  <link href="public/images/apple-touch-icon.png" rel="apple-touch-icon">
    <title>Login | EagleX</title>
  </head>
  <body class="login-main">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-5">
                <div class="login-logo">
                
                 <img src="<?php echo base_url(); ?>/public/images/logo.png" alt="">
</div>

                <?php if(session()->getFlashdata('error')):?>
                    <div class="alert alert-danger">
                       <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif;?>
                <form action="<?php echo base_url(); ?>/loginAuth" method="post">
                    <div class="form-group mb-3">
                        <input type="user_name" name="user_name" placeholder="Username" value="<?= set_value('user_name') ?>" class="form-control" >
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" name="password" placeholder="Password" class="form-control" >
                    </div>
                    
                    <div class="d-grid">
                         <button type="submit" class="btn btn-success default-btn">Log In</button>
                    </div>     
                </form>
            </div>
              
        </div>
    </div>
  </body>
</html>