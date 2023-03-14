<?php
session_start(); // Session starts
$session_id = session_id();
$title = 'Ramp Riot Online Store - Products';
require('templates/header.php');

// creating variables to use in page
$id = $_GET['id'];

$customer = new Customer($id, $sql);
$arr = $customer->getCustomer();

$firstname = $arr[0]['firstname'];
$lastname = $arr[0]['lastname'];
$address = $arr[0]['address'];
$zip = $arr[0]['zip'];
$city = $arr[0]['city'];
$country = $arr[0]['country'];
$phone = $arr[0]['phone'];
$email = $arr[0]['email'];

// getting this customer's orders in array
$order = new Order($id, $sql);
$arr = $order->getOrders();


?>

<!-- CONTENT STARTS -->


<div class="col-lg-10" id="content">

    <div class="row" style="padding: 20px;">
        <h1>Customer
            <h2>Your orders</h2>

            <?php for ($i = 0; $i < count($arr); $i++) { ?>
            <div class="card card-body" style="padding: 20px; margin-bottom: 20px;">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <a class="btn btn-dark" data-bs-toggle="collapse" href="#collapse<?= $arr[$i]['id'] ?>" role="button" aria-expanded="false" aria-controls="collapse<?php $arr[$i]['id']?>">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        </a>
                    </div>
                    <div class="col-md-2">
                        <b>Order id:</b> <?= $arr[$i]['id'] ?>
                    </div>
                    <div class="col-md-4">
                        <b>Date:</b> <?= $arr[$i]['createdate'] ?>
                    </div>
                    <div class="col-md-3">
                        <b>Shipping:</b> <?= $arr[$i]['shipping'] ?>
                    </div>
                    <div class="col-md-3">
                        <b>Status:</b> <?= $arr[$i]['status'] ?>
                    </div>

                    <div class="collapse" id="collapse<?= $arr[$i]['id']?>">
                        <div class="row" style="margin-top: 20px; margin-bottom: 20px;">
                            <div class="col-auto">
                                <ul class="list-group" style="margin-bottom: 30px;">
                                    <li class="list-group-item"><img src="uploads/sk8pants.webp" style="max-width: 250px"></li>
                                    <li class="list-group-item"><b>Product name: Pants Fjisj SFFSsf</b></li>
                                    <li class="list-group-item">Size: XL</li>
                                    <li class="list-group-item">Price: 60 €</li>
                                    <li class="list-group-item">Quantity: 3 pcs</li>
                                </ul>
                            </div>
                            <p>
                                Total price: 180 €
                                Taxes: 50 €
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>

    </div>

</div>
<!-- CONTENT ENDS -->

<?php require('templates/footer.php'); ?>