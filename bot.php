<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Chatbot Assistant</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="jquery-3.5.1.min.js"></script>
<style>
        :root {
            --ocean-deep: #0c1929;
            --ocean-dark: #0f2744;
            --ocean-mid: #1a4971;
            --ocean-light: #2d6a9f;
            --ocean-bright: #4a9eda;
            --teal-primary: #14b8a6;
            --teal-light: #5eead4;
            --aqua: #22d3ee;
            --foam: #e0f7fa;
            --white: #ffffff;
            --text-primary: #f0f9ff;
            --text-secondary: rgba(224, 247, 250, 0.7);
            --shadow-color: rgba(20, 184, 166, 0.25);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(180deg, var(--ocean-deep) 0%, var(--ocean-dark) 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Background decoration */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(ellipse at 20% 20%, rgba(74, 158, 218, 0.15) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 80%, rgba(20, 184, 166, 0.1) 0%, transparent 50%);
            z-index: 0;
            pointer-events: none;
        }

        .chat-container {
            width: 100%;
            max-width: 480px;
            height: 85vh;
            max-height: 700px;
            background: rgba(15, 39, 68, 0.6);
            backdrop-filter: blur(20px);
            border-radius: 28px;
            border: 1px solid rgba(94, 234, 212, 0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
            z-index: 1;
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(94, 234, 212, 0.1) inset;
        }

        /* Header */
        .chat-header {
            background: linear-gradient(135deg, var(--ocean-mid), var(--ocean-dark));
            padding: 20px 24px;
            border-bottom: 1px solid rgba(94, 234, 212, 0.15);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .bot-avatar {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--teal-primary), var(--aqua));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px var(--shadow-color);
        }

        .bot-avatar svg {
            width: 26px;
            height: 26px;
            fill: white;
        }

        .header-info h1 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 2px;
        }

        .header-info .status {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            color: var(--teal-light);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: var(--teal-primary);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.6; transform: scale(1.2); }
        }

        .header-actions {
            margin-left: auto;
            display: flex;
            gap: 8px;
        }

        .header-btn {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(94, 234, 212, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .header-btn svg {
            width: 20px;
            height: 20px;
            fill: var(--text-secondary);
            transition: fill 0.3s;
        }

        .header-btn:hover {
            background: rgba(20, 184, 166, 0.2);
            border-color: var(--teal-primary);
        }

        .header-btn:hover svg {
            fill: var(--teal-light);
        }

        /* Help Panel */
.help-toggle {
            background: rgba(20, 184, 166, 0.15);
            color: var(--teal-light);
            padding: 10px 16px;
    cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
    text-align: center;
            border-bottom: 1px solid rgba(94, 234, 212, 0.1);
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .help-toggle:hover {
            background: rgba(20, 184, 166, 0.25);
        }

.help-panel {
    display: none;
            background: rgba(12, 25, 41, 0.9);
            padding: 20px;
            font-size: 0.85rem;
            max-height: 280px;
            overflow-y: auto;
            border-bottom: 1px solid rgba(94, 234, 212, 0.1);
        }

        .help-panel h3 {
            color: var(--teal-light);
            margin-bottom: 14px;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .help-panel ul {
            list-style: none;
            padding: 0;
        }

        .help-panel li {
            margin-bottom: 10px;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .help-panel code {
            background: rgba(20, 184, 166, 0.15);
            color: var(--teal-light);
            padding: 3px 8px;
            border-radius: 6px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
        }

        /* Messages Area */
        .messages {
            flex: 1;
    overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .messages::-webkit-scrollbar {
            width: 6px;
        }

        .messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .messages::-webkit-scrollbar-thumb {
            background: rgba(94, 234, 212, 0.3);
            border-radius: 3px;
        }

        /* Message Bubbles */
        .message {
            display: flex;
            gap: 12px;
            max-width: 85%;
            animation: messageIn 0.3s ease;
        }

        @keyframes messageIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .message.bot {
            align-self: flex-start;
        }

        .message.user {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bot .message-avatar {
            background: linear-gradient(135deg, var(--teal-primary), var(--ocean-light));
        }

        .user .message-avatar {
            background: linear-gradient(135deg, var(--ocean-bright), var(--ocean-mid));
        }

        .message-avatar svg {
            width: 18px;
            height: 18px;
            fill: white;
        }

        .message-content {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .message-bubble {
            padding: 14px 18px;
            border-radius: 20px;
            font-size: 0.95rem;
            line-height: 1.5;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .bot .message-bubble {
            background: linear-gradient(135deg, rgba(26, 73, 113, 0.8), rgba(15, 39, 68, 0.9));
            color: var(--text-primary);
            border: 1px solid rgba(94, 234, 212, 0.15);
            border-top-left-radius: 6px;
        }

        .user .message-bubble {
            background: linear-gradient(135deg, var(--teal-primary), var(--ocean-light));
            color: white;
            border-top-right-radius: 6px;
        }

        .message-time {
            font-size: 0.7rem;
            color: var(--text-secondary);
            padding: 0 4px;
        }

        .user .message-time {
    text-align: right;
}

        /* Confidence badge */
        .confidence {
            font-size: 0.7rem;
            color: var(--teal-light);
            margin-top: 4px;
        }

        /* Reaction buttons */
        .reactions {
            display: flex;
            gap: 8px;
            margin-top: 8px;
        }

        .reaction-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(94, 234, 212, 0.2);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            color: var(--text-secondary);
        }

        .reaction-btn:hover {
            background: rgba(20, 184, 166, 0.15);
            border-color: var(--teal-primary);
        }

        .reaction-btn.active.thumbs-up {
            background: rgba(20, 184, 166, 0.25);
            border-color: var(--teal-primary);
            color: var(--teal-light);
        }

        .reaction-btn.active.thumbs-down {
            background: rgba(239, 68, 68, 0.2);
            border-color: #ef4444;
            color: #fca5a5;
        }

        /* Typing indicator */
.typing-indicator {
    display: none;
            padding: 8px 16px;
            color: var(--text-secondary);
            font-size: 0.85rem;
    font-style: italic;
}

.typing-indicator.active {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .typing-dots {
            display: flex;
            gap: 4px;
        }

        .typing-dots span {
            width: 6px;
            height: 6px;
            background: var(--teal-primary);
            border-radius: 50%;
            animation: typingBounce 1.4s infinite;
        }

        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typingBounce {
            0%, 60%, 100% { transform: translateY(0); }
            30% { transform: translateY(-6px); }
        }

        /* Input Area */
        .input-area {
            padding: 20px 24px;
            background: rgba(12, 25, 41, 0.6);
            border-top: 1px solid rgba(94, 234, 212, 0.1);
        }

        .input-container {
    display: flex;
            gap: 12px;
    align-items: center;
}

        .input-container input {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(94, 234, 212, 0.2);
            border-radius: 16px;
            padding: 14px 20px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .input-container input::placeholder {
            color: var(--text-secondary);
        }

        .input-container input:focus {
            outline: none;
            border-color: var(--teal-primary);
            background: rgba(20, 184, 166, 0.05);
        }

        .send-btn {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--teal-primary), var(--ocean-light));
            border: none;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
    cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 16px var(--shadow-color);
        }

        .send-btn svg {
            width: 22px;
            height: 22px;
            fill: white;
        }

        .send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--shadow-color);
        }

        .send-btn:active {
            transform: translateY(0);
        }

        /* History Sidebar */
        .history-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: rgba(15, 39, 68, 0.98);
            backdrop-filter: blur(20px);
            border-left: 1px solid rgba(94, 234, 212, 0.15);
            z-index: 1000;
            transition: right 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .history-sidebar.open {
            right: 0;
        }

        .sidebar-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(94, 234, 212, 0.15);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-header h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .sidebar-close, .search-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 2rem;
            cursor: pointer;
            padding: 0;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.3s;
        }

        .sidebar-close:hover, .search-close:hover {
            color: var(--teal-light);
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .new-chat-btn {
            width: 100%;
            padding: 12px 16px;
            background: linear-gradient(135deg, var(--teal-primary), var(--ocean-light));
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 16px;
            transition: transform 0.2s;
        }

        .new-chat-btn:hover {
            transform: translateY(-2px);
        }

        .conversations-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .conversation-item {
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(94, 234, 212, 0.1);
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .conversation-item:hover {
            background: rgba(20, 184, 166, 0.15);
            border-color: var(--teal-primary);
        }

        .conversation-date {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 4px;
        }

        .conversation-meta {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .loading {
            text-align: center;
            color: var(--text-secondary);
            padding: 20px;
        }

        /* Search Modal */
        .search-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            width: 90%;
            max-width: 600px;
            max-height: 80vh;
            background: rgba(15, 39, 68, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(94, 234, 212, 0.15);
            border-radius: 20px;
            z-index: 1001;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .search-modal.open {
            opacity: 1;
            pointer-events: all;
            transform: translate(-50%, -50%) scale(1);
        }

        .search-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(94, 234, 212, 0.15);
            display: flex;
            gap: 12px;
            align-items: center;
        }

        #search-input {
            flex: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(94, 234, 212, 0.2);
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 1rem;
        }

        #search-input:focus {
            outline: none;
            border-color: var(--teal-primary);
            background: rgba(20, 184, 166, 0.05);
        }

        .search-results {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
        }

        .search-placeholder {
            text-align: center;
            color: var(--text-secondary);
            padding: 40px 20px;
        }

        .search-result-item {
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(94, 234, 212, 0.1);
            border-radius: 12px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .search-result-item:hover {
            background: rgba(20, 184, 166, 0.15);
            border-color: var(--teal-primary);
        }

        .search-result-sender {
            font-size: 0.75rem;
            color: var(--teal-light);
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .search-result-message {
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .search-result-date {
            font-size: 0.7rem;
            color: var(--text-secondary);
        }

        .highlight {
            background: rgba(20, 184, 166, 0.3);
            padding: 2px 4px;
            border-radius: 4px;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .chat-container {
                max-width: 100%;
                height: 100vh;
                max-height: 100vh;
                border-radius: 0;
            }

            body {
                padding: 0;
            }

            .history-sidebar {
                width: 100%;
                right: -100%;
            }

            .search-modal {
                width: 95%;
                max-height: 90vh;
            }

            .header-actions {
                gap: 4px;
            }

            .header-btn {
                width: 36px;
                height: 36px;
            }

            .header-btn svg {
                width: 18px;
                height: 18px;
            }

            .message {
                max-width: 90%;
            }

            .message-bubble {
                padding: 12px 16px;
                font-size: 0.9rem;
            }

            .input-container input {
                padding: 12px 16px;
                font-size: 0.9rem;
            }

            .send-btn {
                width: 48px;
                height: 48px;
            }

            .reaction-btn {
                padding: 8px 12px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .header-info h1 {
                font-size: 1rem;
            }

            .header-info .status {
                font-size: 0.75rem;
            }

            .message-bubble {
                padding: 10px 14px;
                font-size: 0.85rem;
            }

            .input-area {
                padding: 16px;
            }
        }

        /* Prevent zoom on input focus (iOS) */
        @media screen and (max-width: 768px) {
            input, textarea, select {
                font-size: 16px !important;
            }
        }

        /* Better touch targets */
        @media (max-width: 768px) {
            .header-btn, .send-btn, .reaction-btn {
                min-width: 44px;
                min-height: 44px;
            }
        }
</style>
</head>
<body>
    <div class="chat-container">
        <!-- Header -->
        <div class="chat-header">
            <div class="header-content">
                <div class="bot-avatar">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                </div>
                <div class="header-info">
                    <h1>AI Chatbot Assistant</h1>
                    <div class="status">
                        <span class="status-dot"></span>
                        Online
                    </div>
                </div>
                <div class="header-actions">
                    <button class="header-btn" id="search-btn" title="Search (Ctrl+K)">
                        <svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                    </button>
                    <button class="header-btn" id="history-btn" title="Chat History">
                        <svg viewBox="0 0 24 24"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
                    </button>
                    <a href="index.php" class="header-btn" title="Home">
                        <svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </a>
                    <a href="admin.php" class="header-btn" title="Admin">
                        <svg viewBox="0 0 24 24"><path d="M19.14 12.94c.04-.31.06-.63.06-.94 0-.31-.02-.63-.06-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.04.31-.06.63-.06.94s.02.63.06.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Help Panel -->
        <div class="help-toggle" id="help-toggle">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M11 18h2v-2h-2v2zm1-16C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-2.21 0-4 1.79-4 4h2c0-1.1.9-2 2-2s2 .9 2 2c0 2-3 1.75-3 5h2c0-2.25 3-2.5 3-5 0-2.21-1.79-4-4-4z"/></svg>
            Tips
        </div>
        <div class="help-panel" id="help-panel">
            <h3>How to use</h3>
            <ul>
                <li>Type your question and press Enter or click Send</li>
                <li>Use 👍 or 👎 to rate responses</li>
                <li>Questions I can't answer will be reviewed by our team</li>
                <li><code>show_context</code> - View conversation history</li>
                <li><code>clear_context</code> - Start fresh</li>
            </ul>
        </div>

        <!-- Messages -->
        <div class="messages" id="messages">
            <div class="message bot">
                <div class="message-avatar">
                    <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg>
                </div>
                <div class="message-content">
                    <div class="message-bubble">Hello! How can I help you today?</div>
                    <div class="message-time" id="initial-time"></div>
                </div>
            </div>
        </div>

        <!-- Typing Indicator -->
            <div class="typing-indicator" id="typing-indicator">
            <div class="typing-dots">
                <span></span><span></span><span></span>
            </div>
            AI is typing...
        </div>

        <!-- Input Area -->
        <div class="input-area">
            <div class="input-container">
                <input type="text" id="message-input" placeholder="Type your message..." autocomplete="off">
                <button class="send-btn" id="send-btn">
                    <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- History Sidebar -->
    <div class="history-sidebar" id="history-sidebar">
        <div class="sidebar-header">
            <h2>Chat History</h2>
            <button class="sidebar-close" id="history-close">&times;</button>
        </div>
        <div class="sidebar-content">
            <button class="new-chat-btn" id="new-chat-btn">+ New Chat</button>
            <div class="conversations-list" id="conversations-list">
                <div class="loading">Loading conversations...</div>
            </div>
        </div>
    </div>
    <div class="sidebar-overlay" id="history-overlay"></div>

    <!-- Search Modal -->
    <div class="search-modal" id="search-modal">
        <div class="search-header">
            <input type="text" id="search-input" placeholder="Search conversations..." autocomplete="off">
            <button class="search-close" id="search-close">&times;</button>
        </div>
        <div class="search-results" id="search-results">
            <div class="search-placeholder">Type to search your conversation history...</div>
        </div>
    </div>
    <div class="sidebar-overlay" id="search-overlay"></div>

    <script>
    $(function(){
        function getTimestamp(){
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            return hours + ':' + minutes + ' ' + ampm;
        }

        $('#initial-time').text(getTimestamp());

            $('#help-toggle').on('click', function(){
                var panel = $('#help-panel');
                panel.slideToggle(200);
            $(this).find('svg').css('transform', panel.is(':visible') ? 'rotate(180deg)' : 'rotate(0)');
            });

            function sanitize(text){
                return $('<div/>').text(text).html();
            }

            function showTyping(){
                $('#typing-indicator').addClass('active');
            scrollToBottom();
            }

            function hideTyping(){
                $('#typing-indicator').removeClass('active');
            }

        function scrollToBottom(){
            var messages = $('#messages');
            messages.scrollTop(messages[0].scrollHeight);
            }

            var lastUserQuery = '';
            
            function appendBotMessage(text, extraHtml, query, reply){
                hideTyping();
                var messageId = 'msg_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                var queryVal = query || lastUserQuery || '';
                var replyVal = reply || text || '';
                
            var reactions = '<div class="reactions">' +
                '<button class="reaction-btn thumbs-up" data-message-id="' + messageId + '" data-query="' + sanitize(queryVal) + '" data-reply="' + sanitize(replyVal) + '">👍</button>' +
                '<button class="reaction-btn thumbs-down" data-message-id="' + messageId + '" data-query="' + sanitize(queryVal) + '" data-reply="' + sanitize(replyVal) + '">👎</button>' +
                    '</div>';
                
            var html = '<div class="message bot">' +
                '<div class="message-avatar"><svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/></svg></div>' +
                '<div class="message-content">' +
                    '<div class="message-bubble">' + sanitize(text) + (extraHtml || '') + '</div>' +
                    reactions +
                    '<div class="message-time">' + getTimestamp() + '</div>' +
                '</div>' +
            '</div>';
            $('#messages').append(html);
            scrollToBottom();
            }

            function appendUserMessage(text){
            var html = '<div class="message user">' +
                '<div class="message-avatar"><svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg></div>' +
                '<div class="message-content">' +
                    '<div class="message-bubble">' + sanitize(text) + '</div>' +
                    '<div class="message-time">' + getTimestamp() + '</div>' +
                '</div>' +
            '</div>';
            $('#messages').append(html);
            scrollToBottom();
            }

            function sendMessage(){
            var value = $('#message-input').val().trim();
                if(!value) return;
            lastUserQuery = value;
                appendUserMessage(value);
            $('#message-input').val('');
                showTyping();

                $.ajax({
                    url: 'message.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { text: value },
                    success: function(res){
                        if(!res || res.ok === false){
                        if(res && res.admin_required){
                            appendBotMessage('This command requires admin access. Please use the admin panel.', '', '', '');
                        } else {
                            appendBotMessage(res && res.error ? res.error : 'Sorry, something went wrong.', '', '', '');
                        }
                                return;
                            }
                    if (res.command) {
                        handleCommandResponse(res);
                            return;
                        }
                        if(res.found){
                        var conf = res.confidence !== undefined ? '<span class="confidence">Confidence: ' + res.confidence + '%</span>' : '';
                        appendBotMessage(res.reply || '', conf, lastUserQuery, res.reply);
                        } else {
                        appendBotMessage(res.message || "I don't have an answer for that yet. Your question has been saved and will be reviewed by our team.", '', lastUserQuery, res.message);
                        }
                    },
                    error: function(xhr){
                        hideTyping();
                    appendBotMessage('Network error. Please try again.', '', '', '');
                    }
                });
            }

        function handleCommandResponse(res){
            hideTyping();
            var msg = '';
            if (res.type === 'list_replies') {
                var items = res.items || [];
                if (items.length === 0) {
                    msg = 'No matches found.';
                } else {
                    msg = items.map(function(it){ return '#' + it.id + ': ' + sanitize(it.query) + ' → ' + sanitize(it.reply); }).join('\n');
                }
            } else if (res.type === 'show_stats') {
                var s = res.stats || {};
                msg = 'Intents: ' + (s.intents||0) + ', Phrases: ' + (s.phrases||0) + ', Responses: ' + (s.responses||0) + ', Matched: ' + (s.history_matched||0) + ', Unmatched: ' + (s.history_unmatched||0);
            } else if (res.type === 'clear_context') {
                msg = res.message || 'Context cleared.';
            } else if (res.type === 'show_context') {
                var history = res.history || [];
                msg = history.length === 0 ? 'No history.' : history.map(function(h){ return 'You: ' + h.user + (h.bot ? ' | Bot: ' + h.bot : ''); }).join('\n');
                        } else {
                msg = res.message || 'Command executed.';
            }
            appendBotMessage(msg, '', '', '');
        }

        $('#send-btn').on('click', sendMessage);
        $('#message-input').on('keypress', function(e){
            if(e.which === 13) sendMessage();
        });

        $(document).on('click', '.reaction-btn', function(){
            var btn = $(this);
            var messageId = btn.data('message-id');
            var query = btn.data('query');
            var reply = btn.data('reply');
            var type = btn.hasClass('thumbs-up') ? 'thumbs_up' : 'thumbs_down';

            btn.addClass('active').siblings().removeClass('active').prop('disabled', true);
            btn.prop('disabled', true);

            $.post('message.php', { feedback: type, message_id: messageId, query: query, reply: reply }, null, 'json');
        });

        // History Sidebar
        function openHistory() {
            $('#history-sidebar').addClass('open');
            $('#history-overlay').addClass('active');
            loadConversations();
        }

        function closeHistory() {
            $('#history-sidebar').removeClass('open');
            $('#history-overlay').removeClass('active');
        }

        function loadConversations() {
            $('#conversations-list').html('<div class="loading">Loading conversations...</div>');
            $.post('message.php', { action: 'list_conversations', limit: 50 }, function(res) {
                if (res && res.ok && res.conversations) {
                    if (res.conversations.length === 0) {
                        $('#conversations-list').html('<div class="loading">No conversations yet</div>');
                        return;
                    }
                    var html = '';
                    res.conversations.forEach(function(conv) {
                        var date = new Date(conv.last_activity);
                        var dateStr = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        html += '<div class="conversation-item" data-id="' + conv.id + '">' +
                            '<div class="conversation-date">' + dateStr + '</div>' +
                            '<div class="conversation-meta">' + conv.message_count + ' messages</div>' +
                            '</div>';
                    });
                    $('#conversations-list').html(html);
                } else {
                    $('#conversations-list').html('<div class="loading">Error loading conversations</div>');
                }
            }, 'json').fail(function() {
                $('#conversations-list').html('<div class="loading">Error loading conversations</div>');
            });
        }

        function loadConversation(conversationId) {
            $.post('message.php', { action: 'load_conversation', conversation_id: conversationId }, function(res) {
                if (res && res.ok && res.messages) {
                    $('#messages').empty();
                    res.messages.forEach(function(msg) {
                        if (msg.sender === 'user') {
                            appendUserMessage(msg.message);
                        } else {
                            appendBotMessage(msg.message, '', '', msg.message);
                        }
                    });
                    closeHistory();
                    scrollToBottom();
                }
            }, 'json');
        }

        function startNewConversation() {
            $.post('message.php', { action: 'new_conversation' }, function(res) {
                if (res && res.ok) {
                    $('#messages').empty();
                    appendBotMessage('Hello! How can I help you today?', '', '', '');
                    closeHistory();
                }
            }, 'json');
        }

        $('#history-btn').on('click', openHistory);
        $('#history-close').on('click', closeHistory);
        $('#history-overlay').on('click', closeHistory);
        $('#new-chat-btn').on('click', startNewConversation);
        $(document).on('click', '.conversation-item', function() {
            var id = $(this).data('id');
            if (id) loadConversation(id);
        });

        // Search Modal
        function openSearch() {
            $('#search-modal').addClass('open');
            $('#search-overlay').addClass('active');
            setTimeout(function() {
                $('#search-input').focus();
            }, 100);
        }

        function closeSearch() {
            $('#search-modal').removeClass('open');
            $('#search-overlay').removeClass('active');
            $('#search-input').val('');
            $('#search-results').html('<div class="search-placeholder">Type to search your conversation history...</div>');
        }

        var searchTimeout;
        $('#search-input').on('input', function() {
            var query = $(this).val().trim();
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                $('#search-results').html('<div class="search-placeholder">Type at least 2 characters to search...</div>');
                return;
            }

            searchTimeout = setTimeout(function() {
                $.post('message.php', { action: 'search_conversations', query: query, limit: 50 }, function(res) {
                    if (res && res.ok && res.results) {
                        if (res.results.length === 0) {
                            $('#search-results').html('<div class="search-placeholder">No results found</div>');
                            return;
                        }
                        var html = '';
                        res.results.forEach(function(result) {
                            var date = new Date(result.created_at);
                            var dateStr = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                            var message = sanitize(result.message);
                            // Simple highlight (case-insensitive)
                            var regex = new RegExp('(' + query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                            message = message.replace(regex, '<span class="highlight">$1</span>');
                            html += '<div class="search-result-item" data-conversation-id="' + result.conversation_id + '">' +
                                '<div class="search-result-sender">' + (result.sender === 'user' ? 'You' : 'Bot') + '</div>' +
                                '<div class="search-result-message">' + message + '</div>' +
                                '<div class="search-result-date">' + dateStr + '</div>' +
                                '</div>';
                        });
                        $('#search-results').html(html);
                    } else {
                        $('#search-results').html('<div class="search-placeholder">Error searching</div>');
                    }
                }, 'json').fail(function() {
                    $('#search-results').html('<div class="search-placeholder">Error searching</div>');
                });
            }, 300);
        });

        $('#search-btn').on('click', openSearch);
        $('#search-close').on('click', closeSearch);
        $('#search-overlay').on('click', closeSearch);
        $(document).on('click', '.search-result-item', function() {
            var convId = $(this).data('conversation-id');
            if (convId) {
                closeSearch();
                loadConversation(convId);
            }
        });

        // Keyboard shortcuts
        $(document).on('keydown', function(e) {
            // Ctrl+K or Cmd+K for search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if ($('#search-modal').hasClass('open')) {
                    closeSearch();
                        } else {
                    openSearch();
                }
            }
            // Escape to close modals
            if (e.key === 'Escape') {
                closeHistory();
                closeSearch();
            }
        });

        // Mobile: Prevent body scroll when sidebar is open
        function toggleBodyScroll(disable) {
            if (disable) {
                $('body').css('overflow', 'hidden');
            } else {
                $('body').css('overflow', '');
            }
        }

        $('#history-btn, #search-btn').on('click', function() {
            toggleBodyScroll(true);
        });

        $('#history-close, #search-close, #history-overlay, #search-overlay').on('click', function() {
            toggleBodyScroll(false);
        });

        // Mobile: Fix viewport height on iOS
        function setViewportHeight() {
            var vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', vh + 'px');
        }
        setViewportHeight();
        $(window).on('resize orientationchange', setViewportHeight);
        });
    </script>
</body>
</html>
