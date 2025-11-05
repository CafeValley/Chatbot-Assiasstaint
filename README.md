# AI Chatbot Assistant

A powerful, trainable chatbot built with PHP, MySQL, and JavaScript. This chatbot learns from interactions and can be trained in real-time through a user-friendly interface.

![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-blue)
![MySQL](https://img.shields.io/badge/MySQL-5.7%2B-orange)
![License](https://img.shields.io/badge/License-MIT-green)

## 🚀 Features

### Core Functionality
- **Interactive Chat Interface** - Clean, modern chat UI with real-time messaging
- **Trainable AI** - Teach the bot new responses directly in the chat
- **Smart Matching** - Advanced fuzzy matching using Jaro-Winkler + Levenshtein algorithms
- **Confidence Threshold** - Only responds when confident (configurable)
- **Multiple Replies** - Store and randomly rotate multiple response variants per query
- **Context Awareness** - Remembers conversation context for better follow-up question handling

### Training & Management
- **In-Chat Training** - Teach bot new responses when it doesn't know an answer
- **Bulk Training** - Upload multiple Q&A pairs at once via JSON
- **Admin Dashboard** - Complete management interface with password protection
- **Export/Import** - Backup and restore chatbot data as JSON
- **Command System** - Powerful commands for training and management

### User Experience
- **Typing Indicator** - Shows "bot is typing..." animation
- **Message Timestamps** - Timestamps on all messages
- **Message Reactions** - Thumbs up/down feedback on responses
- **Command Help Panel** - Built-in help with all available commands
- **Responsive Design** - Works on desktop and mobile devices

### Security & Performance
- **SQL Injection Protection** - Prepared statements throughout
- **XSS Protection** - HTML sanitization on all user inputs
- **Session Management** - Secure session-based authentication
- **Database Optimization** - Efficient queries with proper indexing

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Apache/Nginx web server (or PHP built-in server)
- Modern web browser (Chrome, Firefox, Safari, Edge)

## 🛠️ Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/Chatbot-Assiasstaint.git
cd Chatbot-Assiasstaint
```

### 2. Database Setup

Create a MySQL database and import the schema:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE bot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bot;
```

Then import the SQL file:

```bash
mysql -u root -p bot < botv3.sql
```

Or manually create the tables:

```sql
CREATE TABLE `chatbot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queries` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `replies` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `learning` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queries` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `replies` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `history_chat` (
  `id` int(150) NOT NULL AUTO_INCREMENT,
  `text` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `replay` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Feedback table (created automatically, but can be created manually)
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` varchar(100) NOT NULL,
  `query` text,
  `reply` text,
  `feedback_type` enum('thumbs_up','thumbs_down') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_message_id` (`message_id`),
  KEY `idx_feedback_type` (`feedback_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 3. Configuration

Edit `config.php` or set environment variables:

```php
// Default values in config.php:
$DB_HOST = 'localhost';        // or '127.0.0.1' for TCP
$DB_USER = 'root';
$DB_PASS = 'your_password';    // ⚠️ CHANGE THIS!
$DB_NAME = 'bot';
$DB_PORT = 3306;
```

**Using Environment Variables (Recommended):**

Create a `.env` file or set environment variables:

```bash
export CHATBOT_DB_HOST=localhost
export CHATBOT_DB_USER=root
export CHATBOT_DB_PASS=your_password
export CHATBOT_DB_NAME=bot
export CHATBOT_DB_PORT=3306
export CHATBOT_ADMIN_PASS=changeme
export CHATBOT_CONFIDENCE_THRESHOLD=60
```

### 4. Start the Server

**Using PHP Built-in Server:**
```bash
php -S localhost:8000
```

**Using Apache/Nginx:**
- Place files in your web server's document root
- Ensure PHP is enabled
- Access via `http://localhost/your-project-folder/`

### 5. Access the Application

- **Chat Interface**: `http://localhost:8000/bot.php`
- **Admin Panel**: `http://localhost:8000/admin.php`
  - Default password: `changeme`
  - Change via `CHATBOT_ADMIN_PASS` environment variable

## 📖 Usage

### Basic Chat

1. Open `bot.php` in your browser
2. Type a message and press Enter or click Send
3. If the bot doesn't know the answer, it will ask you to teach it
4. Provide the correct reply in the teaching box

### Training Commands

All commands can be typed directly in the chat:

| Command | Description | Example |
|---------|-------------|---------|
| `replay_with: <reply>` | Teach reply to last unknown question | `replay_with: Hello! How can I help?` |
| `add_reply:id:<id>: <reply>` | Add another reply variant | `add_reply:id:5: Goodbye!` |
| `edit_replay:id:<id>: <reply>` | Edit existing reply | `edit_replay:id:5: Hi there!` |
| `remove_replay:<query>` | Remove a learned reply | `remove_replay:hello` |
| `list_replies: <term>` | List matching replies | `list_replies:hello limit:10` |
| `show_stats` | Show training statistics | `show_stats` |
| `bulk_train: [{"q":"query","a":"reply"}]` | Bulk train multiple pairs | See example below |
| `export_data what:all` | Export data as JSON | `export_data what:chatbot` |
| `show_context` | Show conversation history | `show_context` |
| `clear_context` | Clear conversation history | `clear_context` |

### Bulk Training Example

```json
bulk_train: [{"q":"hello","a":"Hi there!"},{"q":"how are you","a":"I'm doing well, thanks!"},{"q":"goodbye","a":"See you later!"}]
```

### Admin Panel

Access `admin.php` to:
- View all chatbot entries
- Edit/delete replies
- Train pending learning items
- Bulk train multiple Q&A pairs
- Export/import data
- Search and filter entries

## ⚙️ Configuration Options

### Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `CHATBOT_DB_HOST` | `localhost` | Database host |
| `CHATBOT_DB_USER` | `root` | Database username |
| `CHATBOT_DB_PASS` | (empty) | Database password |
| `CHATBOT_DB_NAME` | `bot` | Database name |
| `CHATBOT_DB_PORT` | `3306` | Database port |
| `CHATBOT_ADMIN_PASS` | `changeme` | Admin panel password |
| `CHATBOT_CONFIDENCE_THRESHOLD` | `60` | Matching confidence threshold (0-100) |

### Confidence Threshold

- **Lower values (30-50)**: More lenient matching, responds to more queries
- **Default (60)**: Balanced matching
- **Higher values (70-90)**: Stricter matching, only very similar queries

## 🎯 Matching Algorithm

The chatbot uses a hybrid matching approach:

- **Jaro-Winkler Algorithm (60% weight)**: Excellent for strings with common prefixes
- **Levenshtein Distance (40% weight)**: General-purpose string similarity
- **Combined Score**: Weighted average for optimal accuracy

## 📁 Project Structure

```
Chatbot-Assiasstaint/
├── bot.php              # Main chat interface
├── admin.php            # Admin dashboard
├── message.php          # API endpoint for messages
├── config.php           # Configuration file
├── style.css            # Stylesheet
├── jquery-3.5.1.min.js  # jQuery library
├── botv3.sql            # Database schema
├── task.md              # Development roadmap
└── README.md            # This file
```

## 🔒 Security Notes

⚠️ **Important Security Considerations:**

1. **Change Default Passwords**: Update `CHATBOT_ADMIN_PASS` and database credentials
2. **Environment Variables**: Don't commit `.env` files or `config.php` with real credentials
3. **Database Access**: Use a dedicated database user with minimal privileges
4. **HTTPS**: Use HTTPS in production
5. **Session Security**: Configure secure session settings for production

## 🐛 Troubleshooting

### Database Connection Issues

**Error: "No such file or directory" (macOS)**
- Solution: The config automatically uses TCP (127.0.0.1) instead of socket on macOS

**Error: "Access denied"**
- Check database username and password in `config.php`
- Verify database user has proper permissions

### Chat Not Working

- Check browser console for JavaScript errors
- Verify PHP error logs
- Ensure `message.php` is accessible and returns JSON

### Admin Panel Login Issues

- Default password is `changeme`
- Change via `CHATBOT_ADMIN_PASS` environment variable
- Clear browser cookies if session issues persist

## 🚀 Future Enhancements

See `task.md` for the complete roadmap, including:
- Synonym/alias support
- Intent classification
- Chat history persistence
- Analytics dashboard
- Voice input/output
- Multi-language support
- And more!

## 📝 License

This project is open source and available under the [MIT License](LICENSE).

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Support

For issues, questions, or contributions, please open an issue on GitHub.

## 🙏 Acknowledgments

- Built with PHP, MySQL, jQuery, and modern web technologies
- Inspired by the need for trainable, context-aware chatbots

---

**Made with ❤️ for developers who want a trainable chatbot**
