<!DOCTYPE html>
<html lang="en">

<head>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script>

	function commentSubmit(){
		if(form1.name.value == '' && form1.comments.value == ''){ //exit if one of the field is blank
			alert('Enter your message !');
			return;
		}
		var name = form1.name.value;
		var comments = form1.comments.value;
		var xmlhttp = new XMLHttpRequest(); //http request instance
		
		xmlhttp.onreadystatechange = function(){ //display the content of insert.php once successfully loaded
			if(xmlhttp.readyState==4&&xmlhttp.status==200){
				document.getElementById('comment_logs').innerHTML = xmlhttp.responseText; //the chatlogs from the db will be displayed inside the div section
			}
		}
xmlhttp.open('GET', 'insert.php?name='+name+'&comments='+comments+'&pid='+pid+'&uid='+uid, true); //open and send http request
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


<script>
<?php


session_start();
 @$pid=$_GET["pid"];
// Storing session data
$_SESSION["pid"] = $pid;

?>
	
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
    <link href="css/style.css" rel="stylesheet"> 
	
	
<!-- include the rating javascript file -->
<script src="raty/jquery.raty.js" type="text/javascript"></script>

<!-- to display rating bind this div -->
<div id="prd"></div>
<script>
  $(function() {
    $('#prd').raty({
      number: 5, starOff: 'raty/img/star-off-big.png', starOn: 'raty/img/star-on-big.png', width: 180, scoreName: "score",
    });
  });
</script>

<!-- when user submits the rating fire an ajax event -->
<script>
  $(document).on('click', '#submit', function() {
      var score = $("#score").val();
      if (score.length > 0) {
        $("#rating_zone").html('processing...');
        $.post("update_ratings.php", {
          pid: "<?php echo $_GET["pid"]; ?>",
          uid: "<?php echo $USER_ID; ?>",
          score: score
        }, function(data) {
          if (!data.error) {
            // display the new ratings 
            $("#avg_ratings").html(data.updated_rating);
			// display the success message
            $("#rating_zone").html(data.message).show();
          } else {
            // display the failure message
            $("#rating_zone").html(data.message).show();
          }
        }, 'json'
                );
      } else {
        alert("select the ratings.");
      }
  });
</script>
	
	
	
	
	</head>

<body>
<?php

session_start();
$_SESSION["pid"]=1;
$_SESSION["uid"]=1;
?>
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
      ?><?php
	  $i=0;
          if (count($products) > 0) {
            ?>
            <section class="inner-page-hero bg-image" data-image-src="images/<?php echo $products[$i]["image"] ?>.jpg">
                <div class="profile">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12  col-md-4 col-lg-4 profile-img">
                                <div class="image-wrap">
                                    <figure><img src="images/<?php echo $products[$i]["image"] ?>.jpg" alt="Profile Image"></figure>
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
                                            <a class="nav-link" href="#"> <?php
                  // display the ratings for this product
                  if ($product_rating[0]["count"] > 0) {
                    echo "Average rating <strong>" . round($product_rating[0]["score"], 2) . "</strong> based on <strong>" . $product_rating[0]["count"] . "</strong> users";
                  } else {
                    echo 'No ratings for this product';
                  }
				  
                  ?>  </a>
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
                           
                            <!-- end:Sidebar nav -->
                            <div class="widget-delivery">
                                <form>
                                    <div class="col-xs-6 col-sm-12 col-md-6 col-lg-6">
                                        <label class="custom-control custom-radio">
                                            <input id="radio1" name="radio" type="radio" class="custom-control-input" checked=""> <span class="custom-control-indicator"></span> <span class="custom-control-description">Going</span> </label>
                                    </div>
                                    <div class="col-xs-6 col-sm-12 col-md-6 col-lg-6">
                                        <label class="custom-control custom-radio">
                                            <input id="radio2" name="radio" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Unsure</span> </label>
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
                              Total number going: 75 <a class="btn btn-link pull-right" data-toggle="collapse" href="#popular" aria-expanded="true">
                              <i class="fa fa-angle-right pull-right"></i>
                              <i class="fa fa-angle-down pull-right"></i>
                              </a>
                           </h3>
                                <div class="clearfix"></div>
                            </div>
                            
                        <!-- end:Widget menu -->
                        <div class="menu-widget" id="2">
                            <div class="widget-heading">
                                <h3 class="widget-title text-dark">
                              Total females going: 23
<br>
Male to female ratio: 3:1							  <a class="btn btn-link pull-right" data-toggle="collapse" href="#popular2" aria-expanded="true">
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
                                                <h6><a href="#">Phase1 Pass</a></h6>
                                                <p> VIP access, early bird rates , open bar</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">$ 9.99</span> <a href="#" class="btn btn-small btn btn-secondary pull-right" data-toggle="modal" data-target="#order-modal">&#43;</a> </div>
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
                                                <h6><a href="#">Phase2 Pass</a></h6>
                                                <p> Silver access, one food pass</p>
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
                                                <h6><a href="#">Phase 3 Pass</a></h6>
                                                <p> General Access ,One food pass</p>
                                            </div>
                                            <!-- end:Description -->
                                        </div>
                                        <!-- end:col -->
                                        <div class="col-xs-12 col-sm-12 col-lg-4 pull-right item-cart-info"> <span class="price pull-left">$ 29.99</span> <a href="#" class="btn btn-small btn btn-secondary pull-right" data-toggle="modal" data-target="#order-modal">&#43;</a> </div>
                                    </div>
                                    <!-- end:row -->
                                </div>
                                <!-- end:Food item -->
                                
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
					
	</div>
	</div>
                            </div>
                        </div>
						
						
						<div id="container">
	<div class="page_content">
    	
    </div>
    <div class="comment_input">
        <form name="form1">
        	<input type="text" name="name" placeholder="Name..."/></br></br>
            <textarea name="comments" placeholder="Leave Comments Here..." style="width:635px; height:100px;"></textarea></br></br>
            <a href="#" onClick="commentSubmit()" class="button">Post</a></br>
        </form>
    </div>
    <div id="comment_logs">
    	Loading comments...
    <div>
</div>
	  
	  
	  
	  
	  
    </div>
  </div>
                        <!--/row -->
						
						<?php
                // if user has not rated this product then show the ratings button
                if ($user_product_rating[0]["count"] == 0) {
                  ?>  
                  <div class=" padding10 clearfix"></div>
                  <div id="rating_zone">

                    <div class="pull-left">
                      <!-- ratings will display here, make sure u bind #prd in the javascrript below -->
                      <div id="prd"></div>
                    </div>
                    <div class="pull-left">
                      <button class="btn btn-primary btn-sm" id="submit" type="button">submit</button>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                  <?php
                } else {
                  echo '<div class="padding20 nlp"><p><strike>You have already rated this product</strike></p></div>';
                }
                ?>
                <div class="padding10 clearfix"></div>
                <a class="btn btn-info" href="index.php"><span class="glyphicon glyphicon-chevron-left"></span> back to listing</a>
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
						
						
                    </div>
					
                    <!-- end:Bar -->
                    <!-- end:Order row -->
                                
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