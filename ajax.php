<?php
	ob_start();
    session_start();
    $sessionId = session_id();
    require_once("web_admin/db.php");    
    $date = date('Y-m-d H:i:s');

    require_once('php-mailer/class.phpmailer.php');

    /*use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require 'php-mailer/src/PHPMailer.php';
	require 'php-mailer/src/SMTP.php';
	require 'php-mailer/src/Exception.php';*/


    $type  = $_REQUEST['type'];
    $html = "";

    if($type=="ZipCode"){
       $ZipCode = $_REQUEST['checkZip'];

       if(empty($ZipCode)){
            $html .= "Please enter Zip Code";
            $status = "Error";
       }else{

            $zcQuery = $connection->OneRowCondition("count(*) as COUNT" ,"mrx_zipcode","zip_code='".$ZipCode."' and zip_status='Active'");
            
            if($zcQuery['COUNT'] > 0){

                $html .= "Delivery Available at <strong>".$ZipCode."</strong>";
                $_SESSION['zipCode'] = $ZipCode;
                $status = "Success";
            }else{
                $html .= "Invalid Pincode entered!";
                $status = "Error";
            
            }
        }


        $returnArry = array(
            "data" => $html,
            "status" => $status
        );
        
        echo json_encode($returnArry);

        $html = "";
    }

    else if($type=="addAddress"){

        $patientName = $_REQUEST['patientName'];
        $bldgName = $_REQUEST['bldgName'];
        $streetName = $_REQUEST['streetName'];
        $pinCode = $_REQUEST['pinCode'];
        $mobileNo = $_REQUEST['mobileNo'];
        $addressType = $_REQUEST['addressType'];

        if(!empty($patientName) || !empty($bldgName) || !empty($streetName)){

            $addCol = array(
                'patient_name'=> $patientName, 
                'bldg_name'=> $bldgName, 
                'street_name'=> $streetName, 
                'pincode'=> $pinCode, 
                'mobile'=> $mobileNo, 
                'user_foreign_id'=> $_SESSION['mrx_user_id'], 
                'address_type'=> $addressType, 
                'created_date'=> $date);
                
            $connection->InsertQuery("mrx_customer_address", $addCol); 

            $html = "Address added successfully";
            $status = "Success";

        }else{

            $html = "Please fill all the fields";
            $status = "Error";
        }
 
         $returnArry = array(
             "data" => $html,
             "status" => $status
         );
         
         echo json_encode($returnArry);
 
         $html = "";
     }

    else if($type=="uploadImgSession"){
        if(!empty($_FILES)){
            //print_r($_FILES);
            foreach($_FILES as $file){
                $name       = basename($file['name']);
                $tmp_name   = $file['tmp_name'];
                $size       = $file['size'];
            }
            echo json_encode($_FILES);
        }else{
            echo "not file";
        }
        
    }
    
    else if($type=="loginFront"){

        $uname = $_REQUEST['medsUsername'];
        $upass = $_REQUEST['medsPassword'];
        
        $stmtl = $conn->prepare("SELECT user_password, user_id FROM mrx_customer WHERE user_email=:username and user_status='Active' LIMIT 1");
		$stmtl->execute(array(':username'=>$uname));
		$userRow=$stmtl->fetch(PDO::FETCH_ASSOC);
		if($stmtl->rowCount() > 0){
			if($upass == $userRow['user_password']){		
				$_SESSION['mrx_user_id'] = $userRow['user_id'];				
                //$connection->redirect('index.php');
                $html = "Login is successfully";
                $status = "Success";
			}else
			{
                //$connection->redirect(root."login?loginError1=Wrong");
                $html = "Wrong credentials provided";
                $status = "Error";
			}
		}else{
            //$connection->redirect(root."login?loginError2=User");
            $html = "Username not found";
            $status = "Error";
        }
        
        $returnArry = array(
            "data" => $html,
            "status" => $status
        );
        
        echo json_encode($returnArry);
        $html = "";
    }

    else if($type=="registerFront"){

        $customerName = $_REQUEST['medsName'];
        $customerPhone = $_REQUEST['medsRegPhone'];
        $customerEmail = $_REQUEST['medsEmail'];
        $random = md5(uniqid(rand(), true));
        $error = $customerHtml = $adminHtml = "";

        if(!empty($customerName) || !empty($customerPhone) || !empty($customerEmail)){

            $existUser = $connection->OneRowCondition("count(*) as COUNT", "mrx_customer", "user_email='{$_REQUEST['medsEmail']}'");
            
            if($existUser['COUNT']== 0){

                $password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"),0,8);

                $catCol = array(
                    'user_full_name'=>$_REQUEST['medsName'], 
                    'user_email'=>$_REQUEST['medsEmail'], 
                    'user_phone'=>$_REQUEST['medsRegPhone'], 
                    'user_password' => $password,
                    'user_random_code'=>$random, 
                    'user_status'=>'Inactive', 
                    'user_created_date'=>$date);
                    
                $connection->InsertQuery("mrx_customer", $catCol); 
                $lastId = $conn->lastInsertId();
                
                //$_SESSION['mrx_user_id'] = $lastId;
                //$connection->redirect("index.php");
                $html = "Registered successfully";
                $status = "Success";

                $stmpQuery = $connection->OneRow("*","mrx_smtp_settings");
                $adminQuery = $connection->OneRow("*","mrx_email_settings");

                $secure = $stmpQuery['smtp_secure'];
                $host = $stmpQuery['smtp_host'];
                $port = $stmpQuery['smtp_port'];
                $username = $stmpQuery['smtp_username'];
                $password = $stmpQuery['smtp_password'];
                $fromEmail = "info@medsrx.in";
                $fromName = "MedsRx.in";

                //Sending email to Customer
                $customerEmail = $connection->OneRowCondition("*","mrx_email_content", "email_type='Register'");

                $customerHtml .= $connection->emailHeader();
                    $customerHtml .= "<p style='font-size:11px; text-align:center;'>Having problem in viewing ? Open on <a href='".root."/emails/email-register?email=".$_REQUEST['medsEmail']."' target='_blank'>Browser</a></p>";
                    $customerHtml .= "<div style='background:#fff; min-height:500px; border-radius:10px; padding:15px;'>";
                        $customerHtml .= "<div style='text-align:center; padding:5px 0; border-bottom:1px solid #eee;'><img src='http://www.medsrx.in/demo/images/medsrx-logo.png' border='0'></div>";

                        $userContent = $customerEmail['email_content'];
                        $userContent = str_replace("~name~", $_REQUEST['medsName'], $userContent);

                        $customerHtml .= "<p>".$userContent."</p>";
                        $customerHtml .= $customerEmail['email_signature'];
                    $customerHtml .= "</div>";
                $customerHtml .= $connection->emailFooter();

                $subject = $customerEmail['email_subject'];
              
                $sendEmail = $connection->sendEmail($fromEmail, $fromName, $subject, $customerHtml, $_REQUEST['medsEmail']);
                

                //Sending email to Admin
                $adminEmail = $connection->OneRowCondition("*","mrx_email_content", "email_type='Admin Register'");
                
                $adminHtml .= $connection->emailHeader();
                    $adminHtml .= "<div style='background:#fff; min-height:500px; border-radius:10px; padding:15px;'>";
                        $adminHtml .= "<div style='text-align:center; padding:5px 0; border-bottom:1px solid #eee;'><img src='http://www.medsrx.in/demo/images/medsrx-logo.png' border='0'></div>";

                        $userContent = $adminEmail['email_content'];
                        $userContent = str_replace("~name~", $_REQUEST['medsName'], $userContent);
                        $userContent = str_replace("~email~", $_REQUEST['medsEmail'], $userContent);

                        $adminHtml .= "<p>".$userContent."</p>";
                        $adminHtml .= $adminEmail['email_signature'];
                    $adminHtml .= "</div>";
                $adminHtml .= $connection->emailFooter();

                $adminSub = $adminEmail['email_subject'];

                $sendAdminEmail = $connection->sendEmail($fromEmail, $fromName, $adminSub, $adminHtml, $adminQuery['email_address']);

                //Sending SMS
                $smsCount = $connection->smsQuery();

                /*if($smsCount > 0){

                }*/

                $html = "Email sent";
                $status = "Success";
            }   
            else{
                //$connection->redirect(root."login?regError=Already exist&type=Register");
                $html = "Email already registered!!";
                $status = "Error";
            }

        }

         
        $returnArry = array(
            "data" => $html,
            "status" => $status
        );
        
        echo json_encode($returnArry);
        $html = "";

    }

    else if($type=="ajaxPrice"){

        $qty = $_REQUEST['qty'];
        $proid = $_REQUEST['proid'];
        $unit = $_REQUEST['unit'];
        
        if($qty=="" || $qty == 0){
            $qty = 1;	
        }

        $unitQuery = $connection->OneRowCondition("*", "mrx_pro_unit", "unit_id='{$unit}'");

        $totalAmt = number_format($qty*$unitQuery['unit_price'], 2);

        $returnArry = array(
            "qtySend" => $qty ,
            "boxunitVal" => $unit,
            "totalAmt" => $totalAmt,
        );

        echo json_encode($returnArry);

    }

    else if($type=="ajaxQty"){

        $qty = $_REQUEST['qty'];
	    $proid = $_REQUEST['proid'];
        $unit = $_REQUEST['unit'];
        $tempId = $_REQUEST['tempId'];
        
        if($qty=="" || $qty == 0){
            $qty = 1;	
        }

        $unitQuery = $connection->OneRowCondition("*", "mrx_pro_unit", "unit_id='{$unit}'");

        $totalAmt = $qty*$unitQuery['unit_price'];

        $updateCol = array(
            "temp_basket_qty" => $qty,
            "temp_basket_grand_total" => $totalAmt
        );

        $catCondition = "temp_basket_id=".$tempId;
		$connection->UpdateQuery("mrx_temp_basket", $updateCol, $catCondition); 

        $returnArry = array(
            "qtySend" => $qty ,
            "totalAmt" => number_format($totalAmt,2),
        );

        echo json_encode($returnArry);

    }

    else if($type=="deleteTempPro"){

        $tempId = $_REQUEST['tempId'];
        $cartHtml = "";
        $delete = $connection->DeleteRow("mrx_temp_basket", "temp_basket_id", $tempId);

        
        $tempCountQuery = $connection->OneRowCondition("count(*) AS COUNT", "mrx_temp_basket", "temp_basket_session_id='{$sessionId}'and temp_basket_type='Medicine'" );

        if($tempCountQuery['COUNT'] > 0){
            $cartHtml .="<h5>Medicines:</h5>";
            $cartHtml .= $connection->shopCart($sessionId, 'Medicine');
        }
            
        $tempCountQuery = $connection->OneRowCondition("count(*) AS COUNT", "mrx_temp_basket", "temp_basket_session_id='{$sessionId}'and temp_basket_type='Healthcare Products'" );

        if($tempCountQuery['COUNT'] > 0){
            $cartHtml .= "<h5>Healthcare Products:</h5>";
            $cartHtml .= $connection->shopCart($sessionId, 'Healthcare Products');
        }

        $returnArry = array(
            "data" => $cartHtml,
            "count" => $tempCountQuery['COUNT'],
            "status" => "Success",
        );

        echo json_encode($returnArry);

    }

    else if($type=="forgotPassword"){

        $email = $_REQUEST['emailAdd'];

        $stmtl = $conn->prepare("SELECT user_email, user_password FROM mrx_customer WHERE user_email=:username LIMIT 1");
		$stmtl->execute(array(':username'=>$email));
		$userRow=$stmtl->fetch(PDO::FETCH_ASSOC);
		if($stmtl->rowCount() > 0){
            $stmpQuery = $connection->OneRow("*","mrx_smtp_settings");
            $adminQuery = $connection->OneRow("*","mrx_email_settings");

            $secure = $stmpQuery['smtp_secure'];
            $host = $stmpQuery['smtp_host'];
            $port = $stmpQuery['smtp_port'];
            $username = $stmpQuery['smtp_username'];
            $password = $stmpQuery['smtp_password'];
            $fromEmail = "info@medsrx.in";
            $fromName = "MedsRx.in";

            //Sending email to Customer
            $customerEmail = $connection->OneRowCondition("*","mrx_email_content", "email_type='Forgot Password'");

            $customerHtml .= $connection->emailHeader();
                $customerHtml .= "<p style='font-size:11px; text-align:center;'>Having problem in viewing ? Open on <a href='".root."/emails/email-forgot-password?email=".$email."' target='_blank'>Browser</a></p>";
                $customerHtml .= "<div style='background:#fff; min-height:500px; border-radius:10px; padding:15px;'>";
                    $customerHtml .= "<div style='text-align:center; padding:5px 0; border-bottom:1px solid #eee;'><img src='http://www.medsrx.in/demo/images/medsrx-logo.png' border='0'></div>";

                    $userContent = $customerEmail['email_content'];
                    $userContent = str_replace("~name~", $_REQUEST['medsName'], $userContent);
                    $userContent = str_replace("~password~", $userRow['user_password'], $userContent);

                    $customerHtml .= "<p>".$userContent."</p>";
                    $customerHtml .= $customerEmail['email_signature'];
                $customerHtml .= "</div>";
            $customerHtml .= $connection->emailFooter();

            $subject = $customerEmail['email_subject'];
            
            $sendEmail = $connection->sendEmail($fromEmail, $fromName, $subject, $customerHtml, $userRow['user_email']);
            
            $html = "Email sent successfully";
            $status = "Success";

        }else{

            $html = "Eamil not found, please check the email address";
            $status = "Error";

        }

        $returnArry = array(
            "data" => $html,
            "status" => $status
        );

        echo json_encode($returnArry);

    }

    /*else if($type="showCart"){
        $countCart = $connection->OneRowCondition("count(*) AS COUNT", "mrx_temp_basket", "temp_basket_session_id='{$sessionId}'");
        echo $countCart['COUNT'];
    }*/

    
?>