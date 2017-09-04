<?php
/**
* 
*/
class rules {

	/**
	* verify the item to set which discount will be
	* 
	* @var string $prodId
	* @var int $category
	* @var int $quantity
	* @var int $price
	* @var int $cheapProdId
	* @var int $cheap
	* @var int $sumItems
	*
	*/
	function verifyItem($prodId, $category, $quantity, $price, $cheapProdId, $cheap, $sumItems) {

			$response["product-id"] = $prodId;
			$response["quantity"]	= $quantity;

			if($category == 1) {
				$sumItems += $quantity;
				if ($cheap == 0) {
					$cheap = $price;
					$cheapProdId = $prodId;
					$cheapQuantity = $quantity;
				} else if($cheap > $price) {
					$cheap = $price;
					$cheapProdId = $prodId;
					$cheapQuantity = $quantity;
				}
				if($sumItems >= 2) {
					$discount = $cheap - ($cheap * 0.20);
					$response["price-discount"] = $discount;
				} 

			}

			if($category == 2) {
				if($quantity >= 5) {
					$response["amountFree"] = floor($quantity / 5);
					$cheapProdId = $prodId;
				} else {
					return false;
				}
			}

			$response["cheap"] 				= $cheap;
			$response["cheap-product-id"] 	= $cheapProdId;
			$response["sumItems"] 			= $sumItems;
			$response["price"]				= $price;
			$response["discount"]			= "promo_" . $category;

			return $response;
		
	}

}

