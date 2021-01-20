<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Forums</title>
</head>

<body>
    <?php

        include 'partials/_dbconnect.php';

        if(isset($_GET['threadid'])){

            $id = $_GET['threadid'];
            $sql = "SELECT * FROM `threads` WHERE thread_id = $id";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)){
                $id = $row['thread_id'];
                $title = $row['thread_title'];
                $desc = $row['thread_description'];
                $user_id = $row['thread_user_id'];
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
            if(isset($_POST['comment-desc'])){
                $comment_content = $_POST['comment-desc'];
                $comment_by = $_SESSION['user_id'];
                
                $sql = "INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`)
                VALUES ('$comment_content', '$id', '$comment_by', current_timestamp())";
                $result = mysqli_query($conn, $sql);
                if($result){
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Succes!</strong> Your comment has been added.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                }
                else{
                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Your comment has not been added.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>';
                }
            }
        }
    ?>

    <?php
        $sql = "SELECT user_email FROM users WHERE user_id = '$user_id'";

        if($result = mysqli_query($conn, $sql)){
            $row = mysqli_fetch_assoc($result);
            $user = $row['user_email'];
        }
        else{
            $user = 'Anonymous';
        }
    ?>

    <div class="container my-4" style="max-width: 1000px; min-height: 90vh;">
        <div class="jumbotron py-3">
            <h1 class="display-4"><?php echo $title;?> </h1>
            <p class="lead"><?php echo $desc;?></p>
            <hr class="my-4">
            <p>
                Forum Rules: <br>
                1.No Spam / Advertising / Self-promote in the forums.<br>
                2.Do not post copyright-infringing material.<br>
                3.Do not post “offensive” posts, links or images.<br>
                4.Do not cross post questions.<br>
            </p>
            <p>
                Published by <b><?php echo $user;?></b>
                </p>
        </div>

        <h1> Post a Comment</h1>
        <?php
             if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
                echo '<div class="container">
                <form action="'.$_SERVER['REQUEST_URI'].'" method="post">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Add your comment</label>
                        <textarea class="form-control" id="comment-desc" name="comment-desc" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Comment</button>
                </form>
                </div>';   
             }
             else{
                echo '<div class="container">
                <p class="lead">Please Login to comment.</p>
                </div>';
             }
        ?>
        
        <h1 class="py-2 mt-4">Discussions</h1>
        <div class="container">   
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
            <!-- INSERT INTO `comments` (`comment_content`, `thread_id`, `comment_by`, `comment_time`) VALUES ('this is a comment\r\nplz ignore it', '1', '0', current_timestamp()); -->

        <?php
            $noResult = true;
            $sql = "SELECT * FROM `comments` WHERE thread_id = $id";

            $result = mysqli_query($conn, $sql);

            while($row = mysqli_fetch_assoc($result)){
                $noResult = false;
                $comment_desc = $row['comment_content'];
                $comment_by = $row['comment_by'];

                $sql2 = "SELECT user_email FROM users WHERE user_id = '$comment_by'";
                $result2 = mysqli_query($conn, $sql2);

                $row2 = mysqli_fetch_assoc($result2);
                // for printing time -> (Created date is 15-Aug-20 02:03 pm);
                // $d=strtotime("2020-08-15 14:03:11");
                // echo "Created date is " . date("d-M-y h:i a", $d);
                echo '<div class="media py-2">
                <img class="mr-3" src="img/user_default.png" width="64px" alt="Generic placeholder image">
                <div class="media-body">
                    <p class="font-weight-bold my-0">'.$row2['user_email'].'</p>
                    '.nl2br($comment_desc).'
                </div>
            </div>';

            }
            if($noResult){
                echo '<div class="jumbotron jumbotron-fluid ">
                <div class="container">
                <h1 class="display-5">No Comments Found</h1>
                <p class="lead">Be the first one to comment.</p>
                </div>
            </div>';
            }
        ?>
        </div>
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