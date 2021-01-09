<?php 
/* code by webdevtrick ( https://webdevtrick.com ) */
session_start();
$connect = mysqli_connect("localhost", "root", "", "cart");

if(isset($_POST["add_to_cart"]))
{
	if(isset($_SESSION["shopping_cart"]))
	{
		$item_array_id = array_column($_SESSION["shopping_cart"], "item_id");
		if(!in_array($_GET["id"], $item_array_id))
		{
			$count = count($_SESSION["shopping_cart"]);
			$item_array = array(
				'item_id'			=>	$_GET["id"],
				'item_name'			=>	$_POST["hidden_name"],
				'item_price'		=>	$_POST["hidden_price"],
				'item_quantity'		=>	$_POST["quantity"]
			);
			$_SESSION["shopping_cart"][$count] = $item_array;
		}
		else
		{
			echo '<script>alert("Item Already Added")</script>';
		}
	}
	else
	{
		$item_array = array(
			'item_id'			=>	$_GET["id"],
			'item_name'			=>	$_POST["hidden_name"],
			'item_price'		=>	$_POST["hidden_price"],
			'item_quantity'		=>	$_POST["quantity"]
		);
		$_SESSION["shopping_cart"][0] = $item_array;
	}
}

if(isset($_GET["action"]))
{
	if($_GET["action"] == "delete")
	{
		foreach($_SESSION["shopping_cart"] as $keys => $values)
		{
			if($values["item_id"] == $_GET["id"])
			{
				unset($_SESSION["shopping_cart"][$keys]);
				echo '<script>alert("Item Removed")</script>';
				echo '<script>window.location="order.php"</script>';
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
                <style>
                    *{
                        margin:0;
                        padding:0;
                        list-style: none;
                        text-decoration: none;
                    }

                    .header{
                        width:100%;
                        height:80px;
                        display:block;
                        background-color: #101010;
                    }

                    .inner_header{
                        width:1000px;
                        height:100%;
                        display:block;
                        margin:0 auto;
                    }

                    .logo_container{
                        height:100%;
                        display:table;
                        float:left;
                    }

                    .logo_container > h1{
                        color:white;
                        height:100%;
                        display:table-cell;
                        vertical-align: middle;
                        font-family:'Montserrat';
                        font-size:32px;
                        font-weight: 200;
                    }

                    .logo_container > h1 > span{
                        font-weight:800;
                    }

                    .navigation{
                        float:right;
                        height:100%;
                    }

                    .navigation > li > a{
                        height:100%;
                        display:table;
                        float:left;
                        color:white;
                    }

                    .navigation > a:last-child{
                        padding-right:0px;
                    }

                    .navigation > li{
                        display: table-cell;
                        vertical-align: middle;
                        height:100px;
                        font-family:'Montserrat';
                        font-size: 18px;
                        padding: 30px 30px;
                    }
                    
                    
                    .btn-success {
                        color: #fff;
                        background-color: silver;
                        border-color: black;
                    }
                    
                    /*CSS for footer starts here*/
                    .footer{    
                            width: 100%;
                        height:30px;
                            background-color: #101010;
                            color:white;
                    }
                    .footer_center > p {
                        width:450px;
                        margin: 0 auto;
                        padding:5px;
                    }
                    /*CSS for footer ends here*/
                    
                </style>
        
        </head>
        <?php
include 'header.php';
include 'footer.php';
?>
	<body>
            
      		<br />
		<div class="container">
			<br />
			<br />
			<br />
                        <br /><br />
			<?php
				$query = "SELECT * FROM tbl_product ORDER BY id ASC";
				$result = mysqli_query($connect, $query);
				if(mysqli_num_rows($result) > 0)
				{
					while($row = mysqli_fetch_array($result))
					{
				?>
			<div class="col-md-4">
                            <form method="post" action="order.php?action=add&id=<?php echo $row["id"]; ?>">
					<div style="border:3px solid silver; background-color:whitesmoke; border-radius:5px; padding:16px;" align="center">
						<img src="images/<?php echo $row["image"]; ?>" class="img-responsive" /><br />

						<h4 class="text-info"><?php echo $row["name"]; ?></h4>

						<h4 class="text-danger">$ <?php echo $row["price"]; ?></h4>

						<input type="text" name="quantity" value="1" class="form-control" />

						<input type="hidden" name="hidden_name" value="<?php echo $row["name"]; ?>" />

						<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>" />

						<input type="submit" name="add_to_cart" style="margin-top:5px;" class="btn btn-success" value="Add to Cart" />

					</div>
				</form>
			</div>
			<?php
					}
				}
			?>
			<div style="clear:both"></div>
			<br />
			<h3>Order Details</h3>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tr>
						<th width="40%">Item Name</th>
						<th width="10%">Quantity</th>
						<th width="20%">Price</th>
						<th width="15%">Total</th>
						<th width="5%">Action</th>
					</tr>
					<?php
					if(!empty($_SESSION["shopping_cart"]))
					{
						$total = 0;
						foreach($_SESSION["shopping_cart"] as $keys => $values)
						{
					?>
					<tr>
						<td><?php echo $values["item_name"]; ?></td>
						<td><?php echo $values["item_quantity"]; ?></td>
						<td>$ <?php echo $values["item_price"]; ?></td>
						<td>$ <?php echo number_format($values["item_quantity"] * $values["item_price"], 2);?></td>
						<td><a href="order.php?action=delete&id=<?php echo $values["item_id"]; ?>"><span class="text-danger">Remove</span></a></td>
					</tr>
					<?php
							$total = $total + ($values["item_quantity"] * $values["item_price"]);
						}
					?>
					<tr>
						<td colspan="3" align="right">Total</td>
						<td align="right">$ <?php echo number_format($total, 2); ?></td>
						<td></td>
					</tr>
					<?php
					}
					?>
						
				</table>
			</div>
		</div>
	
	<br />
                <!--section for footer starts here-->
            
            <!--section for footer ends here-->
	</body>
</html>

