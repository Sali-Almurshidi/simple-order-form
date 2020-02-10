<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

//we are going to use session variables so we need to enable sessions
session_start();
$resultCheckEmail = null;
$AddressFileds = null;
$falseStreetNumber = null;
$falseZipcode = null;
$selectNothing = null;
$selectFood = array();
$totalFoodValue = 0.0;

$productsFood = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];

function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}
// check email address
function checkEmail($email){
    filter_var($email, FILTER_VALIDATE_EMAIL);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                     return true ;
                } else {
                    return false ;
                }
}


    if (isset($_POST['order'])){
        $emailAddress = null ;
        $streetnumber = null ;
        $street = null;
        $city = null ;
        $zipcode = null ;


        if(!empty($_POST['email'])){
            $checkEmail = checkEmail($_POST['email']);
            if ($checkEmail == false){
                $resultCheckEmail = "<div class=\"alert alert-danger\" role=\"alert\"> This is not valid email address</div>";

            }else{
                $resultCheckEmail = "<div class=\"alert alert-success\" role=\"alert\"> This is valid email address</div>";
                $emailAddress = $_POST['email'];
            }
        }else{
            $resultCheckEmail = "<div class=\"alert alert-danger\" role=\"alert\"> Enter email address</div>";
        }// the email input empty

        if (!empty(is_numeric['streetnumber']) && !empty($_POST['street']) && !empty($_POST['city']) && is_numeric($_POST['zipcode'])){
            $AddressFileds = "<div class=\"alert alert-success\" role=\"alert\"> Your address is complete </div>";
            $streetnumber = $_POST['streetnumber'];
            $street = $_POST['street'];
            $city = $_POST['city'];
            $zipcode = $_POST['zipcode'];


        }else{
            $AddressFileds = "<div class=\"alert alert-danger\" role=\"alert\"> Complete your address </div>";

            if(!is_numeric['streetnumber']){
                $falseStreetNumber = "<div class=\"alert alert-danger\" role=\"alert\"> Have to be number</div>";
            }
            if(!is_numeric['zipcode']){
                $falseZipcode = "<div class=\"alert alert-danger\" role=\"alert\"> Have to be number</div>";
            }
        }// address


        if(!empty($_POST['productsFood']))
        {
            $selectFood = $_POST['productsFood'];

            foreach($selectFood as $key=>$foodName)
            {
                    $totalFoodValue += floatval($productsFood[$key]["price"]);
            }
            var_dump($productsFood);

            var_dump($selectFood);
        }else{
            $selectNothing = "<div class=\"alert alert-danger\" role=\"alert\"> You didn't select any product </div>";
        }

    }// isset for the button



//your products with their price.


$productsDrinks = [
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 3],
];

$totalValue = 0;

require 'form-view.php';