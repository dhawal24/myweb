<?php
    ob_start();
    require_once("inner-header.php");

    /*if(empty($_SESSION['zipCode'])){
        header("location:".root."check-zipcode");
    }*/
?>

    <div class="orderProcess">
        <div class="container">
            <div class="row">
                <div class="col">
                    <ul class="progressbar">
                        <li class="active"><a href="<?php echo root; ?>my-cart">My Cart</a></li>
                        <li class="active"><a href="<?php echo root; ?>upload-prescription">Upload Prescription</a></li>
                        <li>Delivery Address</li>
                        <li>Confirm Order</li>
                        <!--<li>Order Review</li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <section class="innerContent">        
        <form method="POST" action="<?php root; ?>confirm-order">
            <div class="container">
                <div class="row">
                    <div class="col col-md-6">
                        <h3 class="text-left">Delivery Address</h3>
                    </div>
                    <div class="col col-md-6 text-right">
                        <a href="#" class="secondaryBtn" data-toggle="modal" data-target="#addAddress">Add Address</a>
                    </div>
                </div>                
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="row">
                            <?php
                                $countAddress = $connection->OneRowCondition("count(*) AS COUNT", "mrx_customer_address", "user_foreign_id='{$_SESSION['mrx_user_id']}'");
                                if($countAddress['COUNT'] == 0){
                            ?>
                                <div class="col"><strong>No Address added.</strong></div>
                            <?php }else { 
                                $html = "";
                                $addressQuery = $connection->tableDataCondition("*","mrx_customer_address","user_foreign_id='{$_SESSION['mrx_user_id']}'");
                                while($addressRow = $addressQuery->fetch(PDO::FETCH_ASSOC)){
                                    $html .= "<div class='col col-md-6'>";
                                        $html .= "<div class='card whiteBox boRadius'>";
                                            $html .= "<label>";
                                            $html .= "<div class='card-body'>";
                                            $html .= "<h5 class='card-title'>".$addressRow['patient_name']."</h5>";
                                            $html .= "<input name='deliveryAdd' type='radio' class='deliveryRadio' required value='".$addressRow['address_id']."'/>";
                                                $html .= "<p class='card-text'><strong>Address</strong>:<br>".$addressRow['bldg_name']."<br>".$addressRow['street_name']."<br>".$addressRow['pincode']."</p>";
                                                $html .= "<p class='card-text'><strong>Mobile:</strong> ".$addressRow['mobile']."</p>";
                                                $html .= "<p class='card-text'><strong>Type: </strong>".$addressRow['address_type']."</p>";
                                                $html .= "<p class='card-text'>
                                                            <a href='".root."remove-address/".$addressRow['address_id']."' class='badge badge-pill badge-light float-left'>Remove</a> 
                                                            <a href='".root."edit-address/".$addressRow['address_id']."' class='badge badge-pill badge-info float-right'>Edit</a>
                                                    </p>";
                                            $html .= "</div>";
                                            $html .= "</label>";
                                        $html .= "</div>";
                                    $html .= "</div>";
                                }

                                echo $html;
                            } 
                            ?>  
                        </div>               
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="whiteBox boRadius checkout text-center"> 
                            <div class="cartSuccess">
                                <button type="submit" class="mainBtn">Confirm Order</button>
                            </div>
                        </div> 
                        
                        <?php
                            $medicineCountQuery = $connection->OneRowCondition("count(*) AS COUNT", "mrx_temp_basket", "temp_basket_session_id='{$sessionId}' and temp_basket_type='Medicine'");
                            if($medicineCountQuery['COUNT'] > 0){
                        ?>
                        <h5>Medicines</h5>
                        <div class="whiteBox boRadius checkout text-center"> 
                        <input type="hidden" name="medicineOrder" id="medicineOrder" value="true">
                        <table width="100%">
                                    <tr>
                                        <th width="60%" align="left">Product Name</th>
                                        <th width="20%" align="left">Qty</th>
                                        <th width="20%" align="right">Price</th>
                                    <tr>
                                <?php 
                                    $totalAmt = 0; 
                                    $hpQuery = $connection->tableDataCondition("*", "mrx_temp_basket", "temp_basket_session_id='{$sessionId}' and temp_basket_type='Medicine'");
                                    while($hpRow = $hpQuery->fetch()){  
                                        $proQuery = $connection->OneRowCondition("pro_name, pro_id", "mrx_product", "pro_id='{$hpRow['temp_basket_pro_id']}'");         
                                        $totalAmt = $totalAmt + $hpRow['temp_basket_grand_total'];                
                                ?>
                                    <tr>
                                        <td><?php echo substr($proQuery['pro_name'],0,25); ?>..</td>
                                        <td><?php echo $hpRow['temp_basket_qty']; ?></td>
                                        <td><i class='fa fa-inr'></i> <?php echo $hpRow['temp_basket_grand_total']; ?></td>
                                    </tr>
                                <?php } ?>
                                    <tr>
                                        <td colspan="2" style="text-align:right"><strong>Total:</strong></td>
                                        <td><i class='fa fa-inr'></i> <?php echo number_format($totalAmt,2); ?></td>
                                    </tr>
                                </table>
                                
                            <input type="hidden" name="medicineTotal" id="medicineTotal" value="<?php echo number_format($totalAmt,2); ?>">
                        </div>
                        <?php }
                            $hpCountQuery = $connection->OneRowCondition("count(*) AS COUNT", "mrx_temp_basket", "temp_basket_session_id='{$sessionId}' and temp_basket_type='Healthcare Products'");
                            if($hpCountQuery['COUNT'] > 0){
                        ?>

                         <h5>Healthcare Products</h5>
                        <div class="whiteBox boRadius checkout text-center"> 
                            <div class="table-responsive">
                                <input type="hidden" name="hpOrder" id="hpOrder" value="true">
                                <table width="100%">
                                    <tr>
                                        <th width="60%" align="left">Product Name</th>
                                        <th width="20%" align="left">Qty</th>
                                        <th width="20%" align="right">Price</th>
                                    <tr>
                                <?php 
                                    $totalAmt = 0; 
                                    $hpQuery = $connection->tableDataCondition("*", "mrx_temp_basket", "temp_basket_session_id='{$sessionId}' and temp_basket_type='Healthcare Products'");
                                    while($hpRow = $hpQuery->fetch()){  
                                        $proQuery = $connection->OneRowCondition("pro_name, pro_id", "mrx_product", "pro_id='{$hpRow['temp_basket_pro_id']}'");         
                                        $totalAmt = $totalAmt + $hpRow['temp_basket_grand_total'];                
                                ?>
                                    <tr>
                                        <td><?php echo substr($proQuery['pro_name'],0,25); ?>..</td>
                                        <td><?php echo $hpRow['temp_basket_qty']; ?></td>
                                        <td><i class='fa fa-inr'></i> <?php echo $hpRow['temp_basket_grand_total']; ?></td>
                                    </tr>
                                <?php } ?>
                                    <tr>
                                        <td colspan="2" style="text-align:right"><strong>Total:</strong></td>
                                        <td><i class='fa fa-inr'></i> <?php echo number_format($totalAmt,2); ?></td>
                                    </tr>
                                </table>
                            <input type="hidden" name="hpTotal" id="hpTotal" value="<?php echo number_format($totalAmt,2); ?>">
                            </div>
                        </div>
                        <?php } ?>
                        
                        <h5>Uploaded Prescription</h5>
                        <div class="whiteBox boRadius checkout text-center"> 
                            <?php 
                                if(!empty($_SESSION['uploadImagePrescription'])){
                                    foreach($_SESSION['uploadImagePrescription'] as $key => $value) {
                                        echo "<img src='".root.$value."' class='img-fluid' style='max-width:150px;'>";
                                        echo "<input type='hidden' name='prescriptionImg' id='prescriptionImg' value='".$value."'>";
                                    }
                                }
                            ?> 
                        </div>
                        
                        <h5>Additional Notes</h5>
                        <div class="whiteBox boRadius checkout text-center"> 
                        <textarea class="form-control" name="notes" id="notes" placeholder="Enter text here in case of any additional information regarding your order"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="whiteBox boRadius">
                            <h4 class="text-center">You can also order calling us at <span><a href="tel:+91-70390 70391">+91 70390 70391</a></span></h4>
                        </div>
                    </div>
                </div>           
            </div>
        </form>
    </section>

    <!-- The Modal -->
    <div class="modal fade" id="addAddress">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add New Address</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Modal body -->
                <div class="modal-body">
                    <form method="POST" class="medsForm">
                        <div class="form-group">
                            <label for="patientName">Patient Name <span class="red">*</span></label>
                            <input type="text" class="form-control" id="patientName" name="patientName" required>
                        </div>
                        <div class="form-group">
                            <label for="bldgName">Building Name and Flat Number <span class="red">*</span></label>
                            <input type="text" class="form-control" id="bldgName" name="bldgName" required>
                        </div>
                        <div class="form-group">
                            <label for="streetName">Street Name<span class="red">*</span></label>
                            <input type="text" class="form-control" id="streetName" name="streetName" required>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="pinCode">Pincode <span class="red">*</span></label>
                                <input type="text" class="form-control" id="pinCode" name="pinCode" onchange="checkZipCode();" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="mobileNo">Mobile Number<span class="red">*</span></label>
                                <input type="text" class="form-control" id="mobileNo" name="mobileNo" required>
                            </div>                                    
                            
                        </div>
                        <div class="form-group">
                            <label for="addressType">Address Type<span class="red">*</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="addressType" id="home" value="Home" checked>
                                <label class="form-check-label" for="home">Home</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="addressType" id="office" value="Office">
                                <label class="form-check-label" for="office">Office</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="addressType" id="other" value="Others">
                                <label class="form-check-label" for="other">Others</label>
                            </div>
                        </div>
                        
                        <button type="button" name="addAddressBtn" id="addAddressBtn" onclick="addAddress();" class="btn btn-default">Save</button>
                    </form>
                </div>                
                
                
            </div>
        </div>
    </div>
      
    <?php require_once("footer.php"); ?> 
  </body>
</html>