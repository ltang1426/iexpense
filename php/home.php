<?php
	session_start();
	if(!isset($_SESSION['userid'])){
		header("Location: index.php");
	}
	include_once "connectdb.php";
	$userquery = "SELECT * FROM users WHERE id = " . $_SESSION['userid'];
	$userresult = mysqli_query($con, $query);
	$recentquery = "SELECT * FROM purchases WHERE userid =". $_SESSION['userid'] . " ORDER BY date DESC";
    $recentresult =mysqli_query($con, $recentquery);
    $recentrow = mysqli_fetch_array($recentresult);
    $recentitems = explode(" ", $recentrow['itemlist']);
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Home | iExpense</title>
			<meta content = "width = device.width , initial-scale = 1.0" name = "viewport">
			<link rel = "stylesheet" href = "vendor/font-awesome/css/font-awesome.min.css"/>
	        <link rel = "stylesheet" href = "vendor/bootstrap/css/bootstrap.min.css" type="text/css"/>
	        <link rel = "stylesheet" href = "vendor/owl-carousel/css/owl.carousel.css" type="text/css"/>
	        <link rel = "stylesheet" href = "vendor/owl-carousel/css/owl.theme.css" type="text/css"/>
	        <link rel = "stylesheet" href = "vendor/owl-carousel/css/owl.transitions.css" type="text/css"/>
	        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet"/> 
	        <link rel = "stylesheet" href = "css/main.css" type = "text/css"/>
	</head>
	<body>
		<nav class = "navbar navbar-default" role = "navigation">
            <div class = "container-fluid">
                <div class= " navbar-header">
                    <button type = "button" class = "navbar-toggle" data-toggle ="collapse" data-target = "#navbar">
                        <span class ="sr-only"> Toggle navigation </span>
                        <span class ="icon-bar"></span>
                        <span class = "icon-bar"></span>
                        <span class = "icon-bar"></span>
                    </button>
                    <a class = "navbar-brand" href= "index.php">
                        iExpense
                    </a>
                </div>

                <div class = "collapse navbar-collapse" id = "navbar">
                    <ul class = "nav navbar-nav navbar-right">
						<li>
							<a href = "purchases.php">Purchases</a>
						</li>
						<li>
							<a href = "services.php">Services</a>
						</li>
						<li>
							<a href = "employeeprofile.php">Employee profile</a>
						</li>
						<li>
							<a href = "transactions.php">Transactions</a>
						</li>
						<li>
							<a href = "wallet.php">Wallet</a>
						</li>
                        <li>
                        	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        		<i class="icon-user" aria-hidden="true"></i>
                         		 <?php echo $_SESSION['username'];?>
                         		<span class="caret"></span>
                         	</a>
							<ul class="dropdown-menu">
								<li>
									<a href="logout.php">
										<span class = "glyphicon glyphicon-log-out"></span>
										 Logout
									</a>
								</li>
							</ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class = "container">
        	<div class = "row">
        		<div class = "col-lg-12 col-md-12">
        			<h2 class = "text-center page-header">Welcome <?php echo $_SESSION['username']; ?>!
        			</h2>

        		<?php if($recentrow){ ?>
        			<div class = "panel panel-info">
        				<div class = "panel panel-heading clearfix">
        					<h4 class = "pull-left">Your most recent receipt - <?php
        					$storeid = $recentrow['storeid'];
        					$storequery= "SELECT * FROM stores WHERE id =". $storeid;
        					$storeresult = mysqli_query($con, $storequery);
        					$storerow=mysqli_fetch_array($storeresult);
        					$purchasetotal=0;
        					foreach ($recentitems as $item){
        						$itemquery = "SELECT * FROM items WHERE id =" . $item;
        						$itemresult = mysqli_query($con, $itemquery);
        						$itemrow = mysqli_fetch_array($itemresult);
        						$purchasetotal = $purchasetotal + $itemrow['price'];
        					}
        					echo $storerow['name']. " : $". $purchasetotal;
        					 ?></h4>

        				</div>

        				<div class = "panel panel-body">
        					<div class = "row">
        						<div class="owl-carousel col-md-12">

        							<?php
           								
        								
        								foreach ($recentitems as $item){
        									$itemquery = "SELECT * FROM items WHERE id =" . $item;
        									$itemresult = mysqli_query($con, $itemquery);
        									$itemrow = mysqli_fetch_array($itemresult);
        									
									?>
										<div class = "col-eq-height  thumbnail-item item">
											<a href = "#item-modal" data-toggle = "modal" class = "thumbnail" name = <?php echo '"'. $itemrow['name'].'"'; ?> price = <?php echo '"'. $itemrow['price'].'"'; ?> category = <?php echo "'" . $itemrow['category'] . "'"; ?> store = <?php echo "'". $storerow['name'] ."'";?> image = <?php echo '"'. $itemrow['image'].'"'; ?> date = <?php echo "'" . $recentrow['date']."'"; ?> receiptid = <?php echo "'" . $recentrow['id']."'"; ?> >
												<p><?php echo $itemrow['name']; 
												?></p>
												
												<img src = <?php echo '"'. $itemrow['image'].'"'; ?> >				
											</a>
										</div>
        							<?php }	?>
		   						</div>
        					</div>
        				</div>
        			</div>
        			<?php } ?>
        		</div>

        	</div>
        </div>


		<div class="modal fade" id="item-modal" role="dialog">
			<div class = "modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"></h4>
					</div>
					<div class="modal-body">
						<div class = "container-fluid">	
							<div class = "row center">
								<div class = "col-md-6 col-xs-12">
									<img src = "" class = "modal-img center-block"/>
								</div>
								<div class = "col-md-6 col-xs-12 well">
									<div class = "row">
 										<dl>
  											<dt class = "col-xs-6">Store</dt>
  											<dd class = "col-xs-6 item-store"><p> &nbsp; </p></dd>
  											<dt class = "col-xs-6">Price</dt>
  											<dd class = "item-price col-xs-6"><p></p></dd>
  											<dt class = "col-xs-6">Category</dt>
  											<dd class = "col-xs-6 item-category"><p></p></dd>
  											<dt class = "col-xs-6">Location</dt>
  											<dd class = "col-xs-6 item-location"><p></p></dd>
  											<dt class = "col-xs-6">Date</dt>
  											<dd class = "col-xs-6 item-date"><p></p></dd>
										</dl>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<a href = "transactions.php" type = "button" class = "btn btn-primary col-xs-offset-1" id = "download-receipt">
						<span class="glyphicon glyphicon-download-alt"></span> Download Receipt
						</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		<script src = "vendor/jquery/jquery-3.1.0.min.js"></script>
		<script src = "vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src = "vendor/owl-carousel/js/owl.carousel.min.js"></script>
		<script src = "js/main.js"></script>

		<script>
			$('.owl-carousel').owlCarousel({
				loop: true,
				items: 4
			});
		</script>
	</body>
</html>