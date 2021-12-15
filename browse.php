
    <?php include 'header.php';



    if ($_SESSION["basket"] == false) {
        $_SESSION["basketIDs"] = array();
        $_SESSION["basketQuants"] = array();
    }


    echo "<div class=\"resultsDiv\">";
    if (!isset($_POST['productname'])) {
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM products where productname LIKE :pname OR category LIKE :pname";
            $result = $pdo->prepare($sql);
            $result->bindValue(':pname', '%%');
            $result->execute();

            while ($row = $result->fetch()) {

                //Product images are sourced from Tesco.ie
                echo "<div class=\"searchResultDiv\"><figure>";
                echo '<input class="hiddenInput" type="text" value="' . $row['productname'] . '">';
                echo "<img class=\"searchResultIMG\" src=\"" . $row['imglocation'] . "\"><br>";
                echo "<figcaption>" . '<strong>' . $row['category'] .  '</strong><br>' . $row['productname'] .  '€' . $row['price'] . "</figcaption>";
                echo '<form action="browse.php" method="POST"><button name="addProd" type="submit" value="' . $row['ProductID'] . '">Add to Cart</button> <input type="number" id="quantity" value="" name="quantity" min="1" max="10"></form>';
                echo "</figure></div>";
            }
        } catch (PDOException $e) {
            $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }
    }

    if (isset($_POST['productname'])) {



        try {
            $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = 'SELECT count(productname) FROM products where productname LIKE :pname OR category LIKE :pname';
            $result = $pdo->prepare($sql);
            $result->bindValue(':pname',  '%' . $_POST['productname'] . '%');
            $result->execute();
            if ($result->fetchColumn() > 0) {



                $sql = "SELECT * FROM products where productname LIKE :pname OR category LIKE :pname";
                $result = $pdo->prepare($sql);
                $result->bindValue(':pname', '%' . $_POST['productname'] . '%');
                $result->execute();

                while ($row = $result->fetch()) {

                    //Product images are sourced from Tesco.ie
                    echo "<div class=\"searchResultDiv\"><figure>";
                    echo '<input class="hiddenInput" type="text" value="' . $row['productname'] . '">';
                    echo "<img class=\"searchResultIMG\" src=\"" . $row['imglocation'] . "\"><br>";
                    echo "<figcaption>" . '<strong>' . $row['category'] .  '</strong><br>' . $row['productname'] .  '€' . $row['price'] . "</figcaption>";
                    echo '<form action="browse.php" method="POST"><button name="addProd" type="submit" value="' . $row['ProductID'] . '">Add to Cart</button> <input type="number" id="quantity" value="" name="quantity" min="1" max="10"></form>';
                    echo "</figure></div>";
                }
            } else {
                print "No rows matched the query.";
            }
        } catch (PDOException $e) {
            $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        }
    }

    if (isset($_POST['addProd'])) {

        $_SESSION["basket"] = true;

        array_push($_SESSION["basketIDs"], $_POST['addProd']);
        array_push($_SESSION["basketQuants"], $_POST['quantity']);

        print_r($_SESSION['basketIDs']);
        print_r($_SESSION['basketQuants']);


        echo '<div class="cartSuccess"><h2>Items Successfully Added!</h2></div>';
    }



    echo "</div>";

    ?>

<?php include 'footer.php' ?>