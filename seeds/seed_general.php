<?php
/**
 * SEED FILE: General Customer Service FAQs
 * 
 * Universal questions for any business - contact info, hours, location, greetings.
 * 
 * Usage: php seeds/seed_general.php
 */

require_once __DIR__ . '/../config.php';

echo "===========================================\n";
echo "  SEED: General Customer Service FAQs\n";
echo "===========================================\n\n";

$faqData = [
    
    // =========================================
    // CONTACT & BUSINESS INFO
    // =========================================
    
    /**
     * Contact Us
     */
    [
        'name' => 'general_contact',
        'description' => 'General - Contact information',
        'phrases' => [
            'How do I contact you?',
            'Contact information',
            'Phone number',
            'Email address',
            'Customer service number',
            'Support contact',
            'Get in touch',
            'Contact details',
        ],
        'responses' => [
            'You can reach us by: Phone: (555) 123-4567 (Mon-Fri 9AM-6PM), Email: support@company.com, Live Chat: Available on our website 24/7, Mail: 123 Business Street, City, State 12345.',
        ]
    ],
    
    /**
     * Business Hours
     */
    [
        'name' => 'general_hours',
        'description' => 'General - Business hours',
        'phrases' => [
            'What are your hours?',
            'Business hours',
            'When are you open?',
            'Opening times',
            'Are you open today?',
            'Holiday hours',
            'Hours of operation',
        ],
        'responses' => [
            'Our business hours are Monday through Friday, 9:00 AM to 6:00 PM. Saturday hours are 10:00 AM to 4:00 PM. We are closed on Sundays. Holiday hours may vary - check our website for updates.',
        ]
    ],
    
    /**
     * Location
     */
    [
        'name' => 'general_location',
        'description' => 'General - Location and directions',
        'phrases' => [
            'Where are you located?',
            'Your address',
            'Directions to your office',
            'How do I get there?',
            'Nearest location',
            'Store locator',
            'Find a store',
        ],
        'responses' => [
            'Our main office is located at 123 Business Street, City, State 12345. We are near the intersection of Main St and Commerce Ave. Free parking is available in the back lot. Use our store locator on our website to find the nearest branch.',
        ]
    ],
    
    /**
     * Complaint
     */
    [
        'name' => 'general_complaint',
        'description' => 'General - Filing a complaint',
        'phrases' => [
            'I want to make a complaint',
            'File a complaint',
            'I am not satisfied',
            'Bad experience',
            'Poor service',
            'Speak to someone about my issue',
            'Unhappy with service',
        ],
        'responses' => [
            'We are sorry to hear about your experience. Please share the details of your concern at feedback@company.com or call our customer care line. All complaints are reviewed by management within 24 hours. Your satisfaction is our priority.',
        ]
    ],
    
    /**
     * Feedback
     */
    [
        'name' => 'general_feedback',
        'description' => 'General - Providing feedback',
        'phrases' => [
            'I want to give feedback',
            'Leave a review',
            'Customer feedback',
            'Share my experience',
            'Suggest an improvement',
            'Compliment',
        ],
        'responses' => [
            'We love hearing from our customers! Share feedback at feedback@company.com or leave a review on Google, Yelp, or Facebook. Your input helps us improve. We read every message and share positive feedback with our team.',
        ]
    ],
    
    // =========================================
    // GREETINGS & COMMON PHRASES
    // =========================================
    
    /**
     * Hello
     */
    [
        'name' => 'greeting_hello',
        'description' => 'Greeting - Hello and welcome',
        'phrases' => [
            'Hello',
            'Hi',
            'Hey',
            'Good morning',
            'Good afternoon',
            'Good evening',
            'Howdy',
            'Hi there',
            'Hey there',
            'Greetings',
        ],
        'responses' => [
            'Hello! Welcome! How can I help you today?',
            'Hi there! What can I assist you with?',
            'Hello! I am here to help. What would you like to know?',
        ]
    ],
    
    /**
     * Goodbye
     */
    [
        'name' => 'greeting_goodbye',
        'description' => 'Greeting - Goodbye and farewell',
        'phrases' => [
            'Goodbye',
            'Bye',
            'See you later',
            'Thanks, bye',
            'That is all',
            'I am done',
            'Exit',
            'Bye bye',
            'Talk to you later',
        ],
        'responses' => [
            'Goodbye! Thank you for chatting with us. Have a great day!',
            'Thank you for visiting! Feel free to come back anytime.',
            'Bye! If you have more questions later, I am always here to help.',
        ]
    ],
    
    /**
     * Thanks
     */
    [
        'name' => 'greeting_thanks',
        'description' => 'Greeting - Thank you responses',
        'phrases' => [
            'Thank you',
            'Thanks',
            'Thanks a lot',
            'I appreciate it',
            'That was helpful',
            'Great, thanks',
            'Much appreciated',
            'Thank you so much',
        ],
        'responses' => [
            'You are welcome! Is there anything else I can help you with?',
            'Happy to help! Let me know if you need anything else.',
            'Glad I could assist! Feel free to ask more questions.',
        ]
    ],
    
    /**
     * Help
     */
    [
        'name' => 'general_help',
        'description' => 'General - Request for help',
        'phrases' => [
            'Help',
            'I need help',
            'Can you help me?',
            'Assist me',
            'I have a question',
            'Support',
            'Need assistance',
            'Help me please',
        ],
        'responses' => [
            'Of course! I am here to help. Please tell me what you need assistance with, and I will do my best to help you.',
            'I would be happy to help! What would you like to know?',
        ]
    ],
    
    /**
     * What can you do
     */
    [
        'name' => 'general_capabilities',
        'description' => 'General - Bot capabilities',
        'phrases' => [
            'What can you do?',
            'How can you help me?',
            'What are your capabilities?',
            'What do you do?',
            'Tell me what you can do',
        ],
        'responses' => [
            'I can help you with: answering frequently asked questions, providing business information, guiding you through our services, and connecting you with the right department. Just ask your question!',
        ]
    ],
    
    /**
     * Who are you
     */
    [
        'name' => 'general_identity',
        'description' => 'General - Bot identity',
        'phrases' => [
            'Who are you?',
            'What are you?',
            'Are you a robot?',
            'Are you human?',
            'Are you AI?',
            'Bot or human?',
        ],
        'responses' => [
            'I am an AI-powered assistant here to help answer your questions and guide you through our services. While I am not human, I am designed to provide helpful and accurate information 24/7!',
        ]
    ],
    
    /**
     * Live Agent
     */
    [
        'name' => 'general_live_agent',
        'description' => 'General - Request for human agent',
        'phrases' => [
            'Talk to a human',
            'Speak to a person',
            'Live agent',
            'Real person',
            'Human support',
            'Talk to someone',
            'Connect me to a representative',
        ],
        'responses' => [
            'I understand you would like to speak with a human. You can call us at (555) 123-4567 during business hours, or start a live chat on our website. Our team is happy to assist you personally.',
        ]
    ],

];

// Insert the data
require_once __DIR__ . '/seed_helper.php';
insertSeedData($conn, $faqData, 'General Customer Service');


