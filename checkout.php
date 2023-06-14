<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
}
;

if (isset($_POST['order'])) {
   //récupérer le nom de la personne
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   //récupérer le numéro du téléphone
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   //récupérer son email
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   //récupérer la méthode de payement
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   //récupérer l'addresse
   $address = 'flat no. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   //les produits de cette commande
   $total_products = $_POST['total_products'];
   //Le prix total de la commande
   $total_price = $_POST['total_price'];

   //sélectionner les éléments de la cart de ce user
   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   //s'il existe des éléments dans la liste de cart
   if ($check_cart->rowCount() > 0) {
      //ajouter ces éléments à la liste des ordres
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      //Supprimer ces éléments de la cart (le vider)
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'order placed successfully!';
   } else {
      $message[] = 'your cart is empty';
   }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   
   <!-- custom css file link / ?< echo time(); ?> to make css update while live  -->
   <link rel="stylesheet" href="css/style.css?<?php echo time(); ?>">

</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="checkout-orders">

      <form action="" method="POST">

         <h3>your orders</h3>

         <div class="display-orders">
            <?php
            $grand_total = 0;
            $cart_items[] = '';
            // sélectionner les éléments du cart
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->execute([$user_id]);
            //s'il existe des éléments
            if ($select_cart->rowCount() > 0) {
               while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                  //ajouter les éléments au cart_items
                  $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
                  //les convertir de array en String en implode
                  $total_products = implode($cart_items);
                  //compter le montant total des produits
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                  ?>
                  <!-- Afficher les items de l'order  -->
                  <p>
                     <?= $fetch_cart['name']; ?> <span>(
                        <?= $fetch_cart['price'] . 'DT x ' . $fetch_cart['quantity']; ?>)
                     </span>
                  </p>
                  <?php
               }
               //s'il n'existe pas des éléments
            } else {
               echo '<p class="empty">your cart is empty!</p>';
            }
            ?>
            <input type="hidden" name="total_products" value="<?= $total_products; ?>">
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>" value="">
            <!-- Afficher le total de l'order  -->
            <div class="grand-total">grand total : <span>
                  <?= $grand_total; ?>DT
               </span></div>
         </div>

         <h3>place your orders</h3>

         <div class="flex">
            <div class="inputBox">
               <span>your name :</span>
               <input type="text" name="name" placeholder="enter your name" class="box" maxlength="20" required>
            </div>
            <div class="inputBox">
               <span>your number :</span>
               <input type="text" name="number" placeholder="enter your number" class="box"
                  onkeypress="if(this.value.length == 10) return false;" required>
            </div>
            <div class="inputBox">
               <span>your email :</span>
               <input type="email" name="email" placeholder="enter your email" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>payment method :</span>
               <select name="method" class="box" required>
                  <option value="cash on delivery">cash on delivery</option>
                  <option value="credit card">credit card</option>
                  <option value="paytm">paytm</option>
                  <option value="paypal">paypal</option>
               </select>
            </div>
            <div class="inputBox">
               <span>address line 01 :</span>
               <input type="text" name="flat" placeholder="e.g. flat number" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>address line 02 :</span>
               <input type="text" name="street" placeholder="e.g. street name" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>city :</span>
               <input type="text" name="city" placeholder="e.g. Sakiet Ezzit" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>state :</span>
               <input type="text" name="state" placeholder="e.g. sfax" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>country :</span>
               <input type="text" name="country" placeholder="e.g. Tunisia" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>zip code :</span>
               <input type="number" min="0" name="pin_code" placeholder="e.g. 3021" min="0" max="999999"
                  onkeypress="if(this.value.length == 6) return false;" class="box" required>
            </div>
         </div>

         <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="place order">

      </form>

   </section>

   <?php include 'components/footer.php'; ?>

   <script src="js/script.js"></script>

</body>

</html>