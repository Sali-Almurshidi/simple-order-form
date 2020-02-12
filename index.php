<?php
//this line makes PHP behave in a more strict way
declare(strict_types=1);

session_start();
include 'allData.php';
$data = new allDataHere();

$products = [
    ['name' => 'Club Ham', 'price' => 3.20],
    ['name' => 'Club Cheese', 'price' => 3],
    ['name' => 'Club Cheese & Ham', 'price' => 4],
    ['name' => 'Club Chicken', 'price' => 4],
    ['name' => 'Club Salmon', 'price' => 5]
];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if ($_SESSION['emailAddress'] != '' || $_SESSION['streetnumber'] != '' || $_SESSION['street'] != '' || $_SESSION['city'] != '' || $_SESSION['zipcode'] != '') {
        $data->emailAddress = $_SESSION['emailAddress'];
        $data->streetnumber = $_SESSION['streetnumber'];
        $data->street = $_SESSION['street'];
        $data->city = $_SESSION['city'];
        $data->zipcode = $_SESSION['zipcode'];
        $data->totalValue = $_SESSION['totalValue'];
    }
}// get the information after click on the get method

if ($_GET['food'] != 1 ) {
    $data->listName = " Drinks";
    $products = [
        ['name' => 'Cola', 'price' => 2],
        ['name' => 'Fanta', 'price' => 2],
        ['name' => 'Sprite', 'price' => 2],
        ['name' => 'Ice-tea', 'price' => 3],
    ]; // drinks array;
} else {
    $data->listName = " Food";
    $products = [
        ['name' => 'Club Ham', 'price' => 3.20],
        ['name' => 'Club Cheese', 'price' => 3],
        ['name' => 'Club Cheese & Ham', 'price' => 4],
        ['name' => 'Club Chicken', 'price' => 4],
        ['name' => 'Club Salmon', 'price' => 5]
    ];
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

function getEmail($email, $resultCheckEmail)
{
    $checkEmail = checkEmail($email);
    if ($checkEmail == false) {
        $resultCheckEmail = "<div class=\"alert alert-danger\" role=\"alert\"> This is not valid email address</div>";
        return false;
    } else {
        $resultCheckEmail = "<div class=\"alert alert-success\" role=\"alert\"> This is valid email address</div>";

        //$data->emailAddress = $email;

        $_SESSION['emailAddress'] = $email;
    }
}// check if email not empty and valid;

function getAddress($streetnumber, $street, $city, $zipcode)
{
        $_SESSION['streetnumber'] = $streetnumber;
        $_SESSION['street'] = $street;
        $_SESSION['city'] = $city;
        $_SESSION['zipcode'] = $zipcode;

}// address

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get the input from the screen
    $data->emailAddress = $_POST['email'];
    $data->streetnumber = $_POST['streetnumber'];
    $data->street = $_POST['street'];
    $data->city = $_POST['city'];
    $data->zipcode = $_POST['zipcode'];

    if ($data->emailAddress == null) {
        $data->resultCheckEmail = "<div class=\"alert alert-danger\" role=\"alert\"> Enter email address</div>";
    } else {
        getEmail($data->emailAddress, $data->resultCheckEmail);
    }//  email is not empty

    // if ((!empty($_POST['streetnumber'])) || (!empty($_POST['street'])) || (!empty($_POST['city'])) || (!empty($_POST['zipcode']))) {
    if (($data->streetnumber == null) || ($data->street == null) || ($data->city == null) || ($data->zipcode == null)) {
        $data->AddressFileds = "<div class=\"alert alert-danger\" role=\"alert\"> Complete your address </div>";
    } else {
        if (is_numeric['streetnumber'] && is_numeric['zipcode']) {
            //$streetnumber = '';
            getAddress($data->streetnumber, $data->street, $data->city, $data->zipcode);
            //   $numStreet = false;
        } // the street have to be number
        else{
            $falseStreetNumber = "<div class=\"alert alert-danger\" role=\"alert\"> Have to be number</div>";
        }

     /*   if (!is_numeric['zipcode']) {
            $falseZipcode = "<div class=\"alert alert-danger\" role=\"alert\"> Have to be number</div>";
            $_SESSION['zipcode'] = '';
            //    $numZipCode = false;
        } // // the zip code have to be number*/

    }// address is not empty

    $data->totalValue = $_SESSION['totalValue'];

    if (!empty($_POST['products'])) {
        $selectProducts = $_POST['products'];
        //$totalFoodValue = $_SESSION['totalFoodPrice'];
        //$totalValue = 0.0;
        foreach ($selectProducts as $key => $foodName) {
            $data->totalValue += floatval($products[$key]["price"]);
        }
        $_SESSION['totalValue'] = $data->totalValue;
        // var_dump($selectProducts);
    } else {
        $data->selectNothing = "<div class=\"alert alert-danger\" role=\"alert\"> You didn't select any food </div>";
    }

    $time = " normal ";
    if ($_SESSION['totalValue'] > 15) {
        $time = " extra ";
    }

    if ($_SESSION['emailAddress'] != '' && $_SESSION['streetnumber'] != '' && $_SESSION['street'] != '' && $_SESSION['city'] != '' && $_SESSION['zipcode'] != '' && $_POST['products'] != '') {
        $data->orderSend = "<div class=\"alert alert-dark\" role=\"alert\"> This order has been sent and the expected delivery it will take " . $time . " time maybe</div>";

        $subject = 'Your order';
        $message = 'The total order price is : ' . $_SESSION['totalValue'] . 'and 20€ for delivery =  ' . $_SESSION['totalValue'] + 20 . "\r\n" .
            'your address is : city ' . $_SESSION['city'] . ' ,street ' . $_SESSION['street'] . ' ,street number ' . $_SESSION['streetnumber'] .
            ',zipcode ' . $_SESSION['zipcode'] . "\r\n" .
            'The delivery time is ' . $time . "\r\n";

        $headers = 'From: order@form.com';

        mail($_SESSION['emailAddress'], $subject, $message, $headers);

    } else {
        $data->orderSend = "<div class=\"alert alert-danger\" role=\"alert\"> O_o O_o O_o O_o O_o </div>";
    }// the order is ok or not

} // get the information after click on the post method

require 'form-view.php';