<?php include 'header.php';

$orderValue = 0;
$orderID;

echo '<div class="loginPage">';

if (isset($_POST['checkoutSubmit'])) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO orders (CustID, value, dateOfPurchase) VALUES(:custID, 0, SYSDATE())";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':custID', $_SESSION['userID']);
        //$stmt->bindValue(':date', date('m/d/Y'));

        $stmt->execute();

        //echo "inserted";
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT MAX(OrderID) FROM Orders";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            //echo "got orderid";

            $orderID = $row[0];
        }
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }

    for ($i = 0; $i <= count($_SESSION['basketIDs']) - 1; $i++) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO orderitems (OrderID, ProductID, Quantity) VALUES(:orderID, :prodID, :quantity)";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':prodID', (int)$_SESSION['basketIDs'][$i]);
            $stmt->bindValue(':quantity', (int)$_SESSION['basketQuants'][$i]);
            $stmt->bindValue(':orderID', $orderID);

            //echo "ITEM ID" . (int)$_SESSION['basketIDs'][$i];
            //echo "<br>QUANTITY" . (int)$_SESSION['basketQuants'][$i];
            //echo "<br>ORDER ID" . (int)$orderID;


            $stmt->execute();
        } catch (PDOException $e) {
            $title = 'An error has occurred';
            $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }
    }

    for ($i = 0; $i <= count($_SESSION['basketIDs']) - 1; $i++) {

        echo $i;

        $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM Products Where ProductID = :prodID";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':prodID', (int)$_SESSION['basketIDs'][$i]);

        $stmt->execute();


        while ($row = $stmt->fetch()) {
            $orderValue = $orderValue + ($row['price'] * $_SESSION['basketQuants'][$i]);
        }
    }

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "UPDATE Orders SET value = :value WHERE OrderID = :orderID";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':value', (float)$orderValue);
        $stmt->bindValue(':orderID', (int)$orderID);
        echo "<div><h2>Thank you for your purchase!</h2>";
        echo "<p>Click <a href='browse.php'>here</a> to continue shopping</p></div>";


        $stmt->execute();
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }








    $_SESSION['basketQuants'] = array();
    $_SESSION['basketIDs'] = array();
    unset($_SESSION['orderID']);
}

echo '<div class="cartPane"><table class="checkoutTable"><tr><th>Category</th><th>Name</th><th>Price</th><th>Quantity</th></tr>';

//print_r($_SESSION['basketIDs']);
//print_r($_SESSION['basketQuants']);

for ($i = 0; $i <= count($_SESSION['basketIDs']) - 1; $i++) {

    $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM Products Where ProductID = :prodID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':prodID', (int)$_SESSION['basketIDs'][$i]);

    $stmt->execute();


    while ($row = $stmt->fetch()) {


        //echo [(string)$_SESSION['basketIDs'][$i]];
        echo '<tr><th>' . $row['category'] . '</th>' . '<td>' . $row['productname'] . '<td>€' . $row['price'] . '</td><td>' . $_SESSION['basketQuants'][$i] . '</td></tr>';

        $orderValue = $orderValue + ($row['price'] * $_SESSION['basketQuants'][$i]);
    }
}

echo '<tr><td><form action="checkout.php" method="post">
<input type="submit" name="checkoutSubmit" value="Check Out">
</form></td><td>Your Order Total: €' . $orderValue . '</td></tr></table>';
echo '</div>';

//echo "<h2>Thank you for your purchase!</h2>";

?>


</div>
<?php include 'footer.php'; ?>