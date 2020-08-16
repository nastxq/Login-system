<?php


if(isset($_POST['sub_log'])){

require 'dbcon.php';




  $mailuser= $_POST['logid'];
  $passworduser= $_POST['pasid'];

  if(empty($mailuser) || empty($passworduser)){

    header("Location: ../index.php?emptypwdandlogin");
    exit();

  }

  else{
    $sql="SELECT * FROM users WHERE uIduser=? OR uEmail=?; ";
    $stmt=mysqli_stmt_init($konekt);
    if(!mysqli_stmt_prepare($stmt,$sql)){
      header("Location: ../index.php?error=failedtoloadconnprepare");
      exit();
    }
    else{
      mysqli_stmt_bind_param($stmt,"ss",$mailuser,$mailuser);
      mysqli_stmt_execute($stmt);
      $result=mysqli_stmt_get_result($stmt);
      if($row=mysqli_fetch_assoc($result)){
        $pwdCheck= password_verify($passworduser,$row['uPassword']);
        if($pwdCheck==false){
          header("Location: ../index.php?error=wrongpassword");
          exit();
        }
        else if($pwdCheck==true){
          session_start();
          $_SESSION['usermail']=$row['uEmail'];
          $_SESSION['usernameid']=$row['uIduser'];
          header("Location: ../index.php?login=success");
          exit();
        }
        else{
          header("Location: ../index.php?error=howtfyougethere");
          exit();
        }
      }
      else if($result !=$row){
        header("Location: ../index.php?error=wrongusername");
        exit();

      }


    }

  }
}
else{
header("Location: ../index.php");
  exit();
}
?>
