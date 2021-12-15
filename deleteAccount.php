
    <?php include 'header.php'

        
?>



<?php

  try { 
$pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', ''); 
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = 'SELECT * FROM orders where CustID = :custID';
$result = $pdo->prepare($sql);
$result->bindValue(':custID', $_SESSION['userID']); 
$result->execute();
if($result->fetchColumn() > 0) 
{

    echo'<h2>You may not delete your account as you have an order history with us.</h2>';


}
else {
    $sql = 'DELETE FROM customers where CustID = :custID';
    $result = $pdo->prepare($sql);
    $result->bindValue(':custID', $_SESSION['userID']); 
    $result->execute();
    echo"Your account has been deleted";
    unset($_SESSION['userID']);
   }
} 
catch (PDOException $e){ 
$output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
}


include 'footer.php';
?>



