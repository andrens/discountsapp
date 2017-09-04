<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->post('/api/order/discount', function (Request $request, Response $response) {

	    $id 		= $request->getParam('id');
	    $customerId = $request->getParam('customer-id');
	    $items 		= $request->getParam('items');
	    $total		= $request->getParam('total');

	    $orderDiscount["id"] 			= $id;
	    $orderDiscount["customer-id"] 	= $customerId;

	    $qtdItems 		= count($items);
	    $products 		= new products();
	    $rules			= new rules();
	    $itemsObj		= new items();
	    $sumItems		= 0;
	    $cheap 			= 0;
	    $cheapProdId 	= 0;
	    $sumTotal		= 0;


	    for ($i=0; $i < $qtdItems ; $i++) { 
	    	$prodId 	= $items[$i]["product-id"];
	    	$category 	= $products->getCategory($prodId);
	    	$quantity	= $items[$i]["quantity"];
	    	$price 		= $items[$i]["unit-price"];
	    	$itemTotal	= $items[$i]["total"];

	    	$orderDiscount["items"][$i]["product-id"] 	= $prodId;
	    	$orderDiscount["items"][$i]["category"] 	= $category;
	    	$orderDiscount["items"][$i]["quantity"] 	= (string)$quantity;
	    	$orderDiscount["items"][$i]["unit-price"] 	= (string)$price;
	    	$orderDiscount["items"][$i]["total"] 		= (string)$itemTotal;

	    	$verItems[$i]	= $rules->verifyItem($prodId, $category, $quantity, $price, $cheapProdId, $cheap, $sumItems);
	 		if($verItems[$i]) {
	 			$applyDiscount[$verItems[$i]["cheap-product-id"]] = $verItems[$i];
	    		$cheapProdId 	= $verItems[$i]["cheap-product-id"];
	    		$cheap 			= $verItems[$i]["cheap"]; 
	    		$sumItems 		= $verItems[$i]["sumItems"];
	 		}
	 		$sumTotal += $itemTotal;
	    }
	    if($total <= 1000) {

	    	$totalItemsDiscount = count($applyDiscount);

		    foreach ($applyDiscount as $key => $value) {
		    	$i++;
		    	$index = array_search($key, array_column($orderDiscount["items"], 'product-id'));
		    	if($orderDiscount["items"][$index]["category"] == 1) {
		    		$newQuant = $orderDiscount["items"][$index]["quantity"];
		    		$unitPrice = $orderDiscount["items"][$index]["unit-price"];
		    		if( $newQuant > 1) {

		    			$newQuant -= 1;
		    			$qtDiscount = 1;
		    			$newTotal = $newQuant * $unitPrice;
		    			$orderDiscount["items"][$index]["quantity"] = $newQuant;
		    			$sumTotal -= $orderDiscount["items"][$index]["total"];
		    			$orderDiscount["items"][$index]["total"]	= $newTotal;
		    			$sumTotal += $newTotal;

		    			$orderDiscount["items"][$i]["product-id"] 			= $orderDiscount["items"][$index]["product-id"];
		    			$orderDiscount["items"][$i]["category"] 			= $orderDiscount["items"][$index]["category"];
		    			$orderDiscount["items"][$i]["quantity"] 			= (string)$qtDiscount;
		    			$orderDiscount["items"][$i]["unit-price"] 			= $orderDiscount["items"][$index]["unit-price"];
		    			$orderDiscount["items"][$i]["unit-price-discount"] 	= (string)$value["price-discount"];
		    			$orderDiscount["items"][$i]["total"] 				= (string)($value["price-discount"] * $qtDiscount);
		    			$sumTotal += $value["price-discount"];
		    			$orderDiscount["items"][$i]["discount"]				= "Discount - 20% discount";
		    		} else {
		    			$orderDiscount["items"][$index]["price-discount"] 	= $value["price-discount"];
		    			$orderDiscount["items"][$index]["total"] 			= $value["price-discount"];
		    		}
		    	} else if($orderDiscount["items"][$index]["category"] == 2) {
					$orderDiscount["items"][$i]["product-id"] 	= $orderDiscount["items"][$index]["product-id"];
		    		$orderDiscount["items"][$i]["category"] 	= $orderDiscount["items"][$index]["category"];
		    		$orderDiscount["items"][$i]["quantity"] 	= (string)$value["amountFree"];
		    		$orderDiscount["items"][$i]["unit-price"]	= "0.00";
		    		$orderDiscount["items"][$i]["total"] 		= "0.00";
		    		$orderDiscount["items"][$i]["discount"]		= "Discount - 6th item free";
		    	}
		    }
		    $orderDiscount["total"] = (string)$sumTotal;
	    } else {
	    	$orderDiscount["total"] 			= $total;
	    	$orderDiscount["discount"]			= "Discount - 10% off";
	    	$total = $total - ($total * 0.10);
	    	$orderDiscount["total-discount"]	= (string)$total;
	    }
	    
	    $response = json_encode($orderDiscount);
	    return $response;
	    
});