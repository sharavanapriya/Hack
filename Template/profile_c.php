<!DOCTYPE html>
<html lang="en">

<head>
<script src="raty/jquery.raty.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
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
<?php
require_once './config.php';
?>
<!-- include this file everytime you want to use rating plugin -->
<script src="raty/jquery.raty.js" type="text/javascript"></script>
    <div class="site-wrapper animsition" data-animsition-in="fade-in" data-animsition-out="fade-out">
        <!--header starts-->
        <header id="header" class="header-scroll top-header headrom">
            <!-- .navbar -->
            <?php include('nav.php'); ?>
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
                                    <h6><a href="#"><?php echo $products[$i]["product_name"] ?></a></h6> <a class="btn btn-small btn-green">Open</a>
                                    <p><?php echo $products[0]["pdesc"] ?></p>
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
                    Total: 400  /
									Females: 100 /
									
									Ratio: 1:4
                </div>
            </div>
            <div class="container m-t-30">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3">
                        <div class="sidebar clearfix m-b-20">
                            <div class="main-block">
                                <div class="sidebar-title white-txt">
                                    <h6>Choose Category</h6> <i class="fa fa-cutlery pull-right"></i> </div>
                                <ul>
                                    <li><a href="#1" class="scroll active">Overview</a></li>
                                    <li><a href="#2" class="scroll">Jobs</a></li>
                                    <li><a href="#3" class="scroll">Reviews</a></li>
                                    
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <!-- end:Sidebar nav -->
                            <div class="widget-delivery">
                                <form>
                                    <div class="col-xs-6 col-sm-12 col-md-6 col-lg-6">
                                        <label class="custom-control custom-radio">
                                            <input id="radio1" name="radio" type="radio" class="custom-control-input" checked=""> <span class="custom-control-indicator"></span> <span class="custom-control-description">Working</span> </label>
                                    </div>
                                    <div class="col-xs-6 col-sm-12 col-md-6 col-lg-6">
                                        <label class="custom-control custom-radio">
                                            <input id="radio2" name="radio" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Interested</span> </label>
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
                                <h3 id="1" class="widget-title text-dark">
                              Overview <a class="btn btn-link pull-right" data-toggle="collapse" href="#popular2" aria-expanded="true">
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
                                           
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Working at <?php echo $products[0]["product_name"] ?> </a></h6>
                                                <p> <meter value="0.69">69%</meter></p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                                <div class="food-item white">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                          
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Rate salary as high or average</a></h6>
                                                <p> <meter value="0.89">89%</meter></p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                                <div class="food-item">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Career development</a></h6>
                                                <p> <i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star"></i>
<i style="color:#e6b800" class="fa fa-star-o"></i>

<i style="color:#e6b800" class="fa fa-star-o"></i>


</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                    </div>
                                    <!-- end:row -->
                                </div></div>
								<div class="food-item">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Work life balance</a></h6>
                                                <p> <i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star"></i>
<i style="color:#e6b800" class="fa fa-star-half-o"></i>

<i style="color:#e6b800" class="fa fa-star-o"></i>


</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                    </div>
                                    <!-- end:row -->
                                </div>
								
								
								<div class="food-item">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Management</a></h6>
                                                <p> <i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star"></i>
<i style="color:#e6b800" class="fa fa-star-half-o"></i>

<i style="color:#e6b800" class="fa fa-star-o"></i>


</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                    </div>
                                    <!-- end:row -->
                                </div>
								
								<div class="food-item">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Benefits and Perks</a></h6>
                                                <p> <i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star"></i>
<i style="color:#e6b800" class="fa fa-star-half-o"></i>

<i style="color:#e6b800" class="fa fa-star-o"></i>


</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                    </div>
                                    <!-- end:row -->
                                </div>
								
								<div class="food-item">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-lg-8">
                                            
                                            <!-- end:Logo -->
                                            <div class="rest-descr">
                                                <h6><a href="#">Diversity and Equal Oppurtunity</a></h6>
                                                <p> <i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star"></i>
<i style="color:#e6b800" class="fa fa-star"></i>

<i style="color:#e6b800" class="fa fa-star-half-o"></i>


</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                    </div>
                                    <!-- end:row -->
                                </div></div>
                                <!-- end:Food item -->
								
								<div class="row m-t-30">
                            <div class="col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div id="2" class="panel-heading">
									<b>JOBS</b>
									</div></div>
                                <div class="row m-t-30">
                            <div class="col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-heading">
									Microsoft .Net Developer
									</div>
									A fantastic opportunity for an experienced Developer, with a focus on Microsoft .Net Technology, to join our Tab Technology team
