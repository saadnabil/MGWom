<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="styles.css" />
</head>
<style>
    /* General styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    .email-container {
        background-color: white;
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .bold {
        font-weight: bold;
    }

    .flex {
        display: flex;
        justify-items: flex-start;
    }

    .flex-col {
        display: flex;
        flex-direction: column;
    }

    .items-center {
        justify-content: center;
        align-items: center;
    }

    .items-between {
        justify-content: space-between;
        align-items: center;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo {
        width: 100px;
    }

    .mb-4 {
        margin-bottom: 10px;
    }

    .mr-10 {
        margin-right: 10px;
    }

    .p-10 {
        padding: 10px;
    }

    .delivery-image {
        width: 100%;
        max-width: 300px;
        margin: 10px auto;
    }

    .greeting p {
        font-size: 16px;
        line-height: 1.5;
    }

    .tracking-info {
        text-align: center;
        margin: 20px 0;
    }



    .step {
        background-color: #f0f0f0;
        padding: 10px;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .order-summary,
    .shipping-address {
        margin: 20px 0;
    }

    .order-summary h3,
    .shipping-address h3 {
        background-color: #f0f0f0;
        padding: 10px;
        margin: 0;
    }

    .product {
        display: flex;
        align-items: center;
        margin: 20px 0;
        border-bottom: 1px solid #e0e0e0;
        padding-bottom: 20px;
    }

    .product img {
        width: 80px;
        margin-right: 20px;
    }

    .product-info p {
        margin: 5px 0;
    }

    .order-total {
        padding: 10px;
        margin: 20px 0;
        text-align: right;
    }

    .text-white {
        color: white;
    }

    .footer {
        text-align: center;
        font-size: 14px;
        line-height: 1.5;
        margin-top: 20px;
    }

    .app-links img {
        width: 120px;
        margin: 10px;
    }

    .bg-black {
        background-color: black;
    }

    .rounded-lg {
        border-radius: 10px;
    }

    .p-4 {
        padding: 10px;
    }

    .max-w-lg {
        width: 200px;
    }

    /* Responsive styles */
    @media (max-width: 420px) {
        .product img {
            width: 60px;
        }

        .logo {
            width: 80px;
        }

        .delivery-image {
            max-width: 250px;
        }


        .step {
            width: 50px;
            height: 50px;
            font-size: 12px;
        }

        .order-total {
            text-align: left;
        }

        .app-links img {
            width: 100px;
        }
    }

    @media (max-width: 375px) {
        .email-container {
            padding: 15px;
        }

        .delivery-image {
            max-width: 200px;
        }

        .product img {
            width: 50px;
        }

        .logo {
            width: 70px;
        }

        .order-summary h3,
        .shipping-address h3 {
            font-size: 16px;
        }

        .product-info p {
            font-size: 14px;
        }
    }

    @media (max-width: 320px) {
        .email-container {
            padding: 10px;
        }

        .delivery-image {
            max-width: 180px;
        }

        .logo {
            width: 60px;
        }

        .product img {
            width: 40px;
        }


        .order-summary h3,
        .shipping-address h3 {
            font-size: 14px;
        }

        .product-info p {
            font-size: 12px;
        }

        .app-links img {
            width: 80px;
        }
    }

    /* Responsive styles */
    @media (min-width: 768px) {
        .flex-row {
            flex-direction: row;
        }
    }

    @media (max-width: 767px) {
        .flex-col {
            flex-direction: column;
        }
    }

    /* General styles for tracking steps */
    .tracking-steps {
        display: grid;
        gap: 10px;
        /* Adjust gap between items */
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        /* Default to auto-fit columns */
        justify-content: center;
        /* Center items horizontally */
    }

    /* Responsive adjustments */
    @media (max-width: 600px) {
        .tracking-steps {
            grid-template-columns: 1fr;
            /* Stack items in a single column on small screens */
        }
    }

    .mr-2 {
        margin-right: 4px;
    }
</style>

