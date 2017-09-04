<?php
/**
* 
*/
class items {
	
	/**
	* return the index of the product inside the array
	* 
	* @var string $prodId
	* @var array $items
	*
	*/
	function getItem($prodId, $items) {

		$key = array_search($prodId, array_column($items, 'product-id'));
	
		return $key;
	}

}

