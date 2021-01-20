<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
    .display-5 {
        font-size: 2.5rem;
        font-weight: 300;
        line-height: 1.2;
    }
    </style>
    <title>Forums</title>
</head>

<body>
    <?php

        include 'partials/_dbconnect.php';

        if(isset($_GET['catid'])){

            $catid = $_GET['catid'];
            $sql = "SELECT * FROM `categories` WHERE category_id = $catid";
    
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)){
                $catname = $row['category_name'];
                $catdesc = $row['category_description'];
            }
        }
        else{
                header("location: error.php");
                exit;
        }
    ?>
    <?php include 'partials/_header.php';?>


    <?php
        if($_SERVER['REQUEST_METHOD']=='POST'){
            if(isset($_POST['title'])){
                $th_title = $_POST['title'];
                $th_desc = $_POST['description'];
                $logged_in_user_id = $_SESSION['user_id'];
                $showAlert = false;
                $sql = "INSERT INTO `threads` (`thread_title`, `thread_description`, `thread_category_id`, `thread_user_id`, `timestamp`)
                VALUES ('$th_title', '$th_desc', '$catid', '$logged_in_user_id', current_timestamp());";

                $result = mysqli_query($conn, $sql);
                if($result){
                    $showAlert = true;
                }

                if($showAlert){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Succes!</strong> Your thread has been added.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                }
                else{
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Your thread has not been added.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                }
            }
        }
    ?>

    <div class="container my-4" style="max-width: 1000px; min-height: 90vh;">
        <div class="jumbotron py-3">
            <h1 class="display-4">Welcome to <?php echo $catname;?> forum!</h1>
            <p class="lead"><?php echo $catdesc;?></p>
            <hr class="my-4">
            <p>
                Forum Rules: <br>
                1.No Spam / Advertising / Self-promote in the forums.<br>
                2.Do not post copyright-infringing material.<br>
                3.Do not post “offensive” posts, links or images.<br>
                4.Do not cross post questions.<br>
            </p>
            <!-- <p class="lead">
                </p> -->
        </div>

        <h1 class="py-2">Start a Discussion</h1>

        <?php
        
            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){

                echo '<div class="container">
                    <form action="'.$_SERVER['REQUEST_URI'].'" method="post">
                        <div class="form-group">
                            <label for="exampleInputEmail1"> Problem Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                aria-describedby="emailHelp" placeholder="Problem Title">
                            <small id="emailHelp" class="form-text text-muted">Keep your title short and crisp.</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Elaborate your Concern</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>';
            }
            else{
                echo '<div class="container">
                <p class="lead">Please Login to start a discussion.</p>
                </div>';
            } 
        ?>
            
        <h1 class="py-2">Browse questions</h1>
            
        <div class="container">
        <?php
            if(isset($_GET['catid'])){

                $catid = $_GET['catid'];
                $sql = "SELECT * FROM `threads` WHERE thread_category_id = $catid";
                $noResult = true;
                $result = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                    $noResult = false;
                    $title = $row['thread_title'];
                    $desc = $row['thread_description'];
                    $id = $row['thread_id'];
                    $thread_user_id = $row['thread_user_id'];

                    $sql2 = "SELECT user_email FROM users WHERE user_id = '$thread_user_id'";
                    $result2 = mysqli_query($conn, $sql2);

                    $row2 = mysqli_fetch_assoc($result2);

                    echo '<div class="media py-2">
                    <img class="mr-3" src="img/user_default.png" width="64px" alt="Generic placeholder image">
                    <div class="media-body">
                    <p class="font-weight-bold my-0">'.$row2['user_email'].'</p>
                    <h5 class="mt-0"><a href="thread.php?threadid='.$id.'" class="text-dark">'.$title.'</a></h5>
                    '.$desc.'
                    </div>
                    </div>';
                }
                if($noResult){
                    echo '<div class="jumbotron jumbotron-fluid ">
                    <div class="container">
                      <h1 class="display-5">No Threads Found</h1>
                      <p class="lead">Be the first one to ask the question.</p>
                    </div>
                  </div>';
                }
            }
            else{
                    header("location: error.php");
                    exit;
            } 
        ?>
        </div>
        <!-- <div class="media py-2">
            <img class="mr-3" src="img/user_default.png" width="64px" alt="Generic placeholder image">
            <div class="media-body">
                <h5 class="mt-0">Media heading</h5>
                Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus
                odio,
                vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla.
                Donec
                lacinia congue felis in faucibus.
            </div>
        </div> -->
    </div>



    <?php include 'partials/_footer.php';?>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
</body>

</html>