    <!-- Begin page content -->
 
      <div class="page-header">
        <h5>Statements</h5>
      </div>
      <div class="content">
      <div class="row justify-content-md-center">
      <div class="col-9 text-end"> 
        &nbsp;
  </div>
      <div class="col-3 text-end">
        <?php
   $current_week = date('w');
  $selected_date =    strtotime('monday this week');
  if(isset($_GET['filter_date']))
{
  $selected_date = $_GET['filter_date'];
}
        ?>
        <form  id= "change_date_form">
      <select name="filter_date" class="form-select " id= "change_date">
      <?php
  
foreach($date_list as $week_key=>$date_listloop){
?>

<option value="<?php echo strtotime($date_listloop['week_start']); ?>" <?php echo (strtotime($date_listloop['week_start'])==$selected_date)?"selected":'' ?>><?php echo  $date_listloop['week_start'].' - '. $date_listloop['week_end']; ?></option>

<?php
}
?>
      </select>
        </form>
 </div>
      
      <div class="col-12">
      
     


<table class="table table-striped">
  <thead>
    <tr>
     
      <th scope="col">Driver Name</th>
      <th scope="col">Pay Date</th>
      <th scope="col">Truck Number</th>
      <th scope="col">Total</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>


  <?php 
$page_no=0;
$total_loop=[];
foreach($statement_details as $statement_detail){
?>
    <tr>     
      <td><?php  echo $statement_detail['name']; ?></td>
      <td><?php  echo date("m/d/Y", strtotime($statement_detail['check_date']));   ?></td>
      <td><?php  echo $statement_detail['truck_number']; ?></td>
 <td><?php 
 $pro_total= $statement_detail['total']['pro_total'];
 $driver_advance= $statement_detail['total']['advance_details']['driver_advance'];
 $misc= $statement_detail['total']['advance_details']['misc'];
 $advance_repayment= $statement_detail['total']['advance_details']['advance_repayment'];
 $occupational_insurance= $statement_detail['total']['advance_details']['occupational_insurance'];

 $totalloop= ($pro_total+$driver_advance+$misc)-($advance_repayment+$occupational_insurance);
echo '$';
echo  number_format($totalloop,2);
 $total_loop[]= $totalloop;
 
 ?></td>
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


<?php if(!empty($total_loop)){ ?>
<tr>
<td colspan="3"></td>
<td colspan="2">
  
<b>$<?php
echo number_format(array_sum($total_loop),2);
?></b>

</td>


</tr>
<?php } else {
?>
<tr>
<td  class="text-center" colspan="5"><b>No data for selected week.</b></td>
</tr>
<?php
} ?>
 
  </tbody>
</table>
</div>

<div class="row bottom-pagination">
      <div class="col-6 text-start">
      
      <a href="javascript:void(0)" id="previous_list" class="btn btn-success default-btn  round">&#8249;</a>
      
</div>

<div class="col-6 text-end">

 
<a href="javascript:void(0)"  id="next_list"  class="btn btn-success default-btn  round">&#8250;</a>
 


</div>
</div>

<div class="col-12 pagination-main">
<?php  //$pager->links('default', 'bootstrap'); ?>


      </div>
       
      </div>
      </div>

   

   