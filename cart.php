<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>Geek e-shop</title>
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />
    <meta name="description" content="This page is a smartphone e-shop">
  </head>

  <body>

  <?php
  #create a database connection instance
  session_start();
  $mysqli = new mysqli("localhost", "root", "", "phones_db");
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }
  ?>
    
  <header>
      <div id="header-content">
        <div id="logo">
          <a href="index.php"><img src="images/geek.png" alt="logo"></a>
        </div>
        <div id="cart">
            <img src="images/shopping-cart.png" alt="image of a shopping-cart">
            <?php
             #shows the quantity of the cart
                if(isset($_SESSION['cart']) )
                {
                  $items = $_SESSION['cart'];
                  $cartitems = explode(",", $items);
                  foreach ($cartitems as $key => $value) {
                    if (empty($value)) {
                        unset($cartitems[$key]);
                    }
                  }
                  $n =count($cartitems);
                  echo '<p>My Cart ('.$n.')</p>';
                }
              else {
                echo '<p>My Cart (0)</p>';
              }
              ?>
        </div> 
      </div>    
  </header>

  <div id="shopping-cart">
  <div class="txt-heading">Shopping Cart</div>
  <div id="added">Added to cart!</div>
  <div id="incart">Already in cart!</div>
  <script>
    //if successfully added to cart show the right message
    function added_show() {
      var x = document.getElementById("added");
      x.className = "show";
      setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
    }
  </script>
  <script>
    //if already in cart show the right message
    function incartShow() {
      var x = document.getElementById("incart");
      x.className = "show";
      setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
    }
  </script>

    <?php 
       if(isset($_GET['status']))
       {
         if($_GET['status'] == "success")
          {
            echo '<script type="text/javascript">',
                 'added_show();',
                 '</script>'
            ;
          }
          else if($_GET['status'] == "incart") {
            echo '<script type="text/javascript">',
                 'incartShow();',
                 '</script>'
            ;
          }
       }  
    ?>

      <div class="container">
      <?php 
        $total = 0;
        if(!isset($_SESSION['cart']))
        {
          echo '<br><br>
                <p style="text-align: center;">Your cart is empty!<br><br></p>';
        }
        if(isset($_SESSION['cart']) )
        {
          $items = $_SESSION['cart'];
          $cartitems = explode(",", $items);
          foreach ($cartitems as $key => $value) {
            if (empty($value)) {
               unset($cartitems[$key]);
            }
          }
          if(empty($cartitems))
          {
            echo '<br><br>
            <p style="text-align: center;">Your cart is empty!<br><br></p>';
          }
          else{
            foreach ($cartitems as $key=>$id) {
              $sql = "SELECT * FROM phones WHERE phones.phone_id = '$id'";
              $res = $mysqli->query($sql);
              $r = $res->fetch_assoc();
              echo '<article class="phone">
              <div id="phone-img">
                <img src="images/phones/' . $r["phone_id"]. '.jpeg"/>';
              echo '</div>
                <div id="phone-text">
                  <div id="phone-name"><a href="/item?id=' . $r["phone_id"]. '">'.$r["manufacture"].' '. $r["phone_name"]. '</a>';
              echo '</div>
                  <div id="phone-price">' . $r["price"]. ' €';
              echo '</div>
              <div id="phone-characteristics"> Screen Size: ' . $r["screen_size"]. '", RAM: ' . $r["ram"]. 
              'GB, Camera: ' . $r["camera"]. 'MP';
              echo '</div>';
              echo '<a id="del" href="delcart.php?remove='.$key.'"><strong> Delete </strong></a>';
              echo '</article>';
              $total = $total + $r["price"];
            }
          }
        }
        echo  '<div id="total"><strong>Total Price: </strong><strong>'.$total.' $</strong></div>';
      ?>
        

          
        </div>

      </div>

    

    <footer id="footer">
      Designed by the Geek Team, Leon Vitanos & Achmet Achmetsik.
    </footer>
  </body>
</html>