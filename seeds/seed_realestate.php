<?php
/**
 * SEED FILE: Real Estate FAQs
 * 
 * Common questions for real estate agencies, property management, and rentals.
 * 
 * Usage: php seeds/seed_realestate.php
 */

require_once __DIR__ . '/../config.php';

echo "===========================================\n";
echo "  SEED: Real Estate FAQs\n";
echo "===========================================\n\n";

$faqData = [
    
    /**
     * Listings
     */
    [
        'name' => 'realestate_listings',
        'description' => 'Real Estate - Property listings',
        'phrases' => [
            'Show me available properties',
            'Houses for sale',
            'Apartments for rent',
            'Property listings',
            'New listings',
            'Search homes',
            'Available properties',
            'Homes for sale',
        ],
        'responses' => [
            'Browse our current listings on our website with filters for price, location, and property type. New properties are added daily. Sign up for alerts to receive notifications when properties matching your criteria become available.',
        ]
    ],
    
    /**
     * Viewing
     */
    [
        'name' => 'realestate_viewing',
        'description' => 'Real Estate - Schedule property viewing',
        'phrases' => [
            'I want to see a property',
            'Schedule a viewing',
            'Property tour',
            'Open house',
            'Visit the house',
            'Showing appointment',
            'Tour a home',
        ],
        'responses' => [
            'Schedule a viewing through our website or call (555) 789-0123. We offer in-person tours, virtual tours, and video walkthroughs. Open houses are held on weekends - check individual listings for times. Same-day showings available when possible.',
        ]
    ],
    
    /**
     * Buying Process
     */
    [
        'name' => 'realestate_buying',
        'description' => 'Real Estate - Home buying process',
        'phrases' => [
            'How do I buy a house?',
            'Home buying process',
            'Steps to buy a home',
            'First time home buyer',
            'Making an offer',
        ],
        'responses' => [
            'The buying process: 1) Get pre-approved for a mortgage, 2) Work with our agents to find properties, 3) Make an offer, 4) Home inspection and appraisal, 5) Close the deal. First-time buyers get dedicated support. Typical timeline: 30-60 days.',
        ]
    ],
    
    /**
     * Selling
     */
    [
        'name' => 'realestate_selling',
        'description' => 'Real Estate - Selling your property',
        'phrases' => [
            'I want to sell my house',
            'List my property',
            'How to sell my home',
            'Selling process',
            'Get my home appraised',
            'Home valuation',
        ],
        'responses' => [
            'Ready to sell? We offer free home valuations to determine your property\'s market value. Our marketing includes professional photography, virtual tours, and listing on major platforms. Average days on market: 21 days. Commission: competitive rates.',
        ]
    ],
    
    /**
     * Rental Application
     */
    [
        'name' => 'realestate_rental_apply',
        'description' => 'Real Estate - Rental application process',
        'phrases' => [
            'How do I apply for a rental?',
            'Rental application',
            'Apply for apartment',
            'Tenant application',
            'Rental requirements',
        ],
        'responses' => [
            'Apply online through our website. Requirements: Valid ID, proof of income (3x monthly rent), credit check ($35 fee), references. Applications are processed within 24-48 hours. Move-in costs typically include first month, last month, and security deposit.',
        ]
    ],
    
    /**
     * Mortgage
     */
    [
        'name' => 'realestate_mortgage',
        'description' => 'Real Estate - Mortgage and financing',
        'phrases' => [
            'Do you help with mortgages?',
            'Mortgage pre-approval',
            'Financing options',
            'Mortgage lenders',
            'Down payment requirements',
        ],
        'responses' => [
            'We partner with trusted mortgage lenders who offer competitive rates. Get pre-approved in as little as 24 hours. Down payment options range from 3% to 20%. We can connect you with lenders who specialize in first-time buyers, VA loans, and FHA loans.',
        ]
    ],
    
    /**
     * Property Management
     */
    [
        'name' => 'realestate_management',
        'description' => 'Real Estate - Property management services',
        'phrases' => [
            'Do you offer property management?',
            'Manage my rental property',
            'Property management fees',
            'Landlord services',
            'Tenant management',
        ],
        'responses' => [
            'Yes! Our property management services include tenant screening, rent collection, maintenance coordination, and financial reporting. Fees are typically 8-10% of monthly rent. We handle everything so you can enjoy passive income worry-free.',
        ]
    ],
    
    /**
     * Maintenance Requests
     */
    [
        'name' => 'realestate_maintenance',
        'description' => 'Real Estate - Maintenance requests for tenants',
        'phrases' => [
            'I need something fixed',
            'Maintenance request',
            'Report a problem',
            'Repair needed',
            'Something is broken',
            'Emergency repair',
        ],
        'responses' => [
            'Submit maintenance requests through our tenant portal or call (555) 789-0124. Routine requests are addressed within 48 hours. For emergencies (no heat, water leak, security issues), call our 24/7 emergency line at (555) 789-0199.',
        ]
    ],
    
    /**
     * Lease Information
     */
    [
        'name' => 'realestate_lease',
        'description' => 'Real Estate - Lease terms and questions',
        'phrases' => [
            'What are the lease terms?',
            'Lease length',
            'Can I break my lease?',
            'Renew my lease',
            'Lease agreement',
        ],
        'responses' => [
            'Standard lease terms are 12 months, with 6-month and month-to-month options at higher rates. Early termination requires 60 days notice and a fee equal to 2 months rent. Lease renewals are offered 60 days before expiration.',
        ]
    ],

];

// Insert the data
require_once __DIR__ . '/seed_helper.php';
insertSeedData($conn, $faqData, 'Real Estate');


