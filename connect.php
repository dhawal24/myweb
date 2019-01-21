<?php
	/*use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	require 'php-mailer/src/PHPMailer.php';
	require 'php-mailer/src/SMTP.php';
	require 'php-mailer/src/Exception.php';*/
	
	class Connect{

		private $db="";

		function __construct($conn){
			$this->db= $conn;
		}

		

		public function login($username, $password, $tableName, $passfield, $idfield, $condition)
		{
			try
			{
				$stmt = $this->db->prepare("SELECT $passfield, $idfield FROM $tableName WHERE $condition=:username LIMIT 1");
				$stmt->execute(array(':username'=>$username));
				$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
				if($stmt->rowCount() > 0){
					if($password == $userRow[$passfield]){		
						 $_SESSION['mrx_admin_session'] = $userRow[$idfield];
						return true;
					}else
					{
						return false;
					}
				}else{
						return false;
				}
			}
			catch(PDOException $e)
			 {
				echo $e->getMessage();
			}
		}

		public function redirect($url)
		{
			header("location:".$url);
		}

		public function is_logged_in($session)
		{
			if(isset($session))
			{
				return true;
			}
		}

		public function logout($session)
		{
			session_destroy();
			unset($session);
			return true;
		}
		
		


		public function tableData($field, $table)
		{
			try
			{
				$stmt = $this->db->prepare("select $field from $table");
				$stmt->execute();
				//$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		        
				return $stmt;

			}
			catch(PDOException $e)
			{
				echo 'Query failed'.$e->getMessage();
			}
		}

		public function tableDataCondition($field, $table, $condition)
		{
			try
			{
				$stmt = $this->db->prepare("select $field from $table where $condition");
				$stmt->execute();
				//$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		        
				return $stmt;

			}
			catch(PDOException $e)
			{
				echo 'Query failed'.$e->getMessage();
			}
		}


		public function OneRow($field, $table)
		{
			try
			{
				$stmt = $this->db->prepare("select $field from $table");
				$stmt->execute();
				$results = $stmt->fetch(PDO::FETCH_ASSOC);
		        
				return $results;

			}
			catch(PDOException $e)
			{
				echo 'Query failed'.$e->getMessage();
			}
		}
		

		public function categoryTree($parent_id= 0, $sub_mark = '', $type="add", $selectedId=''){
			$stmt = $this->db->prepare("SELECT * FROM mrx_category WHERE cat_parent_id = '".$parent_id."'");
			$stmt->execute();
			$rowCount =  $stmt->rowCount();

			if($rowCount > 0){
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$chks = "";	
					if($type=="edit" && $selectedId !=''){				
						$parentQuery = $this->OneRowCondition("cat_name, cat_id, cat_parent_id", "mrx_category", "cat_id='{$selectedId}'");
					
						if($row['cat_id']==$parentQuery['cat_id']){
							$chks = "selected";
						}else{
							$chks = "";
						}

						echo  '<option value="'.$row['cat_id'].'" '.$chks.'>'.$sub_mark.$row['cat_name'].'</option>';
						$this->categoryTree($row['cat_id'], $sub_mark.'--- ', 'edit', $parentQuery['cat_id']);
					}else{

						echo  '<option value="'.$row['cat_id'].'" '.$chks.'>'.$sub_mark.$row['cat_name'].'</option>';
						$this->categoryTree($row['cat_id'], $sub_mark.'--- ', '');

					}
					
				}
			}
		}

		
		public function articlesTree($parent_id= 0, $sub_mark = '', $type="add", $selectedId=''){
			$stmt = $this->db->prepare("SELECT * FROM mrx_blog_category WHERE bcat_parent_id = '".$parent_id."'");
			$stmt->execute();
			$rowCount =  $stmt->rowCount();

			if($rowCount > 0){
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$chks = "";	
					if($type=="edit" && $selectedId !=''){				
						$parentQuery = $this->OneRowCondition("bcat_name, bcat_id, bcat_parent_id", "mrx_blog_category", "bcat_id='{$selectedId}'");
					
						if($row['bcat_id']==$parentQuery['bcat_id']){
							$chks = "selected";
						}else{
							$chks = "";
						}

						echo  '<option value="'.$row['bcat_id'].'" '.$chks.'>'.$sub_mark.$row['bcat_name'].'</option>';
						$this->articlesTree($row['bcat_id'], $sub_mark.'--- ', 'edit', $parentQuery['bcat_id']);
					}else{

						echo  '<option value="'.$row['bcat_id'].'" '.$chks.'>'.$sub_mark.$row['bcat_name'].'</option>';
						$this->articlesTree($row['bcat_id'], $sub_mark.'--- ', '');

					}
					
				}
			}
		}
		
		
		public function sanitize_input($data) {
		   $data = trim($data);
		   $data = stripslashes($data);
		   $data = htmlspecialchars($data);
		   return $data;
		}

		public function sendSMTPEmail($secure, $host, $port, $username, $password, $fromEmail, $fromName, $subject, $htmlContent, $addAddress){

			$mail = new PHPMailer();
			$mail->IsSMTP(); 
			$mail->SMTPDebug  = 0; 
			$mail->SMTPAuth   = true; 
			if($secure!="None"){
				$mail->SMTPSecure = $secure;  
			}
			$mail->Host = $host;
			if($port!="None"){
					$mail->Port = $port; 
			}
			$mail->Username = $username; 
			$mail->Password = $password;    

			$mail->From = $fromEmail;
			$mail->FromName = $fromName;
			$mail->Subject = $subject;
			$mail->isHTML(true); 

			$mail->MsgHTML($htmlContent);

			$mail->AddAddress($addAddress);
			$mail->send();
			$mail->ClearAllRecipients();
		}

		public function sendEmail($fromEmail, $fromName, $subject, $htmlContent, $addAddress){
			$headers = 'From: '.$fromEmail.'' . "\r\n" .
				//'Reply-To: webmaster@example.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();

			mail($addAddress, $subject, $htmlContent, $headers);			
		}

		public function emailHeader(){
			$html = "<html><head><link href='https://fonts.googleapis.com/css?family=Raleway:300,400' rel='stylesheet'></head>";
			$html .= "<body style='background:#EEEEEE; font-family: 'Raleway', sans-serif;'>";
				$html .= "<div style='max-width:700px; margin:0 auto;'>";
					$html .= "<p style='font-size:11px; text-align:center;'>Having problem in viewing ? Open on <a href='".root."email-register' target='_blank'>Browser</a></p>";
			
			return $html;
		}

		public function emailFooter(){
			$html = "<p style='font-size:11px; text-align:center;'>You are receiving this email because you have signed up to MedsRx.in</p>";
			$html .=  "</div>";
			$html .= "</body></html>";
			
			return $html;
		}

		public function shopCart($sessionId, $orderType){

			$tempQuery = $this->tableDataCondition("*", "mrx_temp_basket", "temp_basket_session_id='{$sessionId}' and temp_basket_type='{$orderType}'");
			$cartHtml = "";
			while($tempRow = $tempQuery->fetch()) {
				$proDetails = $this->oneRowCondition("*", "mrx_product", "pro_id= '{$tempRow['temp_basket_pro_id']}'");
				$brandsDetails = $this->oneRowCondition("brands_name, brands_id", "mrx_brands", "brands_id= '{$proDetails['pro_brand_id']}'");

				if(empty($proDetails['pro_img'])){
					$img = root."images/medicine.png";
				}else{
					$img = root."products/".$proDetails['pro_img'];
				}
				
				$cartHtml .= "<div class='whiteBox boRadius'>";
					$cartHtml .= "<div class='row'>";
						$cartHtml .= "<div class='col-2'>";
							$cartHtml .= "<div class='imgBox'><img src='".$img."' border='0' class='img-fluid'></div>";
						$cartHtml .= "</div>";
						$cartHtml .= "<div class='col-10'>";
							$cartHtml .= "<div class='removePro'><a href='#' title='Remove Medicine from Cart ' onclick='removeCart(".$tempRow['temp_basket_id'].");'><i class='fa fa-trash'></i></a></div>";
							$cartHtml .= "<h6>".$proDetails['pro_name']."</h6>";
							$cartHtml .= "<small>".$brandsDetails['brands_name']."</small>";
							$cartHtml .= "<div class='priceContainer'>";
								$cartHtml .= "<div class='qtyContainer'>";
										/*$cartHtml .= "<div class='col-md-1 col-sm-1 col-xs-2' style='padding:0;'>
										<button type='button' class='btn btn-number' data-type='minus' data-field='qty'> <i class='fa fa-minus'></i> </button></div>";*/
										$cartHtml .= "<div class='col-md-1 col-sm-2 col-xs-4' style='padding:0;'>
											<input name='qty' type='text' id='qty-".$tempRow['temp_basket_id']."' class='input-number' maxlength='2' value='".$tempRow['temp_basket_qty']."' min='1' max='30' onchange='updateQty(".$tempRow['temp_basket_id'].")' style='padding:0 5px;'><input type='hidden' value='".$tempRow['temp_basket_pro_id']."' name='prodId' id='prodId'><input type='hidden' value='".$tempRow['temp_basket_unit_id']."' name='unitId' id='unitId-".$tempRow['temp_basket_id']."'></div>";
										/*$cartHtml .= "<div class='col-md-1 col-sm-1 col-xs-2' style='padding:0;'><button type='button' class='btn btn-number' data-type='plus' data-field='qty'> <i class='fa fa-plus'></i> </button></div>";*/
								$cartHtml .= "</div>";
								$cartHtml .= "<div class='totalAmt'>";
									$cartHtml .= "<p><i class='fa fa-inr'></i> <span id='totalAmt-".$tempRow['temp_basket_id']."'>".$tempRow['temp_basket_grand_total']."</span></p>";
								$cartHtml .= "</div>";
							$cartHtml .= "</div>";
						$cartHtml .= "</div>";								
						
						
						$cartHtml .= "";
					$cartHtml .= "</div>";
				$cartHtml .= "</div>";
			}

			return $cartHtml;
		}

		public function OneRowCondition($field, $table, $condition)
		{
			try
			{
				$stmt = $this->db->prepare("select $field from $table where $condition");
				$stmt->execute();
				$results =  $stmt->fetch(PDO::FETCH_ASSOC);
								
				return $results;

			}
			catch(PDOException $e)
			{
				echo 'Query failed'.$e->getMessage();
			}
		}


		public function rowCount($field, $table, $condition)
		{
			try
			{
				if($condition!=""){
					$stmt = $this->db->prepare("select $field from $table where $condition");
				}else{
					$stmt = $this->db->prepare("select $field from $table");					
				}

				$stmt->execute();
				$results =  $stmt->rowCount();
		        
				return $results;

			}
			catch(PDOException $e)
			{
				echo 'Query failed'.$e->getMessage();
			}
		}


		public function DeleteRow($table, $field, $condition)
		{
			try
			{
				$stmt = $this->db->prepare("DELETE FROM $table WHERE $field='$condition'");
				$stmt->execute();

			}
			catch(PDOException $e)
			{
				echo 'Query failed'.$e->getMessage();
			}
		}

		public function showCart($sessionId){
			try{
				$stmt = $this->db->prepare("SELECT count(*) AS COUNT FROM mrx_temp_basket WHERE temp_basket_session_id='{$sessionId}'");
				$stmt->execute();
				$results =  $stmt->fetch(PDO::FETCH_ASSOC);
				return $results['COUNT'];

			}
			catch(PDOException $e)
			{
				echo 'Query failed'.$e->getMessage();
			}
		}


		public function InsertQuery($table, $cols)
		{
			try
			{	
				$colCount = count($cols);
				$columns = implode(", ",array_keys($cols));
				$colBind = implode(", :",array_keys($cols));
				//$values = implode("', '", array_values($cols));

				$stmt = $this->db->prepare("INSERT into $table ($columns) VALUES (:$colBind)");
				for($i=0; $i<$colCount;$i++){
					$key = array_keys($cols);
					$keys = $key[$i];
					$value = array_values($cols);
					$values = $value[$i];
					$stmt->bindValue(":".$keys, $values);
				}
				$stmt->execute();

			}
			catch(PDOException $e)
			{
				echo 'Query failed'.$e->getMessage();
			}
		}

		

		public function UpdateQuery($table, $cols, $condition)
		{
			try
			{	
				$colCount = count($cols);
				$columns = array_keys($cols);
				$values="";

				$sql = "UPDATE $table SET ";
					for($j=0; $j<$colCount;$j++){
							$values .= $columns[$j]."=:". $columns[$j].", ";
					}
					if (substr($values, -2) == ", "){
					  $values2 = substr($values, 0, -2);
					}
				$sql .= $values2;
				$sql .= " where $condition";

				$stmt = $this->db->prepare($sql);
				for($i=0; $i<$colCount;$i++){

					$key = array_keys($cols);
					$keys = $key[$i];
					$value = array_values($cols);
					$values = $value[$i];
					$stmt->bindValue(":".$keys, $values);
				}

				$stmt->execute();

			}
			catch(PDOException $e)
			{
				echo 'Query failed'.$e->getMessage();
			}
		}

		
		public function smsQuery(){
			$query = $this->OneRow("count(*) AS COUNT", "mrx_sms_settings");
			return $query['COUNT'];
		}


		public function sendSMS($mobile, $message, $route){

			$query = $this->OneRow("count(*) AS COUNT", "mrx_sms_settings");

			if($query['COUNT'] > 0){

				$smsQuery = $this->OneRow("*", "mrx_sms_settings");
									
				$url = "http://my.msgwow.com/api/sendhttp.php?authkey=".$smsQuery['sms_auth_key']."&mobiles=".$mobile."&message=".$message."&sender=MedsRx&route=".$route."&country=91";
				
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$curl_scraped_page = curl_exec($ch);
				curl_close($ch);

				$sms_status = $curl_scraped_page;	
			}		

		}

		
	}
?>