<html>
    <head>
        <title>Browse Products</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>


    <form method="POST" action="browse.php">
            <input type="text" name="productname">
            <input type="submit" value="search">
    </form>

        <?php

            if(isset($_POST['productname'])){
                try { 
                $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', ''); 
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = 'SELECT count(productname) FROM products where productname LIKE :pname';
                $result = $pdo->prepare($sql);
                $result->bindValue(':pname',  '%'. $_POST['productname'] . '%'); 
                $result->execute();
                if($result->fetchColumn() > 0) 
                {

                    

                    $sql = "SELECT * FROM products where productname LIKE :pname";
                    $result = $pdo->prepare($sql);
                    $result->bindValue(':pname', '%'. $_POST['productname'] . '%'); 
                    $result->execute();
                
                while ($row = $result->fetch()) { 
                      echo $row['productname'] .  ' €'. $row['price'].  ' ' . $row['category']. '<br>';
                   }

                   
                }
                else {
                      print "No rows matched the query.";
                   }
                } 
                catch (PDOException $e){ 
                $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
                }

            }
            
                
        
        
            
                
        ?>

        
    </body>
</html>