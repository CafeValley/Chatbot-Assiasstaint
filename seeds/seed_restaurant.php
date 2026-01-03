<?php
/**
 * SEED FILE: Restaurant / Food Service FAQs
 * 
 * Common questions for restaurants, cafes, and food service businesses.
 * 
 * Usage: php seeds/seed_restaurant.php
 */

require_once __DIR__ . '/../config.php';

echo "===========================================\n";
echo "  SEED: Restaurant / Food Service FAQs\n";
echo "===========================================\n\n";

$faqData = [
    
    /**
     * Reservations
     */
    [
        'name' => 'restaurant_reservation',
        'description' => 'Restaurant - Table reservations',
        'phrases' => [
            'I want to make a reservation',
            'Book a table',
            'Table for tonight',
            'Reserve a table',
            'Reservation for 4',
            'Do you take reservations?',
            'Book dinner',
            'Make a booking',
        ],
        'responses' => [
            'We accept reservations! Book online at our website, call (555) 456-7890, or use OpenTable. For parties of 8 or more, please call directly. We recommend booking 2-3 days in advance for weekends. Walk-ins welcome based on availability.',
        ]
    ],
    
    /**
     * Menu
     */
    [
        'name' => 'restaurant_menu',
        'description' => 'Restaurant - Menu and dietary options',
        'phrases' => [
            'Can I see the menu?',
            'What is on the menu?',
            'Do you have vegetarian options?',
            'Gluten-free menu',
            'Vegan options',
            'Allergen information',
            'Menu items',
            'What do you serve?',
        ],
        'responses' => [
            'Our full menu is available on our website. We offer vegetarian, vegan, and gluten-free options clearly marked on the menu. Please inform your server of any allergies. Our kitchen can accommodate most dietary restrictions with advance notice.',
        ]
    ],
    
    /**
     * Delivery
     */
    [
        'name' => 'restaurant_delivery',
        'description' => 'Restaurant - Delivery and takeout',
        'phrases' => [
            'Do you deliver?',
            'Delivery options',
            'Order for pickup',
            'Takeout available?',
            'UberEats',
            'DoorDash',
            'Food delivery',
            'Take away',
        ],
        'responses' => [
            'Yes! We offer delivery through DoorDash, UberEats, and Grubhub. For direct orders, call us or order through our website for 10% off. Pickup orders ready in 20-30 minutes. Free delivery within 3 miles for orders over $30.',
        ]
    ],
    
    /**
     * Hours
     */
    [
        'name' => 'restaurant_hours',
        'description' => 'Restaurant - Operating hours',
        'phrases' => [
            'What are your hours?',
            'When do you open?',
            'When do you close?',
            'Restaurant hours',
            'Are you open now?',
            'Sunday hours',
            'Late night hours',
        ],
        'responses' => [
            'We are open Tuesday-Thursday 11AM-9PM, Friday-Saturday 11AM-11PM, and Sunday 10AM-8PM for brunch and dinner. We are closed on Mondays. Happy hour is 3PM-6PM Tuesday-Friday.',
        ]
    ],
    
    /**
     * Private Events
     */
    [
        'name' => 'restaurant_events',
        'description' => 'Restaurant - Private events and catering',
        'phrases' => [
            'Do you host private events?',
            'Private dining',
            'Book for a party',
            'Catering services',
            'Event space',
            'Birthday party',
            'Corporate event',
        ],
        'responses' => [
            'Yes! Our private dining room accommodates up to 40 guests. We offer customized menus for special events including birthdays, corporate dinners, and celebrations. We also provide off-site catering. Contact our events team for details.',
        ]
    ],
    
    /**
     * Parking
     */
    [
        'name' => 'restaurant_parking',
        'description' => 'Restaurant - Parking information',
        'phrases' => [
            'Is there parking?',
            'Where do I park?',
            'Parking available?',
            'Valet parking',
            'Street parking',
        ],
        'responses' => [
            'We offer complimentary valet parking on Friday and Saturday evenings. There is a public parking lot behind the restaurant ($2/hour) and metered street parking. We validate parking for the adjacent garage with purchases over $50.',
        ]
    ],
    
    /**
     * Kids Menu
     */
    [
        'name' => 'restaurant_kids',
        'description' => 'Restaurant - Kids menu and family dining',
        'phrases' => [
            'Do you have a kids menu?',
            'Children menu',
            'Family friendly?',
            'High chairs available?',
            'Kids eat free?',
        ],
        'responses' => [
            'Yes, we have a dedicated kids menu for children 12 and under with favorites like pasta, chicken fingers, and mini burgers. High chairs and booster seats are available. Kids eat free on Tuesdays with an adult entree purchase.',
        ]
    ],
    
    /**
     * Gift Cards
     */
    [
        'name' => 'restaurant_giftcard',
        'description' => 'Restaurant - Gift cards',
        'phrases' => [
            'Do you sell gift cards?',
            'Restaurant gift card',
            'Buy gift certificate',
            'Gift card for restaurant',
        ],
        'responses' => [
            'Yes! Gift cards are available in any amount starting at $25. Purchase in-restaurant or online. Physical cards can be mailed or picked up. E-gift cards are delivered instantly via email. Gift cards never expire.',
        ]
    ],
    
    /**
     * Dress Code
     */
    [
        'name' => 'restaurant_dress',
        'description' => 'Restaurant - Dress code',
        'phrases' => [
            'What is the dress code?',
            'Dress code?',
            'What should I wear?',
            'Smart casual?',
            'Formal attire required?',
        ],
        'responses' => [
            'Our dress code is smart casual. We ask guests to avoid athletic wear, flip-flops, and beach attire. Collared shirts are appreciated but not required. For special occasions, feel free to dress up!',
        ]
    ],

];

// Insert the data
require_once __DIR__ . '/seed_helper.php';
insertSeedData($conn, $faqData, 'Restaurant/Food Service');


