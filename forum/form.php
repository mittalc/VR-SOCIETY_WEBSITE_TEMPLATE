<?php
   session_start();
    
   if(isset($_GET['logout'])){    
        
       //Simple exit message
       $logout_message = "<div class='msgln'><span class='left-info'>User <b class='user-name-left'>". $_SESSION['name'] ."</b> has left the forum.</span><br></div>";
       file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
        
       session_destroy();
       header("Location: form.php"); //Redirect the user
   }
    
   if(isset($_POST['enter'])){
       if($_POST['name'] != ""){
           $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
       }
       else{
           echo '<span class="error">Please type in a name</span>';
       }
   }
    
   function loginForm(){
       echo
       '<div id="loginform">
       <p>Please enter your forum name. Make sure to keep the chat friendly! </p>
       <form action="form.php" method="post">
         <input placeholder="enter your name here"  type="text" name="name" id="name" required/>
         <input type="submit" name="enter" id="enter" value="Enter" />
       </form>
     </div>';
   }
    
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <title>FORUM</title>
      <meta name="description" />
      <link rel="stylesheet" href="style.css" />
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
   </head>
   <body>
      <?php
         if(!isset($_SESSION['name'])){
             loginForm();
         }
         else {
         ?>
      <div id="wrapper">
         <a href="../index.php" class="home-page">Home</a>
         <div id="menu">
            <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b>! This is a group forum where you can post your questions and discuss with the community.</p>
         </div>
         <div id="chatbox">
            <?php
               if(file_exists("log.html") && filesize("log.html") > 0){
                   $contents = file_get_contents("log.html");          
               }
               ?>
         </div>
         <form name="message" action="">
            <input name="usermsg" type="text" id="usermsg" />
            <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
         </form>
         <p class="logout" style="text-align:center"><a id="exit" href="#">Leave Forum</a></p>
      </div>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script type="text/javascript">
         // jQuery Document
         $(document).ready(function () {
             $("#submitmsg").click(function () {
                 var clientmsg = $("#usermsg").val();
                 $.post("post.php", { text: clientmsg });
                 $("#usermsg").val("");
                 return false;
             });
         
             function loadLog() {
                 var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height before the request
         
                 $.ajax({
                     url: "log.html",
                     cache: false,
                     success: function (html) {
                         $("#chatbox").html(html); //Insert chat log into the #chatbox div
         
                         //Auto-scroll           
                         var newscrollHeight = $("#chatbox")[0].scrollHeight - 20; //Scroll height after the request
                         if(newscrollHeight > oldscrollHeight){
                             $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
                         }   
                     }
                 });
             }
         
             setInterval (loadLog, 2500);
         
             $("#exit").click(function () {
                 var exit = confirm("Are you sure you want to end the session?");
                 if (exit == true) {
                 window.location = "form.php?logout=true";
         
                 }
             });
         });
      </script>
   </body>
</html>
<?php
   }
   ?>