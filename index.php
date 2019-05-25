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
      session_start();
      #create a database connection instance
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

    
    <?php 
    #if any checkbox checked remain checked
      function test($v)
      {
        if(isset($_POST['brands']))
        {
          $checked = $_POST['brands'];
          $n=count($checked);
          for($i=0; $i<$n;$i++)
          {
            if($checked[$i] == $v)
              echo "checked='checked'";
          }
        } 
      }
    ?>
    <main id="main">
      <div id="left-side">
        <h2>Filters</h2>
        <p>Brands</p>
        <!-- filter checkboxes  -->
      <form action="index.php" method="post" id="form">
        <input type="checkbox" name="brands[]" value="Samsung" onchange="this.form.submit()" <?php test("Samsung"); ?> />Samsung <br />
        <input type="checkbox" name="brands[]" value="Apple" onchange="this.form.submit()" <?php test("Apple"); ?> />Apple <br />
        <input type="checkbox" name="brands[]" value="LG" onchange="this.form.submit()" <?php test("LG"); ?>/>LG <br />
        <input type="checkbox" name="brands[]" value="Xiaomi" onchange="this.form.submit()" <?php test("Xiaomi"); ?> />Xiaomi <br />
        <input type="checkbox" name="brands[]" value="Huawei" onchange="this.form.submit()" <?php test("Huawei"); ?>/>Huawei <br />
        <input type="checkbox" name="brands[]" value="Sony" onchange="this.form.submit()" <?php test("Sony"); ?>/>Sony <br />
        <input type="checkbox" name="brands[]" value="Google" onchange="this.form.submit()" <?php test("Google"); ?>/>Google <br />
        <input type="checkbox" name="brands[]" value="Nokia" onchange="this.form.submit()" <?php test("Nokia"); ?>/>Nokia<br />
        <input type="checkbox" name="brands[]" value="OnePlus" onchange="this.form.submit()" <?php test("OnePlus"); ?>/>OnePlus <br />
      </form>
        
      </div>
      <div id="right-side">
        <h2>Phones</h2>
        
        <?php    
        #determine number of results stored in database and filter them by checkboxes
        $sql = "";
        if(isset($_POST['brands']))
        {
          $brand = "WHERE phones.manufacture='";
          $checked = $_POST['brands'];
          $brandsql = "SELECT * FROM phones WHERE phones.manufacture='";
          $n=count($checked);
          for($i=0; $i<$n;$i++)
          {
              if($i == 0)
              {
                $brandsql .=$checked{$i}."'";
                $brand .=$checked{$i}."'";
              }
              else {
                $brandsql .=" OR phones.manufacture='".$checked[$i]."'" ;
                $brand .=" OR phones.manufacture='".$checked[$i]."'" ;
              }
          }
        }
        
        if(isset($_POST['brands']))
        {
          $sql = $brandsql;
        }
        else {
          $sql = "SELECT * FROM phones ";
        }

        $result = $mysqli->query($sql);

        

        #determine number of total pages available
        $results_per_page=8;
        $number_of_results = mysqli_num_rows($result);
        $number_of_pages = ceil($number_of_results/$results_per_page);

        #determine which page number visitor is currently on
        if(!isset($_GET['page']))
          $page = 1;
        else
          $page = $_GET['page'];

        #determine the sql LIMIT starting number
        $this_page_first_result = ($page-1)*$results_per_page;

        #retrieve selected results
        if(isset($_POST['brands']))
        {
          $sql = "SELECT phone_id, manufacture, phone_name, screen_size, ram, camera, price FROM phones $brand
          LIMIT " .$this_page_first_result. ',' .$results_per_page. '';
          $result = $mysqli->query($sql);
        }
        else
        {
          $sql = "SELECT phone_id, manufacture, phone_name, screen_size, ram, camera, price FROM phones
          LIMIT " .$this_page_first_result. ',' .$results_per_page. '';
          $result = $mysqli->query($sql);
        }


        #display the links to the pages
        echo 'Pages:';
        for($i=1; $i<=$number_of_pages; $i++){
          if($i==$page)
          {
            echo ' <a href="index.php?page=' .$i. '"><strong>' .$i. '</strong></a>';
          }
          else
            {
              echo' <a href="index.php?page=' .$i. '">' .$i. '</a>';
            }
        }

        // output data of each row
        if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo '<article class="phone">
              <div id="phone-img">
                <img src="images/phones/' . $row["phone_id"]. '.jpeg"/>';
              echo '</div>
                <div id="phone-text">
                  <div id="phone-name"><a href="/item?id=' . $row["phone_id"]. '">' . $row["manufacture"]. ' ' . $row["phone_name"]. '</a>';
              echo '</div>
                  <div id="phone-characteristics"> Screen Size: ' . $row["screen_size"]. '", RAM: ' . $row["ram"]. 
                  'GB, Camera: ' . $row["camera"]. 'MP';
              echo '</div>
              </div>
              <div id="phone-price">' . $row["price"]. ' €';
              echo '</div>
            </article>';
          }
        } else {
          echo "0 results";
        }

        #display the links to the pages
        echo '<br>Pages:';
        for($i=1; $i<=$number_of_pages; $i++){
          if($i==$page)
            echo ' <a href="index.php?page=' .$i. '"><strong>' .$i. '</strong></a>';
          else
            echo ' <a href="index.php?page=' .$i. '">' .$i. '</a>';
        }

      $mysqli->close();
      ?> 
      </div>
    </main>

    <footer id="footer">
      Designed by the Geek Team, Leon Vitanos & Achmet Achmetsik.
    </footer>
  </body>
</html>