# Vendra Order API

API Platform resources for the Vendra Order module: customers read their own
orders and convert their cart into one through a single checkout operation.

## Endpoints

| Method | URI | Description |
| --- | --- | --- |
| `GET` | `/api/sales/orders` | Orders placed by the authenticated customer |
| `GET` | `/api/sales/orders/{id}` | One order owned by the authenticated customer |
| `POST` | `/api/sales/checkout` | Convert the customer's cart into an order |

All operations require `auth:sanctum`.

## Checkout

```json
{
  "cartToken": "8f0e…",
  "currencyCode": "USD",
  "gateway": "bank-transfer",
  "paymentReference": "TRF-8891",
  "cardMessage": "Happy birthday."
}
```

Prices are never taken from the request. The processor reads the product name,
price and stock from `misaf/vendra-product` and snapshots them onto the order,
so a client cannot dictate what it pays. Delivery is not priced here:
`misaf/vendra-delivery` owns zones, slots and fees, so orders placed through
this endpoint carry a zero delivery amount until that module supplies one.

## Requirements

- PHP 8.4+
- `misaf/vendra-api`, `misaf/vendra-order`, `misaf/vendra-product`

## Testing

```bash
php artisan test --compact --testsuite=vendra-order-api
```

## License

MIT. See [LICENSE](LICENSE).
