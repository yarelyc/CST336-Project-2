<?php
session_start();
include('includes/database.php');
$dbConn = getDatabaseConnection('ooa');

//for this function, it performs a query for every item. and gathers and displays the info
//with each loop. Probably not the best way to do it, but it works.
function getItems(){
    
     global $dbConn;
     
     $sql = "SELECT productName, productId, price FROM product";
     $statement =  $dbConn->prepare($sql);
     $statement->execute();
     $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    
     return $records;
}
    

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Project 2</title>
        <link href ="style.css" rel="stylesheet"/>
        <header>
            <img src = "includes/shopping_cart.jpg">
        </header>
        
        
    </head>
    <body>
        <br>
        <?php
        if(!isset($_POST['checkedItems'])){
                echo "<h1 style='text-align:center;color:#503A3A'> THERE IS NOTHING IN YOUR CART </h1><br/>";
                echo "<h1 style='text-align:center'><a href='index.php'> Ott's Oddities Main Page</a></h1>";
            }
        ?>
        <div id="welcome">
        Thank You For Shopping With Us!
       </div>
        <br>
        <div id="cart"> 
        <div style="float:left" padding="10px" id="table">
        <table border="1" align="center" padding ="10px">
            <th>Product Name</th>
            <th>Price</th>
        <?php
            $productId = $_POST['checkedItems'];
            $total = 0;
            $records = getItems();
            for ($i=0; $i<sizeof($productId); $i++){
                 foreach($records as $record){
                    // echo "test: " . $record['productId'] . " == " . $productId[$i] . "<br>";
                    if($record['productId'] == $productId[$i]){                            echo "<tr>";
                        echo "<td> <a href='getProductInfo.php?productId=".$record['productId']."' target = 'productInfoiFrame'>" . $record['productName'] . "</a></td>";
                        echo "<td> $".$record['price']."</td>";
                        $total += $record['price'];
                        echo "</tr>";
                     }  
                    }
            }
            echo "Total Price: $" . $total;
        ?>
        </table>
        </div>
        </div>
        
        <div style="float:left" id="table">
          <iframe name = "productInfoiFrame" width = "200" height "300"
         src="getProductInfo.php" frameborder="1"></iframe> 
          </div>

    </body>
</html>