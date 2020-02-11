<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);
$cookie_name = "user";
$cookie_value = "John Doe";
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

//we are going to use session variables so we need to enable sessions
session_start();
$resultCheckEmail = null;
$emailAddress = null;
$AddressFileds = null;
$falseStreetNumber = null;
$falseZipcode = null;
$selectNothing = null;
$selectFood = array();
$totalFoodValue = 0.0;
$orderSend = null;
$listName = " Food";
$productsFood = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
]; // food array
$productsDrinks = [
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 3],
]; // drinks array


function whatIsHappening()
{
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

// get nav number
if(isset($_GET['food'])){
    if ($_GET['food'] == 1){
        echo "this is food";
        issetOrder($_GET['food'], $productsFood);
    }else {
        echo "this is drinks";
        issetOrder($_GET['food'], $productsDrinks);
    }
}

function checkEmail($email)
{
    filter_var($email, FILTER_VALIDATE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}// check email address

//issetOrder($foodNav, $drinksNav , $productsFood);

function issetOrder($pageNumber , $products)
{
    $totalFoodValue = 0.0;
    $totalDrinksValue = 0.0;

    if ($pageNumber == 1) {
        $listName = " Food" ;
        echo " this is food";

        if (!empty($_POST['products'])) {
            $selectFood = $_POST['products'];

            foreach ($selectFood as $key => $foodName) {
                $totalFoodValue += floatval($products[$key]["price"]);
            }
            var_dump($products);

            var_dump($selectFood);
        } else {
            $selectNothing = "<div class=\"alert alert-danger\" role=\"alert\"> You didn't select any product </div>";
        }
    }
    if ($pageNumber == 2) {
        $listName = "drinks" ;
        echo " this is drinks";

    }

    if (isset($_POST['order'])) {
        $emailAddress = null;
        $streetnumber = null;
        $street = null;
        $city = null;
        $zipcode = null;


        if (!empty($_POST['email'])) {
            $checkEmail = checkEmail($_POST['email']);
            if ($checkEmail == false) {
                $resultCheckEmail = "<div class=\"alert alert-danger\" role=\"alert\"> This is not valid email address</div>";

            } else {
                $resultCheckEmail = "<div class=\"alert alert-success\" role=\"alert\"> This is valid email address</div>";
                $emailAddress = $_POST['email'];
            }
        } else {
            $resultCheckEmail = "<div class=\"alert alert-danger\" role=\"alert\"> Enter email address</div>";
        }// the email input empty

        if (!empty($_POST['streetnumber']) && !empty($_POST['street']) && !empty($_POST['city']) && is_numeric($_POST['zipcode'])) {
            $AddressFileds = "<div class=\"alert alert-success\" role=\"alert\"> Your address is complete </div>";
            $streetnumber = $_POST['streetnumber'];
            $street = $_POST['street'];
            $city = $_POST['city'];
            $zipcode = $_POST['zipcode'];

            $adsressArray = array($streetnumber, $street, $city, $zipcode);
            //var_dump($adsressArray);
            $_SESSION['address'] = $adsressArray;

            if (!is_numeric['streetnumber']) {
                $falseStreetNumber = "<div class=\"alert alert-danger\" role=\"alert\"> Have to be number</div>";
            }
            if (!is_numeric['zipcode']) {
                $falseZipcode = "<div class=\"alert alert-danger\" role=\"alert\"> Have to be number</div>";
            }


        } else {
            $AddressFileds = "<div class=\"alert alert-danger\" role=\"alert\"> Complete your address </div>";

            if (!is_numeric['streetnumber']) {
                $falseStreetNumber = "<div class=\"alert alert-danger\" role=\"alert\"> Have to be number</div>";
            }
            if (!is_numeric['zipcode']) {
                $falseZipcode = "<div class=\"alert alert-danger\" role=\"alert\"> Have to be number</div>";
            }
        }// address*/

        /*if (){
            if (!empty($_POST['productsFood'])) {
                $selectFood = $_POST['productsFood'];

                foreach ($selectFood as $key => $foodName) {
                    $totalFoodValue += floatval($products[$key]["price"]);
                }
                var_dump($products);

                var_dump($selectFood);
            } else {
                $selectNothing = "<div class=\"alert alert-danger\" role=\"alert\"> You didn't select any product </div>";
            }
        }// food list
        else{
            if (!empty($_POST['productsFood'])) {
                $selectFood = $_POST['productsFood'];

                foreach ($selectFood as $key => $foodName) {
                    $totalFoodValue += floatval($products[$key]["price"]);
                }
                var_dump($products);

                var_dump($selectFood);
            } else {
                $selectNothing = "<div class=\"alert alert-danger\" role=\"alert\"> You didn't select any product </div>";
            }
        }// drink list*/
        $selectNothing = "<div class=\"alert alert-danger\" role=\"alert\"> You didn't select any product </div>";


        if (($emailAddress != null) && ($streetnumber != null) && ($street != null) && ($city != null) && ($zipcode != null) && ($selectNothing != null)) {
            $orderSend = "<div class=\"alert alert-dark\" role=\"alert\"> This order has been sent </div>";
        }

    }// isset for the button
}

$totalValue = $totalFoodValue;

require 'form-view.php';