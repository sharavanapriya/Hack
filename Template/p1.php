<!DOCTYPE html>
<html lang="en">

<head>
<script src="raty/jquery.raty.js" type="text/javascript"></script>
<script>
<?php


session_start();
 @$pid=$_GET["pid"];
// Storing session data
$_SESSION["pid"] = $pid;

?>
	function commentSubmit(){
		if(form1.name.value == '' && form1.comments.value == ''){ //exit if one of the field is blank
			alert('Enter your message !');
			return;
		}
		var name = form1.name.value;
		var comments = form1.comments.value;
		var xmlhttp = new XMLHttpRequest(); //http request instance
		var pid= <?php echo $pid;?>
		
		xmlhttp.onreadystatechange = function(){ //display the content of insert.php once successfully loaded
			if(xmlhttp.readyState==4&&xmlhttp.status==200){
				document.getElementById('comment_logs').innerHTML = xmlhttp.responseText; //the chatlogs from the db will be displayed inside the div section
			}
		}
		xmlhttp.open('GET', 'insert.php?name='+name+'&comments='+comments+'&pid='+pid, true); //open and send http request
		xmlhttp.send();
	}
	
		$(document).ready(function(e) {
			$.ajaxSetup({cache:false});
			
			setInterval(function() {$('#comment_logs').load('logs.php');}, 2000);
		});
		
</script>
<?php
require_once './config.php';
?>
<!-- include this file everytime you want to use rating plugin -->
<script src="raty/jquery.raty.js" type="text/javascript"></script>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Starter Template for Bootstrap</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet"> </head>

<body>
    <div class="site-wrapper animsition" data-animsition-in="fade-in" data-animsition-out="fade-out">
        <!--header starts-->
        <header id="header" class="header-scroll top-header headrom">
            <!-- .navbar -->
            <nav class="navbar navbar-dark">
                <div class="container">
                    <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                    <a class="navbar-brand" href="index.html"> <img class="img-rounded" src="images/food-picky-logo.png" alt=""> </a>
                    <div class="collapse navbar-toggleable-md  float-lg-right" id="mainNavbarCollapse">
                        <ul class="nav navbar-nav">
                            <li class="nav-item"> <a class="nav-link active" href="index.html">Home <span class="sr-only">(current)</span></a> </li>
                            <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Food</a>
                                <div class="dropdown-menu"> <a class="dropdown-item" href="food_results.html">Food results</a> <a class="dropdown-item" href="map_results.html">Map results</a></div>
                            </li>
                            <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Restaurants</a>
                                <div class="dropdown-menu"> <a class="dropdown-item" href="restaurants.html">Search results</a> <a class="dropdown-item" href="profile.html">Profile page</a></div>
                            </li>
                            <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Pages</a>
                                <div class="dropdown-menu"> <a class="dropdown-item" href="pricing.html">Pricing</a> <a class="dropdown-item" href="contact.html">Contact</a> <a class="dropdown-item" href="submition.html">Submit restaurant</a> <a class="dropdown-item" href="registration.html">Registration</a>
                                    <div class="dropdown-divider"></div> <a class="dropdown-item" href="checkout.html">Checkout</a> </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- /.navbar -->
        </header>
        <div class="page-wrapper">
            <!-- top Links -->
            <div class="top-links">
                <div class="container">
                    <ul class="row links">
                        <li class="col-xs-12 col-sm-3 link-item"><span>1</span><a href="index.html">Choose Your Location</a></li>
                        <li class="col-xs-12 col-sm-3 link-item"><span>2</span><a href="restaurants.html">Choose Restaurant</a></li>
                        <li class="col-xs-12 col-sm-3 link-item active"><span>3</span><a href="profile.html">Pick Your favorite food</a></li>
                        <li class="col-xs-12 col-sm-3 link-item"><span>4</span><a href="checkout.html">Order and Pay online</a></li>
                    </ul>
                </div>
            </div>
            <!-- end:Top links -->
            <!-- start: Inner page hero -->
			<!-- include this file everytime you want to use rating plugin -->
