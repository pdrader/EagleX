    <!-- Begin page content -->
 
      <div class="page-header">
        <h5>Upload Panther Data</h5>
      </div>
      <div class="content">
      <div class="row justify-content-md-center">
      <div class="col-5">
      <form action="<?php echo base_url(); ?>/admin/payment-upload" method="post" enctype="multipart/form-data">
      <div class="mb-3">
  <label for="upload_file" class="form-label">Upload File to EagleX</label> 
  <!--(<a href="<?php echo base_url('/public/sample/sample-pro.xls'); ?>" class="link-primary">Sample File</a>)-->
  <input class="form-control" type="file" id="upload_file" name="upload_file">
</div>

<div class="d-grid">
                         <button type="submit" class="btn btn-success default-btn">Upload</button>
                    </div> 
      </form>
      </div>
       
      </div>
      </div>

   

   