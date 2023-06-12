Feature:
  Order feature

  Scenario: I can create an order without voucher code
    When I send a "POST" request to "/orders" with body:
        """
        {
          "amount": 100
        }
        """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON nodes should contain:
      | data.subtotal | 100 |
      | data.discount_total | 0      |
      | data.grand_total    | 100    |
    And the JSON node "data.purchase_date" should not be null

  Scenario: I can create an order with voucher code
    Given Created entities of "\App\Entity\Voucher" with
      | code | amount | isActive | validUntil |
      | 1234 | 10    | true     | 2024-01-01 23:59:59 |
    When I send a "POST" request to "/orders" with body:
        """
        {
          "amount": 100,
          "voucher_code": 1234
        }
        """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON nodes should contain:
      | data.subtotal | 100 |
      | data.discount_total | 10     |
      | data.grand_total    | 90    |
    And the JSON node "data.purchase_date" should not be null

  Scenario: when I create an order with a voucher the voucher should not be
    usable anymore.
    Given Created entities of "\App\Entity\Voucher" with
      | code | amount | isActive | validUntil |
      | 1234 | 10    | true     | 2024-01-01 23:59:59 |
    When I send a "POST" request to "/orders" with body:
        """
        {
          "amount": 100,
          "voucher_code": 1234
        }
        """
    And I send a "POST" request to "/orders" with body:
        """
        {
          "amount": 100,
          "voucher_code": 1234
        }
        """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON node "data.message" should not be null
    And the JSON node "data.message" should be equal to "This voucher code is already used."

  Scenario: when I create an order with a voucher in which the
    amount of the voucher is greater than the order amount then i should get 400
    Given Created entities of "\App\Entity\Voucher" with
      | code | amount | isActive | validUntil |
      | 1234 | 200    | true     | 2024-01-01 23:59:59 |
    When I send a "POST" request to "/orders" with body:
        """
        {
          "amount": 100,
          "voucher_code": 1234
        }
        """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON node "data.message" should not be null


  Scenario: when I create an order with an invalid voucher I should get 400
    Given Created entities of "\App\Entity\Voucher" with
      | code | amount | isActive | validUntil |
      | 1234 | 10    | false     | 2024-01-01 23:59:59 |
    When I send a "POST" request to "/orders" with body:
        """
        {
          "amount": 100,
          "voucher_code": 1234
        }
        """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON node "data.message" should not be null

  Scenario: a list of orders can be shown.
    Given Created entities of "\App\Entity\Order" with
      | subtotal | discountTotal | grandTotal |
      | 100     | 0             | 100         |
      | 200     | 0             | 200         |
    When I send a "GET" request to "/orders"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "data.items" should have 1 element
    And the JSON node "data.links" should not be null
    And the JSON node "data.links.next" should not be null
    And the JSON node "data.links.self" should not be null
    And the JSON node "data.links.last" should not be null

  Scenario: a list of orders can be shown.
    Given Created entities of "\App\Entity\Order" with
      | subtotal | discountTotal | grandTotal |
      | 100     | 0             | 100         |
      | 200     | 0             | 200         |
    When I send a "GET" request to "/orders?page=2"
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "data.items" should have 1 element
    And the JSON node "data.links" should not be null
    And the JSON node "data.links.prev" should be equal to "/orders?page=1"
    And the JSON node "data.links.next" should be null
