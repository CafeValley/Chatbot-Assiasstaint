<?php
/**
 * SEED FILE: Stone / Construction Materials FAQs
 * 
 * Common questions for stone suppliers, countertop businesses, and construction materials.
 * 
 * Usage: php seeds/seed_stone.php
 */

require_once __DIR__ . '/../config.php';

echo "===========================================\n";
echo "  SEED: Stone / Construction FAQs\n";
echo "===========================================\n\n";

$faqData = [
    
    /**
     * Types Available
     */
    [
        'name' => 'stone_types',
        'description' => 'Stone - Types of stone available',
        'phrases' => [
            'What types of stone do you have?',
            'Stone varieties',
            'Do you have granite?',
            'Marble options',
            'Types of countertops',
            'Natural stone selection',
            'Quartz options',
            'Stone materials',
        ],
        'responses' => [
            'We carry a wide selection of natural and engineered stones including Granite, Marble, Quartz, Quartzite, Soapstone, Travertine, and Limestone. Visit our showroom to see over 500 slabs in stock. Custom orders available for specialty stones.',
        ]
    ],
    
    /**
     * Pricing
     */
    [
        'name' => 'stone_pricing',
        'description' => 'Stone - Stone pricing per square foot',
        'phrases' => [
            'How much is granite per square foot?',
            'Stone prices',
            'Countertop cost',
            'Marble pricing',
            'Quartz price',
            'Cost of installation',
            'Price per square foot',
            'How much for countertops?',
        ],
        'responses' => [
            'Stone prices range from $40-$200 per square foot installed, depending on the material. Granite starts at $45/sq ft, Quartz at $55/sq ft, and premium Marble at $75/sq ft. Price includes fabrication, edge profile, and professional installation.',
        ]
    ],
    
    /**
     * Installation
     */
    [
        'name' => 'stone_installation',
        'description' => 'Stone - Installation process and timeline',
        'phrases' => [
            'How long does installation take?',
            'Installation process',
            'When can you install?',
            'Do you install countertops?',
            'Installation timeline',
            'How does installation work?',
        ],
        'responses' => [
            'Our typical timeline is: Template measurement (1 day) → Fabrication (5-7 days) → Installation (1 day). Most kitchen countertop projects are completed within 2 weeks from template. Rush orders available for additional fee.',
        ]
    ],
    
    /**
     * Care and Maintenance
     */
    [
        'name' => 'stone_care',
        'description' => 'Stone - Care and maintenance instructions',
        'phrases' => [
            'How do I clean granite?',
            'Stone maintenance',
            'Do I need to seal the countertop?',
            'Caring for marble',
            'Stone cleaning products',
            'How to maintain countertops?',
            'Sealing stone',
        ],
        'responses' => [
            'For daily cleaning, use warm water and mild dish soap. Avoid acidic cleaners on marble and limestone. Granite should be sealed annually. We provide a care kit with every installation and offer professional resealing services.',
        ]
    ],
    
    /**
     * Showroom
     */
    [
        'name' => 'stone_showroom',
        'description' => 'Stone - Showroom visits and hours',
        'phrases' => [
            'Can I visit your showroom?',
            'Showroom hours',
            'Where is your warehouse?',
            'See slabs in person',
            'Showroom appointment',
            'Visit showroom',
        ],
        'responses' => [
            'Our showroom is open Monday-Friday 9AM-5PM and Saturday 10AM-3PM. Walk-ins welcome, but appointments are recommended for dedicated assistance. Address: 123 Stone Way, Industrial District. Free parking available.',
        ]
    ],
    
    /**
     * Edge Profiles
     */
    [
        'name' => 'stone_edges',
        'description' => 'Stone - Edge profile options',
        'phrases' => [
            'What edge profiles do you offer?',
            'Edge options',
            'Countertop edges',
            'Bullnose edge',
            'Beveled edge',
            'Edge styles',
        ],
        'responses' => [
            'We offer various edge profiles: Eased, Beveled, Bullnose, Half Bullnose, Ogee, and Waterfall. Standard edges are included in the price. Premium edges like Ogee and Waterfall are an additional $15-25 per linear foot.',
        ]
    ],
    
    /**
     * Samples
     */
    [
        'name' => 'stone_samples',
        'description' => 'Stone - Stone samples',
        'phrases' => [
            'Can I get samples?',
            'Stone samples',
            'Sample pieces',
            'Take samples home',
            'Sample cost',
        ],
        'responses' => [
            'Yes! We provide small stone samples free of charge so you can see how the material looks in your home lighting. Stop by our showroom to pick up samples or request them online. Large sample pieces are available for a $25 deposit.',
        ]
    ],
    
    /**
     * Sink Cutouts
     */
    [
        'name' => 'stone_sink',
        'description' => 'Stone - Sink and fixture cutouts',
        'phrases' => [
            'Do you do sink cutouts?',
            'Sink installation',
            'Undermount sink',
            'Faucet holes',
            'Cutouts included?',
        ],
        'responses' => [
            'Yes, all necessary cutouts are included in our installation price. This includes sink cutout (undermount or drop-in), faucet holes, cooktop cutout if needed, and any outlet cutouts. We can work with your existing fixtures or help you select new ones.',
        ]
    ],
    
    /**
     * Remnants
     */
    [
        'name' => 'stone_remnants',
        'description' => 'Stone - Remnant pieces for small projects',
        'phrases' => [
            'Do you sell remnants?',
            'Small pieces',
            'Remnant stone',
            'Leftover pieces',
            'Discount stone',
        ],
        'responses' => [
            'Yes! We have a remnant yard with discounted stone pieces perfect for small projects like bathroom vanities, tabletops, or backsplashes. Remnants are priced 30-50% below full slab prices. Visit our yard to browse available pieces.',
        ]
    ],

];

// Insert the data
require_once __DIR__ . '/seed_helper.php';
insertSeedData($conn, $faqData, 'Stone/Construction');

