<?php

    if($_SERVER['REQUEST_METHOD']=='POST'){

        include '_dbconnect.php';

        $showError = "false";
        $email = $_POST['signupEmail'];
        $pass = $_POST['signupPassword'];
        $cpass = $_POST['signupcPassword'];

        $existsql = "SELECT * FROM `users` WHERE `user_email` = '$email'";

        $result = mysqli_query($conn, $existsql);
        $numRows = mysqli_num_rows($result);
        
        if($numRows > 0){
            $showError = "Email already in use";
        }
        else{
            if($pass == $cpass){

                $hash = password_hash($pass, PASSWORD_DEFAULT);

                $sql = "INSERT INTO `users` (`user_email`, `user_password`, `timestamp`) 
                VALUES ('$email', '$hash', current_timestamp());";

                $result = mysqli_query($conn, $sql);
                if($result){
                    $showAlert = true;
                    $url = $_POST['url'];
                    echo '<form name="fr" action='.$url.' method="post">
                    <input type="hidden" name="signupsuccess" id="signupsuccess" value="true">
                    </form>
                    <script type="text/javascript">
                    document.fr.submit();
                    </script>';
                    // /forum/index.php?signupsuccess=true
                    // header("location: ".$url."");
                    exit;
                }
            }
            else{
                $showError = "Passwords do not match";
            }
        }
        $url = $_POST['url'];
        echo '<form name="fr" action='.$url.' method="post">
        <input type="hidden" name="signupsuccess" id="signupsuccess" value="false">
        <input type="hidden" name="error" value="'.$showError.'">
        </form>
        <script type="text/javascript">
        document.fr.submit();
        </script>';
        // header("location: /forum/index.php?signupsuccess=false&error=$showError");
    }
?>