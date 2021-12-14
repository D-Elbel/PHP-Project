
    <?php include 'header.php';

   if($_SESSION["basket"] == false){
    $_SESSION["basketIDs"] = array();
    $_SESSION["basketQuants"] = array();
   }

   if (isset($_POST['purchase'])) {                   
    try { 
        $cfirstname = $_POST['cfirstname'];
        $clastname = $_POST['clastname'];
        $cphone = $_POST['cphone'];
        $cemail = $_POST['cemail'];
        $ceircode = $_POST['ceircode'];
        $ccounty = $_POST['ccounty'];
        $ctown = $_POST['ctown'];
        $cstreet = $_POST['cstreet'];

        if ($cfirstname == ''  or $clastname == '')
        {
            echo("You did not complete the insert form correctly <br> ");
        }
        else{
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
        } 
        catch (PDOException $e) { 
            $title = 'An error has occurred';
            $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        } 
        } 


        if (isset($_POST['signIn'])) {                   
            try { 
                $lcemail = $_POST['lcemail'];                
                $lcphone = $_POST['lcphone'];
        
                if ($lcemail == ''  or $lcphone == '')
                {
                    echo("Please enter your login details");
                }
                else{
                    $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', ''); 
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   
                    $sql = "SELECT * FROM customers where phone = :cphone AND email = :cemail";
                    
                    $stmt = $pdo->prepare($sql);
                    
                    
                    $stmt->bindValue(':cphone', $lcphone);
                    $stmt->bindValue(':cemail', $lcemail);                   
                    $stmt->execute();

                    if($stmt->fetchColumn() > 0){

                        $sql = "SELECT * FROM customers where phone = :cphone AND email = :cemail";
                        $st = $pdo->prepare($sql);
                        $stmt->bindValue(':cphone', $lcphone);
                        $stmt->bindValue(':cemail', $lcemail);                   
                        $stmt->execute();

                        while ($row = $stmt->fetch()) { 

                            echo"You have successfully logged in";
                        $_SESSION['loggedIn'] = true;
                        $_SESSION['userID'] = $row['CustID'];
                        
                           
                         }
                        // echo"error";
                        
                        
                    }
                    else{
                        echo"error";
                    }
                    
                    }
                } 
                catch (PDOException $e) { 
                    $title = 'An error has occurred';
                    $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
                } 
                }

    
                               
    ?>

<?php include 'footer.php'?>