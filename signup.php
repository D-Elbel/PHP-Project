<html>
    <head>
        <title>Sign Up</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>


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

            echo  "Test";
            $stmt->execute();
            echo  "Added try doing another";
            }
        } 
        catch (PDOException $e) { 
            $title = 'An error has occurred';
            $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
        } 
        } 
          
    

    
?>

        
    </body>
</html>