<?php
    session_start();
    include('includes/database.php');
    $dbConn = getDatabaseConnection('ooa');
    
function getContributors(){
    //this method gets all  contributos from the database
     global $dbConn;
     
     $sql = "SELECT contributorName FROM contributor";
       //prepare, execute, fetch
    $statement =  $dbConn->prepare($sql);
    $statement->execute();
    $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    
   
    return $records;  
    
}
  

function getRegion(){
    //this method gets all regions from the database
     global $dbConn;
     
     $sql = "SELECT region FROM product GROUP BY region";
       //prepare, execute, fetch
    $statement =  $dbConn->prepare($sql);
    $statement->execute();
    $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    
   print_r($records);
    return $records;  
    
}
    
function getProducts() {
    global $dbConn;
    
    //Start query with joining all tables
    $sql = "SELECT productId, productName, price, era, region, productType
    FROM product NATURAL JOIN productType NATURAL JOIN contributor";

    if (isset($_GET['inputForm'])) { //checks if the form is submitted

        //this  filters the data by era
        $era = "none";
        if (isset($_GET['filertByEra']) && $_GET['filertByEra'] != "all" ) {
            
            if($_GET['filertByEra'] == "1750"){
                $era = " WHERE era > 1750 and era <= 1850";
            }else if($_GET['filertByEra'] == "1850"){
                $era = " WHERE era > 1850 and era <= 1950";
            }else if($_GET['filertByEra'] == "1950"){
                $era = " WHERE era > 1950";
            }
        }
        
          //this  filters the data by type
        if (isset($_GET['filertByType']) && $_GET['filertByType'] != "all") {
            if($era = "none"){
                $era = " WHERE";//if there is no filter by era we add the WHERE clause
            }else{
                $era .= " AND"; //If there is other filters included we add the AND
            }
            $era .= " productType = :productType"; //Using Named Parameters to prevent SQL Injection
            $namedParameters[":productType"] =  $_GET['filertByType']; //sending parameters
        }
        
          //this  filters the data by type
        if (isset($_GET['contributorName']) && $_GET['contributorName'] != "all") {
      
            if($era != "none"){
                 $era .= " AND"; //If there is other filters included 
            }else{
                 $era = " WHERE";//if there is no filter added  we add the WHERE clause
            }
        
            $era .= " contributorName = :contributorName"; //Using Named Parameters to prevent SQL Injection
            $namedParameters[":contributorName"] =  $_GET['contributorName']; //sending parameters
        }

           //this  filters the data by type
        if (isset($_GET['region']) && $_GET['region'] != "all") {
      
            if($era != "none"){
                 $era .= " AND"; //If there is other filters included 
            }else{
                 $era = " WHERE";//if there is no filter added  we add the WHERE clause
            }
        
            $era .= " region = :region"; //Using Named Parameters to prevent SQL Injection
            $namedParameters[":region"] =  $_GET['region']; //sending param
        }
        if($era != "none"){
            $sql .= $era;
        }
         
        //order by price or name in ascending or descending form
        if (isset($_GET['orderBy'])) {
      
            if($_GET['orderBy'] == "priceLow"){
                $sql .= " ORDER BY price ASC";
            }else if($_GET['orderBy'] == "priceHigh"){
                 $sql .= " ORDER BY price DESC";
            }else if($_GET['orderBy'] == "nameAsc"){
                $sql .= " ORDER BY productName ASC";
            }else{
                $sql .= " ORDER BY productName DESC";
            }
        }
        
        
         //prepare, execute, fetch
    $statement = $dbConn->prepare($sql);
    $statement->execute($namedParameters);
    $records = $statement->fetchAll(PDO::FETCH_ASSOC);

    return $records; 

    }  
    
    return (array());
    
    
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Project 2</title>
        <link href ="style.css" rel="stylesheet"/>
    </head>
    <body>
        
        <header>
            <img src = "includes/home_banner.jpg">
        </header>
        <div id="welcome">
            Welcome To Our Store!<br>    
        </div>
        <main>
            <div id="sort">
            <form>
                <strong>Sort by: </strong>
                  <select name="orderBy">
                      <option value="priceLow"> Price: Low to High </option>
                      <option value="priceHigh"> Price: High to Low </option>
                      <option value="nameAsc"> Name: Ascending Order </option>
                      <option value="nameDes"> Name: Descending Order </option>
                  </select>
                  <br/>
                  
                  <h3>Filter By </h3>
                 
                  <strong>Era:   </strong>
                   <input type="radio" name="filertByEra" value="all" id = "one" checked><label for="one">All</label>
                  <input type="radio" name="filertByEra" value="1750" id = "two"><label for="two">1750's - 1850's</label> 
                  <input type="radio" name="filertByEra" value="1850" id = "three"><label for="three">1850's - 1950's</label> 
                  <input type="radio" name="filertByEra" value="1950" id = "four"><label for="four">1950's - 2000's</label>
                 
            
                 <br/>
                  <strong>Type   </strong>
                   <input type="radio" name="filertByType" value="all" id = "oneType" checked><label for="oneType"> All</label>
                  <input type="radio" name="filertByType" value="Cloth" id = "twoType"><label for="twoType"> Cloth</label> 
                  <input type="radio" name="filertByType" value="Furniture" id = "threeType"><label for="threeType"> Furniture</label> 
                     <input type="radio" name="filertByType" value="Ceramics" id = "fourType"><label for="fourType"> Ceramics</label> 
                  <input type="radio" name="filertByType" value="Jewelry" id = "fiveType"><label for="fiveType">Jewelry</label>
                  <input type="radio" name="filertByType" value="Metal" id = "sixType"><label for="sixType">Metal</label>
                  
                  <br/>
           
                  
                  <!--This first get the contributors names from the database to then display them-->
                  <strong>Contributor Name: </strong>
                   <select name="contributorName"> <!--the data will later be retrive using the contributorName-->
                      <option value="all"> All </option>
                          <?php
                             $contributorNames = getContributors(); //retrives the names 
                             foreach ($contributorNames as $candidate){
                           
                               echo "<option value='".$candidate['contributorName']."'>" . $candidate['contributorName'] . " </option>";  
                   
                            }
                        ?>
                  </select>
                  
                     <strong>Region: </strong>
                    <select name="region">
                      <option value="all"> All </option>
                          <?php
                             $regionsAvailable = getRegion();
                             foreach ($regionsAvailable as $region){
                               echo "<option value='".$region['region']."'>" . $region['region'] . " </option>";  
                   
                            }
                        ?>
                  </select>
                 
                 <br/>
                  <br/>
                  <input type="submit" value="Search Products" name="inputForm">
            </form>
         </div> <!-- Ends "sort" div-->
            <!--Table to display output-->
        
        <br>

        <div id="results">
        
         <div style="float:left" padding="10px" id="table">
             <table border = "1">
             <form method="post" action="shoppingCart.php">
        <!--New table row-->
                 <tr>
                     <th> Name </th>
                     <th> Price</th>
                     <th> Era</th>
                     <th> Region </th>
                     <th> Product Type</th>
                </tr>
             <?php
                if(isset($_GET['inputForm'])){
                 
                    
                    $records = getProducts();
                    foreach ($records as $record) {
                        echo "<tr>";
                        echo "<td> <a href='getProductInfo.php?productId=".$record['productId']."' target = 'productInfoiFrame'>" . $record['productName'] . "</a></td>";
                        echo "<td>".$record['price']."</td>"; //displays the price, era, region, and product Type
                        echo "<td>".$record['era']."</td>";
                        echo "<td>".$record['region']."</td>";
                        echo "<td>".$record['productType']."</td>";
                        echo "<td><input type='checkbox' name='checkedItems[]' value='".$record['productId']."'/>"."</td>";
                        $_SESSION['productId'] = $record['productId'];
                        echo "</tr>";
                 }
                 //echo '<input type="submit" value="View Cart and Checkout">';//create a form somewhow within the php to submit it
                 
             }?>
             <INPUT TYPE="image" SRC="includes/shopping.png" ALT="SUBMIT" name="submitCart">
             </form>
             </table>
             </div>
          </div><!-- ends results div-->
          
          <div style="float:left" id="table">
          <iframe name = "productInfoiFrame" width = "200" height "300"
         src="getProductInfo.php" frameborder="1"></iframe> 
          </div>
         
        </main>
    </body>
</html>