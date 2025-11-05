# Chatbot Assistant - Task List

## ✅ Completed Features

- [x] Basic chatbot interface with user and bot messages
- [x] Database-backed query/reply system
- [x] Learning system - bot can be trained when it doesn't know an answer
- [x] In-chat training interface (teach box appears when bot doesn't know)
- [x] Command: `replay_with: <reply>` - teach reply to last unknown question
- [x] Command: `remove_replay: <query>` - remove a learned reply
- [x] Command: `remove_replay:id:<id>` - remove reply by ID
- [x] SQL injection protection (prepared statements)
- [x] XSS protection (HTML sanitization)
- [x] Fuzzy matching using Levenshtein distance
- [x] History tracking (successful/unsuccessful queries)
- [x] JSON API responses
- [x] Secure database configuration
- [x] Command: `edit_replay: <query or id>` - Update an existing reply
- [x] Command: `list_replies: <search term>` - Show matching replies with IDs
- [x] Command: `list_learning` - Show pending learning items
- [x] Command: `show_stats` - Display training statistics (total replies, pending learning, etc.)
- [x] Command: `export_data` - Export chatbot data as JSON/CSV
- [x] Command: `import_data` - Import training data from file
- [x] Admin dashboard - view all replies, learning queue, statistics
- [x] User authentication - password-protected admin panel
- [x] Typing indicator - show "bot is typing..." animation
- [x] Message timestamps - display time for each message
- [x] Confidence threshold - only match if similarity score is above threshold (default: 60%)
- [x] Multiple reply support - store and rotate multiple replies per query
- [x] Bulk training mode - upload multiple Q&A pairs at once
- [x] Better fuzzy matching - use multiple algorithms (Jaro-Winkler + Levenshtein)
- [x] Context awareness - remember previous messages in conversation
- [x] Message reactions (thumbs up/down for feedback)

## 🚀 High Priority Enhancements

### Training & Management

### Matching & Intelligence
- [ ] Synonym/alias support - map multiple queries to same canonical intent
- [ ] Intent classification - group similar queries together

### User Experience
- [ ] Chat history persistence - save conversations per session/user
- [ ] Search chat history
- [ ] Voice input/output support
- [ ] Multi-language support
- [ ] Dark mode toggle
- [ ] Mobile responsive improvements

### Admin & Analytics
- [ ] Analytics page - most asked questions, unknown queries, training frequency
- [ ] Audit log - track who trained what and when
- [ ] Data backup/restore functionality
- [ ] Performance metrics - response time, accuracy rate

## 💡 Nice-to-Have Features

### Advanced AI Integration
- [ ] OpenAI/Claude API integration - use LLM for better initial responses
- [ ] Hybrid mode - combine learned responses with AI fallback
- [ ] Sentiment analysis - detect user mood and respond accordingly
- [ ] Named entity recognition - extract and use dates, names, locations
- [ ] Natural language understanding (NLU) - better intent detection

### Specialized Features
- [ ] Multi-turn conversations - handle follow-up questions
- [ ] Conditional responses - if/then logic based on user data
- [ ] Integration with external APIs (weather, news, etc.)
- [ ] File upload support - learn from documents
- [ ] Image recognition - respond to images
- [ ] Scheduled messages/reminders
- [ ] Rich media support - buttons, cards, images in responses

### Developer Features
- [ ] REST API documentation (OpenAPI/Swagger)
- [ ] Webhook support - trigger external actions
- [ ] Plugin system - allow custom extensions
- [ ] Testing framework - unit tests for matching logic
- [ ] CI/CD pipeline setup
- [ ] Docker containerization
- [ ] Environment-specific configs (dev/staging/prod)

## 🔧 Technical Improvements

### Code Quality
- [ ] Refactor PHP code into classes (OOP structure)
- [ ] Add error logging system
- [ ] Input validation improvements
- [ ] Rate limiting - prevent spam/abuse
- [ ] Caching layer - cache frequent queries
- [ ] Database indexing optimization
- [ ] Code documentation (PHPDoc)

### Security
- [ ] CSRF protection
- [ ] Rate limiting per IP/user
- [ ] Input sanitization review
- [ ] SQL injection audit (even though prepared statements are used)
- [ ] Session management
- [ ] HTTPS enforcement
- [ ] API key authentication for API endpoints

### Performance
- [ ] Database query optimization
- [ ] Pagination for large datasets
- [ ] Lazy loading for chat history
- [ ] CDN for static assets
- [ ] Database connection pooling
- [ ] Response compression

## 📊 Database Schema Enhancements

### New Tables (if needed)
- [ ] `users` - user management
- [ ] `conversations` - track full conversations
- [ ] `feedback` - store user feedback on replies
- [ ] `synonyms` - synonym/alias mapping
- [ ] `intents` - intent classification
- [ ] `analytics` - usage statistics

### Schema Improvements
- [ ] Add indexes on frequently queried columns
- [ ] Add `created_at`, `updated_at` timestamps to all tables
- [ ] Add `user_id` for multi-user support
- [ ] Add `confidence_score` to chatbot table
- [ ] Add `usage_count` to track popular replies

## 🎨 UI/UX Improvements

- [ ] Better loading states
- [ ] Smooth animations
- [ ] Emoji support in messages
- [ ] Markdown formatting in bot replies
- [ ] Code syntax highlighting
- [ ] Drag-and-drop file uploads
- [ ] Keyboard shortcuts (Ctrl+K for commands, etc.)
- [ ] Accessible design (ARIA labels, keyboard navigation)
- [ ] Customizable theme colors
- [ ] Chat export (download conversation as text/PDF)

## 📚 Documentation

- [ ] User guide - how to train the bot
- [ ] Admin manual - managing replies and data
- [ ] API documentation
- [ ] Installation guide
- [ ] Database setup guide
- [ ] Contributing guidelines
- [ ] Changelog

## 🧪 Testing & Quality Assurance

- [ ] Unit tests for matching algorithms
- [ ] Integration tests for API endpoints
- [ ] End-to-end tests for training flow
- [ ] Performance testing
- [ ] Security testing
- [ ] Cross-browser testing
- [ ] Mobile device testing

## 🌐 Deployment & DevOps

- [ ] Production deployment guide
- [ ] Environment variables documentation
- [ ] Database migration scripts
- [ ] Backup automation
- [ ] Monitoring setup (error tracking, uptime)
- [ ] Logging infrastructure
- [ ] Health check endpoints

---

## Quick Reference: Current Commands

### Chat Commands
- `replay_with: <reply>` - Teach a reply to the most recent unknown question
- `remove_replay: <query>` - Remove a learned reply by query text
- `remove_replay:id:<id>` - Remove a learned reply by database ID
- `edit_replay:id:<id>: <new reply>` - Edit a reply by ID
- `edit_replay:<query>: <new reply>` - Edit a reply by query text
- `add_reply:id:<id>: <new reply>` - Add another reply variant (enables multiple replies)
- `add_reply:<query>: <new reply>` - Add reply variant by query
- `list_replies: <term> limit:N` - List matching replies (default limit: 10, max: 50)
- `list_learning limit:N` - List pending learning items (default: 50, max: 200)
- `show_stats` - Display training statistics
- `show_context` - Show recent conversation history
- `clear_context` - Clear conversation history
- `bulk_train: [{"q":"query","a":"reply"},...]` - Bulk train multiple Q&A pairs at once
- `export_data what:all|chatbot|learning` - Export data as JSON file
- `import_data: {"chatbot":[...],"learning":[...]}` - Import JSON data

### Admin Panel
- Access at `admin.php` (password-protected)
- Default password: `changeme` (set `CHATBOT_ADMIN_PASS` env var to change)
- Features: View/edit/delete chatbot entries, train learning items, export/import data

### Configuration
- `CHATBOT_CONFIDENCE_THRESHOLD` - Set matching confidence threshold (0-100, default: 60)
  - Lower values = more lenient matching (matches more)
  - Higher values = stricter matching (only close matches)
  - Confidence score is shown below each matched reply

### Matching Algorithm
- Uses **Jaro-Winkler** (60% weight) + **Levenshtein** (40% weight) for improved accuracy
  - Jaro-Winkler excels at matching strings with common prefixes and short strings
  - Levenshtein provides good general-purpose distance calculation
  - Combined weighted score provides better overall matching accuracy

### Context Awareness
- **Session-based conversation history** - Tracks user and bot messages per session
- **Context-enhanced matching** - Uses recent conversation context to improve matching
  - Follow-up questions like "what about tomorrow?" work better after "what are your hours?"
  - Last 3 messages are included in matching calculations
- **Commands:**
  - `show_context` - View recent conversation history
  - `clear_context` - Clear conversation history for current session

### Feedback System
- **Message reactions** - Thumbs up/down buttons on bot messages
  - Users can provide feedback on helpful/unhelpful responses
  - Feedback stored in `feedback` table for analytics
  - Visual feedback shows which reaction was selected
  - Helps identify which responses need improvement

---

**Note:** Prioritize tasks based on your use case and user needs. Start with high-priority enhancements that add the most value.

