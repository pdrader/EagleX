    <!-- Begin page content -->
 
 

      <div class="content">
      <div class="row justify-content-md-center">
      <div class="col-12">
      <div class="runreport-main">


      <form action="<?php echo base_url(); ?>/admin/runreport-update" method="post" >

      <?php

      ?>
      <input type="hidden" name="driver_id" value="<?php echo $driver_id; ?>">
      <input type="hidden" name="check_date" value="<?php echo $check_date; ?>">
      <input type="hidden" name="truck_id" value="<?php echo $truck_id; ?>">


      <h2 class="text-center"><b>Eagle Expedited, LLC</b></h2>
      <h5 class="text-center"><b>Settlement Statement</b></h5>
      <p class="text-center"><b>Date:</b> <?php  echo date("m/d/Y", strtotime($runreport_details[0]['check_date']));   ?> </p>
      
      

      <div class="row">
      
      <div class="col-6">
      <p class="text-start">  <?php  echo $driver_name;   ?> </p>
      </div>
     

  <div class="col-6">
 
      <p class="text-end">&nbsp;</p>
  </div> 

  <div class="col-6">
      <p class="text-start">  <?php  echo $driver_email;   ?> </p>
  </div>
  <div class="col-6">
  <p class="text-end"><b>Truck:</b> <?php  echo $runreport_details[0]['truck_number'];   ?> </p>
  </div>
      </div>

      <div class="row">
      <div class="col-12">
<h5  class="text-start"><b> Payments</b></h5>
  </div>
  </div>

  <div class="row">
      <div class="col-12">
  <table class="table table-bordered run-report-table">
  <thead>
    <tr>
     
      <th scope="col" width="120px">Pro #</th>
      <th scope="col" width="75px" >RPM</th>
      <th scope="col" width="75px">Miles</th>
      <th scope="col" width="75px">Factorial</th>
      <th scope="col">Detention</th>
      <th scope="col">Layover</th>
      <th scope="col">Stopoff</th>
      <th scope="col" width="50px">Handload</th>
      <th scope="col" width="50px">Deadhead</th>
      <th scope="col">Bonus</th>
      <th scope="col"  width="75px">Pro Total</th>
      <th scope="col" width="75px"> Action</th>
  


    </tr>
  </thead>
  <tbody>
  <?php 
$payments_subtotal=[];
$advance_details['driver_advance']=isset($advance_details['driver_advance'])?$advance_details['driver_advance']:'0.00';
$advance_details['misc']=isset($advance_details['misc'])?$advance_details['misc']:'0.00';
$advance_details['advance_repayment']=isset($advance_details['advance_repayment'])?$advance_details['advance_repayment']:'0.00';
$advance_details['occupational_insurance']=isset($advance_details['occupational_insurance'])?$advance_details['occupational_insurance']:'0.00';

$advance_details['advance_comment']=isset($advance_details['advance_comment'])?$advance_details['advance_comment']:'';
$advance_details['total_comment']=isset($advance_details['total_comment'])?$advance_details['total_comment']:'';




foreach($runreport_details as $runreport_detail){
?>
    <tr>     
      <td>        
      <input type="text"    class="form-control only-numeric-not-null" name="payment[<?php echo $runreport_detail['id']; ?>][pro_number]" value="<?php  echo trim($runreport_detail['pro_number']); ?>">
      </td>
      
      <td >        
      <input type="text" class="form-control only-decimal"  name="payment[<?php echo $runreport_detail['id']; ?>][rate]"  min="0" value="<?php  echo trim($runreport_detail['rate']); ?>">
     </td>
      <td >

      <input type="text"  class="form-control only-numeric" name="payment[<?php echo $runreport_detail['id']; ?>][miles]" value=" <?php  echo trim($runreport_detail['miles']); ?>">
 
      </td>
     <td >        
      <input type="text" class="form-control only-decimal"  name="payment[<?php echo $runreport_detail['id']; ?>][factorial]"  min="0" value="<?php  echo trim($runreport_detail['factorial']); ?>">
     </td>

      <td  >
       <div class="payment-20-text"> $<?php
         $detention=  number_format(($runreport_detail['detention']*trim($runreport_detail['factorial'])),2,'.','');         
         echo number_format($detention,2);
         ?></div>

        <input type="text" class="form-control only-decimal payment-20-input"  name="payment[<?php echo $runreport_detail['id']; ?>][detention]"  min="0" value="<?php  echo trim($runreport_detail['detention']); ?>">

    </td>
      <td>
      <div class="payment-20-text">

        $<?php  $layover= number_format(($runreport_detail['layover']*trim($runreport_detail['factorial'])),2,'.',''); 
           echo number_format($layover,2);
     
        
        ?>

        </div>


        <input type="text" class="form-control only-decimal payment-20-input"  name="payment[<?php echo $runreport_detail['id']; ?>][layover]"  min="0" value="<?php  echo trim($runreport_detail['layover']); ?>">
      
      </td>
      <td>
      <div class="payment-20-text">
        $<?php  
        $stopoff= number_format(($runreport_detail['stopoff']*trim($runreport_detail['factorial'])),2,'.','');

        echo number_format($stopoff,2);
        
        ?>
        </div>

        <input type="text" class="form-control only-decimal payment-20-input"  name="payment[<?php echo $runreport_detail['id']; ?>][stopoff]"  min="0" value="<?php  echo trim($runreport_detail['stopoff']); ?>">
      
      </td>
      <td   >
        
        <input type="text" class="form-control only-decimal"  name="payment[<?php echo $runreport_detail['id']; ?>][handload]"  min="0" value="<?php  echo trim($runreport_detail['handload']); ?>">
      </td>
      <td>
 
      
        <input type="text" class="form-control only-decimal"  name="payment[<?php echo $runreport_detail['id']; ?>][deadhead]"  min="0" value="<?php  echo trim($runreport_detail['deadhead']); ?>">
      </td>
      <td>
      <div class="payment-20-text">
        $<?php

          $bonus= number_format(($runreport_detail['bonus']*trim($runreport_detail['factorial'])),2,'.','');
          echo number_format($bonus,2);
          

         ?>
</div>

        <input type="text" class="form-control only-decimal payment-20-input"  name="payment[<?php echo $runreport_detail['id']; ?>][bonus]"  min="0" value="<?php  echo trim($runreport_detail['bonus']); ?>">
    
    
    </td>
      <td class="text-end">$<?php
      
       $total=(trim($runreport_detail['miles'])*trim($runreport_detail['rate'])*trim($runreport_detail['factorial']))+trim($detention)+trim($stopoff)+trim($layover)+trim($runreport_detail['handload'])+trim($runreport_detail['deadhead'])+trim($bonus);
      echo number_format($total,2);
      $payments_subtotal[]=trim($total);
      ?></td>

<td>

<a  href="<?php echo base_url('admin/pro/delete/'.$runreport_detail['id']); ?>" class="btn btn-danger  delete-confirm" >Delete</a>
</td>
     
    </tr>
<?php
}
?>
<tr>
  <td colspan="12">&nbsp; </td>