Melbourne
									</div></div></div>
									<div class="row m-t-30">
                            <div class="col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-heading">
									Scrum Master
									</div>
									A great opportunity to join the Tab Technology team as a Scrum Master, coaching the Scrum team on delivery of projects
Melbourne
									</div></div></div>
									
									

                                <!-- end:Food item -->
                            </div>
                            <!-- end:Collapse -->
                        </div>
                        <!-- end:Widget menu -->
                        <div class="row m-t-30">
                            <div class="col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" class="panel-toggle collapsed" href="#faq1" aria-expanded="false">How does TogetHer work?</a></h4> </div>
                                    <div class="panel-collapse collapse" id="faq1" aria-expanded="false" role="article" style="height: 0px;">
                                        <div class="panel-body">Order food or takeout online.Read and write reviews based on your experiences to help other females.</div>
                                    </div>
                                </div>
                                <!--//panel-->
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" class="panel-toggle" href="#faq2"><i class="ti-info-alt"></i>What do I write in my review?</a></h4> </div>
                                    <div class="panel-collapse collapse" id="faq2">
                                        <div class="panel-body">Keep reviews to the point. Reviews about safety and your overall experience are preferred.Mention the location,politeness of waitstaff, general ambience and the usual crowd in attendance</div>
                                    </div>
                                </div>
                                <!--//panel-->
                                <div class="panel">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" class="panel-toggle" href="#faq3"><i class="ti-info-alt"></i>What else can I do with TogetHer?</a></h4> </div>
                                    <div class="panel-collapse collapse" id="faq3">
                                        <div class="panel-body">Book events,order food,look up restaurants, companies  and events to check for women safety.</div>
                                    </div>
                                </div>
                                <!--//panel-->
                                
                                <!--//panel-->
		
					
                    <!-- end:Bar -->
                    
                                <!-- end:Order row -->
								
                                
                                   
					<div id="3" class="comment_input">
					<b>REVIEWS</b>
					<?php
					require("db/db.php");


                // if user has not rated this product then show the ratings button
                if ($user_product_rating[0]["count"] == 0) {
                  ?> 
				   <div class=" padding10 clearfix"></div>
                  <div id="rating_zone">
				  <div class="pull-left">
                      <!-- ratings will display here, make sure u bind #prd in the javascrript below -->
                      <div id="prd"></div>
                    </div>
					<br><br><br>
					<div class="clearfix">
        <form name="form" method="post" action="insert.php?pid=<?php echo $_SESSION["pid"] ?>">
            <input type="radio" value="s" name="type">Safety
			<input type="radio" value="g" name="type">General
			<input type="radio" value="e" name="type">Experiences
			<textarea name="comments" placeholder="Leave Comments Here..." style="width:635px; height:100px;"></textarea></br></br>
            <input type="submit" class="button" name="b1">
        </form>
		</div>
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
	</div>
	<?php } else { ?>
            <div class="col-sm-12">
              <div class="padding20 nlp"><p><strike>No products found</strike></p></div>
            </div>
          <?php } ?>
                            </div>
                        </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
					
    
            
        </div>
        <!-- end:page wrapper -->
    </div>
							
                        <!--/row -->
						
                    </div>
    <!--/end:Site wrapper -->
    <!-- Modal -->
    
				
				
            </div>
        </div>
    </div>
	<script>
  $(function() {
    $('#prd').raty({
      number: 5, starOff: 'raty/img/star-off-big.png', starOn: 'raty/img/star-on-big.png', width: 180, scoreName: "score",
    });
  });
</script>

<script>
  $(document).on('click', '#submit', function() {
<?php
if (!isset($USER_ID)) {
  ?>
      alert("You need to have a account to rate this product?");
      return false;
<?php } else { ?>

      var score = $("#score").val();
      if (score.length > 0) {
        $("#rating_zone").html('processing...');
        $.post("update_ratings.php", {
          pid: "<?php echo $_GET["pid"]; ?>",
          uid: "<?php echo $USER_ID; ?>",
          score: score
        }, function(data) {
          if (!data.error) {
            // success message
            $("#avg_ratings").html(data.updated_rating);
            $("#rating_zone").html(data.message).show();
          } else {
            // failure message
            $("#rating_zone").html(data.message).show();
          }
        }, 'json'
                );
      } else {
        alert("select the ratings.");
      }

<?php } ?>
  });
  
  
</script>
	
	
	
	
	
	
	
	
	
	
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