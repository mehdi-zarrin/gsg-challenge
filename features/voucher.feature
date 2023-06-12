Feature:
    voucher CRUD

    Scenario: a valid voucher request should be persisted
        When I send a "POST" request to "/vouchers" with body:
        """
        {
          "valid_until": "2023-06-03 23:59:59",
          "amount": 10
        }
        """
        Then the response status code should be 201
        And the response should be in JSON
        And the JSON nodes should contain:
            | data.valid_until | 2023-06-03 23:59:59 |
            | data.amount      | 10                  |
        And the JSON node "data.code" should not be null

    Scenario: an invalid voucher create request should result in validation errors.
        When I send a "POST" request to "/vouchers" with body:
        """
        {
          "amount": 10
        }
        """
        Then the response status code should be 400
        And the response should be in JSON
        And the JSON node "validationErrors" should exist
        And the JSON node "validationErrors[0].message" should not be null
        And the JSON node "validationErrors[0].field" should not be null

    Scenario: when I create 4 vouchers which only two of them are valid then only valid ones
        should be displayed.
        Given Created entities of "\App\Entity\Voucher" with
            | code | amount | isActive | validUntil |
            | 1234 | 100    | true     | 2024-01-01 23:59:59 |
            | 4567 | 100    | true     | 2024-01-01 23:59:59 |
            | 7890 | 100    | false     | 2024-01-01 23:59:59 |
            | 1112 | 100    | true     | 2000-01-01 23:59:59 |
        When I send a "GET" request to "/vouchers?state=active"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node 'data.items' should have 2 elements

    Scenario: when I create 4 vouchers which only two of them are valid then only invalid ones
    should be displayed.
        Given Created entities of "\App\Entity\Voucher" with
            | code | amount | isActive | validUntil |
            | 1234 | 100    | true     | 2024-01-01 23:59:59 |
            | 4567 | 100    | true     | 2024-01-01 23:59:59 |
            | 7890 | 100    | false     | 2024-01-01 23:59:59 |
            | 1112 | 100    | true     | 2000-01-01 23:59:59 |
        When I send a "GET" request to "/vouchers?state=inactive"
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node 'data.items' should have 2 elements

    Scenario: when I edit a voucher with a payload changes should be reflected.
        Given Auto Increment is rest for "\App\Entity\Voucher"
        Given Created entities of "\App\Entity\Voucher" with
            | code | amount | isActive | validUntil |
            | 1234 | 100    | true     | 2024-01-01 23:59:59 |
        When I send a "PUT" request to "/vouchers/1" with body:
        """
        {
          "amount": 101,
          "valid_until": "2025-01-01 23:59:59"
        }
        """
        Then the response status code should be 200
        And the response should be in JSON
        And the JSON node 'data.amount' should be equal to '101'
        And the JSON node 'data.valid_until' should be equal to '2025-01-01 23:59:59'

    Scenario: inactive voucher could not be edited.
        Given Auto Increment is rest for "\App\Entity\Voucher"
        Given Created entities of "\App\Entity\Voucher" with
            | code | amount | isActive | validUntil |
            | 1234 | 100    | false     | 2024-01-01 23:59:59 |
        When I send a "PUT" request to "/vouchers/1" with body:
        """
        {
          "amount": 101,
          "valid_until": "2025-01-01 23:59:59"
        }
        """
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON node 'data.message' should not be null

    Scenario: expired voucher could not be edited.
        Given Auto Increment is rest for "\App\Entity\Voucher"
        Given Created entities of "\App\Entity\Voucher" with
            | code | amount | isActive | validUntil |
            | 1234 | 100    | true     | 2000-01-01 23:59:59 |
        When I send a "PUT" request to "/vouchers/1" with body:
        """
        {
          "amount": 101,
          "valid_until": "2025-01-01 23:59:59"
        }
        """
        Then the response status code should be 404
        And the response should be in JSON
        And the JSON node 'data.message' should not be null

    Scenario: a voucher can de deleted.
        Given Auto Increment is rest for "\App\Entity\Voucher"
        Given Created entities of "\App\Entity\Voucher" with
            | code | amount | isActive | validUntil |
            | 1234 | 100    | true     | 2000-01-01 23:59:59 |
        When I send a "DELETE" request to "/vouchers/1"
        Then the response status code should be 204