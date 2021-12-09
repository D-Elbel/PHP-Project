
    <?php include 'header.php'

        
    ?>

    <div class="loginPage">

    <div class="signupDiv">
    <form action="signup.php" method="post">                
             Firstname: <input type="text" name="cfirstname"><br>
             Lastname: <input type="text" name="clastname"><br>
             Phone: <input type="text" name="cphone"><br>
             Email: <input type="text" name="cemail" ><br>
             Eircode: <input type="text" name="ceircode" ><br>
             County: <input type="text" name="ccounty" ><br>
             Town: <input type="text" name="ctown" ><br>
             Street: <input type="text" name="cstreet" ><br>
             <input type="submit" name="submitdetails" value="SUBMIT" >
        </form>
    </div>
    
    <div class="loginDiv">
    <form action="signup.php" method="post">                
             Email: <input type="text" name="lcemail" ><br>
             Phone: <input type="text" name="lcphone"><br>
             <input type="submit" name="signIn" value="SUBMIT" >
        </form>
    </div>  

    </div>

    <?php
session_start();
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
          
    

    include 'footer.php';
?>

        