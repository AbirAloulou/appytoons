<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
         <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="home.php" class="logo " style="display: flex;justify-content: center;align-items: center;gap:2rem"><img
            src="images/favicon.png" width="70px" />
         <h2 id="logo-text">Appy Toons</h2>
      </a>
      <div class="flex-nav">
         <nav class="navbar">
            <a class="active" href="home.php">HOME</a>
            <a href="about.php">ABOUT</a>
            <a href="orders.php">ORDERS</a>
            <a href="shop.php">SHOP</a>
            <a href="contact.php">CONTACT</a>
         </nav>

         <div class="icons">
            <?php
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            $total_wishlist_counts = $count_wishlist_items->rowCount();

            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
            ?>
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php"><i class="fas fa-search"></i></a>
            <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?=$total_wishlist_counts; ?>)
               </span></a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?=$total_cart_counts; ?>)
               </span></a>
            <div id="user-btn" class="fas fa-user"></div>
         </div>

         <div class="profile">
            <?php
            //chercher le profil connecté depuis son id 
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            //sélectionner le profil cherché
            if ($select_profile->rowCount() > 0) {
               // en cas l'utilisateur est connecté
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
               ?>
               <p>
                  <!-- Afficher le nom de l'utilisateur -->
                  <?= $fetch_profile["name"]; ?>
               </p>
               <!-- bouton pour mise à jour du profile  -->
               <a href="update_user.php" class="btn">update profile</a>
               <!-- bouton pour logout avec avertissement de logout  -->
               <a href="components/user_logout.php" class="delete-btn"
                  onclick="return confirm('logout from the website?');">logout</a>
               <?php
            } else {
               ?>
               <!-- En cas d'un utilisateur non connecté  -->
               <p>please login or register first!</p>
               <div class="flex-btn">
                  <a href="user_register.php" class="option-btn">register</a>
                  <a href="user_login.php" class="option-btn">login</a>
               </div>
               <?php
            }
            ?>


         </div>
      </div>

   </section>

</header>