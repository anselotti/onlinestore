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
        <h1>Customer</h1>

        <form action="do_modify.php" method="POST" class="row g-3">
            <div class="row g-3">
            <h2 id="your-data">Your data</h2>
                <?php

                $customer = new Customer($_SESSION['logged_id'], $sql);

                $customer_data = $customer->getCustomer();

                if (isset($_GET['error'])) {

                    if ($_GET['error'] == 3) {
                        echo '<p style="padding: 20px; border-radius: 10px; color: white; background-color: #537072;">Your data has updated!</p>';
                    }

                    if ($_GET['error'] == 5) {
                        echo '<p style="padding: 20px; border-radius: 10px; color: white; background-color: rgb(122, 47, 47);">Your cart is empty. Please select products to continue payment.</p>';
                    }
                }


                ?>
                <div class="col-sm-12">
                    <input type="text" class="form-control" name="id" value="<?= $customer_data[0]['id'] ?>" hidden>
                </div>
                <div class="col-sm-6">
                    <label for="firstname">First name</label>
                    <input type="text" class="form-control" name="firstname" value="<?= $customer_data[0]['firstname'] ?>">
                </div>
                <div class="col-sm-6">
                    <label for="lastname">Last name</label>
                    <input type="text" class="form-control" name="lastname" value="<?= $customer_data[0]['lastname'] ?>">
                </div>
                <div class="col-sm-12">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" value="<?= $customer_data[0]['address'] ?>">
                </div>
                <div class="col-sm-4">
                    <label for="zip">Zip</label>
                    <input type="text" class="form-control" name="zip" value="<?= $customer_data[0]['zip'] ?>">
                </div>
                <div class="col-sm-4">
                <label for="city">City</label>
                    <input type="text" class="form-control" name="city" value="<?= $customer_data[0]['city'] ?>">
                </div>
                <div class="col-sm-4">
                <label for="country">Country</label>
                    <input type="text" class="form-control" name="country" value="<?= $customer_data[0]['country'] ?>">
                </div>
                <div class="col-sm-12">
                <label for="phone">Phone</label>
                    <input type="text" class="form-control" name="phone" value="<?= $customer_data[0]['phone'] ?>">
                </div>
                <div class="col-sm-12">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= $customer_data[0]['email'] ?>">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-dark" name="submit">Update data</button>
                </div>

            </div>

        </form>
        

        <h2 id="your-orders">Your orders</h2>

        <?php for ($i = 0; $i < count($arr); $i++) { ?>
            <div class="card card-body" style="padding: 20px; margin-bottom: 20px;">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <a class="btn btn-dark" data-bs-toggle="collapse" href="#collapse<?= $arr[$i]['id'] ?>" role="button" aria-expanded="false" aria-controls="collapse<?php $arr[$i]['id'] ?>">
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

                    <div class="collapse" id="collapse<?= $arr[$i]['id'] ?>">

                        <div class="row" style="margin-top: 20px; margin-bottom: 20px;">

                            <?php
                            $ordered_items = $sql->query("SELECT * FROM order_items WHERE order_id = '" . $arr[$i]['id'] . "'");
                            $price = 0;
                            while ($row_oi = $ordered_items->fetch_assoc()) {
                                $price = $row_oi['price'] + $price;
                                $tax = $price - ($price / 1.24)
                            ?>

                                <div class="col-auto">
                                    <ul class="list-group" style="margin-bottom: 30px;">
                                        <li class="list-group-item"><b><a href="product.php?id=<?= $row_oi['product_id'] ?>"><?= $row_oi['name'] ?></b></a></li>
                                        <li class="list-group-item">Size: <?= $row_oi['product_size'] ?></li>
                                        <li class="list-group-item">Price: <?= $row_oi['price'] ?></li>
                                        <li class="list-group-item">Quantity: <?= $row_oi['pcs'] ?> pcs</li>
                                    </ul>
                                </div>

                            <?php
                            }
                            ?>
                        </div>


                        <p>
                            <b>Total price: <?= $price ?> €</b> (taxes: <?= round($tax, 2) ?> €)
                        </p>
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