<?php
session_start();
include('includes/database.php');
$dbConn = getDatabaseConnection('ooa');


function getCartItems(){
    
    global $dbConn;
    
    //Start query with joining all tables
    $sql = "SELECT productId, productName, price, era, region, productType
    FROM product NATURAL JOIN productType NATURAL JOIN contributor";
    
    foreach($_POST['checkedItems'] as $productId){
        echo "Product Id: ".$productId."<br>";//Product id
        $sql .= " WHERE productId=".$_POST['checkedItems'];
        
        $statement = $dbConn->prepare($sql);
        $statement->execute($namedParameters);
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        
    }

}


?>

<!DOCTYPE html>
<html>
    <head>
        <title>OOA's Shopping Cart</title></title>
        <h1>Your Shopping Cart</h1>
        
        <?php
            getCartItems();
        ?>
        
        
    </head>
    <body>

    </body>
</html>