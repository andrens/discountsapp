<!DOCTYPE html>
<html>
<head>
	<title>sending order</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

	<script type="text/javascript">

		var sendOrder= {
	  "id": "3",
	  "customer-id": "3",
	  "items": [
	    {
	      "product-id": "A101",
	      "quantity": "2",
	      "unit-price": "9.75",
	      "total": "19.50"
	    },
	    {
	      "product-id": "A102",
	      "quantity": "1",
	      "unit-price": "49.50",
	      "total": "49.50"
	    },
	    {
	      "product-id": "B102",
	      "quantity": "50",
	      "unit-price": "10.00",
	      "total": "500.00"
	    }
	  ],
	  "total": "569.00"
	}

	$.ajax({
      url: "discount",
      dataType: "json",
      type : "POST",
      data: JSON.stringify(sendOrder),
      contentType: "application/json; charset=utf-8",
      success : function(r) {
        console.log(r);
      }
    });

	</script>
</head>
<body>

</body>
</html>