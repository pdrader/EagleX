 
 

      
      <div class="runreport-main">


       

      
     


      <h2 style=" text-align: center;"><b>Eagle Expedited, LLC</b></h2>
      <h3 style=" text-align: center;"><b>Settlement Statement</b></h3>
      <p style=" text-align: center;"><b>Date:</b> <?php  echo date("m/d/Y", strtotime($runreport_details[0]['check_date']));   ?> </p>
     
      

      <div class="row">


      <div style="width:50%; float:left;">
      <p style=" text-align: left;">  <?php  echo $driver_name;   ?> </p>
  </div>


  <div style="width:50%; float:left;">
      <p style=" text-align: right;">&nbsp;</p>
  </div>

  <div style="width:50%; float:left;">
      <p style=" text-align: left;">  <?php  echo $driver_email;   ?> </p>
  </div>

  <div style="width:50%; float:left;">
  <p style=" text-align: right;"><b>Truck:</b> <?php  echo $runreport_details[0]['truck_number'];   ?> </p>
  </div>
      </div>

      <div class="row">
      <div class="col-12">
<h3  style=" text-align: left;"><b> Payments</b></h3>
  </div>
  </div>

  <div class="row">
      <div class="col-12">
  <table class="table table-bordered run-report-table"   width="100%" cellpadding="2" cellspacing="0" border="1">
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
$advance_details['occupational_insurance']=isset($advance_details['occupational_insurance'])?$advance_details['occupational_insurance']:'0.00';

$advance_details['advance_comment']=isset($advance_details['advance_comment'])?$advance_details['advance_comment']:'';
$advance_details['total_comment']=isset($advance_details['total_comment'])?$advance_details['total_comment']:'';
foreach($runreport_details as $runreport_detail){
?>
    <tr>     
      <td style=" text-align: right;">        
      <?php  echo trim($runreport_detail['pro_number']); ?> 
      </td>
      
      <td  style=" text-align: right;" >        
       $<?php  echo trim($runreport_detail['rate']); ?> 
     </td>
      <td  style=" text-align: right;">

       <?php  echo trim($runreport_detail['miles']); ?> 
 
      </td>
      <td  style=" text-align: right;" >
        $<?php
         $detention=  number_format(($runreport_detail['detention']*trim($runreport_detail['factorial'])),2,'.',''); 
         echo number_format($detention,2);
         ?> 

       
    </td>
      <td style=" text-align: right;" >
      

        $<?php  $layover= number_format(($runreport_detail['layover']*trim($runreport_detail['factorial'])),2,'.',''); 
        
        echo number_format($layover,2);
        
        ?>

        


 
      
      </td>
      <td style=" text-align: right;">
      
        $<?php  
        $stopoff= number_format(($runreport_detail['stopoff']*trim($runreport_detail['factorial'])),2,'.','');
        echo number_format($stopoff,2);
        ?>
         

        
      
      </td>
      <td  style=" text-align: right;" >
        
         $<?php  echo number_format(trim($runreport_detail['handload']),2); ?> 
      </td>
      <td style=" text-align: right;">
 
      
       $<?php  echo number_format(trim($runreport_detail['deadhead']),2); ?> 
      </td>
      <td style=" text-align: right;">
      
        $<?php

          $bonus= number_format(($runreport_detail['bonus']*trim($runreport_detail['factorial'])),2,'.','');
          echo number_format($bonus,2);

         ?>
 

        
    
    
    </td>
      <td style=" text-align: right;">
      $<?php
      
       $total=(trim($runreport_detail['miles'])*trim($runreport_detail['rate'])*trim($runreport_detail['factorial']))+trim($detention)+trim($stopoff)+trim($layover)+trim($runreport_detail['handload'])+trim($runreport_detail['deadhead'])+trim($bonus);
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
Comments:   
<?php echo trim($advance_details['advance_comment']) ?> 
 

 
</td> 
  <td colspan="4" style=" text-align: right;">Driver Advance </td>  
  <td style=" text-align: right;">  $<?php echo number_format(trim($advance_details['driver_advance']),2) ?> 
</tr>

<tr>
  <td colspan="4"  style=" text-align: right;">Misc</td>  
  <td style=" text-align: right;">  $<?php echo number_format(trim($advance_details['misc']),2) ?> </td>
</tr>
<tr>
  <td colspan="10">&nbsp; </td>
</tr>

<tr>
 
  <td  colspan="9"  style=" text-align: right;"><b>Payments Subtotal</b></td>
  <td style=" text-align: right;">$<?php 
  
  
  
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
  <td colspan="10"><h3><b>Deductions</b></h3></td>
</tr>


 


 
<tr>
  <td colspan="9" style=" text-align: right;">Advance Repayment</td>  
   
  <td style=" text-align: right;">  $<?php echo  number_format(trim($advance_details['advance_repayment']),2) ?>  </td>
</tr>

<tr>
  <td colspan="9" style=" text-align: right;">Occupational Insurance  </td>
   
  <td style=" text-align: right;">
    

  $<?php echo number_format(trim($advance_details['occupational_insurance']),2); ?>

 

</td>


  
</tr>


<tr>
  <td colspan="10">&nbsp; </td>
</tr>

<tr>
 
  <td colspan="9" style=" text-align: right;"><b>Deductions Subtotal</b></td>
  <td style=" text-align: right;">
    $
<?php

 


 $deduction_subtotal_final=(trim($advance_details['occupational_insurance'])+trim($advance_details['advance_repayment']));

  echo number_format($deduction_subtotal_final,2);
?>

</td>
 
</tr>

<tr>
<td colspan="5" rowspan="2"> 
  Comment: 
 <?php echo trim($advance_details['total_comment']) ?>
</td>
 
  <td colspan="6" >&nbsp; </td>
</tr>
<tr>
 
  <td colspan="4" style=" text-align: right;"><h3><b>Total Payment</b> </h3></td>
  <td style=" text-align: right;">
    $<?php

$total_payment=($payments_subtotal_final-$deduction_subtotal_final);

echo number_format($total_payment,2 );

    ?>

</td>


  </tbody>
  </table>


  </div>
  </div>
      
 


 



      </div>
     


  
   