<script src="raty/jquery.raty.js" type="text/javascript"></script>
			<?php
      // fetch product details
      $sql = "SELECT `product_id`, `product_name`, `address`, `pdesc`,`title`,`image`,`logo` FROM `tbl_products` WHERE 1 AND product_id = :pid";
      try {

        $stmt = $DB->prepare($sql);
        $stmt->bindValue(":pid", intval($_GET["pid"]));
        $stmt->execute();
        // fetching products details
        $products = $stmt->fetchAll();
      } catch (Exception $ex) {
        echo $ex->getMessage();
      }

      // fetching ratings for specific product
      $ratings_sql = "SELECT count(*) as count, AVG(ratings_score) as score FROM `tbl_products_ratings` WHERE 1 AND product_id = :pid";
      $stmt2 = $DB->prepare($ratings_sql);

      try {
        $stmt2->bindValue(":pid", $_GET["pid"]);
        $stmt2->execute();
        $product_rating = $stmt2->fetchAll();
      } catch (Exception $ex) {
        // you can turn it off in production mode.
        echo $ex->getMessage();
      }

      if (isset($USER_ID)) {
        // check if user has rated this product or not
        $user_rating_sql = "SELECT count(*) as count FROM `tbl_products_ratings` WHERE 1 AND product_id = :pid AND user_id= :uid";
        $stmt3 = $DB->prepare($user_rating_sql);

        try {
          $stmt3->bindValue(":pid", $_GET["pid"]);
          $stmt3->bindValue(":uid", $USER_ID);
          $stmt3->execute();
          $user_product_rating = $stmt3->fetchAll();
        } catch (Exception $ex) {
          // you can turn it off in production mode.
          echo $ex->getMessage();
        }
      }
      ?>
	   <?php
	   $i=0;
          if (count($products) > 0) {
            ?>
            <section class="inner-page-hero bg-image" data-image-src="images/<?php echo $products[$i]["image"] ?>.jpg">
                <div class="profile">
                    <div class="container">
                        <div class="row">
						
                            <div class="col-xs-12 col-sm-12  col-md-4 col-lg-4 profile-img">
                                <div class="image-wrap">
                                    <figure><img src="images/<?php echo $products[$i]["logo"] ?>.jpg" alt="Profile Image"></figure>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 profile-desc">
                                <div class="pull-left right-text white-txt">
                                    <h6><a href="#"><?php echo $products[0]["product_name"] ?></a></h6> <a class="btn btn-small btn-green">Open</a>
                                    <p><?php echo $products[$i]["pdesc"] ?></p>
                                    <ul class="nav nav-inline">
                                        <li class="nav-item"> <a class="nav-link active" href="#"><i class="fa fa-check"></i> Min $ 10,00</a> </li>
                                        <li class="nav-item"> <a class="nav-link" href="#"><i class="fa fa-motorcycle"></i> 30 min</a> </li>
                                        <li class="nav-item ratings">
                                            <a class="nav-link" href="#">  <?php
                  // display the ratings for this product
                  if ($product_rating[0]["count"] > 0) {
                    echo "Average rating <strong>" . round($product_rating[0]["score"], 2) . "</strong> based on <strong>" . $product_rating[0]["count"] . "</strong> users";
                  } else {
                    echo 'No ratings for this product';
                  }
				  
                  ?> </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- end:Inner page hero -->
            <div class="breadcrumb">
                <div class="container">
                    <ul>
                        <li><a href="#" class="active">Home</a></li>
                        <li><a href="#">Search results</a></li>
                        <li>Profile</li>
                    </ul>
                </div>
            </div>
            <div class="container m-t-30">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        <div class="sidebar clearfix m-b-20">
                            <div class="main-block">
                                <div class="sidebar-title white-txt">
                                    <h6>Choose Cusine</h6> <i class="fa fa-cutlery pull-right"></i> </div>
                                <ul>
                                    <li><a href="#1" class="scroll active">Pizza</a></li>
                                    <li><a href="#2" class="scroll">Barbecuing and Grilling</a></li>
                                    <li><a href="#3" class="scroll">Appetizers</a></li>
                                    <li><a href="#4" class="scroll">Soup and salads</a></li>
                                    <li><a href="#5" class="scroll">Pasta</a></li>
                                    <li><a href="#6" class="scroll">Seafood</a></li>
                                    <li><a href="#7" class="scroll">Beverages</a></li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <!-- end:Sidebar nav -->
                            <div class="widget-delivery">
                                <form>
                                    <div class="col-xs-6 col-sm-12 col-md-6 col-lg-6">
                                        <label class="custom-control custom-radio">
                                            <input id="radio1" name="radio" type="radio" class="custom-control-input" checked=""> <span class="custom-control-indicator"></span> <span class="custom-control-description">Delivery</span> </label>
                                    </div>
                                    <div class="col-xs-6 col-sm-12 col-md-6 col-lg-6">
                                        <label class="custom-control custom-radio">
                                            <input id="radio2" name="radio" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Takeout</span> </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- end:Left Sidebar -->
                        <div class="widget clearfix">
                            <!-- /widget heading -->
                            <div class="widget-heading">
                                <h3 class="widget-title text-dark">
                              Popular tags
                           </h3>
                                <div class="clearfix"></div>
                            </div>
                            <div class="widget-body">
                                <ul class="tags">
                                    <li> <a href="#" class="tag">
                                 Coupons
                                 </a> </li>
                                    <li> <a href="#" class="tag">
                                 Discounts
                                 </a> </li>
                                    <li> <a href="#" class="tag">
                                 Deals
                                 </a> </li>
                                    <li> <a href="#" class="tag">
                                 Amazon 
                                 </a> </li>
                                    <li> <a href="#" class="tag">
                                 Ebay
                                 </a> </li>
                                    <li> <a href="#" class="tag">
                                 Fashion
                                 </a> </li>
                                    <li> <a href="#" class="tag">
                                 Shoes
                                 </a> </li>
                                    <li> <a href="#" class="tag">
                                 Kids
                                 </a> </li>
                                    <li> <a href="#" class="tag">
                                 Travel
                                 </a> </li>
                                    <li> <a href="#" class="tag">
                                 Hosting
                                 </a> </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
                        <div class="menu-widget m-b-30">
                            <div class="widget-heading">
                                <h3 class="widget-title text-dark">
                              POPULAR ORDERS Delicious hot food! <a class="btn btn-link pull-right" data-toggle="collapse" href="#popular" aria-expanded="true">
                              <i class="fa fa-angle-right pull-right"></i>
                              <i class="fa fa-angle-down pull-right"></i>
                              </a>
                           </h3>
                                <div class="clearfix"></div>
                            </div>
                            <div class="collapse in" id="1">
                                <div class="food-item white">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            <div class="rest-logo pull-left">
                                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/100x80" alt="Food logo"></a>
                                            </div>
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Veg Extravaganza</a></h6>
                                                <p> Burgers, American, Sandwiches, Fast Food, BBQ</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">$ 19.99</span> <a href="#" class="btn btn-small btn btn-secondary pull-right" data-toggle="modal" data-target="#order-modal">&#43;</a> </div>
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                                <div class="food-item">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            <div class="rest-logo pull-left">
                                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/100x80" alt="Food logo"></a>
                                            </div>
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Veg Extravaganza</a></h6>
                                                <p> Burgers, American, Sandwiches, Fast Food, BBQ</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">$ 19.99</span> <a href="#" class="btn btn-small btn btn-secondary pull-right" data-toggle="modal" data-target="#order-modal">&#43;</a> </div>
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                                <div class="food-item white">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            <div class="rest-logo pull-left">
                                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/100x80" alt="Food logo"></a>
                                            </div>
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Veg Extravaganza</a></h6>
                                                <p> Burgers, American, Sandwiches, Fast Food, BBQ</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">$ 19.99</span> <a href="#" class="btn btn-small btn btn-secondary pull-right" data-toggle="modal" data-target="#order-modal">&#43;</a> </div>
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                                <div class="food-item">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            <div class="rest-logo pull-left">
                                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/100x80" alt="Food logo"></a>
                                            </div>
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Veg Extravaganza</a></h6>
                                                <p> Burgers, American, Sandwiches, Fast Food, BBQ</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">$ 19.99</span> <a href="#" class="btn btn-small btn btn-secondary pull-right" data-toggle="modal" data-target="#order-modal">&#43;</a> </div>
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                            </div>
                            <!-- end:Collapse -->
                        </div>
                        <!-- end:Widget menu -->
                        <div class="menu-widget" id="2">
                            <div class="widget-heading">
                                <h3 class="widget-title text-dark">
                              POPULAR ORDERS Delicious hot food! <a class="btn btn-link pull-right" data-toggle="collapse" href="#popular2" aria-expanded="true">
                              <i class="fa fa-angle-right pull-right"></i>
                              <i class="fa fa-angle-down pull-right"></i>
                              </a>
                           </h3>
                                <div class="clearfix"></div>
                            </div>
                            <div class="collapse in" id="popular2">
                                <div class="food-item">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            <div class="rest-logo pull-left">
                                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/100x80" alt="Food logo"></a>
                                            </div>
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Veg Extravaganza</a></h6>
                                                <p> Burgers, American, Sandwiches, Fast Food, BBQ</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">$ 19.99</span> <a href="#" class="btn btn-small btn btn-secondary pull-right" data-toggle="modal" data-target="#order-modal">&#43;</a> </div>
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                                <div class="food-item white">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            <div class="rest-logo pull-left">
                                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/100x80" alt="Food logo"></a>
                                            </div>
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Veg Extravaganza</a></h6>
                                                <p> Burgers, American, Sandwiches, Fast Food, BBQ</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">$ 19.99</span> <a href="#" class="btn btn-small btn btn-secondary pull-right" data-toggle="modal" data-target="#order-modal">&#43;</a> </div>
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                                <div class="food-item">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            <div class="rest-logo pull-left">
                                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/100x80" alt="Food logo"></a>
                                            </div>
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Veg Extravaganza</a></h6>
                                                <p> Burgers, American, Sandwiches, Fast Food, BBQ</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">$ 19.99</span> <a href="#" class="btn btn-small btn btn-secondary pull-right" data-toggle="modal" data-target="#order-modal">&#43;</a> </div>
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                                <div class="food-item white">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            <div class="rest-logo pull-left">
                                                <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/100x80" alt="Food logo"></a>
                                            </div>
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Veg Extravaganza</a></h6>
                                                <p> Burgers, American, Sandwiches, Fast Food, BBQ</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">$ 19.99</span> <a href="#" class="btn btn-small btn btn-secondary pull-right" data-toggle="modal" data-target="#order-modal">&#43;</a> </div>
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                            </div>
                            <!-- end:Collapse -->
                        </div>
                        <!-- end:Widget menu -->
                        <div class="row m-t-30">
                            <div class="col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" class="panel-toggle collapsed" href="#faq1" aria-expanded="false">Can I viverra sit amet quam eget lacinia?</a></h4> </div>
                                    <div class="panel-collapse collapse" id="faq1" aria-expanded="false" role="article" style="height: 0px;">
                                        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam rutrum ut erat a ultricies. Phasellus non auctor nisi, id aliquet lectus. Vestibulum libero eros, aliquet at tempus ut, scelerisque sit amet nunc. Vivamus id porta neque, in pulvinar ipsum. Vestibulum sit amet quam sem. Pellentesque accumsan consequat venenatis. Pellentesque sit amet justo dictum, interdum odio non, dictum nisi. Fusce sit amet turpis eget nibh elementum sagittis. Nunc consequat lacinia purus, in consequat neque consequat id.</div>
                                    </div>
                                </div>
                                <!--//panel-->
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" class="panel-toggle" href="#faq2"><i class="ti-info-alt"></i>What is the ipsum dolor sit amet quam tortor?</a></h4> </div>
                                    <div class="panel-collapse collapse" id="faq2">
                                        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam rutrum ut erat a ultricies. Phasellus non auctor nisi, id aliquet lectus. Vestibulum libero eros, aliquet at tempus ut, scelerisque sit amet nunc. Vivamus id porta neque, in pulvinar ipsum. Vestibulum sit amet quam sem. Pellentesque accumsan consequat venenatis. Pellentesque sit amet justo dictum, interdum odio non, dictum nisi. Fusce sit amet turpis eget nibh elementum sagittis. Nunc consequat lacinia purus, in consequat neque consequat id.</div>
                                    </div>
                                </div>
                                <!--//panel-->
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" class="panel-toggle" href="#faq3"><i class="ti-info-alt"></i>How does lorem ipsum work?</a></h4> </div>
                                    <div class="panel-collapse collapse" id="faq3">
                                        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam rutrum ut erat a ultricies. Phasellus non auctor nisi, id aliquet lectus. Vestibulum libero eros, aliquet at tempus ut, scelerisque sit amet nunc. Vivamus id porta neque, in pulvinar ipsum. Vestibulum sit amet quam sem. Pellentesque accumsan consequat venenatis. Pellentesque sit amet justo dictum, interdum odio non, dictum nisi. Fusce sit amet turpis eget nibh elementum sagittis. Nunc consequat lacinia purus, in consequat neque consequat id.</div>
                                    </div>
                                </div>
                                <!--//panel-->
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" class="panel-toggle" href="#faq4"><i class="ti-info-alt"></i>Can I ipsum dolor sit amet nascetur ridiculus?</a></h4> </div>
                                    <div class="panel-collapse collapse" id="faq4">
                                        <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam rutrum ut erat a ultricies. Phasellus non auctor nisi, id aliquet lectus. Vestibulum libero eros, aliquet at tempus ut, scelerisque sit amet nunc. Vivamus id porta neque, in pulvinar ipsum. Vestibulum sit amet quam sem. Pellentesque accumsan consequat venenatis. Pellentesque sit amet justo dictum, interdum odio non, dictum nisi. Fusce sit amet turpis eget nibh elementum sagittis. Nunc consequat lacinia purus, in consequat neque consequat id.</div>
                                    </div>
                                </div>
                                <!--//panel-->
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
                        <div class="menu-widget m-b-30">
					<div class="comment_input">
					<b>REVIEWS</b>
					<?php
                // if user has not rated this product then show the ratings button
                if ($user_product_rating[0]["count"] == 0) {
                  ?> 
				  <div class="pull-left">
                      <!-- ratings will display here, make sure u bind #prd in the javascrript below -->
                      <div id="prd"></div>
                    </div>
					<br><br><br>
					<div class="clearfix">
        <form name="form1">
            <textarea name="comments" placeholder="Leave Comments Here..." style="width:635px; height:100px;"></textarea></br></br>
            <a href="#" onClick="commentSubmit()" class="button">Post</a></br>
        </form>
    </div>
	 <?php
                } else {
                  echo '<div class="padding20 nlp"><p><strike>You have already rated this product</strike></p></div>';
                }
                ?>
                <div class="padding10 clearfix"></div>
				<div id="comment_logs">
    	Loading comments...
    <div>
	
	</div>
	</div>
	<?php } else { ?>
            <div class="col-sm-12">
              <div class="padding20 nlp"><p><strike>No products found</strike></p></div>
            </div>
          <?php } ?>
                            </div>
                        </div>
                        <!--/row -->
						
                    </div>
					
                    <!-- end:Bar -->
                    <div class="col-xs-12 col-md-12 col-lg-3">
                        <div class="sidebar-wrap">
                            <div class="widget widget-cart">
                                <div class="widget-heading">
                                    <h3 class="widget-title text-dark">
                                 Your Shopping Cart
                              </h3>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="order-row bg-white">
                                    <div class="widget-body">
                                        <div class="title-row">Pizza Quatro Stagione <a href="#"><i class="fa fa-trash pull-right"></i></a></div>
                                        <div class="form-group row no-gutter">
                                            <div class="col-xs-8">
                                                <select class="form-control b-r-0" id="exampleSelect1">
                                                    <option>Size SM</option>
                                                    <option>Size LG</option>
                                                    <option>Size XL</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-4">
                                                <input class="form-control" type="number" value="2" id="example-number-input"> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="order-row">
                                    <div class="widget-body">
                                        <div class="title-row">Carlsberg Beer <a href="#"><i class="fa fa-trash pull-right"></i></a></div>
                                        <div class="form-group row no-gutter">
                                            <div class="col-xs-8">
                                                <select class="form-control b-r-0">
                                                    <option>Size SM</option>
                                                    <option>Size LG</option>
                                                    <option>Size XL</option>
                                                </select>
                                            </div>
                                            <div class="col-xs-4">
                                                <input class="form-control" value="4" id="quant-input"> </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end:Order row -->
                                <div class="widget-delivery clearfix">
                                    <form>
                                        <div class="col-xs-6 col-sm-12 col-md-6 col-lg-6 b-t-0">
                                            <label class="custom-control custom-radio">
                                                <input id="radio4" name="radio" type="radio" class="custom-control-input" checked=""> <span class="custom-control-indicator"></span> <span class="custom-control-description">Delivery</span> </label>
                                        </div>
                                        <div class="col-xs-6 col-sm-12 col-md-6 col-lg-6 b-t-0">
                                            <label class="custom-control custom-radio">
                                                <input id="radio3" name="radio" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Takeout</span> </label>
                                        </div>
                                    </form>
                                </div>
                                <div class="widget-body">
                                    <div class="price-wrap text-xs-center">
                                        <p>TOTAL</p>
                                        <h3 class="value"><strong>$ 25,49</strong></h3>
                                        <p>Free Shipping</p>
                                        <button onclick="location.href='checkout.html'" type="button" class="btn theme-btn btn-lg">Checkout</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
					
    
            
        </div>
        <!-- end:page wrapper -->
    </div>
    <!--/end:Site wrapper -->
    <!-- Modal -->
    <div class="modal fade" id="order-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                <div class="modal-body cart-addon">
                    <div class="food-item white">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
                                <!-- end:Logo -->
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
                                <!-- end:Description -->
                            </div>
                            <!-- end:col -->
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 2.99</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect2">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-2"> </div>
                                </div>
                            </div>
                        </div>
                        <!-- end:row -->
                    </div>
                    <!-- end:Food item -->
                    <div class="food-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
                                <!-- end:Logo -->
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
                                <!-- end:Description -->
                            </div>
                            <!-- end:col -->
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 2.49</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect3">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-3"> </div>
                                </div>
                            </div>
                        </div>
                        <!-- end:row -->
                    </div>
                    <!-- end:Food item -->
                    <div class="food-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
                                <!-- end:Logo -->
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
                                <!-- end:Description -->
                            </div>
                            <!-- end:col -->
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 1.99</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect5">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-4"> </div>
                                </div>
                            </div>
                        </div>
                        <!-- end:row -->
                    </div>
                    <!-- end:Food item -->
                    <div class="food-item">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-lg-6">
                                <div class="item-img pull-left">
                                    <a class="restaurant-logo pull-left" href="#"><img src="http://placehold.it/70x70" alt="Food logo"></a>
                                </div>
                                <!-- end:Logo -->
                                <div class="rest-descr">
                                    <h6><a href="#">Sandwich de Alegranza Grande Menü (28 - 30 cm.)</a></h6> </div>
                                <!-- end:Description -->
                            </div>
                            <!-- end:col -->
                            <div class="col-xs-6 col-sm-2 col-lg-2 text-xs-center"> <span class="price pull-left">$ 3.15</span></div>
                            <div class="col-xs-6 col-sm-4 col-lg-4">
                                <div class="row no-gutter">
                                    <div class="col-xs-7">
                                        <select class="form-control b-r-0" id="exampleSelect6">
                                            <option>Size SM</option>
                                            <option>Size LG</option>
                                            <option>Size XL</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input class="form-control" type="number" value="0" id="quant-input-5"> </div>
                                </div>
                            </div>
                        </div>
                        <!-- end:row -->
                    </div>
					
                    <!-- end:Food item -->
                </div>
				
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn theme-btn">Add to cart</button>
                
				</div>
				
            </div>
        </div>
    </div>
	
	

	
	
	
	
	
	
	
	
	
	
	
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
</body>

</html>