<?php
session_start();
$url = $_SERVER['REQUEST_URI'];
echo '<nav class="navbar navbar-expand-lg navbar-expand-md navbar-fixed-top navbar-dark bg-dark">
  <a class="navbar-brand" href="#">Forums</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="/forum">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="about.php">About</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Categories
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">';
          $sql = "SELECT category_name, category_id FROM categories";
          $result = mysqli_query($conn, $sql);
          while($row = mysqli_fetch_assoc($result)){
            $category = $row['category_name'];
            echo '<a class="dropdown-item" href="/forum/thread_list.php?catid='.$row['category_id'].'">'.$category.'</a>';
          }
          // <a class="dropdown-item" href="#">Action</a>
          
          // <div class="dropdown-divider"></div>
          // <a class="dropdown-item" href="#">Something else here</a>

        echo '</div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="contact.php">Contact</a>
      </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-success my-2 my-sm-0" type="submit">Search</button>
        </form>';
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin']==true){
    echo '<p class="my-0 px-2 text-light">Hello <b>'.$_SESSION['user_email'].'!</b></p>
    <form action="/forum/partials/_logout.php" method="post">
    <input type="hidden" name="url" value="'.$url.'">
    <button type="submit" class="btn btn-outline-success">Log out</button>
    </form>';
}
else{
echo  '<button class="btn btn-outline-success ml-2" data-toggle="modal" data-target="#loginModal">Login</button>
  <button class="btn btn-outline-success ml-2" data-toggle="modal" data-target="#signupModal">Sign Up</button>';
}
        
echo  '</div>
</nav>';

include 'partials/_loginmodal.php';
include 'partials/_signupmodal.php';

//  Handle signup alerts
if(isset($_POST['signupsuccess']) && $_POST['signupsuccess']=="true"){
  echo '<div class="alert alert-success alert-dismissible fade show my-0" role="alert">
  <strong>Success!</strong> You can now login.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>';
}
elseif(isset($_POST['signupsuccess']) && isset($_POST['error'])){
    $showError = $_POST['error'];
    echo '<div class="alert alert-danger alert-dismissible fade show my-0" role="alert">
    <strong>Error!</strong> '.$showError.'.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>';
}

// Handle login alerts
if(isset($_POST['loginsuccess']) && $_POST['loginsuccess']=="true"){
  echo '<div class="alert alert-success alert-dismissible fade show my-0" role="alert">
  <strong>Success!</strong> You are now logged in.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>'; 
}
elseif(isset($_POST['loginsuccess']) && isset($_POST['error'])){
  $showError = $_POST['error'];
    echo '<div class="alert alert-danger alert-dismissible fade show my-0" role="alert">
    <strong>Error!</strong> '.$showError.'.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>';
}
?>