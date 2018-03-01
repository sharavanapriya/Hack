<html>
<head>
<style>
body {font-family: Arial, Helvetica, sans-serif;}

/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>
</head>
<nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.php"> <img class="img-rounded" style="height:30px;width:30px;"src="images/foodlogo.png" alt=""> </a>
                    <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"> <a class="nav-link active" href="index.php">Home <span class="sr-only">(current)</span></a> </li>
                            <li class="nav-item"> <a class="nav-link active" href="login.php" >Login <span class="sr-only">(current)</span></a> </li>
                           
						   <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Food</a>
                                <div class="dropdown-menu"> <a class="dropdown-item" href="food_results.php">Food results</a> <a class="dropdown-item" href="map_results.html">Map results</a></div>
                            </li>
                            <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Restaurants</a>
                                <div class="dropdown-menu"> <a class="dropdown-item" href="restaurants.php">Search results</a> <a class="dropdown-item" href="profile.html">Profile page</a></div>
                            </li>
							<li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Events</a>
                                <div class="dropdown-menu"> <a class="dropdown-item" href="restaurants.php">Nearby</a> <a class="dropdown-item" href="profile.html">By Category</a></div>
								
                            </li>
							<li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="profile_c.php?pid=7" role="button" aria-haspopup="true" aria-expanded="false">Companies</a>
                                <div class="dropdown-menu"> <a class="dropdown-item" href="profile_c.php?pid=7">Openings</a> <a class="dropdown-item" href="profile.html">Review Profile</a></div>
                            </li>
							
                            <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Pages</a>
                                <div class="dropdown-menu"> <a class="dropdown-item" href="pricing.html">Pricing</a> <a class="dropdown-item" href="contact.html">Contact</a> <a class="dropdown-item" href="submition.php">Submit restaurant</a> <a class="dropdown-item" href="registration.php">Registration</a>
                                    <div class="dropdown-divider"></div> <a class="dropdown-item" href="checkout.php">Checkout</a> </div>
                            </li>
							                            <li class="nav-item"> <a class="nav-link active" href="forum\index.php">Forum <span class="sr-only">(current)</span></a> </li>

							                            <li class="nav-item"> <a class="nav-link active" href="logout.php" >Log Out <span class="sr-only">(current)</span></a> </li>

                        </ul>
                    </div>
                </div>
            </nav>
			
			<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
	<p><br><br>
    <form action="#" method="post">
	LOGIN
		  <input type="text" name="email" placeholder="Enter Email"><br>
		  <br><input type="password" name="pwd" placeholder="Enter Password">
		   <br><input type="submit" name= "b1" value="submit" ><br>
        </form>
		  </p>
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
			
			 

<?php

			if(isset ($_POST["b1"]))
			{
			@$email=$_POST["email"];
			@$pwd=$_POST["pwd"];
			
			$conn= new mysqli("localhost","root","","hack");
$sql = "SELECT pwd FROM `user_det` WHERE email = '$email' LIMIT 0, 30 ";
$result= $conn->query($sql);
if ($result->num_rows >0)
{
	while($row = $result->fetch_assoc())
	{
		if($row["pwd"] == $pwd)
			start_session();
		@$uid1="SELECT uid FROM `user_det` WHERE email = '$email' LIMIT 0, 30 ";
		$uid= $conn->query($uid1);
		$_SESSION["uid"]=$uid;

		}
	
}
			
			
			}
			
			
			
			?>
			
		</html>