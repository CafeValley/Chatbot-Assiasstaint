<?php
/**
 * SEED FILE: Management / HR FAQs
 * 
 * Common questions for HR, careers, and company management.
 * 
 * Usage: php seeds/seed_management.php
 */

require_once __DIR__ . '/../config.php';

echo "===========================================\n";
echo "  SEED: Management / HR FAQs\n";
echo "===========================================\n\n";

$faqData = [
    
    /**
     * Job Openings
     */
    [
        'name' => 'hr_jobs',
        'description' => 'HR - Job openings and careers',
        'phrases' => [
            'Are you hiring?',
            'Job openings',
            'Career opportunities',
            'How do I apply for a job?',
            'Current vacancies',
            'Send my resume',
            'Employment opportunities',
            'Open positions',
            'Work for you',
        ],
        'responses' => [
            'We regularly have job openings across various departments. Visit our Careers page to see current positions. You can apply online by submitting your resume and cover letter. We review all applications within 5-7 business days.',
        ]
    ],
    
    /**
     * Company Info
     */
    [
        'name' => 'management_company_info',
        'description' => 'Management - Company information',
        'phrases' => [
            'Tell me about your company',
            'Company history',
            'Who founded the company?',
            'How long have you been in business?',
            'Company background',
            'About your company',
            'Company story',
        ],
        'responses' => [
            'We were founded in 2010 with a mission to provide excellent products and services. Over the years, we have grown to serve thousands of satisfied customers. Our team of dedicated professionals is committed to quality and innovation.',
        ]
    ],
    
    /**
     * Contact Management
     */
    [
        'name' => 'management_contact',
        'description' => 'Management - Contact management and executives',
        'phrases' => [
            'How do I contact management?',
            'Speak to a manager',
            'Talk to supervisor',
            'Escalate my issue',
            'Contact CEO',
            'Executive contact',
            'Management email',
        ],
        'responses' => [
            'To reach our management team, please email management@company.com with your concern. For urgent matters, call our main line and ask to speak with a duty manager. We aim to respond to all management inquiries within 24 hours.',
        ]
    ],
    
    /**
     * Employee Benefits
     */
    [
        'name' => 'hr_benefits',
        'description' => 'HR - Employee benefits',
        'phrases' => [
            'What benefits do you offer?',
            'Employee benefits',
            'Health insurance for employees',
            'Vacation policy',
            'PTO policy',
            '401k',
            'Benefits package',
            'Perks',
        ],
        'responses' => [
            'We offer comprehensive benefits including health, dental, and vision insurance, 401(k) with company match, paid time off (15 days starting), paid holidays, professional development opportunities, and flexible work arrangements.',
        ]
    ],
    
    /**
     * Remote Work
     */
    [
        'name' => 'hr_remote',
        'description' => 'HR - Remote work policy',
        'phrases' => [
            'Do you allow remote work?',
            'Work from home policy',
            'Remote positions',
            'Hybrid work',
            'Can I work remotely?',
            'Telecommuting',
        ],
        'responses' => [
            'We offer flexible work arrangements including remote and hybrid options for eligible positions. Each role has specific requirements, so please check the job posting or ask during the interview process about remote work eligibility.',
        ]
    ],
    
    /**
     * Interview Process
     */
    [
        'name' => 'hr_interview',
        'description' => 'HR - Interview process',
        'phrases' => [
            'What is the interview process?',
            'How many interview rounds?',
            'Interview stages',
            'What to expect in interview?',
            'Interview timeline',
        ],
        'responses' => [
            'Our interview process typically includes: 1) Phone screening (15-30 min), 2) Video interview with hiring manager (45 min), 3) Skills assessment or case study, 4) Final interview with team. The entire process usually takes 2-3 weeks.',
        ]
    ],
    
    /**
     * Company Culture
     */
    [
        'name' => 'management_culture',
        'description' => 'Management - Company culture and values',
        'phrases' => [
            'What is your company culture?',
            'Work environment',
            'Company values',
            'Team culture',
            'What is it like to work there?',
        ],
        'responses' => [
            'We pride ourselves on a collaborative, inclusive work environment. Our core values are integrity, innovation, and customer focus. We host regular team events, support professional growth, and maintain a healthy work-life balance.',
        ]
    ],
    
    /**
     * Internships
     */
    [
        'name' => 'hr_internship',
        'description' => 'HR - Internship programs',
        'phrases' => [
            'Do you have internships?',
            'Internship program',
            'Summer internship',
            'Student opportunities',
            'Intern positions',
        ],
        'responses' => [
            'Yes! We offer paid internship programs in summer and fall. Internships are available in various departments and typically last 10-12 weeks. Apply through our Careers page by March for summer and July for fall positions.',
        ]
    ],

];

// Insert the data
require_once __DIR__ . '/seed_helper.php';
insertSeedData($conn, $faqData, 'Management/HR');


