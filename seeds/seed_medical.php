<?php
/**
 * SEED FILE: Medical / Healthcare FAQs
 * 
 * Common questions for clinics, hospitals, and healthcare providers.
 * 
 * Usage: php seeds/seed_medical.php
 */

require_once __DIR__ . '/../config.php';

echo "===========================================\n";
echo "  SEED: Medical / Healthcare FAQs\n";
echo "===========================================\n\n";

$faqData = [
    
    /**
     * Appointment Scheduling
     */
    [
        'name' => 'medical_appointment',
        'description' => 'Medical - Appointment scheduling and booking',
        'phrases' => [
            'How do I book an appointment?',
            'I need to schedule a doctor visit',
            'Can I make an appointment online?',
            'How to schedule a consultation?',
            'I want to see a doctor',
            'Book appointment',
            'Schedule visit',
            'Make an appointment',
            'Doctor appointment',
        ],
        'responses' => [
            'You can book an appointment by calling our reception at (555) 123-4567, or use our online booking system on our website. We offer same-day appointments for urgent cases.',
        ]
    ],
    
    /**
     * Operating Hours
     */
    [
        'name' => 'medical_hours',
        'description' => 'Medical - Clinic operating hours',
        'phrases' => [
            'What are your clinic hours?',
            'When is the clinic open?',
            'Operating hours',
            'What time do you open?',
            'What time do you close?',
            'Are you open on weekends?',
            'Sunday hours',
            'Saturday hours',
            'When can I come in?',
        ],
        'responses' => [
            'Our clinic is open Monday to Friday from 8:00 AM to 6:00 PM, and Saturday from 9:00 AM to 2:00 PM. We are closed on Sundays and public holidays.',
        ]
    ],
    
    /**
     * Insurance
     */
    [
        'name' => 'medical_insurance',
        'description' => 'Medical - Insurance and payment information',
        'phrases' => [
            'Do you accept insurance?',
            'What insurance do you take?',
            'Is my insurance accepted?',
            'Insurance coverage',
            'Do you take Medicare?',
            'Payment options',
            'Can I pay with insurance?',
            'Health insurance',
            'Medicaid accepted?',
        ],
        'responses' => [
            'We accept most major insurance providers including Blue Cross, Aetna, Cigna, United Healthcare, and Medicare. Please bring your insurance card to your appointment. For uninsured patients, we offer flexible payment plans.',
        ]
    ],
    
    /**
     * Prescriptions
     */
    [
        'name' => 'medical_prescriptions',
        'description' => 'Medical - Prescription refills and medications',
        'phrases' => [
            'How do I refill my prescription?',
            'I need a prescription refill',
            'Can you send my prescription to the pharmacy?',
            'Medication refill',
            'Renew my prescription',
            'Prescription renewal',
            'Get my meds',
        ],
        'responses' => [
            'For prescription refills, please call our office at least 48 hours in advance. You can also request refills through our patient portal. We will send the prescription directly to your preferred pharmacy.',
        ]
    ],
    
    /**
     * Emergency
     */
    [
        'name' => 'medical_emergency',
        'description' => 'Medical - Emergency situations',
        'phrases' => [
            'Is this an emergency?',
            'I have an emergency',
            'Urgent medical help',
            'Emergency contact',
            'What to do in emergency?',
            'Medical emergency',
            'Need urgent care',
        ],
        'responses' => [
            'If you are experiencing a medical emergency, please call 911 immediately or go to the nearest emergency room. For urgent but non-emergency situations, call our 24/7 nurse hotline at (555) 999-8888.',
        ]
    ],
    
    /**
     * Test Results
     */
    [
        'name' => 'medical_results',
        'description' => 'Medical - Lab and test results',
        'phrases' => [
            'How do I get my test results?',
            'Lab results',
            'When will my results be ready?',
            'Check my test results',
            'Blood test results',
            'X-ray results',
        ],
        'responses' => [
            'Test results are typically available within 2-5 business days. You can view your results through our patient portal, or we will call you if any results require immediate attention. For questions about specific results, please schedule a follow-up appointment.',
        ]
    ],
    
    /**
     * New Patients
     */
    [
        'name' => 'medical_new_patient',
        'description' => 'Medical - New patient information',
        'phrases' => [
            'I am a new patient',
            'First time patient',
            'New patient registration',
            'What do new patients need?',
            'First visit requirements',
            'New patient forms',
        ],
        'responses' => [
            'Welcome! New patients should arrive 15 minutes early to complete paperwork. Please bring: valid ID, insurance card, list of current medications, and any relevant medical records. You can also download and complete new patient forms from our website.',
        ]
    ],
    
    /**
     * Telehealth
     */
    [
        'name' => 'medical_telehealth',
        'description' => 'Medical - Virtual/telehealth appointments',
        'phrases' => [
            'Do you offer telehealth?',
            'Virtual appointment',
            'Video visit',
            'Online consultation',
            'Telemedicine',
            'Remote appointment',
        ],
        'responses' => [
            'Yes, we offer telehealth appointments for eligible conditions. Book a virtual visit through our patient portal or call us. You will receive a secure video link before your appointment. Telehealth visits are covered by most insurance plans.',
        ]
    ],

];

// Insert the data
require_once __DIR__ . '/seed_helper.php';
insertSeedData($conn, $faqData, 'Medical/Healthcare');

