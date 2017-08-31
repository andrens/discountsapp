<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->get('/api/product/{id}', function (Request $request, Response $response) {
	$id = $request->getAttribute('id');

	require('products.php');

	foreach ($prodArray as $key => $value) {
		if($id == $value->id) { $category = $value->category; }
	}

	//return $category;

});

$app->post('/api/discount', function (Request $request, Response $response) {

    $id 		= $request->getParam('id');
    $customerId = $request->getParam('customer-id');
    $items 		= $request->getParam('items');
    $total		= $request->getParam('total');

    $discount 		= "";	
    $sumProds		= 0;
    $cheapest		= 0;
    $c 				= 0;
    $finalTotal		= 0;

    $orderDiscount["id"] 			= $id;
    $orderDiscount["customer-id"] 	= $customerId;
    $orderDiscount["items"] 		= $items;

    if($total < 1000) {

	    require('products.php');
	    //run all items
	    foreach ($items as $idx => $item) {
	    	//run an item
	    	foreach ($item as $key => $value) {
	    		if($key == "product-id") {
	    			//run to search the product
					foreach ($prodArray as $prod) {
						if($value == $prod->id) { 
							$category = $prod->category; 
						}
					}
					$prodId = $value;
	    		}
	    		if($key == "quantity") {
	    			$quant = $value;
	    			if ($category == 2) {
						//sixth product is free
						if($quant >=5 ) {
							$discount = "6th free";
							$amountFree = floor($quant / 5);
						}
					} else if ($category == 1) {
						//cheapest gets 20%
						$sumProds += $quant;
						if($sumProds >= 2) {
							$discount = "cheap -20%";
						} else {
							$discount = "";
						}
					}
	    		}
	    		if($key == "unit-price") {
	    			$unitPrice = $value;
	    			if($category == 1 && $cheapest == 0) {
	    				$cheapest = $unitPrice;
	    				$cheapestProdId = $prodId;
	    				$cheapestIndex = $c;
	    			} else if($category == 1 && $sumProds >=2 ) {
	    				if($cheapest > $unitPrice) {
	    					$cheapest = $unitPrice;
	    					$cheapestProdId = $prodId;
	    					$cheapestIndex = $c;
	    				}
	    			}
	    		}
	    		if($key == "total") {
	    			$totalItem = $value;
	    		}
	    	}
 
	    	$orderDiscount["items"][$c]["product-id"] = $prodId;
			$orderDiscount["items"][$c]["category"] = $category;
			$orderDiscount["items"][$c]["quantity"] = $quant;
			$orderDiscount["items"][$c]["unit-price"] = $unitPrice;
			$orderDiscount["items"][$c]["total"] = $totalItem;
			if($discount == "6th free") {
				$c++;
				$orderDiscount["items"][$c]["product-id"] = $prodId;
				$orderDiscount["items"][$c]["category"] = $category;
				$orderDiscount["items"][$c]["quantity"] = $amountFree;
				$orderDiscount["items"][$c]["unit-price"] = "0.00";
				$orderDiscount["items"][$c]["total"] = "0.00";
				$orderDiscount["items"][$c]["discount"] = $discount;
	    	}
	    	$c++;
	    	$finalTotal += $totalItem;
	    }
	    if($sumProds >= 2) {
			if($orderDiscount["items"][$cheapestIndex]["quantity"] >= 2){
				//verifies if the cheapest one has more than 1 unit to take out one
				$unitPrice = $orderDiscount["items"][$cheapestIndex]["unit-price"];
				$quantity = $orderDiscount["items"][$cheapestIndex]["quantity"] - 1;
				$totalItem = $unitPrice * $quantity;
				$orderDiscount["items"][$cheapestIndex]["quantity"] = $quantity;
				$orderDiscount["items"][$cheapestIndex]["total"] = $totalItem;

				$finalTotal -= $unitPrice;
				
				//find the last position in the array
				end($orderDiscount["items"]);
				$finalKey = key($orderDiscount["items"]) + 1;

				//add a new item with the 20% discount
				$orderDiscount["items"][$finalKey]["product-id"] = $prodId;
				$orderDiscount["items"][$finalKey]["category"] = $category;
				$orderDiscount["items"][$cheapestIndex]["quantity"] = 1;
				$discountValue = $orderDiscount["items"][$cheapestIndex]["unit-price"];
				$discountValue = $discountValue - ($discountValue * 0.20);
				$orderDiscount["items"][$finalKey]["unit-price"] = $discountValue;
				$orderDiscount["items"][$finalKey]["total"] = $discountValue;
				$orderDiscount["items"][$finalKey]["discount"] = "20% off on the cheapest";		
				$finalTotal += $discountValue;
			} else {
				$discountValue = $orderDiscount["items"][$cheapestIndex]["unit-price"];
				$discountValue = $discountValue - ($discountValue * 0.20);
				$orderDiscount["items"][$cheapestIndex]["unit-price"] = $discountValue;
				$orderDiscount["items"][$cheapestIndex]["total"] = $discountValue;
				$orderDiscount["items"][$cheapestIndex]["discount"] = "20% off on the cheapest";
				$finalTotal += $discountValue;
			}	
		}
	} else {

		$orderDiscount["discount"] = '10% off';
		$finalTotal = $total - ($total * 0.10);

	}
	
	$orderDiscount["total"] = $finalTotal;
	$response = json_encode($orderDiscount);
    return $response;
});