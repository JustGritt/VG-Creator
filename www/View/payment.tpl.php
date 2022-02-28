<!DOCTYPE html>
<html>
    <head>
    <meta charset="UTF-8">
    <title>Buy cool new product</title>
    <meta name="description" content="Description de ma page">
    <link rel="stylesheet" href="style.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch%22%3E</script>
    <script src="https://js.stripe.com/v3/%22%3E</script>
</head>
<body>

    <form action="http://localhost/payment" method="POST">
    <button type="submit" id="checkout-button">Checkout</button>
    </form>
    <?php include "View/".$this->view.".view.php"; ?>


</body>
</html>