<body>
    <div class="email-container">
        <div class="header">
            <div class="flex">
                <img src="{{ url('images/logo.svg') }}" alt="Womniz Logo" class="logo" />
            </div>
            <h2>Your order is on its way!</h2>
            <img src="{{ url('images/delivery_image.svg') }}" alt="Delivery Image" class="delivery-image" />
        </div>

        <div class="greeting">
            <p class="bold">Hello Ahmed,</p>
            <p>
                We have good news for you. We want to let you know that your order has
                been dispatched
            </p>
        </div>

        <div class="tracking-info">
            <div class="tracking-steps">
                <div class="">
                    <img src="{{ url('images/ordered.svg') }}" />
                    <p>Ordered</p>
                </div>
                <div class="">
                    <img src="{{ url('images/packed.svg') }}" />
                    <p>Packed</p>
                </div>
                <div class="">
                    <img src="{{ url('images/shipping.svg') }}" />
                    <p>Shipping</p>
                </div>
                <div class="">
                    <img src="{{ url('images/delievered.svg') }}" />
                    <p>Delivered</p>
                </div>
            </div>
            <p>Shipped: Sep 14, 2024</p>
        </div>

        <div class="order-summary">
            <h3>ORDER SUMMARY</h3>
            <p>Order No: {{ $order['id'] }}</p>
            <p>Shipment No: {{ $order['id'] }}</p>
        </div>

        <div class="shipping-address">
            <h3>SHIPPING ADDRESS</h3>
            <p>{{ $order['user']['name'] }}</p>
            <p>{{ $order['address']['street_address'] }}</p>
        </div>

        @foreach($order['orderDetails'] as $key => $details)
            <div class="product">
                <img src="{{ url('storage/'. $details['product']['thumbnail']) }}" alt="Product Image" />
                <div class="product-info">
                    @if($details['price'] == $details['price_after_sale'])
                        <p><strong>{{ $details['price_after_sale'] }} EGP</strong></p>
                    @else
                        <p> <span style="text-decoration: line-through;">{{ $details['price'] }} </span> EGP  <strong style="padding-left:20px;">{{ $details['price_after_sale'] }} EGP</strong></p>
                    @endif
                    <p>Zara</p>
                    <p>{{ $details['product']['name_en'] }}</p>
                    <p>Quantity: {{ $details['quantity'] }}</p>
                </div>
            </div>
        @endforeach


        <div class="flex items-between">
            <div class="order-total">
                <p>Sub total: {{ $order['totalsub'] }} EGP</p>
                <p>Shipping: {{ $order['shipping'] }} EGP</p>
                <p><strong>Total: {{ $order['total'] }} EGP</strong></p>
            </div>
            <div>
                <img src="images/price.svg" />
            </div>
        </div>

        <div class="">
            <div class="flex-col gap-5">
                <p class="bold">Thank you,</p>
                <p class="bold text-xl m-0">Womniz team</p>
            </div>
            <p>
                If you have any questions or need assistance, please feel free to
                reach out to our customer service team at [Customer Service Email] or
                [Customer Service Phone Number]. We are here to help!
            </p>
            <p class="bold">TAX ID: 777-777-777</p>
            <p class="bold">CR: 12345678</p>
            <h1 class="flex text-xl items-center">Womniz</h1>
            <div class="flex flex-col gap-5 items-center flex-row ">
                <div class="max-w-lg flex items-center gap-2 bg-black rounded-lg mb-4 p-10 mr-2">
                    <img src="{{ url('images/appstore.svg') }}" alt="App Store" class="mr-10" />
                    <div class="flex flex-col">
                        <p class="bold text-white">Download on the App Store</p>
                    </div>
                </div>
                <div class="max-w-lg flex items-center gap-2 bg-black mb-4 rounded-lg p-10">
                    <img src="{{ url('images/googleIcon.svg') }}" alt="Google Play" class="mr-10" />
                    <div class="flex flex-col">
                        <p class="bold m-0 text-white">
                            Download on the Google Play
                        </p>
                    </div>
                </div>
            </div>

            <p>
                Thank you once again for choosing Womniz. We look forward to serving
                you again!
            </p>
            <p>Best regards, The Womniz Team</p>
        </div>
    </div>
</body>

</html>