</tr>
<tr>
  <td colspan="12">&nbsp; </td>
</tr>
<tr>


<td colspan="5" rowspan="2" class="">

  
  <textarea class="form-control" id="advance_comment" name="advance_comment" rows="3" placeholder="Advance Comment"><?php echo trim($advance_details['advance_comment']) ?></textarea>
    
 
  


    
</td>  
<td   colspan="6" class="text-end">
Driver Advance

</td>

  <td>  <input type="text" class="form-control only-decimal"  name="driver_advance"  min="0"  value="<?php echo trim($advance_details['driver_advance']) ?>"></td>
</tr>

<tr>
   
  <td    colspan="6"  class="text-end">Misc</td>  
  <td> <input type="text" class="form-control only-decimal"  name="misc"  min="0" value="<?php echo trim($advance_details['misc']) ?>"></td>
</tr>
<tr>
  <td colspan="12">&nbsp; </td>
</tr>

<tr>
 
  <td  colspan="11"  class="text-end"><b>Payments Subtotal</b></td>
  <td class="text-end">$<?php 
  
  
  
 $payments_subtotal_sum=array_sum($payments_subtotal);


 $payments_subtotal_final=($payments_subtotal_sum+trim($advance_details['driver_advance'])+trim($advance_details['misc']));

  echo number_format($payments_subtotal_final,2);
  
  ?> </td>
 
</tr>
 
<tr>
  <td colspan="12">&nbsp; </td>
</tr>
<tr>
  <td colspan="12">&nbsp; </td>
</tr>
<tr>
  <td colspan="12"><h5><b>Deductions</b></h5></td>
</tr>

<tr>
  <td colspan="11" class="text-end">Advance Repayment</td>  
   
  <td> <input type="text" class="form-control only-decimal"  name="advance_repayment"  min="0" value="<?php echo  trim($advance_details['advance_repayment']) ?>"> </td>
</tr>
 
<tr>
  <td colspan="11" class="text-end">Occupational Insurance </td>
   
  <td> <input type="text"  class="form-control  only-decimal" name="occupational_insurance" value="<?php echo trim($advance_details['occupational_insurance']); ?>"></td>


  
</tr>

 





<tr>
  <td colspan="12">&nbsp; </td>
</tr>

<tr>
 
  <td colspan="11" class="text-end"><b>Deductions Subtotal</b></td>
  <td class="text-end">
    
$<?php

 


 $deduction_subtotal_final=(trim($advance_details['occupational_insurance'])+trim($advance_details['advance_repayment']));

  echo number_format($deduction_subtotal_final,2);
?>

</td>
 
</tr>

<tr>
<td colspan="5" rowspan="2"> 
  
<textarea class="form-control" id="total_comment" name="total_comment" rows="3" placeholder="Total Comment"><?php echo trim($advance_details['total_comment']) ?></textarea>
</td>
 
  <td colspan="7" >&nbsp; </td>
</tr>
<tr>
 
 
  <td  colspan="6" class="text-end">
  
  <h5><b>Total Payment</b> </h5>

   

</td>
  <td class="text-end">
    $<?php

$total_payment=($payments_subtotal_final-$deduction_subtotal_final);

echo number_format($total_payment,2);

    ?>

</td>


  </tbody>
  </table>


  </div>
  </div>
      
  <div class="row">
      <div class="col-12 text-end">
      <button type="submit" class="btn btn-success default-btn  recalculate" name="recalculate">Recalculate</button>
      <button type="submit" class="btn btn-success default-btn  recalculate_approved"  name="recalculate_approved">Recalculate & Approved</button>
</div>
</div>


</form>



      </div>
     


 
      </div>
       
      </div>
      </div>

   

   