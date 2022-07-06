<html lang="en">
  <head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/fontawesome.min.css"   />
  <link href="<?php echo base_url(); ?>/public/css/styles.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"  ></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"  ></script>
  <script src="<?php echo base_url(); ?>/public/js/custom.js"  ></script>
 
  <title><?php echo $title; ?> | EagleX</title>
  </head>

  <body class="d-flex flex-column min-vh-100">
  <header id="site-header">
      <div class="container">
  <nav class="navbar navbar-expand-lg  ">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo base_url(); ?>"> <img src="<?php echo base_url(); ?>/public/images/logo.png" alt="" height="50"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">

        
      <?php if(session()->get('type')=='Admin') {?>
        <li class="nav-item">
          <a class="nav-link" href="<?= site_url('admin/statement-list'); ?>">Statements</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= site_url('admin/payment'); ?>">Payment</a>
        </li>
      
<?php }elseif(session()->get('type')=='Driver') { ?>
  <li class="nav-item">
          <a class="nav-link" href="<?= site_url('driver/statement-list'); ?>">Statements List</a>
        </li>
  
<?php 
} ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= site_url('/logout'); ?>">Logout</a>
        </li>
      </ul>

      
    </div>
  </div>
</nav>
      </div>
  </header>

