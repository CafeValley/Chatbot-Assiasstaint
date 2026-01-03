<?php
/**
 * SEED FILE: Technical Support FAQs
 * 
 * Common questions for IT support, software, and technical troubleshooting.
 * 
 * Usage: php seeds/seed_techsupport.php
 */

require_once __DIR__ . '/../config.php';

echo "===========================================\n";
echo "  SEED: Technical Support FAQs\n";
echo "===========================================\n\n";

$faqData = [
    
    /**
     * Password Reset
     */
    [
        'name' => 'tech_password',
        'description' => 'Tech Support - Password reset',
        'phrases' => [
            'I forgot my password',
            'Reset password',
            'Cannot log in',
            'Locked out of account',
            'Password not working',
            'Change my password',
            'Password reset',
        ],
        'responses' => [
            'To reset your password: 1) Go to the login page, 2) Click "Forgot Password", 3) Enter your email address, 4) Check your email for reset link (check spam folder), 5) Create a new password. The link expires in 24 hours.',
        ]
    ],
    
    /**
     * App Issues
     */
    [
        'name' => 'tech_app',
        'description' => 'Tech Support - App troubleshooting',
        'phrases' => [
            'App is not working',
            'App crashes',
            'How to update the app',
            'App error',
            'Mobile app problem',
            'Download the app',
            'App freezing',
        ],
        'responses' => [
            'For app issues: 1) Ensure you have the latest version from App Store/Google Play, 2) Try closing and reopening the app, 3) Clear app cache in settings, 4) Restart your device. If problems persist, contact support with your device model and OS version.',
        ]
    ],
    
    /**
     * Account Issues
     */
    [
        'name' => 'tech_account',
        'description' => 'Tech Support - Account problems',
        'phrases' => [
            'Account issues',
            'Cannot access account',
            'Account locked',
            'Account suspended',
            'Verify my account',
            'Account problem',
        ],
        'responses' => [
            'If you cannot access your account: Check for a verification email, ensure you are using the correct email address, or try the password reset. For suspended accounts, contact support with your account email. We will investigate and restore access if appropriate.',
        ]
    ],
    
    /**
     * Connectivity
     */
    [
        'name' => 'tech_connectivity',
        'description' => 'Tech Support - Connection issues',
        'phrases' => [
            'Connection problems',
            'Cannot connect',
            'Internet not working',
            'Service down',
            'Offline error',
            'No connection',
        ],
        'responses' => [
            'For connection issues: 1) Check your internet connection, 2) Refresh the page or restart the app, 3) Check our status page for outages, 4) Try disabling VPN or firewall temporarily. If problems continue, report the issue with your location and ISP.',
        ]
    ],
    
    /**
     * Slow Performance
     */
    [
        'name' => 'tech_performance',
        'description' => 'Tech Support - Slow performance',
        'phrases' => [
            'System is slow',
            'App running slow',
            'Performance issues',
            'Loading takes forever',
            'Very slow',
            'Lagging',
        ],
        'responses' => [
            'To improve performance: 1) Close unused tabs and applications, 2) Clear browser cache and cookies, 3) Check your internet speed, 4) Update to the latest version. Minimum requirements: 4GB RAM, modern browser. Contact support if issues persist.',
        ]
    ],
    
    /**
     * Error Messages
     */
    [
        'name' => 'tech_errors',
        'description' => 'Tech Support - Error messages',
        'phrases' => [
            'Getting an error',
            'Error message',
            'What does this error mean?',
            'Error code',
            'Something went wrong',
        ],
        'responses' => [
            'Please provide the exact error message or code. Common fixes: Error 404 - page not found, try homepage. Error 500 - server issue, try again later. Error 403 - permission denied, re-login. For other errors, contact support with a screenshot.',
        ]
    ],
    
    /**
     * Data Sync
     */
    [
        'name' => 'tech_sync',
        'description' => 'Tech Support - Data synchronization',
        'phrases' => [
            'Data not syncing',
            'Sync issues',
            'Changes not saving',
            'Data missing',
            'Not updating',
        ],
        'responses' => [
            'For sync issues: 1) Check your internet connection, 2) Pull down to refresh or click sync button, 3) Log out and log back in, 4) Ensure you have the latest app version. Data syncs every 5 minutes automatically. Contact support if data is missing.',
        ]
    ],
    
    /**
     * Installation
     */
    [
        'name' => 'tech_install',
        'description' => 'Tech Support - Installation help',
        'phrases' => [
            'How do I install?',
            'Installation guide',
            'Setup instructions',
            'Install the software',
            'Getting started',
        ],
        'responses' => [
            'Download our software from our website. System requirements: Windows 10+, macOS 11+, or Linux. Run the installer and follow the prompts. For mobile, download from App Store (iOS) or Google Play (Android). See our setup guide for detailed instructions.',
        ]
    ],
    
    /**
     * Browser Support
     */
    [
        'name' => 'tech_browser',
        'description' => 'Tech Support - Browser compatibility',
        'phrases' => [
            'Which browsers are supported?',
            'Browser compatibility',
            'Works on Chrome?',
            'Safari support',
            'Browser requirements',
        ],
        'responses' => [
            'We support the latest versions of: Chrome (recommended), Firefox, Safari, and Edge. Internet Explorer is not supported. For best experience, keep your browser updated. Enable JavaScript and cookies. Mobile browsers are fully supported.',
        ]
    ],
    
    /**
     * Two-Factor Auth
     */
    [
        'name' => 'tech_2fa',
        'description' => 'Tech Support - Two-factor authentication',
        'phrases' => [
            'Two-factor authentication',
            '2FA not working',
            'Lost my authenticator',
            'Verification code not received',
            'Enable 2FA',
        ],
        'responses' => [
            'For 2FA issues: If you lost access to your authenticator app, use backup codes provided during setup. No backup codes? Contact support with ID verification to reset 2FA. To enable 2FA, go to Security Settings. We support Google Authenticator, Authy, and SMS.',
        ]
    ],

];

// Insert the data
require_once __DIR__ . '/seed_helper.php';
insertSeedData($conn, $faqData, 'Technical Support');


