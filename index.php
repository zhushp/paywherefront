<?php require_once("common.php"); ?>
<!DOCTYPE html>
<html lang="en" ng-app id="ng-app">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $SHOPNAME ?> - Product List</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Add custom CSS here -->
    <link href="css/common.css" rel="stylesheet">
    
    <script src="js/angular.js"></script>
    
    <script>
	function getTotalQuantity($scope, $http)
	{
		$http({method: 'GET', url: 'http://<?php echo $HOMEURL ?>/api/cart/quantity'}).success(function(data)
		{
			$scope.get = data; // response data 
		});
	}
	</script>

</head>

<body>
	
    <?php include("header.php"); ?>

    <div class="container">

        <div class="row">

            <div class="col-md-3">
                <p class="lead"><?php echo $SHOPNAME ?></p>
                <div class="list-group">
                    <a href="#" class="list-group-item active">All</a>
                    <a href="#" class="list-group-item">Premium Limited Edition</a>
                    <a href="#" class="list-group-item">Classic Craft Limited Quantity</a>
                    <a href="#" class="list-group-item">Basic Craft Wide Variety</a>
                </div>
            </div>

            <div class="col-md-9">

                <div class="row">
                
                	<?php
						$products = json_decode(file_get_contents('http://localhost/api/product'));
						foreach ($products as $product) {
					?>
                    		<div class="col-sm-4 col-lg-4 col-md-4">
                                <div class="thumbnail">
                                	<div class="thumbpic">
	                               		<img src="<?php echo $product->urlThumb; ?>" alt="" >
                                    </div>
                                    <div class="caption">
                                          <h4><?php echo $product->productName; ?>
                                      </h4><div class="pricecurrency">S$ </div><div class="price"><?php echo $product->productPrice; ?></div><div class="origin-price"><?php echo $product->originalPrice; ?></div></p>
                                    </div>
                                    <div class="addcart">
                                        <form id="<?php echo "form_".$product->id; ?>">
                                        	<input value="<?php echo $product->id; ?>" name="id" type="hidden">
                                            <input value="<?php echo $product->productName; ?>" name="productName" type="hidden">
                                            <p class="addcartquantity pull-right">
                                                <input type="text" value="" name="quantity">
                                            </p>
                                            <p>
                                                <button type="submit" class="btn btn-default">
                                                    <span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart
                                                </button>
                                            </p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                    <?php
						}
					?>

                </div>

            </div>

        </div>

    </div>
    <!-- /.container -->

    <?php include("footer.php") ?>
    
    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">
          	<div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title" id="mySmallModalLabel">Successful</h4>
            </div>
            <div class="modal-body" id="modal-body">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Continue Shopping</button>
              <button type="button" class="btn btn-primary" onclick="window.location.href='/shop/cart.php'">View My Cart</button>
            </div>
        </div>
      </div>
    </div>
    <!-- /.modal -->

    <!-- JavaScript -->
    <script src="js/jquery-1.11.1.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.touchspin.js"></script>
    
    <script>
		
		
		function getTotalQuantity() {
			$.getJSON( "http://<?php echo $HOMEURL ?>/api/cart/quantity", function( json ) {
				$("#totalQuantity").text(json.TotalQuantity);
			});
		}
		
		$("input[name='quantity']").TouchSpin({
			initval: 1,
			min: 1
		});

		$( "form" ).submit(function( event ) {
			event.preventDefault();
			var $form = $( this ),
			id = $form.find( "input[name='id']" ).val(),
			quantity = $form.find( "input[name='quantity']" ).val(),
			productName = $form.find( "input[name='productName']" ).val();
			//console.log($(this).closest("form").attr("id"));
			//console.log("id: " + id + " quantity: " + quantity);
			$.post( "http://<?php echo $HOMEURL ?>/api/cart/add_item", JSON.stringify({ "productId": id, "quantity": quantity }) );
			getTotalQuantity();
			$('#modal-body').html("You have successfully added " + quantity + " " + productName + " to your cart");
			$('#myModal').modal();
		});
	</script>

</body>

</html>
