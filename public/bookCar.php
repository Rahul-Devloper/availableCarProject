<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayPal Checkout</title>
</head>
<body>

<!-- Add the PayPal Button Container -->
<div id="paypal-button-container"></div>

<!-- Include the PayPal JavaScript SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=AV6wrYbSjSe3HF8xSYo1Ejsmj-M-z8AiozJA31piu1Xs-cNHz0-26XxwTdV4eDS5HxnSU3WKshPG4jnU"></script>

<!-- Include Your Custom JavaScript Code -->
<script>
    // Render the PayPal button
    paypal.Buttons({
        createOrder: function(data, actions) {
            // Set up the transaction
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '10.00' // Replace with your desired amount
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            // Capture the funds from the transaction
            return actions.order.capture().then(function(details) {
                // Display a success message to the buyer
                alert('Transaction completed by ' + details.payer.name.given_name);
            });
		
        }
    }).render('#paypal-button-container');
</script>

</body>
</html>
