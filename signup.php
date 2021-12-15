<?php include 'header.php'

?>

<div class="loginPage">

    <div class="signupDiv">
        <form action="signup.php" method="post">

            <table>
                <tr>
                    <td>Firstname</td>
                    <td><input type="text" name="cfirstname"></td>
                </tr>
                <tr>
                    <td>Lastname</td>
                    <td><input type="text" name="clastname"></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><input type="text" name="cphone"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="cemail"></td>
                </tr>
                <tr>
                    <td>Eircode</td>
                    <td><input type="text" name="ceircode"></td>
                </tr>
                <tr>
                    <td>County</td>
                    <td><input type="text" name="ccounty"></td>
                </tr>
                <tr>
                    <td>Town</td>
                    <td><input type="text" name="ctown"></td>
                </tr>
                <tr>
                    <td>Street</td>
                    <td><input type="text" name="cstreet"></td>
                </tr>
            </table>

            <input type="submit" name="submitdetails" value="SUBMIT">
        </form>
    </div>

    <div class="loginDiv">
        <form action="signup.php" method="post">
            <table>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="lcemail"></td>
                </tr>
                <tr>
                    <td>Phone</td>
                    <td><input type="text" name="lcphone"></td>
                </tr>
            </table>
            <input type="submit" name="signIn" value="SUBMIT">
        </form>
    </div>

</div>

<?php




if (isset($_POST['submitdetails'])) {
    try {
        $cfirstname = $_POST['cfirstname'];
        $clastname = $_POST['clastname'];
        $cphone = $_POST['cphone'];
        $cemail = $_POST['cemail'];
        $ceircode = $_POST['ceircode'];
        $ccounty = $_POST['ccounty'];
        $ctown = $_POST['ctown'];
        $cstreet = $_POST['cstreet'];

        if ($cfirstname == ''  or $clastname == '') {
            echo ("You did not complete the insert form correctly <br> ");
        } else {
            $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO customers (firstname, lastname, phone, email, eircode, county, town, street) VALUES(:cfirstname, :clastname, :cphone, :cemail, :ceircode,:ccounty, :ctown, :cstreet)";

            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':cfirstname', $cfirstname);
            $stmt->bindValue(':clastname', $clastname);
            $stmt->bindValue(':cphone', $cphone);
            $stmt->bindValue(':cemail', $cemail);
            $stmt->bindValue(':ceircode', $ceircode);
            $stmt->bindValue(':ccounty', $ccounty);
            $stmt->bindValue(':ctown', $ctown);
            $stmt->bindValue(':cstreet', $cstreet);

            $stmt->execute();
            echo  "Sign Up Successful!";
        }
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
}


if (isset($_POST['signIn'])) {
    try {
        $lcemail = $_POST['lcemail'];
        $lcphone = $_POST['lcphone'];

        if ($lcemail == ''  or $lcphone == '') {
            echo ("Please enter your login details");
        } else {
            $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM customers where phone = :cphone AND email = :cemail";

            $stmt = $pdo->prepare($sql);


            $stmt->bindValue(':cphone', $lcphone);
            $stmt->bindValue(':cemail', $lcemail);
            $stmt->execute();

            if ($stmt->fetchColumn() > 0) {

                $sql = "SELECT * FROM customers where phone = :cphone AND email = :cemail";
                $st = $pdo->prepare($sql);
                $stmt->bindValue(':cphone', $lcphone);
                $stmt->bindValue(':cemail', $lcemail);
                $stmt->execute();

                while ($row = $stmt->fetch()) {

                    echo "You have successfully logged in";
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['userID'] = $row['CustID'];
                }
                // echo"error";


            } else {
                echo "error";
            }
        }
    } catch (PDOException $e) {
        $title = 'An error has occurred';
        $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
    }
}



include 'footer.php';
?>