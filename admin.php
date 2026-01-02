<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - AI Chatbot Assistant</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
            --danger: #ef4444;
            --danger-light: #fca5a5;
            --success: #22c55e;
            --warning: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, var(--ocean-deep) 0%, var(--ocean-dark) 100%);
            min-height: 100vh;
            color: var(--text-primary);
        }

        /* Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(ellipse at 20% 0%, rgba(74, 158, 218, 0.12) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 100%, rgba(20, 184, 166, 0.08) 0%, transparent 50%);
            z-index: 0;
            pointer-events: none;
        }

        /* Login Screen */
        .login-screen {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-box {
            background: rgba(15, 39, 68, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(94, 234, 212, 0.15);
            border-radius: 28px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
        }

        .login-box h1 {
            font-size: 1.8rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--teal-light), var(--aqua));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-box p {
            text-align: center;
            color: var(--text-secondary);
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            background: rgba(12, 25, 41, 0.8);
            border: 1px solid rgba(94, 234, 212, 0.2);
            border-radius: 14px;
            padding: 14px 18px;
            color: var(--text-primary);
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: var(--teal-primary);
            background: rgba(20, 184, 166, 0.05);
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 28px;
            border-radius: 14px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--teal-primary), var(--ocean-light));
            color: white;
            box-shadow: 0 4px 16px var(--shadow-color);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px var(--shadow-color);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
            border: 1px solid rgba(94, 234, 212, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(20, 184, 166, 0.15);
            border-color: var(--teal-primary);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger-light);
            border: 1px solid rgba(239, 68, 68, 0.4);
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.3);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.85rem;
            border-radius: 10px;
        }

        .btn-block {
            width: 100%;
        }

        /* Admin Panel */
        .admin-container {
            display: none;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .admin-container.active {
            display: block;
        }

        /* Header */
        .admin-header {
            background: rgba(15, 39, 68, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(94, 234, 212, 0.1);
            padding: 16px 32px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.4rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--teal-light), var(--aqua));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .header-actions a, .header-actions button {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(94, 234, 212, 0.2);
            border-radius: 12px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
            font-family: inherit;
        }

        .header-actions a:hover, .header-actions button:hover {
            background: rgba(20, 184, 166, 0.15);
            border-color: var(--teal-primary);
            color: var(--teal-light);
        }

        /* Main Content */
        .main-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 32px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: rgba(26, 73, 113, 0.4);
            border: 1px solid rgba(94, 234, 212, 0.15);
            border-radius: 20px;
            padding: 24px;
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--teal-light), var(--aqua));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 8px;
        }

        .stat-card p {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            background: rgba(15, 39, 68, 0.5);
            padding: 8px;
            border-radius: 18px;
            border: 1px solid rgba(94, 234, 212, 0.1);
        }

        .tab-btn {
            padding: 12px 24px;
            background: transparent;
            border: none;
            border-radius: 12px;
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: inherit;
        }

        .tab-btn:hover {
            background: rgba(20, 184, 166, 0.1);
            color: var(--text-primary);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, var(--teal-primary), var(--ocean-light));
            color: white;
            box-shadow: 0 4px 16px var(--shadow-color);
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Cards */
        .card {
            background: rgba(26, 73, 113, 0.3);
            border: 1px solid rgba(94, 234, 212, 0.15);
            border-radius: 24px;
            padding: 28px;
            margin-bottom: 24px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .card-header h2 {
            font-size: 1.3rem;
            font-weight: 700;
        }

        /* Tables */
        .table-container {
            overflow-x: auto;
            border-radius: 16px;
            border: 1px solid rgba(94, 234, 212, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid rgba(94, 234, 212, 0.08);
        }

        th {
            background: rgba(12, 25, 41, 0.6);
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            font-size: 0.9rem;
            color: var(--text-primary);
        }

        tr:hover td {
            background: rgba(20, 184, 166, 0.05);
        }

        .actions-cell {
            display: flex;
            gap: 8px;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.2);
            color: #86efac;
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: #fcd34d;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        .badge-info {
            background: rgba(20, 184, 166, 0.2);
            color: var(--teal-light);
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(12, 25, 41, 0.9);
            backdrop-filter: blur(8px);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal {
            background: rgba(15, 39, 68, 0.95);
            border: 1px solid rgba(94, 234, 212, 0.2);
            border-radius: 24px;
            padding: 32px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .modal-header h3 {
            font-size: 1.3rem;
            font-weight: 700;
        }

        .modal-close {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(94, 234, 212, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: 1.2rem;
            transition: all 0.3s;
        }

        .modal-close:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: var(--danger);
            color: var(--danger-light);
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .modal-actions .btn {
            flex: 1;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            fill: rgba(94, 234, 212, 0.3);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: rgba(15, 39, 68, 0.95);
            border: 1px solid rgba(94, 234, 212, 0.3);
            border-radius: 14px;
            padding: 16px 24px;
            color: var(--text-primary);
            font-weight: 500;
            z-index: 2000;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            border-color: var(--success);
        }

        .toast.error {
            border-color: var(--danger);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-header { padding: 16px 20px; }
            .main-content { padding: 20px; }
            .header-actions span { display: none; }
            .tabs { overflow-x: auto; flex-wrap: nowrap; }
            .tab-btn { white-space: nowrap; }
        }
    </style>
</head>
<body>
    <!-- Login Screen -->
    <div class="login-screen" id="login-screen">
        <div class="login-box">
            <h1>Admin Panel</h1>
            <p>Enter credentials to manage your chatbot</p>
            <div class="form-group">
                <label>Username</label>
                <input type="text" id="login-username" placeholder="Enter username">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" id="login-password" placeholder="Enter password">
            </div>
            <button class="btn btn-primary btn-block" id="login-btn">Login</button>
        </div>
    </div>

    <!-- Admin Panel -->
    <div class="admin-container" id="admin-container">
        <!-- Header -->
        <header class="admin-header">
            <div class="header-content">
                <div class="logo">AI Chatbot Admin</div>
                <div class="header-actions">
                    <a href="index.php"><span>Home</span></a>
                    <a href="bot.php"><span>Chat</span></a>
                    <button id="logout-btn"><span>Logout</span></button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Stats -->
            <div class="stats-grid" id="stats-grid">
                <div class="stat-card">
                    <h3 id="stat-intents">0</h3>
                    <p>Intents</p>
                </div>
                <div class="stat-card">
                    <h3 id="stat-phrases">0</h3>
                    <p>Training Phrases</p>
                </div>
                <div class="stat-card">
                    <h3 id="stat-responses">0</h3>
                    <p>Responses</p>
                </div>
                <div class="stat-card">
                    <h3 id="stat-learning">0</h3>
                    <p>Learning Queue</p>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab-btn active" data-tab="intents">Intents</button>
                <button class="tab-btn" data-tab="phrases">Training Phrases</button>
                <button class="tab-btn" data-tab="responses">Responses</button>
                <button class="tab-btn" data-tab="learning">Learning Queue</button>
                <button class="tab-btn" data-tab="legacy">Legacy Data</button>
            </div>

            <!-- Intents Tab -->
            <div class="tab-content active" id="tab-intents">
                <div class="card">
                    <div class="card-header">
                        <h2>Manage Intents</h2>
                        <button class="btn btn-primary btn-sm" id="add-intent-btn">+ Add Intent</button>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Phrases</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="intents-table"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Training Phrases Tab -->
            <div class="tab-content" id="tab-phrases">
                <div class="card">
                    <div class="card-header">
                        <h2>Training Phrases</h2>
                        <button class="btn btn-primary btn-sm" id="add-phrase-btn">+ Add Phrase</button>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Intent</th>
                                    <th>Phrase</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="phrases-table"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Responses Tab -->
            <div class="tab-content" id="tab-responses">
                <div class="card">
                    <div class="card-header">
                        <h2>Responses</h2>
                        <button class="btn btn-primary btn-sm" id="add-response-btn">+ Add Response</button>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Intent</th>
                                    <th>Response</th>
                                    <th>Confidence</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="responses-table"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Learning Queue Tab -->
            <div class="tab-content" id="tab-learning">
                <div class="card">
                    <div class="card-header">
                        <h2>Learning Queue</h2>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Question</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="learning-table"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Legacy Data Tab -->
            <div class="tab-content" id="tab-legacy">
                <div class="card">
                    <div class="card-header">
                        <h2>Legacy Chatbot Data</h2>
                    </div>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Query</th>
                                    <th>Reply</th>
                                </tr>
                            </thead>
                            <tbody id="legacy-table"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <div class="modal-overlay" id="modal-overlay">
        <div class="modal" id="modal-content"></div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast"></div>

    <script>
    (function(){
        const API_URL = 'admin_api.php';
        let authToken = localStorage.getItem('admin_auth') || '';

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast ' + type + ' show';
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // Modal handling
        function openModal(html) {
            document.getElementById('modal-content').innerHTML = html;
            document.getElementById('modal-overlay').classList.add('active');
        }

        function closeModal() {
            document.getElementById('modal-overlay').classList.remove('active');
        }

        document.getElementById('modal-overlay').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // API helper
        async function api(action, data = {}) {
            data.action = action;
            data.auth = authToken;
            try {
                const res = await fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(data)
                });
                return await res.json();
            } catch (err) {
                showToast('Network error', 'error');
                return { ok: false };
            }
        }

        // Login handling
        document.getElementById('login-btn').addEventListener('click', async function() {
            const username = document.getElementById('login-username').value;
            const password = document.getElementById('login-password').value;
            
            if (username === 'admin' && password === 'admin') {
                authToken = btoa(username + ':' + password);
                localStorage.setItem('admin_auth', authToken);
                showAdmin();
            } else {
                showToast('Invalid credentials', 'error');
            }
        });

        document.getElementById('login-password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') document.getElementById('login-btn').click();
        });

        document.getElementById('logout-btn').addEventListener('click', function() {
            localStorage.removeItem('admin_auth');
            authToken = '';
            location.reload();
        });

        function showAdmin() {
            document.getElementById('login-screen').style.display = 'none';
            document.getElementById('admin-container').classList.add('active');
            loadStats();
            loadIntents();
        }

        if (authToken) showAdmin();

        // Tab handling
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('tab-' + this.dataset.tab).classList.add('active');
                
                switch(this.dataset.tab) {
                    case 'intents': loadIntents(); break;
                    case 'phrases': loadPhrases(); break;
                    case 'responses': loadResponses(); break;
                    case 'learning': loadLearning(); break;
                    case 'legacy': loadLegacy(); break;
                }
            });
        });

        // Load Stats
        async function loadStats() {
            const res = await api('get_stats');
            if (res.ok) {
                document.getElementById('stat-intents').textContent = res.stats.intents || 0;
                document.getElementById('stat-phrases').textContent = res.stats.phrases || 0;
                document.getElementById('stat-responses').textContent = res.stats.responses || 0;
                document.getElementById('stat-learning').textContent = res.stats.learning_pending || 0;
            }
        }

        // Intents
        async function loadIntents() {
            const res = await api('list_intents');
            const tbody = document.getElementById('intents-table');
            if (!res.ok || !res.intents?.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="empty-state"><h3>No intents yet</h3><p>Create your first intent to get started</p></td></tr>';
                return;
            }
            tbody.innerHTML = res.intents.map(i => `
                <tr>
                    <td>${i.id}</td>
                    <td><strong>${escapeHtml(i.name)}</strong></td>
                    <td>${escapeHtml(i.description || '-')}</td>
                    <td><span class="badge badge-info">${i.phrase_count || 0}</span></td>
                    <td>${i.is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>'}</td>
                    <td class="actions-cell">
                        <button class="btn btn-secondary btn-sm" onclick="editIntent(${i.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteIntent(${i.id})">Delete</button>
                    </td>
                </tr>
            `).join('');
        }

        document.getElementById('add-intent-btn').addEventListener('click', function() {
            openModal(`
                <div class="modal-header">
                    <h3>Create Intent</h3>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="form-group">
                    <label>Intent Name</label>
                    <input type="text" id="intent-name" placeholder="e.g., greeting, faq_hours">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="intent-desc" placeholder="What this intent handles..."></textarea>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="saveIntent()">Create Intent</button>
                </div>
            `);
        });

        window.saveIntent = async function() {
            const name = document.getElementById('intent-name').value.trim();
            const description = document.getElementById('intent-desc').value.trim();
            if (!name) return showToast('Name is required', 'error');
            
            const res = await api('create_intent', { name, description });
            if (res.ok) {
                showToast('Intent created!');
                closeModal();
                loadStats();
                loadIntents();
            } else {
                showToast(res.message || 'Failed to create', 'error');
            }
        };

        window.editIntent = async function(id) {
            const res = await api('get_intent', { id });
            if (!res.ok) return showToast('Failed to load', 'error');
            const i = res.intent;
            openModal(`
                <div class="modal-header">
                    <h3>Edit Intent</h3>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="form-group">
                    <label>Intent Name</label>
                    <input type="text" id="intent-name" value="${escapeHtml(i.name)}">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="intent-desc">${escapeHtml(i.description || '')}</textarea>
                </div>
                <div class="form-group">
                    <label>Active</label>
                    <select id="intent-active">
                        <option value="1" ${i.is_active ? 'selected' : ''}>Active</option>
                        <option value="0" ${!i.is_active ? 'selected' : ''}>Inactive</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="updateIntent(${id})">Save Changes</button>
                </div>
            `);
        };

        window.updateIntent = async function(id) {
            const name = document.getElementById('intent-name').value.trim();
            const description = document.getElementById('intent-desc').value.trim();
            const is_active = document.getElementById('intent-active').value;
            
            const res = await api('update_intent', { id, name, description, is_active });
            if (res.ok) {
                showToast('Intent updated!');
                closeModal();
                loadIntents();
            } else {
                showToast(res.message || 'Failed to update', 'error');
            }
        };

        window.deleteIntent = async function(id) {
            if (!confirm('Delete this intent? This will also delete all associated phrases and responses.')) return;
            const res = await api('delete_intent', { id });
            if (res.ok) {
                showToast('Intent deleted');
                loadStats();
                loadIntents();
            } else {
                showToast(res.message || 'Failed to delete', 'error');
            }
        };

        // Training Phrases
        async function loadPhrases() {
            const res = await api('list_training_phrases');
            const tbody = document.getElementById('phrases-table');
            if (!res.ok || !res.phrases?.length) {
                tbody.innerHTML = '<tr><td colspan="4" class="empty-state"><h3>No training phrases</h3><p>Add phrases to train your intents</p></td></tr>';
                return;
            }
            tbody.innerHTML = res.phrases.map(p => `
                <tr>
                    <td>${p.id}</td>
                    <td><span class="badge badge-info">${escapeHtml(p.intent_name)}</span></td>
                    <td>${escapeHtml(p.phrase)}</td>
                    <td class="actions-cell">
                        <button class="btn btn-danger btn-sm" onclick="deletePhrase(${p.id})">Delete</button>
                    </td>
                </tr>
            `).join('');
        }

        document.getElementById('add-phrase-btn').addEventListener('click', async function() {
            const intentsRes = await api('list_intents');
            const options = intentsRes.intents?.map(i => `<option value="${i.id}">${escapeHtml(i.name)}</option>`).join('') || '';
            
            openModal(`
                <div class="modal-header">
                    <h3>Add Training Phrase</h3>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="form-group">
                    <label>Intent</label>
                    <select id="phrase-intent">${options}</select>
                </div>
                <div class="form-group">
                    <label>Phrase</label>
                    <textarea id="phrase-text" placeholder="Enter a phrase users might say..."></textarea>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="savePhrase()">Add Phrase</button>
                </div>
            `);
        });

        window.savePhrase = async function() {
            const intent_id = document.getElementById('phrase-intent').value;
            const phrase = document.getElementById('phrase-text').value.trim();
            if (!phrase) return showToast('Phrase is required', 'error');
            
            const res = await api('create_training_phrase', { intent_id, phrase });
            if (res.ok) {
                showToast('Phrase added!');
                closeModal();
                loadStats();
                loadPhrases();
            } else {
                showToast(res.message || 'Failed to add', 'error');
            }
        };

        window.deletePhrase = async function(id) {
            if (!confirm('Delete this phrase?')) return;
            const res = await api('delete_training_phrase', { id });
            if (res.ok) {
                showToast('Phrase deleted');
                loadStats();
                loadPhrases();
            } else {
                showToast(res.message || 'Failed to delete', 'error');
            }
        };

        // Responses
        async function loadResponses() {
            const res = await api('list_responses');
            const tbody = document.getElementById('responses-table');
            if (!res.ok || !res.responses?.length) {
                tbody.innerHTML = '<tr><td colspan="6" class="empty-state"><h3>No responses</h3><p>Add responses for your intents</p></td></tr>';
                return;
            }
            tbody.innerHTML = res.responses.map(r => `
                <tr>
                    <td>${r.id}</td>
                    <td><span class="badge badge-info">${escapeHtml(r.intent_name)}</span></td>
                    <td>${escapeHtml(r.response.substring(0, 100))}${r.response.length > 100 ? '...' : ''}</td>
                    <td>${r.confidence}</td>
                    <td>${r.is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>'}</td>
                    <td class="actions-cell">
                        <button class="btn btn-secondary btn-sm" onclick="editResponse(${r.id})">Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteResponse(${r.id})">Delete</button>
                    </td>
                </tr>
            `).join('');
        }

        document.getElementById('add-response-btn').addEventListener('click', async function() {
            const intentsRes = await api('list_intents');
            const options = intentsRes.intents?.map(i => `<option value="${i.id}">${escapeHtml(i.name)}</option>`).join('') || '';
            
            openModal(`
                <div class="modal-header">
                    <h3>Add Response</h3>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="form-group">
                    <label>Intent</label>
                    <select id="response-intent">${options}</select>
                </div>
                <div class="form-group">
                    <label>Response</label>
                    <textarea id="response-text" placeholder="Enter the bot's response..."></textarea>
                </div>
                <div class="form-group">
                    <label>Confidence (0.0 - 1.0)</label>
                    <input type="number" id="response-confidence" value="1.0" min="0" max="1" step="0.1">
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="saveResponse()">Add Response</button>
                </div>
            `);
        });

        window.saveResponse = async function() {
            const intent_id = document.getElementById('response-intent').value;
            const response = document.getElementById('response-text').value.trim();
            const confidence = document.getElementById('response-confidence').value;
            if (!response) return showToast('Response is required', 'error');
            
            const res = await api('create_response', { intent_id, response, confidence });
            if (res.ok) {
                showToast('Response added!');
                closeModal();
                loadStats();
                loadResponses();
            } else {
                showToast(res.message || 'Failed to add', 'error');
            }
        };

        window.editResponse = async function(id) {
            const res = await api('list_responses');
            const r = res.responses?.find(x => x.id == id);
            if (!r) return showToast('Response not found', 'error');
            
            openModal(`
                <div class="modal-header">
                    <h3>Edit Response</h3>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <div class="form-group">
                    <label>Response</label>
                    <textarea id="response-text">${escapeHtml(r.response)}</textarea>
                </div>
                <div class="form-group">
                    <label>Confidence</label>
                    <input type="number" id="response-confidence" value="${r.confidence}" min="0" max="1" step="0.1">
                </div>
                <div class="form-group">
                    <label>Active</label>
                    <select id="response-active">
                        <option value="1" ${r.is_active ? 'selected' : ''}>Active</option>
                        <option value="0" ${!r.is_active ? 'selected' : ''}>Inactive</option>
                    </select>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="updateResponse(${id})">Save Changes</button>
                </div>
            `);
        };

        window.updateResponse = async function(id) {
            const response = document.getElementById('response-text').value.trim();
            const confidence = document.getElementById('response-confidence').value;
            const is_active = document.getElementById('response-active').value;
            
            const res = await api('update_response', { id, response, confidence, is_active });
            if (res.ok) {
                showToast('Response updated!');
                closeModal();
                loadResponses();
            } else {
                showToast(res.message || 'Failed to update', 'error');
            }
        };

        window.deleteResponse = async function(id) {
            if (!confirm('Delete this response?')) return;
            const res = await api('delete_response', { id });
            if (res.ok) {
                showToast('Response deleted');
                loadStats();
                loadResponses();
            } else {
                showToast(res.message || 'Failed to delete', 'error');
            }
        };

        // Learning Queue
        async function loadLearning() {
            const res = await api('list_learning_queue');
            const tbody = document.getElementById('learning-table');
            if (!res.ok || !res.items?.length) {
                tbody.innerHTML = '<tr><td colspan="5" class="empty-state"><h3>No pending questions</h3><p>Questions the bot couldn\'t answer will appear here</p></td></tr>';
                return;
            }
            tbody.innerHTML = res.items.map(l => `
                <tr>
                    <td>${l.id}</td>
                    <td>${escapeHtml(l.question)}</td>
                    <td><span class="badge badge-${l.status === 'pending' ? 'warning' : l.status === 'assigned' ? 'success' : 'secondary'}">${l.status}</span></td>
                    <td>${l.created_at || '-'}</td>
                    <td class="actions-cell">
                        <button class="btn btn-primary btn-sm" onclick="assignLearning(${l.id}, '${escapeHtml(l.question).replace(/'/g, "\\'")}')">Assign to Intent</button>
                        <button class="btn btn-danger btn-sm" onclick="dismissLearning(${l.id})">Dismiss</button>
                    </td>
                </tr>
            `).join('');
        }

        window.assignLearning = async function(id, question) {
            const intentsRes = await api('list_intents');
            const options = intentsRes.intents?.map(i => `<option value="${i.id}">${escapeHtml(i.name)}</option>`).join('') || '';
            
            openModal(`
                <div class="modal-header">
                    <h3>Assign to Intent</h3>
                    <button class="modal-close" onclick="closeModal()">&times;</button>
                </div>
                <p style="margin-bottom: 20px; color: var(--text-secondary);">Question: "${question}"</p>
                <div class="form-group">
                    <label>Select Intent</label>
                    <select id="assign-intent">${options}</select>
                </div>
                <div class="form-group">
                    <label>Response (optional - will create new if provided)</label>
                    <textarea id="assign-response" placeholder="Enter response for this question..."></textarea>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button class="btn btn-primary" onclick="doAssignLearning(${id})">Assign</button>
                </div>
            `);
        };

        window.doAssignLearning = async function(id) {
            const intent_id = document.getElementById('assign-intent').value;
            const response = document.getElementById('assign-response').value.trim();
            
            const res = await api('assign_learning_to_intent', { id, intent_id, response });
            if (res.ok) {
                showToast('Assigned to intent!');
                closeModal();
                loadStats();
                loadLearning();
            } else {
                showToast(res.message || 'Failed to assign', 'error');
            }
        };

        window.dismissLearning = async function(id) {
            if (!confirm('Dismiss this question?')) return;
            const res = await api('dismiss_learning', { id });
            if (res.ok) {
                showToast('Question dismissed');
                loadStats();
                loadLearning();
            } else {
                showToast(res.message || 'Failed to dismiss', 'error');
            }
        };

        // Legacy Data
        async function loadLegacy() {
            const res = await api('list_legacy_chatbot');
            const tbody = document.getElementById('legacy-table');
            if (!res.ok || !res.items?.length) {
                tbody.innerHTML = '<tr><td colspan="3" class="empty-state"><h3>No legacy data</h3><p>The chatbot table is empty or doesn\'t exist</p></td></tr>';
                return;
            }
            tbody.innerHTML = res.items.map(l => `
                <tr>
                    <td>${l.id}</td>
                    <td>${escapeHtml(l.query || l.question || '-')}</td>
                    <td>${escapeHtml(l.reply || l.answer || '-')}</td>
                </tr>
            `).join('');
        }

        // Utilities
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        window.closeModal = closeModal;
    })();
    </script>
</body>
</html>
