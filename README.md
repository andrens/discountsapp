# Discountsapp
----
Returns json with the proper discount and applies it to the order

* **URL**

  /discount

* **Method:**

  `POST`

* **Request Body:**
````json
{
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
````


* **Success Response:**

  * **Code:** 200 <br />
    **Content:** `{"id":"3","customer-id":"3","items":{"0":{"product-id":"A101","category":"1","quantity":1,"unit-price":"9.75","total":9.75},"1":{"product-id":"A102","category":"1","quantity":"1","unit-price":"49.50","total":"49.50"},"2":{"product-id":"B102","category":"2","quantity":"50","unit-price":"10.00","total":"500.00"},"4":{"product-id":"A101","category":"1","quantity":"1","unit-price":"9.75","unit-price-discount":"7.8","total":"7.8","discount":"Discount - 20% discount"},"5":{"product-id":"B102","category":"2","quantity":"10","unit-price":"0.00","total":"0.00","discount":"Discount - 6th item free"}},"total":"567.05"}`

* **Sample Call:**

```javascript
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
```
