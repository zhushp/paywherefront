<?php require_once("common.php"); ?>
<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $SHOPNAME ?> - My Cart</title>

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
                <p class="lead"><?php echo $SHOPNAME ?> - My Cart</p>
                <div class="list-group">
                    <a href="#" class="list-group-item active">All</a>
                    <a href="#" class="list-group-item">Premium Limited Edition</a>
                    <a href="#" class="list-group-item">Classic Craft Limited Quantity</a>
                    <a href="#" class="list-group-item">Basic Craft Wide Variety</a>
                </div>
            </div>

            <div class="col-md-9">
            
            <?php
				$items = json_decode(file_get_contents('http://localhost/api/cart'));
				$totalPrice = 0;
				foreach ($items as $item) {
			?>
              <div class="thumbnail cartItem" id="<?php echo "cartItem".$item->id; ?>">
                    <div class="caption-full">
                    <table width="100%" border="0" cellpadding="10">
                      <tr>
                        <td width="100px"><img src="<?php echo $item->urlThumb; ?>" class="cartimg"></td>
                        <td><h4><?php echo $item->productName; ?></h4></td>
                        <td width="100px"><h4 id="<?php echo "subTotal".$item->id; ?>" class="pull-right">S$<?php echo number_format((float)$item->productPrice*$item->quantity, 2, '.', ''); ?></h4></td>
                      </tr>
                    </table>
                    </div>
                    <div class="addcart">
                    	<form>
                        	<input value="<?php echo $item->id; ?>" name="id" type="hidden">
                            <input id="<?php echo "price".$item->id; ?>" value="<?php echo $item->productPrice; ?>" type="hidden"></input>
                            <p class="addcartquantity pull-right">
                                <input id="<?php echo "qty".$item->id; ?>" type="text" value="<?php echo $item->quantity; ?>" name="quantity">
                            </p>
                            <p class="pull-right">
                                <label>qty:</label>
                            </p>
                            <p>
                                <button type="submit" class="btn btn-default btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span>Remove
                                </button>
                            </p>
                      </form>
                    </div>
                </div>
			<?php
                }
            ?>

                <div class="well">
                
                	<div class="row">
                        <div class="col-md-12" style="font-size:24px">
                        	<span class="pull-right" id="price">S$-</span>
                            <span class="pull-right" >Subtotal:&nbsp;&nbsp;</span>
                        </div>
                    </div>

                    <hr>

                    <div class="text-right">
                        <a class="btn btn-success">Checkout</a>
                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- /.container -->

    <?php include("footer.php") ?>

    <script src="js/jquery-1.11.1.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.touchspin.js"></script>
    <script>
		function getTotalQuantity() {
			var n = 0;
			$.getJSON( "http://<?php echo $HOMEURL ?>/api/cart/quantity", function( json ) {
				n = json.TotalQuantity;
			}).done(function() {
				$("#totalQuantity").text(n);
			});
		}
		
		function getTotalPrice() {
			var price = 0;
			$.getJSON( "http://<?php echo $HOMEURL ?>/api/cart/price", function( json ){
				price = json.price;
			})
			.done(function() {
				$("#price").text("S$" + price);
			});
		}
		
		$( document ).ready(function() {
			getTotalPrice();
		});
		
		var i = $("input[name='quantity']")
		
		i.TouchSpin({
			min: 1
		});
		
		i.on("change", function () {
            //console.log("touchspin.on.stopspin");
			var id = $(this).attr("id").substr(3);
			var quantity = $(this).val();
			var price = $("#price"+id).val();
			$("#subTotal"+id).text( "S$" + (price*quantity).toFixed(2) );
			//console.log(price);
			$.post( "http://<?php echo $HOMEURL ?>/api/cart/update_item", JSON.stringify({ "id": id, "quantity": quantity }) );
			getTotalQuantity();
			getTotalPrice();
        });
		
		$( "form" ).submit(function( event ) {
			event.preventDefault();
			var $form = $( this ),
			id = $form.find( "input[name='id']" ).val();
			$.post( "http://<?php echo $HOMEURL ?>/api/cart/remove_item", JSON.stringify({ "id": id }) );
			console.log(id);
			$( "#cartItem" + id ).slideToggle( "slow" );
			getTotalQuantity();
			getTotalPrice();
		});
	</script>

</body>

</html>
