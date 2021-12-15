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
        $stmt->execute();
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

            $stmt->execute();
        } catch (PDOException $e) {
            $title = 'An error has occurred';
            $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE Products SET quantity = quantity - :quantity  WHERE ProductID = :prodID";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':prodID', (int)$_SESSION['basketIDs'][$i]);
            $stmt->bindValue(':quantity', (int)$_SESSION['basketQuants'][$i]);

            $stmt->execute();
        } catch (PDOException $e) {
            $title = 'An error has occurred';
            $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }


        try {
            $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM Products Where ProductID = :prodID";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':prodID', (int)$_SESSION['basketIDs'][$i]);

            $stmt->execute();

            while ($row = $stmt->fetch()) {
                $orderValue = $orderValue + ($row['price'] * $_SESSION['basketQuants'][$i]);
            }
        } catch (PDOException $e) {
            $title = 'An error has occurred';
            $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
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

for ($i = 0; $i <= count($_SESSION['basketIDs']) - 1; $i++) {

    $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM Products Where ProductID = :prodID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':prodID', (int)$_SESSION['basketIDs'][$i]);

    $stmt->execute();


    while ($row = $stmt->fetch()) {

        echo '<tr><th>' . $row['category'] . '</th>' . '<td>' . $row['productname'] . '<td>€' . $row['price'] . '</td><td>' . $_SESSION['basketQuants'][$i] . '</td></tr>';

        $orderValue = $orderValue + ((float)$row['price'] * (float)$_SESSION['basketQuants'][$i]);
    }
}

echo '<tr><td><form action="checkout.php" method="post">
<input type="submit" name="checkoutSubmit" value="Check Out">
</form></td><td>Your Order Total: €' . $orderValue . '</td></tr></table>';
echo '</div>';
?>


</div>
<?php include 'footer.php'; ?>