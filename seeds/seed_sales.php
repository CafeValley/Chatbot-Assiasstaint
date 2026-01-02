<?php
/**
 * SEED FILE: Sales / Business FAQs
 * 
 * Common questions for sales teams, B2B, and business inquiries.
 * 
 * Usage: php seeds/seed_sales.php
 */

require_once __DIR__ . '/../config.php';

echo "===========================================\n";
echo "  SEED: Sales / Business FAQs\n";
echo "===========================================\n\n";

$faqData = [
    
    /**
     * Pricing
     */
    [
        'name' => 'sales_pricing',
        'description' => 'Sales - Product pricing and quotes',
        'phrases' => [
            'What are your prices?',
            'How much does it cost?',
            'Can I get a quote?',
            'Pricing information',
            'Price list',
            'How much for bulk orders?',
            'Wholesale pricing',
            'Request a quote',
            'Cost estimate',
        ],
        'responses' => [
            'Our pricing varies based on the product and quantity. For a personalized quote, please contact our sales team at sales@company.com or call (555) 234-5678. We offer competitive bulk discounts for orders over 100 units.',
        ]
    ],
    
    /**
     * Discounts
     */
    [
        'name' => 'sales_discounts',
        'description' => 'Sales - Discounts and promotions',
        'phrases' => [
            'Do you have any discounts?',
            'Current promotions',
            'Any sales going on?',
            'Coupon codes',
            'Special offers',
            'First-time buyer discount',
            'Promo code',
            'Discount available?',
        ],
        'responses' => [
            'Yes! We regularly offer promotions. Subscribe to our newsletter for exclusive discounts. First-time customers get 10% off with code WELCOME10. Check our website for current seasonal sales.',
        ]
    ],
    
    /**
     * Payment Methods
     */
    [
        'name' => 'sales_payment',
        'description' => 'Sales - Payment methods and terms',
        'phrases' => [
            'What payment methods do you accept?',
            'Can I pay by credit card?',
            'Do you accept PayPal?',
            'Payment options',
            'Can I pay in installments?',
            'Net 30 terms',
            'Payment plans',
            'Credit terms',
        ],
        'responses' => [
            'We accept all major credit cards (Visa, MasterCard, American Express), PayPal, bank transfers, and checks. For business clients, we offer Net 30 payment terms upon credit approval. Installment plans are available for orders over $500.',
        ]
    ],
    
    /**
     * Minimum Order
     */
    [
        'name' => 'sales_minimum_order',
        'description' => 'Sales - Minimum order quantities',
        'phrases' => [
            'What is the minimum order?',
            'Minimum order quantity',
            'MOQ',
            'Can I order just one?',
            'Small orders',
            'Minimum purchase',
        ],
        'responses' => [
            'Our standard minimum order is $50 for retail customers. For wholesale accounts, the minimum order is $500 or 50 units, whichever is greater. Sample orders are available for evaluation.',
        ]
    ],
    
    /**
     * Bulk Orders
     */
    [
        'name' => 'sales_bulk',
        'description' => 'Sales - Bulk and wholesale orders',
        'phrases' => [
            'Bulk order discount',
            'Wholesale orders',
            'Large quantity pricing',
            'Corporate orders',
            'Volume discount',
            'Bulk pricing',
        ],
        'responses' => [
            'We offer tiered pricing for bulk orders: 10% off for 50+ units, 15% off for 100+ units, and 25% off for 500+ units. Contact our wholesale team for custom pricing on larger orders.',
        ]
    ],
    
    /**
     * Product Information
     */
    [
        'name' => 'sales_product_info',
        'description' => 'Sales - Product specifications and details',
        'phrases' => [
            'Tell me about your products',
            'Product specifications',
            'Product details',
            'What do you sell?',
            'Product catalog',
            'Product information',
        ],
        'responses' => [
            'We offer a wide range of high-quality products. Visit our website to browse our full catalog with detailed specifications, images, and customer reviews. Need help finding the right product? Contact our sales team for personalized recommendations.',
        ]
    ],
    
    /**
     * Custom Orders
     */
    [
        'name' => 'sales_custom',
        'description' => 'Sales - Custom and personalized orders',
        'phrases' => [
            'Do you do custom orders?',
            'Customization options',
            'Personalized products',
            'Custom manufacturing',
            'Bespoke orders',
            'Made to order',
        ],
        'responses' => [
            'Yes, we offer customization services! Custom orders typically require a minimum of 100 units and 4-6 weeks lead time. Contact our custom orders team with your specifications for a detailed quote.',
        ]
    ],
    
    /**
     * Warranty
     */
    [
        'name' => 'sales_warranty',
        'description' => 'Sales - Product warranty information',
        'phrases' => [
            'What is the warranty?',
            'Warranty policy',
            'Guarantee period',
            'Product guarantee',
            'Warranty coverage',
            'How long is the warranty?',
        ],
        'responses' => [
            'All our products come with a standard 1-year warranty against manufacturing defects. Extended warranty options are available at checkout. Warranty does not cover damage from misuse or normal wear and tear.',
        ]
    ],

];

// Insert the data
require_once __DIR__ . '/seed_helper.php';
insertSeedData($conn, $faqData, 'Sales/Business');

