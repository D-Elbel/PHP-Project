
    <?php include 'header.php'

        
    ?>

    

<?php
//session_start();

    $firstname = "";
    $lastname = "";
    $phone = "";
    $email = "";
    $eircode = "";
    $county = "";
    $town = ""; 
    $street = "";
    //echo $_SESSION['userID'];

    

  
      try { 
    $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', ''); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT * FROM customers where CustID = :custID';
    $result = $pdo->prepare($sql);
    $result->bindValue(':custID', $_SESSION['userID']); 
    $result->execute();
    if($result->fetchColumn() > 0) 
    {

        

        $sql = 'SELECT * FROM customers where CustID = :custID';
        $result = $pdo->prepare($sql);
        $result->bindValue(':custID', $_SESSION['userID']); 
        $result->execute();
    
    while ($row = $result->fetch()) { 
            
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $phone = $row['phone'];
        $email = $row['email'];
        $eircode = $row['eircode'];
        $county = $row['county'];
        $town = $row['town'];
        $street = $row['street'];

        //echo $firstname;
       }

    
    }
    else {
          print "No rows matched the query.";
       }
    } 
    catch (PDOException $e){ 
    $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
    }
  
  

    if(isset($_POST['submitupdate'])){
        
        $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', ''); 
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'UPDATE customers SET firstname = :cfirstname, lastname = :clastname, phone = :cphone, email = :cemail, eircode = :ceircode, county = :ccounty, town = :ctown, street = :cstreet WHERE CustID = :custID';
        $result = $pdo->prepare($sql);
        $result->bindValue(':custID', $_SESSION['userID']);
        $result->bindValue(':cfirstname', $_POST['cfirstname']);
        $result->bindValue(':clastname', $_POST['clastname']);
        $result->bindValue(':cphone', $_POST['cphone']);
        $result->bindValue(':cemail', $_POST['cemail']);
        $result->bindValue(':ceircode', $_POST['ceircode']);
        $result->bindValue(':ccounty', $_POST['ccounty']);
        $result->bindValue(':ctown', $_POST['ctown']);
        $result->bindValue(':cstreet', $_POST['cstreet']); 
        $result->execute();
              
        unset($_POST['submitupdate']);

        
        //echo $firstname;
       
    }

?>

<div class="signupDiv">
    <form action="account.php" method="post">     
        
        <table>
            <tr> <td>Firstname</td> <td><input type="text" name="cfirstname" value=<?php echo $firstname?>></td> </tr>
            <tr> <td>Lastname</td> <td><input type="text" name="clastname" value=<?php echo $lastname?>></td> </tr>
            <tr> <td>Phone</td> <td><input type="text" name="cphone" value=<?php echo $phone?>></td> </tr>
            <tr> <td>Email</td> <td><input type="text" name="cemail" value=<?php echo $email?>></td> </tr>
            <tr> <td>Eircode</td> <td><input type="text" name="ceircode" value=<?php echo $eircode?>></td> </tr>
            <tr> <td>County</td> <td><input type="text" name="ccounty" value=<?php echo $county?>></td> </tr>
            <tr> <td>Town</td> <td><input type="text" name="ctown" value=<?php echo $town?>></td> </tr>
            <tr> <td>Street</td> <td><input type="text" name="cstreet" value=<?php echo $street?>></td> </tr>
        </table>                                                 
                                       
        <input type="submit" name="submitupdate" value="Update Account Details" >
        </form>
    </div>

<?php
include 'footer.php';
?>



    