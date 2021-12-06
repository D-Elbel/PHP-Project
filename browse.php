
    <?php include 'header.php'?>



        <?php

        echo"<div class=\"resultsDiv\">";

            if(isset($_POST['productname'])){
                try { 
                $pdo = new PDO('mysql:host=localhost;dbname=grocerystore; charset=utf8', 'root', ''); 
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = 'SELECT count(productname) FROM products where productname LIKE :pname OR category LIKE :pname';
                $result = $pdo->prepare($sql);
                $result->bindValue(':pname',  '%'. $_POST['productname'] . '%'); 
                $result->execute();
                if($result->fetchColumn() > 0) 
                {

                    

                    $sql = "SELECT * FROM products where productname LIKE :pname OR category LIKE :pname";
                    $result = $pdo->prepare($sql);
                    $result->bindValue(':pname', '%'. $_POST['productname'] . '%'); 
                    $result->execute();
                
                while ($row = $result->fetch()) { 

                      echo "<div class=\"searchResultDiv\"><figure>";
                      
                      echo"<img class=\"searchResultIMG\" src=\"" . $row['imglocation'] . "\"><br>";
                      echo "<figcaption>" . '<strong>' . $row['category'] .  '</strong><br>'. $row['productname'].  '€' . $row['price']. "</figcaption>";
                      echo "</figure></div>";
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
            
            echo"</div>";      
                                   
        ?>

<?php include 'footer.php'?>