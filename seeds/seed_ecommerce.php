<?php
/**
 * SEED FILE: E-commerce / Online Store FAQs
 * 
 * Common questions for online shops, shipping, returns, and orders.
 * 
 * Usage: php seeds/seed_ecommerce.php
 */

require_once __DIR__ . '/../config.php';

echo "===========================================\n";
echo "  SEED: E-commerce / Online Store FAQs\n";
echo "===========================================\n\n";

$faqData = [
    
    /**
     * Shipping
     */
    [
        'name' => 'ecommerce_shipping',
        'description' => 'E-commerce - Shipping information',
        'phrases' => [
            'How much is shipping?',
            'Shipping costs',
            'Do you offer free shipping?',
            'How long does shipping take?',
            'Shipping options',
            'Expedited shipping',
            'Delivery time',
            'Shipping rates',
        ],
        'responses' => [
            'We offer FREE standard shipping on orders over $50. Standard shipping (5-7 business days) is $5.99. Express shipping (2-3 days) is $12.99. Overnight shipping available for $24.99. International shipping calculated at checkout.',
        ]
    ],
    
    /**
     * Returns
     */
    [
        'name' => 'ecommerce_returns',
        'description' => 'E-commerce - Return policy',
        'phrases' => [
            'What is your return policy?',
            'Can I return this?',
            'How do I return an item?',
            'Return shipping',
            'Refund policy',
            '30 day return',
            'Return an order',
            'Exchange policy',
        ],
        'responses' => [
            'We offer a 30-day hassle-free return policy. Items must be unused and in original packaging. To initiate a return, log into your account or contact support. Return shipping is free for defective items. Refunds processed within 5-7 business days.',
        ]
    ],
    
    /**
     * Order Tracking
     */
    [
        'name' => 'ecommerce_tracking',
        'description' => 'E-commerce - Order tracking',
        'phrases' => [
            'Where is my order?',
            'Track my order',
            'Order status',
            'Tracking number',
            'Has my order shipped?',
            'When will my order arrive?',
            'Package tracking',
        ],
        'responses' => [
            'You can track your order by logging into your account and viewing order history, or use the tracking link sent to your email. Orders typically ship within 1-2 business days. If you need assistance, provide your order number and we will help.',
        ]
    ],
    
    /**
     * Account
     */
    [
        'name' => 'ecommerce_account',
        'description' => 'E-commerce - Account management',
        'phrases' => [
            'How do I create an account?',
            'Reset my password',
            'Forgot password',
            'Update my address',
            'Change email address',
            'Delete my account',
            'Account settings',
        ],
        'responses' => [
            'To create an account, click "Sign Up" on our website. To reset your password, click "Forgot Password" on the login page. You can update your address and email in Account Settings. To delete your account, please contact our support team.',
        ]
    ],
    
    /**
     * Stock
     */
    [
        'name' => 'ecommerce_stock',
        'description' => 'E-commerce - Stock availability',
        'phrases' => [
            'Is this item in stock?',
            'Out of stock',
            'When will you restock?',
            'Notify me when available',
            'Backorder',
            'Pre-order',
            'Check availability',
        ],
        'responses' => [
            'Stock availability is shown on each product page. For out-of-stock items, click "Notify Me" to receive an email when restocked. Most items are restocked within 2-3 weeks. Pre-orders ship as soon as inventory arrives.',
        ]
    ],
    
    /**
     * Payment Issues
     */
    [
        'name' => 'ecommerce_payment_issue',
        'description' => 'E-commerce - Payment and checkout issues',
        'phrases' => [
            'My payment was declined',
            'Payment failed',
            'Checkout not working',
            'Credit card error',
            'Payment issue',
            'Cannot complete order',
        ],
        'responses' => [
            'If your payment was declined: 1) Verify card details are correct, 2) Ensure billing address matches card, 3) Check with your bank for blocks, 4) Try a different payment method. Contact support if issues persist - we are here to help!',
        ]
    ],
    
    /**
     * Order Cancellation
     */
    [
        'name' => 'ecommerce_cancel',
        'description' => 'E-commerce - Order cancellation',
        'phrases' => [
            'Cancel my order',
            'How to cancel order?',
            'Order cancellation',
            'Can I cancel?',
            'Stop my order',
        ],
        'responses' => [
            'Orders can be cancelled within 1 hour of placement through your account or by contacting us immediately. Once an order is processing or shipped, it cannot be cancelled, but you can return it when received.',
        ]
    ],
    
    /**
     * Gift Cards
     */
    [
        'name' => 'ecommerce_giftcard',
        'description' => 'E-commerce - Gift cards',
        'phrases' => [
            'Do you sell gift cards?',
            'Gift card',
            'Buy a gift card',
            'Gift certificate',
            'Redeem gift card',
        ],
        'responses' => [
            'Yes! Digital gift cards are available in $25, $50, $100, and $200 denominations. They are delivered instantly via email and never expire. To redeem, enter the gift card code at checkout.',
        ]
    ],
    
    /**
     * Promo Codes
     */
    [
        'name' => 'ecommerce_promo',
        'description' => 'E-commerce - Promo and discount codes',
        'phrases' => [
            'How do I use a promo code?',
            'Apply discount code',
            'Coupon not working',
            'Where to enter code?',
            'Promo code help',
        ],
        'responses' => [
            'Enter your promo code at checkout in the "Discount Code" field and click Apply. Codes are case-sensitive. Only one code can be used per order. If your code is not working, check the expiration date and minimum order requirements.',
        ]
    ],
    
    /**
     * International Orders
     */
    [
        'name' => 'ecommerce_international',
        'description' => 'E-commerce - International shipping',
        'phrases' => [
            'Do you ship internationally?',
            'International shipping',
            'Ship to Canada',
            'Ship to UK',
            'Ship to Europe',
            'Customs fees',
        ],
        'responses' => [
            'Yes, we ship to over 50 countries! International shipping rates are calculated at checkout based on weight and destination. Delivery takes 7-21 business days. Customers are responsible for any customs duties or import taxes.',
        ]
    ],

];

// Insert the data
require_once __DIR__ . '/seed_helper.php';
insertSeedData($conn, $faqData, 'E-commerce/Online Store');

