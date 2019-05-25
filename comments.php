<?php

function addComment($commsqli){
    if(isset($_POST['commentSubmit'])){
        $phone_id = $_POST['phone_id'];
        $username = $_POST['username'];
        $date = $_POST['date'];
        $message = $_POST['message'];
        $rating = $_POST['rating'];

        $sql = "INSERT INTO comments (phone_id,username,date,message,rating) VALUES ('$phone_id', '$username', '$date', '$message', '$rating')";
        $result = $commsqli->query($sql);
    }
}

#Find the number of stars and show the correct picture
function ShowStarImage($number){
    if($number == 1){
        echo '<img src=images/stars/star-1.png> <br>';
    }
    elseif ($number == 2) {
        echo '<img src=images/stars/star-2.png> <br>';
    }
    elseif ($number == 3) {
        echo '<img src=images/stars/star-3.png> <br>';
    }
    elseif ($number == 4) {
        echo '<img src=images/stars/star-4.png> <br>';
    }
    elseif ($number == 5) {
        echo '<img src=images/stars/star-5.png> <br>';
    }
}

function getComments($commsqli){
    $sql = 'SELECT * FROM comments WHERE phone_id='.$_GET["id"]."";
    $result = $commsqli->query($sql);
    while ($row = $result->fetch_assoc()){
        echo '<div class="comment-box">';
        echo $row['username'].'<br>';
        echo $row['date'].'<br>';
        ShowStarImage($row['rating']);
        echo nl2br($row['message']);
        echo '</div>';
    }
}