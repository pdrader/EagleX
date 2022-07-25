    <!-- Begin page content -->
 
      <div class="page-header">
        <h5>Statements</h5>
      </div>
      <div class="content">
      <div class="row justify-content-md-center">
      <div class="col-12">
      
     


<table class="table table-striped">
  <thead>
    <tr>
     
      <th scope="col">Driver Name</th>
      <th scope="col">Pay Date</th>
      <th scope="col">Truck Number</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>


  <?php 
$page_no=0;
foreach($statement_details as $statement_detail){
?>
    <tr>     
      <td><?php  echo $statement_detail['name']; ?></td>
      <td><?php  echo date("m/d/Y", strtotime($statement_detail['check_date']));   ?></td>
      <td><?php  echo $statement_detail['truck_number']; ?></td>

      <td>
      <?php if(session()->get('type')=='Admin') {?>
        <a  href="<?php echo base_url('admin/run/'.$statement_detail['driver_id'].'/'.strtotime($statement_detail['check_date']).'/'.$statement_detail['truck_id']) ?>" class="btn btn-success default-btn">Run</a>
<?php } ?>

<?php if($statement_detail['status']=='Approved') {?>
  <?php if(session()->get('type')=='Admin') {?>
        <a  href="<?php echo base_url('admin/view/'.$statement_detail['driver_id'].'/'.strtotime($statement_detail['check_date']).'/'.$statement_detail['truck_id']) ?>" class="btn btn-success default-btn">View</a>
  <?php } else { ?>
    <a  href="<?php echo base_url('driver/report/'.$page_no) ?>" class="btn btn-success default-btn">View</a>
    <?php
  }
  
        }else { ?>
      <a    class="btn btn-success default-btn disabled">View</a>
      <?php
}

    ?>
     <?php if(session()->get('type')=='Admin') {?>
     <a  href="<?php echo base_url('admin/statement/delete/'.$statement_detail['driver_id'].'/'.strtotime($statement_detail['check_date']).'/'.$statement_detail['truck_id']) ?>" class="btn btn-danger delete-confirm ">Delete</a>
     <?php } ?>
    
    </td>
    </tr>
<?php
$page_no++;
}
?>

 
  </tbody>
</table>
</div>
<div class="col-12 pagination-main">
<?= $pager->links('default', 'bootstrap'); ?>


      </div>
       
      </div>
      </div>

   

   