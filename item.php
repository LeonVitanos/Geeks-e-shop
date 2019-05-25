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
  date_default_timezone_set('Europe/Athens');
  include 'comments.php';
  session_start();
  #load comments database
  $commsqli = new mysqli("localhost", "root", "", "comments_db");
  if ($commsqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $commsqli->connect_errno . ") " . $commsqli->connect_error;
  }

  #create a database connection instance
  $mysqli = new mysqli("localhost", "root", "", "phones_db");
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
  }

  #search for item with id
  $sql = 'SELECT * FROM phones WHERE phone_id='.$_GET["id"]."";
  $result = $mysqli->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
  } else {
    echo "Item doesn't exist";
  }
  ?>
    
    <header>
        <div id="header-content">
          <div id="logo">
            <a href="index.php"><img src="images/geek.png" alt="logo"></a>
          </div>
          <div id="cart">
              <a href="cart.php"><img src="images/shopping-cart.png" alt="image of a shopping-cart"></a>
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

    <main id="main">
      <div id="left-side">
        <?php echo '<img src="images/phones/' . $row["phone_id"]. '.jpeg">';?>
      </div>
        
      </div>
      <div id="right-side">
      <?php echo '<h2>' . $row["manufacture"]. ' ' . $row["phone_name"].'</h2>';
       echo '<p>Screen Size: ' . $row["screen_size"].'"</p>';
       echo '<p>RAM: ' . $row["ram"].'GB</p>';
       echo '<p>Camera: ' . $row["camera"].'MB</p>';
       echo '<p>OS: ' . $row["os"].'</p>';
       echo '<p>Year: ' . $row["year"].'</p>';
       echo '<p>Price: ' . $row["price"].' €</p>';
       
       $mysqli->close();  
      

      echo '<form action="/addtocart.php">
        <input type = "hidden" name = "id" value = "' .$row["phone_id"].'"/>';
      echo '<button type="submit" name="submit">Add to Cart!</button>
      </form> <br><br>
      <form method="POST" action="'.addComment($commsqli).'">
      <input type="hidden" name="date" value="' .date('Y-m-d H:i:s').'">
      <br>
      <span class="rating">
          <input id="rating5" type="radio" name="rating" value="5">
          <label for="rating5">5</label>
          <input id="rating4" type="radio" name="rating" value="4">
          <label for="rating4">4</label>
          <input id="rating3" type="radio" name="rating" value="3">
          <label for="rating3">3</label>
          <input id="rating2" type="radio" name="rating" value="2" >
          <label for="rating2">2</label>
          <input id="rating1" type="radio" name="rating" value="1"checked>
          <label for="rating1">1</label>
      </span>
  
      <p>Name: <input type="text" name="username" value="Anonymous"></p>
      <textarea name="message"></textarea><br>
      <input type="hidden" name="phone_id" value=' .$row["phone_id"]. '>
      <button type="submit" name="commentSubmit">Comment</button>
      </form><br>';

      getComments($commsqli);
      ?> 


    </form>
      </div>
    </main>

    <footer id="footer">
      Designed by the Geek Team, Leon Vitanos & Achmet Achmetsik.
    </footer>
  </body>
</html>
