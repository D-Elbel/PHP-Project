<?php include 'header.php';

$orderValue = 0;
$orderID;

if (isset($_POST['checkoutSubmit'])) {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO orders (CustID, value, dateOfPurchase) VALUES(:custID, 0, SYSDATE())";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':custID', $_SESSION['userID']);
        //$stmt->bindValue(':date', date('m/d/Y'));

        $stmt->execute();

        echo "inserted";
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
            echo "got orderid";

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

            echo "ITEM ID" . (int)$_SESSION['basketIDs'][$i];
            echo "<br>QUANTITY" . (int)$_SESSION['basketQuants'][$i];
            echo "<br>ORDER ID" . (int)$orderID;


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
        echo "ORder value is " . $orderValue;
        echo "ORder ID is " . $orderID . "<br><br>";


        $stmt->execute();
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }








    $_SESSION['basketQuants'] = array();
    $_SESSION['basketIDs'] = array();
    unset($_SESSION['orderID']);
}

echo '<table>';

//print_r($_SESSION['basketIDs']);
//print_r($_SESSION['basketQuants']);

for ($i = 0; $i <= count($_SESSION['basketIDs']) - 1; $i++) {

    echo $i;

    $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT * FROM Products Where ProductID = :prodID";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':prodID', (int)$_SESSION['basketIDs'][$i]);

    $stmt->execute();


    while ($row = $stmt->fetch()) {


        //echo [(string)$_SESSION['basketIDs'][$i]];
        echo '<tr><td>' . $row['category'] . '</td>' . '<td>' . $row['productname'] . '<td>€' . $row['price'] . '</td><td>' . $_SESSION['basketQuants'][$i] . '</td></tr>';

        $orderValue = $orderValue + ($row['price'] * $_SESSION['basketQuants'][$i]);
    }
}

echo '</table>';

echo "<h2>Thank you for your purchase!</h2>";


echo $orderValue;

?>

<form action="checkout.php" method="post">
    <input type="submit" name="checkoutSubmit" value="SUBMIT">
</form>

<?php include 'footer.php'; ?>