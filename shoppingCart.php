<?php
session_start();
include('includes/database.php');
$dbConn = getDatabaseConnection('ooa');

//for this function, it performs a query for every item. and gathers and displays the info
//with each loop. Probably not the best way to do it, but it works.
function getCartItems(){
    
     global $dbConn;
     $total=0;
     
     $productId=$_POST['checkedItems'];
     
    echo "<br>";
    
 
    for ($i=0; $i<sizeof($productId); $i++){
        $sql = "SELECT * FROM product WHERE productId= ".$productId[$i];
    
        $statement =  $dbConn->prepare($sql);
        $statement->execute();
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($records as $record){
            
            echo "<tr>";
            echo "<td>".$record['productName']."</td>";
            echo "<td>".$record['price']."</td>";
            $total += $record['price'];
            echo "</tr>";
            
        }
    }
    
    echo "Total Price: $". $total;
    
}



?>

<!DOCTYPE html>
<html>
    <head>
        <title>OOA's Shopping Cart</title></title>
        <h1>Your Shopping Cart</h1>
        
    </head>
    <body>
        
        <table border="1">
            <th>Product Name</th>
            <th>Price</th>
        <?php
            getCartItems();//using this function to display the cart items
            
        ?>
        </table>

    </body>
</html>