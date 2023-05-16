

<?php
@include 'config.php';

/*Attention : 
I have a more indepth course on paystack integration where I built projects using paystack like:
1. Donation application
2. User subscription etc 
CONTACT Me now if you want it: thelordofapps@gmail.com
*/
$amount = 40;
//Sanitize form inputs from harmful data
 $sanitizer = filter_var_array($_POST, FILTER_SANITIZE_STRING);
 
 //Collect form data into regular post variables





 session_start();
$user_id = $_SESSION['user_id'];

if(isset($_POST['order'])){



  

  $cart_total = 0;
  $cart_products[] = '';




  $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
  if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
          $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
          $sub_total = ($cart_item['price'] * $cart_item['quantity']);
          $cart_total += $sub_total;
      }
  }


  $select_cart = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
  if(mysqli_num_rows($select_cart) > 0){
      while($fetch_cart = mysqli_fetch_assoc($select_cart)){

 
          $emails = $fetch_cart['email'] ; 


  }
}
  $total_products = implode(', ',$cart_products);

  $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE  email = '$emails' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

  if($cart_total == 0){
      $message[] = 'your cart is empty!';
  }elseif(mysqli_num_rows($order_query) > 0){
      $message[] = 'order placed already!';
  }else{
      mysqli_query($conn, "INSERT INTO `orders`(user_id, email, total_products, total_price) VALUES('$user_id',  '$emails','$total_products', '$cart_total')") or die('query failed');
      mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      $message[] = 'order placed successfully!';
  }
}

//echo $first_name;
//echo $email;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Paystack pay page</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>


        

     <?php
       
        $select_cart = mysqli_query($conn, "SELECT * FROM `users` WHERE id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
     ?> 
     	
       <?php $emails = $fetch_cart['email'] ; ?>
      
       <?php
   
        }
     }else{
        echo '<p class="empty">your cart is empty</p>';
     }
     ?>



<h2>Hi, <?php echo $emails; ?></h2>




		


<form action="" method="POST">
  <script src="https://js.paystack.co/v1/inline.js"></script>

  <button name="order" type="submit" onclick="payPageWithPaystack()"> Proceed </button> 

</form>
 

<script>
  function payPageWithPaystack(){
const api = "pk_test_2e6b1d2bb5c805c7a524e3de16be71c44939fd8d";
    var handler = PaystackPop.setup({
      key: api,

      
      email: "<?php echo $emails; ?>",
      amount:    <?php echo $amount*100; ?>,
      currency: "GHS",
      ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
      firstname: "<?php echo". $emails."; ?>",
      lastname: "<?php echo $emails; ?>",
      phone: "<?php echo $emails; ?>",
      // label: "Optional string that replaces customer email"
      metadata: {
      
      },
      callback: function(response){
           const referenced = response.reference;
		  window.location.href='success.php?successfullypaid='+ referenced;
      },
      onClose: function(){
          alert('window closed');
      }
    });
    handler.openIframe();
  }
</script>


</body>
</html>