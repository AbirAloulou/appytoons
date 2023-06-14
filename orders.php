<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link / ?< echo time(); ?> to make css update while live  -->
   <link rel="stylesheet" href="css/style.css?<?php echo time(); ?>">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders" style="min-height: 43vh;">

   <h1 class="heading"><span style="color: black;">Placed</span> Orders</h1>

   <div class="box-container">

   <?php
   //en cas où aucun utl est connecte
      if($user_id == ''){
         echo '<p class="empty">please login to see your orders</p>';
      }else{
         //sinon on sélectionne les orders de l'util 
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            //boucler les ordres:
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
   <table>
  <tr>
    <th>Field</th>
    <th>Value</th>
  </tr>
  <tr>
    <td>placed on</td>
    <td><span><?= $fetch_orders['placed_on']; ?></span></td>
  </tr>
  <tr>
    <td>name</td>
    <td><span><?= $fetch_orders['name']; ?></span></td>
  </tr>
  <tr>
    <td>email</td>
    <td><span><?= $fetch_orders['email']; ?></span></td>
  </tr>
  <tr>
    <td>number</td>
    <td><span><?= $fetch_orders['number']; ?></span></td>
  </tr>
  <tr>
    <td>address</td>
    <td><span><?= $fetch_orders['address']; ?></span></td>
  </tr>
  <tr>
    <td>payment method</td>
    <td><span><?= $fetch_orders['method']; ?></span></td>
  </tr>
  <tr>
    <td>your orders</td>
    <td><span><?= $fetch_orders['total_products']; ?></span></td>
  </tr>
  <tr>
    <td>total price</td>
    <td><span><?= $fetch_orders['total_price']; ?>DT</span></td>
  </tr>
  <tr>
    <td>payment status</td>
    <td><span class="<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'pending'; }else{ echo 'completed'; }; ?>"><?= $fetch_orders['payment_status']; ?></span></td>
  </tr>
</table>
      <!-- <tr>
         <td>Placed on</td>
         <td><span><?= $fetch_orders['placed_on']; ?></span></td>
      </tr>
      <tr>
         <td>Name</td>
         <td><span><?= $fetch_orders['name']; ?></span></td>
      </tr>
      <tr>
         <td>email</td>
         <td><span><?= $fetch_orders['name']; ?></span></td>
      </tr>
      <tr>
         <td>Name</td>
         <td><span><?= $fetch_orders['name']; ?></span></td>
      </tr> -->
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>