


<?php
if(isset($_POST['signupsubmit'])){

require 'dbcon.php';


$username=$_POST['slog'];
$email=$_POST['mail'];
$password=$_POST['pwd'];
$passwordRepeat=$_POST['repeatpwd'];


$emptyError=false;
$usernameError=false;
$mailError=false;
$conError=false;
$passRepeat=false;
$userRepeat=false;
$mailRepeat=false;
$lengthError=false;

if(empty($username) || empty($email) || empty($password) || empty($passwordRepeat) ){
  $emptyError=true;


}
else if(!filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/",$username)){
  $usernameError=true;
  $mailError=true;


}
else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  $mailError=true;

}

else if(!preg_match("/^[a-zA-Z0-9]*$/",$username)){
  $usernameError=true;

}
else if($password !== $passwordRepeat){
  $passRepeat=true;

}
else if(strlen($username)<4 || strlen($email)<4 ||strlen($password)<4){
  $lengthError=true;
}

else{

  $sqlMail="SELECT uEmail FROM users WHERE uEmail=?";
  $stmtMail=mysqli_stmt_init($konekt);
  if(!mysqli_stmt_prepare($stmtMail,$sqlMail)){
    $conError=true;
  }
  else{
    mysqli_stmt_bind_param($stmtMail,"s",$email);
    mysqli_stmt_execute($stmtMail);
    mysqli_stmt_store_result($stmtMail);
    $resultMail=mysqli_stmt_num_rows($stmtMail);
    if($resultMail>0){
      $mailRepeat=true;
    }
  }


  $sql="SELECT uIduser FROM users WHERE uIduser=?";
  $stmt=mysqli_stmt_init($konekt);
  if(!mysqli_stmt_prepare($stmt,$sql)){
    $conError=true;

  }
  else{
    mysqli_stmt_bind_param($stmt,"s",$username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $resultCheck= mysqli_stmt_num_rows($stmt);
    if($resultCheck>0){
      $userRepeat=true;

    }
  else{

    $sql="INSERT INTO users(uIduser,uEmail,uPassword) VALUES (?,?,?)";
    $stmt=mysqli_stmt_init($konekt);
    if(!mysqli_stmt_prepare($stmt,$sql)){
      $conError=true;

  }

  else{
      $hsdPassword=password_hash($password,PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt,"sss",$username,$email,$hsdPassword);
    mysqli_stmt_execute($stmt);

  }
}
}

mysqli_stmt_close($stmtMail);
mysqli_stmt_close($stmt);
mysqli_close($konekt);

}
}


else{
  header("Location: ../index.php");
  exit();
}

?>

<script>
  var emptyError="<?php echo $emptyError; ?>"
  var usernameError="<?php echo $usernameError; ?>"
  var mailError="<?php echo $mailError; ?>"
  var conError="<?php echo $conError; ?>"
  var passRepeat="<?php echo $passRepeat; ?>"
  var userRepeat="<?php echo $userRepeat; ?>"
  var mailRepeat="<?php echo $mailRepeat; ?>"
  var lengthError="<?php echo $lengthError; ?>"

  if(emptyError==true ){
    $("#reg-text").removeClass("text-success").addClass("text-danger");
    $("#reg-text").text("Wypełnij wszystkie pola ");
  }
  else if(mailError==true){
      $("#reg-text").removeClass("text-success").addClass("text-danger");
      $("#reg-text").text("Podaj prawidłowo adres e-mail ");
  }
  else if(conError==true){
      $("#reg-text").removeClass("text-success").addClass("text-danger");
    $("#reg-text").text("Błąd połączenia ");
  }
  else if(usernameError==true){
      $("#reg-text").removeClass("text-success").addClass("text-danger");
    $("#reg-text").text("Wybierz nazwe użytkownika z zakresu | a-z Z-A 0-9 |");
  }
  else if(passRepeat==true){
      $("#reg-text").removeClass("text-success").addClass("text-danger");
      $("#reg-text").text("Źle powtórzyłeś hasło ");
  }
  else if(userRepeat==true){
      $("#reg-text").removeClass("text-success").addClass("text-danger");
        $("#reg-text").text("Ta nazwa użytkownika jest już w użyciu ");
  }
  else if(mailRepeat==true){
      $("#reg-text").removeClass("text-success").addClass("text-danger");
    $("#reg-text").text("Ten mail został już zarejestrowany");
  }
  else if(lengthError==true){
    $("#reg-text").removeClass("text-success").addClass("text-danger");
    $("#reg-text").text("Login, e-mail i hasło muszą mieć co najmniej 4 znaki");
  }

  else{
    $("#reg-text").removeClass("text-danger").addClass("text-success")
    $("#log,#mailx,#passwordx,#repwd,.form-message").val("");
    $("#reg-text").text("Udało Ci się zarejestrować ");
  }


</script>
