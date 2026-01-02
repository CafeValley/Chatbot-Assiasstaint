# Database Seed Files

Pre-built FAQ data for various industries. Each file contains common questions and answers that can be loaded into the chatbot.

## Quick Start

```bash
# Run all seeds (clears existing data first)
php seeds/seed_all.php

# Keep existing data and add new seeds
php seeds/seed_all.php --keep

# Clear all intent data
php seeds/seed_clear.php
```

## Individual Industry Seeds

Run only the industries you need:

| File | Industry | Intents | Description |
|------|----------|---------|-------------|
| `seed_general.php` | General | 12 | Contact info, hours, greetings, help |
| `seed_medical.php` | Medical/Healthcare | 8 | Appointments, insurance, prescriptions |
| `seed_sales.php` | Sales/Business | 8 | Pricing, discounts, payment, warranty |
| `seed_management.php` | Management/HR | 8 | Jobs, benefits, company info |
| `seed_stone.php` | Stone/Construction | 9 | Types, pricing, installation, care |
| `seed_ecommerce.php` | E-commerce | 10 | Shipping, returns, tracking, accounts |
| `seed_restaurant.php` | Restaurant | 9 | Reservations, menu, delivery, events |
| `seed_banking.php` | Banking/Finance | 9 | Balance, transfers, loans, fraud |
| `seed_realestate.php` | Real Estate | 9 | Listings, viewings, rentals, mortgage |
| `seed_techsupport.php` | Tech Support | 10 | Password, app issues, connectivity |

### Example: Load Only Medical and E-commerce

```bash
# First clear existing data
php seeds/seed_clear.php

# Then load specific industries
php seeds/seed_medical.php
php seeds/seed_ecommerce.php
```

## Customizing Seeds

Each seed file follows the same structure:

```php
$faqData = [
    [
        'name' => 'unique_intent_name',
        'description' => 'Description for admin panel',
        'phrases' => [
            'Question 1?',
            'Question 2?',
            'Question 3?',
        ],
        'responses' => [
            'Answer to the questions.',
        ]
    ],
    // More intents...
];
```

### Adding Custom Intents

1. Copy an existing seed file
2. Modify the `$faqData` array with your questions and answers
3. Run your custom seed file

## File Structure

```
seeds/
├── README.md           # This file
├── seed_helper.php     # Shared functions (required)
├── seed_all.php        # Master seed (runs all)
├── seed_clear.php      # Clears all data
├── seed_general.php    # General customer service
├── seed_medical.php    # Medical/Healthcare
├── seed_sales.php      # Sales/Business
├── seed_management.php # Management/HR
├── seed_stone.php      # Stone/Construction
├── seed_ecommerce.php  # E-commerce/Online Store
├── seed_restaurant.php # Restaurant/Food Service
├── seed_banking.php    # Banking/Finance
├── seed_realestate.php # Real Estate
└── seed_techsupport.php# Technical Support
```

## Totals (All Seeds)

- **92 Intents**
- **596 Training Phrases**
- **99 Responses**

