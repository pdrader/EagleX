<?php 

	echo view('includes/header.php');
  ?>
     <div class="container middle-content">
     <?php if(session()->getFlashdata('error')):?>
                    <div class="alert alert-danger">
                       <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif;?>

                <?php if(session()->getFlashdata('success')):?>
                    <div class="alert alert-success">
                       <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif;?>

                <?php if(session()->getFlashdata('errors')):?>
                    
                     
                 <?php   
                   $errors= session()->getFlashdata('errors');

                   foreach($errors as $key=>$errorloop) 
                   {

                     foreach($errorloop as $childkey=>$childerrorloop) 
                     {
                        ?>
                <div class="alert alert-danger">
                       <?php

                       echo '#'.$key.' In '.$childkey.' Value Missing.';
                       ?>
                    </div>
                        <?php
                       
                     }



                   }


                    ?> 
                <?php endif;?>

                
                <?php if(session()->getFlashdata('warings')):?>
                    
                     
                    <?php   
                      $warings= session()->getFlashdata('warings');
   
                      foreach($warings as $wkey=>$waringloop) 
                      {
   
                        foreach($waringloop as $wchildkey=>$childwaringloop) 
                        {
                           ?>
                   <div class="alert alert-warning">
                          <?php
   
                          echo '#'.$wkey.' In '.$wchildkey.' Value Missing.';
                          ?>
                       </div>
                           <?php
                          
                        }
   
   
   
                      }
   
   
                       ?> 
                   <?php endif;?>


  <?php
	echo view($main_content);
  ?>
  </div>
  <?php
	echo view('includes/footer.php');

?>