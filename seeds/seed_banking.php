<?php
/**
 * SEED FILE: Banking / Finance FAQs
 * 
 * Common questions for banks, credit unions, and financial services.
 * 
 * Usage: php seeds/seed_banking.php
 */

require_once __DIR__ . '/../config.php';

echo "===========================================\n";
echo "  SEED: Banking / Finance FAQs\n";
echo "===========================================\n\n";

$faqData = [
    
    /**
     * Account Balance
     */
    [
        'name' => 'banking_balance',
        'description' => 'Banking - Check account balance',
        'phrases' => [
            'How do I check my balance?',
            'Account balance',
            'What is my balance?',
            'Check balance',
            'Available funds',
            'Current balance',
            'How much money do I have?',
        ],
        'responses' => [
            'You can check your balance through: 1) Online banking at our website, 2) Mobile banking app, 3) ATMs, 4) Phone banking at (555) 999-0000, 5) Visiting any branch. Real-time balance updates are available 24/7 through digital channels.',
        ]
    ],
    
    /**
     * Transfer
     */
    [
        'name' => 'banking_transfer',
        'description' => 'Banking - Money transfers',
        'phrases' => [
            'How do I transfer money?',
            'Send money',
            'Wire transfer',
            'Transfer to another account',
            'International transfer',
            'Transfer fees',
            'Move money',
        ],
        'responses' => [
            'You can transfer money via online banking, mobile app, or in-branch. Domestic transfers are free between our accounts, $25 for external banks. International wire transfers are $45. Same-day transfers available for a fee. Transfer limits apply based on account type.',
        ]
    ],
    
    /**
     * Open Account
     */
    [
        'name' => 'banking_open_account',
        'description' => 'Banking - Open a new account',
        'phrases' => [
            'How do I open an account?',
            'New account',
            'Open checking account',
            'Open savings account',
            'Account requirements',
            'Start an account',
        ],
        'responses' => [
            'You can open an account online in minutes or visit any branch. Requirements: Valid ID, Social Security number, initial deposit (starting at $25). Checking accounts have no monthly fee with direct deposit. Compare our account options on our website.',
        ]
    ],
    
    /**
     * Lost Card
     */
    [
        'name' => 'banking_lost_card',
        'description' => 'Banking - Lost or stolen card',
        'phrases' => [
            'I lost my card',
            'Card stolen',
            'Report lost card',
            'Cancel my card',
            'Card missing',
            'Debit card lost',
            'Credit card stolen',
        ],
        'responses' => [
            'Report a lost or stolen card immediately! Call our 24/7 hotline at (555) 888-0000 or freeze your card instantly in our mobile app. We will cancel the card and send a replacement within 3-5 business days. Rush delivery available.',
        ]
    ],
    
    /**
     * Loan Information
     */
    [
        'name' => 'banking_loans',
        'description' => 'Banking - Loan products and applications',
        'phrases' => [
            'What loans do you offer?',
            'Apply for a loan',
            'Personal loan',
            'Mortgage rates',
            'Auto loan',
            'Loan application',
            'Interest rates',
        ],
        'responses' => [
            'We offer personal loans, auto loans, mortgages, and home equity lines of credit. Current rates start at 6.99% APR for qualified borrowers. Apply online for pre-approval in minutes with no impact to your credit score.',
        ]
    ],
    
    /**
     * ATM Locations
     */
    [
        'name' => 'banking_atm',
        'description' => 'Banking - ATM locations and fees',
        'phrases' => [
            'Where are your ATMs?',
            'ATM near me',
            'ATM fees',
            'Free ATM',
            'Find ATM',
            'ATM locations',
        ],
        'responses' => [
            'Use our ATM locator on our website or mobile app to find the nearest ATM. Our network includes over 30,000 fee-free ATMs nationwide. We also reimburse up to $10/month in out-of-network ATM fees for premium accounts.',
        ]
    ],
    
    /**
     * Online Banking
     */
    [
        'name' => 'banking_online',
        'description' => 'Banking - Online and mobile banking',
        'phrases' => [
            'How do I access online banking?',
            'Mobile banking app',
            'Online banking login',
            'Set up online banking',
            'Mobile deposit',
        ],
        'responses' => [
            'Enroll in online banking at our website using your account number. Download our mobile app from the App Store or Google Play. Features include mobile check deposit, bill pay, transfers, and account alerts. Two-factor authentication keeps your account secure.',
        ]
    ],
    
    /**
     * Fraud
     */
    [
        'name' => 'banking_fraud',
        'description' => 'Banking - Fraud and suspicious activity',
        'phrases' => [
            'I see a suspicious charge',
            'Fraud on my account',
            'Unauthorized transaction',
            'Report fraud',
            'Account hacked',
            'Identity theft',
        ],
        'responses' => [
            'If you suspect fraud, contact us immediately at (555) 888-0000 (24/7). We will freeze your account, investigate the charges, and issue provisional credit within 10 days. Never share your PIN, passwords, or one-time codes with anyone.',
        ]
    ],
    
    /**
     * Direct Deposit
     */
    [
        'name' => 'banking_direct_deposit',
        'description' => 'Banking - Direct deposit setup',
        'phrases' => [
            'How do I set up direct deposit?',
            'Direct deposit form',
            'Routing number',
            'Account number for direct deposit',
            'Payroll deposit',
        ],
        'responses' => [
            'To set up direct deposit, provide your employer with: Routing Number (123456789) and your Account Number (found in online banking or on your checks). You can download a pre-filled direct deposit form from our website.',
        ]
    ],

];

// Insert the data
require_once __DIR__ . '/seed_helper.php';
insertSeedData($conn, $faqData, 'Banking/Finance');


