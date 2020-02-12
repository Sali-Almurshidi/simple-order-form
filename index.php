<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);
/*$cookie_name = "user";
$cookie_value = "John Doe";
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day*/
/*ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);*/

//we are going to use session variables so we need to enable sessions
session_start();
$emailAddress = $streetnumber = $street = $city = $zipcode = $listName = null;
$totalValue = 0.0;
$products = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];
$resultCheckEmail = null;
$AddressFileds = null;
$falseStreetNumber = null;
$falseZipcode = null;
$selectNothing = null;
$orderSend = null;
$AddressFileds = null ;
//dataforCLIENT


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_SESSION['emailAddress'] != '' || $_SESSION['streetnumber'] != '' || $_SESSION['street'] != '' || $_SESSION['city'] != '' || $_SESSION['zipcode'] != '') {
        $emailAddress = $_SESSION['emailAddress'];
        $streetnumber = $_SESSION['streetnumber'];
        $street = $_SESSION['street'];
        $city = $_SESSION['city'];
        $zipcode = $_SESSION['zipcode'];
        $totalValue = $_SESSION['totalValue'];
    }
}// get the information after click on the get method

if ($_GET['food'] == 1) {
    $listName = " Food";
    $products = [
        ['name' => 'Club Ham', 'price' => 3.20],
        ['name' => 'Club Cheese', 'price' => 3],
        ['name' => 'Club Cheese & Ham', 'price' => 4],
        ['name' => 'Club Chicken', 'price' => 4],
        ['name' => 'Club Salmon', 'price' => 5]
    ];
} else {
    $listName = " Drinks";
    $products = [
        ['name' => 'Cola', 'price' => 2],
        ['name' => 'Fanta', 'price' => 2],
        ['name' => 'Sprite', 'price' => 2],
        ['name' => 'Ice-tea', 'price' => 3],
    ]; // drinks array;
}// change list items

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

function checkEmail($email)
{
    filter_var($email, FILTER_VALIDATE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}// check email address

function getEmail($email)
{
        $checkEmail = checkEmail($email);
        if ($checkEmail == false) {
            $resultCheckEmail = "<div class=\"alert alert-danger\" role=\"alert\"> This is not valid email address</div>";
            return false;
        } else {
            $resultCheckEmail = "<div class=\"alert alert-success\" role=\"alert\"> This is valid email address</div>";
            $emailAddress = $email;
            $_SESSION['emailAddress'] = $emailAddress;
        }
}// check if email not empty and valid;

function getAddress($streetnumber, $street, $city, $zipcode)
{
        $numStreet = true;
        $numZipCode = true;

        if (!is_numeric['streetnumber']) {
            $falseStreetNumber = "<div class=\"alert alert-danger\" role=\"alert\"> Have to be number</div>";
            $numStreet = false;
        } // the street have to be number

        if (!is_numeric['zipcode']) {
            $falseZipcode = "<div class=\"alert alert-danger\" role=\"alert\"> Have to be number</div>";
            $numZipCode = false;
        } // // the zip code have to be number

        if ($numStreet = true && $numZipCode = true) {
            $AddressFileds = "<div class=\"alert alert-success\" role=\"alert\"> Your address is complete </div>";

            $_SESSION['streetnumber'] = $streetnumber;
            $_SESSION['street'] = $street;
            $_SESSION['city'] = $city;
            $_SESSION['zipcode'] = $zipcode;

        }

}// address

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     // get the input from the screen
    $emailAddress = $_POST['email'];
    $streetnumber = $_POST['streetnumber'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];

    if($emailAddress == null){
        $resultCheckEmail = "<div class=\"alert alert-danger\" role=\"alert\"> Enter email address</div>";
    }else{
        getEmail($emailAddress);
    }//  email is not empty

    if((!empty($_POST['streetnumber'])) || (!empty($_POST['street'])) || (!empty($_POST['city'])) || (!empty($_POST['zipcode']))){
        $AddressFileds = "<div class=\"alert alert-danger\" role=\"alert\"> Complete your address </div>";
    }else{
        getAddress($streetnumber, $street, $city, $zipcode);
    }// address is not empty

    $totalValue = $_SESSION['totalValue'];

    if (!empty($_POST['products'])) {
        $selectProducts = $_POST['products'];
        //$totalFoodValue = $_SESSION['totalFoodPrice'];
        //$totalValue = 0.0;
        foreach ($selectProducts as $key => $foodName) {
            $totalValue += floatval($products[$key]["price"]);
        }
        $_SESSION['totalValue'] = $totalValue;
        // var_dump($selectProducts);
    } else {
        $selectNothing = "<div class=\"alert alert-danger\" role=\"alert\"> You didn't select any food </div>";
    }

    $time = " normal ";
    if ($_SESSION['totalValue'] > 15) {
        $time = " extra ";
    }

    if ($_SESSION['emailAddress'] != '' && $_SESSION['streetnumber'] != '' && $_SESSION['street'] != '' && $_SESSION['city'] != '' && $_SESSION['zipcode'] != '' && $_POST['products'] != '') {
        $orderSend = "<div class=\"alert alert-dark\" role=\"alert\"> This order has been sent and the expected delivery it will take " . $time . " time maybe</div>";

        //$to      = 'nobody@example.com';
        $subject = 'Your order';
        $message = 'The total order price is : ' . $_SESSION['totalValue'] . 'and 20€ for delivery =  ' . $_SESSION['totalValue'] + 20 . "\r\n" .
            'your address is : city ' . $_SESSION['city'] . ' ,street ' . $_SESSION['street'] . ' ,street number ' . $_SESSION['streetnumber'] .
            ',zipcode ' . $_SESSION['zipcode'] . "\r\n" .
            'The delivery time is ' . $time . "\r\n";

        $headers = 'From: order@form.com';

        mail($_SESSION['emailAddress'], $subject, $message, $headers);
        var_dump(mail);

    } else {
        $orderSend = "<div class=\"alert alert-danger\" role=\"alert\"> O_o O_o O_o O_o O_o </div>";
    }// the order is ok or not

} // get the information after click on the post method


require 'form-view.php';