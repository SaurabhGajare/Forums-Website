<?php
    if($_SERVER['REQUEST_METHOD']=="POST"){
        
        include '_dbconnect.php';
        
        $url = $_POST['url'];

        $email = $_POST['useremail'];
        $pass = $_POST['userpassword'];

        $sql = "SELECT * FROM users WHERE user_email = '$email'";

        $result = mysqli_query($conn, $sql);
        $numRows = mysqli_num_rows($result);
        if($numRows == 1){
            $row = mysqli_fetch_assoc($result);
            if(password_verify($pass, $row['user_password'])){
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['user_email'] = $row['user_email'];
                $_SESSION['user_id'] = $row['user_id'];

                // header("location: ".$url."");
                echo '<form name="fr" action='.$url.' method="post">
                <input type="hidden" name="loginsuccess" id="loginsuccess" value="true">
                </form>
                <script type="text/javascript">
                document.fr.submit();
                </script>';
                exit;
            }
            else{
                $showError = "Invalid Password";
            }
        }
        else{
            $showError = "Invalid Email";
        }
        
        echo '<form name="fr" action='.$url.' method="post">
        <input type="hidden" name="loginsuccess" id="loginsuccess" value="false">
        <input type="hidden" name="error" value="'.$showError.'">
        </form>
        <script type="text/javascript">
        document.fr.submit();
        </script>';
        exit;
    }
?>