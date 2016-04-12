<?php
    include('includes/database.php');
    function getProductInfo(){
        $dbConnection = getDatabaseConnection('ooa');
        $sql = "SELECT description 
                FROM product
                WHERE productId = :productId";
        $namedParameters = array(":productId"=>$_GET['productId']);
        // Prepare
        $statement  = $dbConnection -> prepare($sql);
        // Exexute
        $statement -> execute($namedParameters);
        $product = $statement -> fetch(PDO::FETCH_ASSOC);
        return $product;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title> </title> 
    </head>
    <body>
        <?php
            $productInfo = getProductInfo();
            echo $productInfo['description'];
        ?>
    </body>
</html>