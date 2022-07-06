    <!-- Begin page content -->
 
 

      <div class="content">
      <div class="row justify-content-md-center">
      <div class="col-12">
      <div class="runreport-main"  id="printarea">


       

      <?php
if($total_record==0)
{
  ?>
<h2>There is not any settlement statement for you.</h2>
  <?php

}else{
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
 
      <p class="text-end"><b>Driver:</b> <?php  echo $runreport_details[0]['driver_number'];   ?> </p>
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
     
      <th scope="col"  >Pro #</th>
      <th scope="col"   >RPM</th>
      <th scope="col" >Miles</th>
      <th scope="col">Detention</th>
      <th scope="col">Layover</th>
      <th scope="col">Stopoff</th>
      <th scope="col" >Handload</th>
      <th scope="col"  >Deadhead</th>
      <th scope="col">Bonus</th>
      <th scope="col">Pro Total</th>
  


    </tr>
  </thead>
  <tbody>
  <?php 
$payments_subtotal=[];
$advance_details['driver_advance']=isset($advance_details['driver_advance'])?$advance_details['driver_advance']:'0.00';
$advance_details['misc']=isset($advance_details['misc'])?$advance_details['misc']:'0.00';
$advance_details['advance_repayment']=isset($advance_details['advance_repayment'])?$advance_details['advance_repayment']:'0.00';
$advance_details['advance_comment']=isset($advance_details['advance_comment'])?$advance_details['advance_comment']:'';
$advance_details['total_comment']=isset($advance_details['total_comment'])?$advance_details['total_comment']:'';
foreach($runreport_details as $runreport_detail){
?>
    <tr>     
      <td  class="text-end">        
      <?php  echo trim($runreport_detail['pro_number']); ?> 
      </td  class="text-end">
      
      <td  class="text-end" >        
       $<?php  echo trim($runreport_detail['rate']); ?> 
     </td>
      <td  class="text-end">

       <?php  echo trim($runreport_detail['miles']); ?> 
 
      </td>
      <td  class="text-end" >
        $<?php
         $detention=  number_format(($runreport_detail['detention']*trim($runreport_detail['factorial'])),2,'.',''); 
         echo number_format($detention,2);
         ?> 

       
    </td>
      <td class="text-end" >
      

        $<?php  $layover= number_format(($runreport_detail['layover']*trim($runreport_detail['factorial'])),2,'.',''); 
        
        echo number_format($layover,2);
        
        ?>

        


 
      
      </td>
      <td class="text-end">
      
        $<?php  
        $stopoff= number_format(($runreport_detail['stopoff']*trim($runreport_detail['factorial'])),2,'.','');
        echo number_format($stopoff,2);
        ?>
         

        
      
      </td>
      <td  class="text-end" >
        
         $<?php  echo number_format(trim($runreport_detail['handload']),2); ?> 
      </td>
      <td class="text-end">
 
      
       $<?php  echo number_format(trim($runreport_detail['deadhead']),2); ?> 
      </td>
      <td class="text-end">
      
        $<?php

          $bonus= number_format(($runreport_detail['bonus']*trim($runreport_detail['factorial'])),2,'.','');
          echo number_format($bonus,2);

         ?>
 

        
    
    
    </td>
      <td class="text-end">
        $<?php
      
       $total=(trim($runreport_detail['miles'])*trim($runreport_detail['rate']))+trim($detention)+trim($stopoff)+trim($layover)+trim($runreport_detail['handload'])+trim($runreport_detail['deadhead'])+trim($bonus);
      echo number_format($total,2);
      $payments_subtotal[]=trim($total);
      ?></td>


     
    </tr>
<?php
}
?>
<tr>
  <td colspan="10">&nbsp; </td>
</tr>
<tr>
  <td colspan="10">&nbsp; </td>
</tr>
<tr>
<td colspan="5" rowspan="2" class="">

  
 <?php echo trim($advance_details['advance_comment']) ?> 
  
 
  
</td> 
  <td colspan="4" class="text-end">Driver Advance </td>  
  <td class="text-end">  $<?php echo number_format(trim($advance_details['driver_advance']),2) ?> 
</tr>

<tr>

  <td colspan="4"  class="text-end">Misc</td>  
  <td class="text-end">  $<?php echo number_format(trim($advance_details['misc']),2) ?> </td>
</tr>
<tr>
  <td colspan="10">&nbsp; </td>
</tr>

<tr>
 
  <td  colspan="9"  class="text-end"><b>Payments Subtotal</b></td>
  <td class="text-end">
    $<?php 
  
  
  
 $payments_subtotal_sum=array_sum($payments_subtotal);


 $payments_subtotal_final=($payments_subtotal_sum+trim($advance_details['driver_advance'])+trim($advance_details['misc']));

  echo number_format($payments_subtotal_final,2);
  
  ?> </td>
 
</tr>
 
<tr>
  <td colspan="10">&nbsp; </td>
</tr>
<tr>
  <td colspan="10">&nbsp; </td>
</tr>
<tr>
  <td colspan="10"><h5><b>Deductions</b></h5></td>
</tr>


<?php 
 $deduction_subtotal=array();
foreach($deduction_details as $deduction_detail){
?>
<tr>
  <td colspan="9" class="text-end">
    

   
  <?php
  echo $deduction_detail['description'];
  ?>
  </td>
   
  <td class="text-end">
    

  $<?php echo number_format(trim($deduction_detail['deduction_amount']),2); ?>

<?php
 $deduction_subtotal[]=trim($deduction_detail['deduction_amount']);

?>

</td>


  
</tr>

<?php
}
?>
<tr>
  <td colspan="9" class="text-end">Advance Repayment</td>  
   
  <td class="text-end">  $<?php echo  number_format(trim($advance_details['advance_repayment']),2); ?>  </td>
</tr>




<tr>
  <td colspan="10">&nbsp; </td>
</tr>

<tr>
 
  <td colspan="9" class="text-end"><b>Deductions Subtotal</b></td>
  <td class="text-end">
    
$<?php

$deduction_subtotal_sum=array_sum($deduction_subtotal);


 $deduction_subtotal_final=($deduction_subtotal_sum+trim($advance_details['advance_repayment']));

  echo number_format($deduction_subtotal_final,2);
?>

</td>
 
</tr>

<tr>
<td colspan="5" rowspan="2"> 
  
 <?php echo trim($advance_details['total_comment']) ?>
</td>
 
  <td colspan="6" >&nbsp; </td>
</tr>
<tr>
 
 
  <td colspan="4" class="text-end"><h5><b>Total Payment</b> </h5></td>
  <td class="text-end">
    $<?php

$total_payment=($payments_subtotal_final-$deduction_subtotal_final);

echo number_format($total_payment,2);

    ?>

</td>


  </tbody>
  </table>


  </div>
  <div class="col-12 text-end">

  <a href="javascript:void(0)" class="btn btn-success default-btn " id="print-report">Print</a>
   
  <a href="<?php  echo base_url('driver/report/send/'.$driver_id.'/'.strtotime($check_date).'/'.$truck_id) ?>" class="btn btn-success default-btn ">Resend Report</a>
</a>

</div>

  </div>
      
  <div class="row bottom-pagination">
      <div class="col-6 text-start">
        <?php if($page_num>0){ ?>
      <a href="<?php  echo base_url('driver/report/'.($page_num-1))?>" class="btn btn-success default-btn previous round">&#8249;</a>
      <?php } ?>
</div>

<div class="col-6 text-end">

<?php if($total_record>$page_num+1){ ?>
<a href="<?php  echo base_url('driver/report/'.($page_num+1))?>" class="btn btn-success default-btn next round">&#8250;</a>
<?php } ?>


</div>
</div>

<?php

}
?>
 



      </div>
     


 
      </div>
       
      </div>
      </div>

   

   