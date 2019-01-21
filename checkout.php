<?php
    ob_start();
    session_start();
    $sessionId = session_id();
    require_once("web_admin/db.php");

    $date = date('Y-m-d H:i:s');

    if(!empty($_SESSION['mrx_user_id'])){
        $connection->redirect(root."delivery-address");
    }    

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="canonical" href="<?php echo root.$_SERVER['REQUEST_URI']; ?>"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700|Roboto:300,400|Satisfy" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo root; ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo root; ?>css/login.css">
    <link rel="stylesheet" href="<?php echo root; ?>css/responsive.css" media="screen and (max-width: 840px)">
    <title>Login | MedsRx </title>
    <meta name="description" content="MedsRx is Online Medicine ordering website, offering flat 20% discount. Doorstep Delivery, Authentic Medicines, Ontime Delivery, Online Pharmacy, Mumbai, India.">
    <meta name="keywords" content="Online Pharmacy, Online Medicine Store Mumbai, Buy Medicine Online India">
    <meta name="author" content="Made By Dhawal | i3TechSolutions">
  </head>
    <body>
        <div class="preLoader" style="display:none;">
            <img src='<?php echo root;?>images/spinner.gif' alt='Pre Loader'>
        </div>
        <header class="text-center">
            <img src="<?php echo root; ?>images/medsrx-logo.png" alt="MedsRX Logo" class="img-fluid">
        </header>
        <section class="innerContent">        
            <div class="container">
                <div class="row justify-content-md-center loginTxt">
                    <div class="col-6">
                        <div class="login-page">
                            
                            <?php 
                                if(!empty($_REQUEST['regError'])){
                                    echo "<div class='alert alert-danger'>Email already exist</div>";
                                } 
                                if(!empty($_REQUEST['loginError1'])){
                                    echo "<div class='alert alert-danger'>Wrong credentials provided</div>";
                                } 
                                if(!empty($_REQUEST['loginError2'])){
                                    echo "<div class='alert alert-danger'>User not found</div>";
                                }                             
                            ?>
                            
                                <div class="form">                                 
                                    <div class="loginBox">      
                                        <h2 class="text-center">Welcome Back!</h2>       
                                        <div id="loginError" style="display:none;" class='alert alert-danger'></div>
                                        <div id="loginSuccess" style="display:none;" class='alert alert-success'></div>                  
                                        <form class="login-form" name="loginForm" id="loginForm" method="POST" action="#">
                                            <div class="form-group">
                                                <input type="email" name="medsUsername" id="medsUsername" placeholder="username/email" autocomplete="off"/>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" name="medsPassword" id="medsPassword" placeholder="password" autocomplete="off"/>
                                            </div>
                                            <div class="form-group">
                                                <a href="#" id="forgotLink">Forgot Password ?</a>
                                            </div>
                                            <button class="btn btn-primary mainBtn" name="loginBtn" id="loginBtn" type="submit">Login</button>
                                            <a href="#" id="registerLink" class="secondaryBtn">Don't have an account? Signup</a>
                                        </form>
                                    </div>
                                    <div class="forGotBox" style="display:none;">          
                                        <h2 class="text-center">Forgot Password</h2>  
                                        <div id="forgotError" style="display:none;" class='alert alert-danger'></div>
                                        <div id="forgotSuccess" style="display:none;" class='alert alert-success'></div> 
                                        <p>Enter your email address below and we’ll send you a confirmation code to reset your password.</p>  
                                        <form class="login-form" name="forgotForm" id="forgotForm" method="POST" action="#">
                                            <div class="form-group">
                                                <input type="email" name="medsforEmail" id="medsforEmail" placeholder="username/email" autocomplete="off"/>
                                            </div>                                       
                                            <div class="form-group">
                                                <a href="#" class="backLogin">Back to Login</a>
                                            </div>
                                            <button class="btn btn-primary mainBtn" name="forgotBtn" id="forgotBtn" type="submit">Send Password</button>
                                        </form>
                                    </div>
                                    <div class="registerBox" style="display:none;">          
                                        <h2 class="text-center">Create your MedsRx account</h2>  
                                        <div id="registerError" style="display:none;" class='alert alert-danger'></div>
                                        <div id="registerSuccess" style="display:none;" class='alert alert-success'></div>                                         
                                        <form class="login-form" name="regForm" id="regForm" method="POST" action="#">
                                            <div class="form-group">
                                                <input type="text" name="medsName" id="medsName" placeholder="name" autocomplete="off"/>
                                            </div>   
                                            <div class="form-group">
                                                <input type="text" name="medsRegPhone" id="medsRegPhone" placeholder="phone no." autocomplete="off"/>
                                            </div>  
                                            <div class="form-group">
                                                <input type="email" name="medsEmail" id="medsEmail" placeholder="email address" autocomplete="off"/>
                                            </div>                                      
                                            <div class="form-group">
                                                <a href="#" class="backLogin">Back to Login</a>
                                            </div>
                                            <button class="btn btn-primary mainBtn" name="registerBtn" id="registerBtn" type="submit">Register</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>                    
            </div>
        </section>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="<?php echo root; ?>js/bootstrap.min.js"></script> 
    <script>
     
        $('#forgotLink').click(function(){
            $(".loginBox").hide("slow");
            $(".forGotBox").show("slow");
            $(".registerBox").hide("slow");
        }); 
        
        
        $('.backLogin').click(function(){
            $(".loginBox").show("slow");
            $(".forGotBox").hide("slow");
            $(".registerBox").hide("slow");
        });  
        
        $('#registerLink').click(function(){
            $(".loginBox").hide("slow");
            $(".forGotBox").hide("slow");
            $(".registerBox").show("slow");
        }); 

        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }
        

        $("#loginBtn").click(function(e){
            e.preventDefault();
            username = $("#medsUsername").val();
            password = $("#medsPassword").val();
            
            if(username==""){
                $("#loginError").show();
                $("#loginError").html("Please enter Username");
                e.preventDefault();

            }else if(!isEmail(username)){
                $("#loginError").show();
                $("#loginError").html("Please enter valid email");
                e.preventDefault();

            }else if(password==""){
                $("#loginError").show();
                $("#loginError").html("Please enter Password");
                e.preventDefault();
                
            }else{
                $("#loginError").hide();
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "ajax.php?type=loginFront", // 
                    data: "medsUsername="+username+"&medsPassword="+password, 
                    beforeSend: function() {
                        $(".preLoader").show();
                    },
                    success: function(data){
                        msg = $.parseJSON(data); 
                        if(msg.status=="Error"){
                            $("#loginError").show();
                            $("#loginError").html(msg.data);
                        }else if(msg.status=="Success"){
                            $("#loginSuccess").show();
                            $("#loginSuccess").html("Login successful !! Redirecting...");
                            window.location.href = window.location.origin+"/MedsRx/delivery-address";
                        }
                    },
                    error: function(){
                        alert("failure");
                    },
                    complete: function() {
                        $(".preLoader").hide();
                    }
                });
            }
          
        })


        $("#forgotBtn").click(function(e)){
            e.preventDefault();
            emailAdd = $("#medsforEmail").val();

            if(emailAdd==""){
                $("#forgotError").show();
                $("#forgotError").html("Please enter email address");
                e.preventDefault();
                
            }else if(!isEmail(emailAdd)){
                $("#forgotError").show();
                $("#forgotError").html("Please enter valid email address");
                e.preventDefault();
                
            }else{
                $("#forgotError").hide();
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "ajax.php?type=forgotPassword", // 
                    data: "emailAdd="+emailAdd, 
                    beforeSend: function() {
                        $(".preLoader").show();
                    },
                    success: function(data){
                        msg = $.parseJSON(data); 
                        if(msg.status=="Error"){
                            $("#forgotError").show();
                            $("#forgotError").html(msg.data);
                        }else if(msg.status=="Success"){
                            $("#forgotSuccess").show();
                            $("#forgotSuccess").html("Password sent on your registered email!!");
                        }
                    },
                    error: function(){
                        alert("failure");
                    },
                    complete: function() {
                        $(".preLoader").hide();
                    }
                });
            }

        }
         
        $("#registerBtn").click(function(e)){
            e.preventDefault();
            fullname = $("#medsName").val();
            phone = $("#medsRegPhone").val();
            email = $("#medsEmail").val();
            
            if(fullname==""){
                $("#registerError").show();
                $("#registerError").html("Please enter full name");
                e.preventDefault();

            }else if(phone==""){
                $("#registerError").show();
                $("#registerError").html("Please enter Phone No.");
                e.preventDefault();

            }else if(email==""){
                $("#registerError").show();
                $("#registerError").html("Please enter email");
                e.preventDefault();

            }else if(!isEmail(email)){
                $("#registerError").show();
                $("#registerError").html("Please enter valid email");
                e.preventDefault();

            }else{
                $("#registerError").hide();
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "ajax.php?type=registerFront", // 
                    data: "medsName="+fullname+"&medsRegPhone="+phone+"&medsEmail="+email,
                    beforeSend: function() {
                        $(".preLoader").show();
                    },
                    success: function(data){
                        msg = $.parseJSON(data);  
                        if(msg.status=="Error"){
                            $("#registerError").show();
                            $("#registerError").html(msg.data);
                        }else if(msg.status=="Success"){
                            $("#registerSuccess").show();
                            $("#registerSuccess").html("Please check your email to confirm your registration!!");
                            console.log("Registered succesfully!!");
                        }
                    },
                    error: function(){
                        alert("failure");
                    },
                    complete: function() {
                        $(".preLoader").hide();
                    }
                });
            }
        })
       

    </script>
  </body>
</html>