<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Chatbot in PHP | CampCodes</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="jquery-3.5.1.min.js"></script>
</head>
<style>
.help-toggle {
    background: #007bff;
    color: white;
    padding: 8px 12px;
    cursor: pointer;
    font-size: 12px;
    border-radius: 0 0 5px 5px;
    text-align: center;
    margin-top: -1px;
}
.help-toggle:hover { background: #006fef; }
.help-panel {
    display: none;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-top: none;
    padding: 15px;
    font-size: 12px;
    max-height: 300px;
    overflow-y: auto;
}
.help-panel h3 { margin: 0 0 10px 0; font-size: 14px; color: #007bff; }
.help-panel ul { margin: 0; padding-left: 20px; }
.help-panel li { margin: 5px 0; }
.help-panel code { background: #e9e9e9; padding: 2px 4px; border-radius: 3px; font-size: 11px; }
.msg-timestamp {
    font-size: 10px;
    color: #999;
    margin-top: 4px;
    text-align: right;
}
.user-inbox .msg-timestamp { text-align: left; }
.typing-indicator {
    display: none;
    padding: 8px 12px;
    color: #666;
    font-size: 12px;
    font-style: italic;
}
.typing-indicator.active {
    display: block;
}
.typing-indicator .dots {
    display: inline-block;
    width: 20px;
}
.typing-indicator .dots span {
    animation: typing 1.4s infinite;
    display: inline-block;
}
.typing-indicator .dots span:nth-child(2) { animation-delay: 0.2s; }
.typing-indicator .dots span:nth-child(3) { animation-delay: 0.4s; }
.reaction-buttons {
    margin-top: 6px;
    display: flex;
    gap: 8px;
    align-items: center;
}
.reaction-btn {
    background: transparent;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 4px 8px;
    cursor: pointer;
    font-size: 14px;
    color: #666;
    transition: all 0.2s;
}
.reaction-btn:hover {
    background: #f5f5f5;
    border-color: #007bff;
}
.reaction-btn.active {
    background: #e7f3ff;
    border-color: #007bff;
    color: #007bff;
}
.reaction-btn.thumbs-up.active { background: #d4edda; border-color: #28a745; color: #28a745; }
.reaction-btn.thumbs-down.active { background: #f8d7da; border-color: #dc3545; color: #dc3545; }
</style>
<body>
    <div class="wrapper">
        <div class="title">AI Chatbot Assistant</div>
        <div class="help-toggle" id="help-toggle">📋 Show Commands</div>
        <div class="help-panel" id="help-panel">
            <h3>Available Commands:</h3>
            <ul>
                <li><code>replay_with: &lt;reply&gt;</code> - Teach reply to last unknown question</li>
                <li><code>remove_replay: &lt;query&gt;</code> - Remove a learned reply by query</li>
                <li><code>remove_replay:id:&lt;id&gt;</code> - Remove reply by database ID</li>
                <li><code>edit_replay:id:&lt;id&gt;: &lt;new reply&gt;</code> - Edit reply by ID</li>
                <li><code>edit_replay:&lt;query&gt;: &lt;new reply&gt;</code> - Edit reply by query text</li>
                <li><code>add_reply:id:&lt;id&gt;: &lt;new reply&gt;</code> - Add another reply variant (creates multiple replies)</li>
                <li><code>add_reply:&lt;query&gt;: &lt;new reply&gt;</code> - Add reply variant by query</li>
                <li><code>list_replies: &lt;term&gt; limit:N</code> - List matching replies (default: 10, max: 50)</li>
                <li><code>list_learning limit:N</code> - List pending learning items (default: 50)</li>
                <li><code>show_stats</code> - Display training statistics</li>
                <li><code>show_context</code> - Show recent conversation history</li>
                <li><code>clear_context</code> - Clear conversation history</li>
                <li><code>bulk_train: [{"q":"query","a":"reply"},...]</code> - Bulk train multiple Q&A pairs at once</li>
                <li><code>export_data what:all|chatbot|learning</code> - Export data as JSON</li>
                <li><code>import_data: {"chatbot":[...],"learning":[...]}</code> - Import JSON data</li>
            </ul>
            <p style="margin-top: 10px; color: #666;"><strong>Tip:</strong> When the bot doesn't know something, you can teach it directly in the chat!</p>
        </div>
        <div class="form">
            <div class="bot-inbox inbox">
                <div class="icon">
                    <i class="fas fa-user"></i>
                </div>
                <div class="msg-header">
                    <p>Hello there, how can I help you?</p>
                    <div class="msg-timestamp"></div>
                </div>
            </div>
            <div class="typing-indicator" id="typing-indicator">
                Bot is typing<span class="dots"><span>.</span><span>.</span><span>.</span></span>
            </div>
        </div>
        <div class="typing-field">
            <div class="input-data">
                <input id="data" type="text" placeholder="Type something here.." required>
                <button id="send-btn">Send</button>
            </div>
        </div>
    </div>

    <script>
        function getTimestamp(){
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;
            return hours + ':' + minutes + ' ' + ampm;
        }

        $(document).ready(function(){
            // Set initial welcome message timestamp
            $('.msg-timestamp').first().text(getTimestamp());

            // Toggle help panel
            $('#help-toggle').on('click', function(){
                var panel = $('#help-panel');
                panel.slideToggle(200);
                $(this).text(panel.is(':visible') ? '📋 Hide Commands' : '📋 Show Commands');
            });

            function sanitize(text){
                return $('<div/>').text(text).html();
            }

            function showTyping(){
                $('#typing-indicator').addClass('active');
                $(".form").scrollTop($(".form")[0].scrollHeight);
            }

            function hideTyping(){
                $('#typing-indicator').removeClass('active');
            }

            var lastUserQuery = '';
            
            function appendBotMessage(text, extraHtml, query, reply){
                hideTyping();
                var safe = sanitize(text);
                var extra = extraHtml || '';
                var ts = getTimestamp();
                var messageId = 'msg_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                var queryVal = query || lastUserQuery || '';
                var replyVal = reply || text || '';
                
                var reactionsHtml = '<div class="reaction-buttons">' +
                    '<button class="reaction-btn thumbs-up" data-message-id="' + messageId + '" data-query="' + sanitize(queryVal) + '" data-reply="' + sanitize(replyVal) + '" title="Helpful">👍</button>' +
                    '<button class="reaction-btn thumbs-down" data-message-id="' + messageId + '" data-query="' + sanitize(queryVal) + '" data-reply="' + sanitize(replyVal) + '" title="Not helpful">👎</button>' +
                    '</div>';
                
                var html = '<div class="bot-inbox inbox" data-message-id="' + messageId + '">' +
                    '<div class="icon"><i class="fas fa-user"></i></div>' +
                    '<div class="msg-header">' +
                    '<p>'+ safe + extra +'</p>' +
                    reactionsHtml +
                    '<div class="msg-timestamp">'+ ts +'</div>' +
                    '</div></div>';
                $(".form").append(html);
                $(".form").scrollTop($(".form")[0].scrollHeight);
            }

            function appendUserMessage(text){
                var safe = sanitize(text);
                var ts = getTimestamp();
                var html = '<div class="user-inbox inbox"><div class="msg-header"><p>'+ safe +'</p><div class="msg-timestamp">'+ ts +'</div></div></div>';
                $(".form").append(html);
            }

            function sendMessage(){
                var value = $("#data").val();
                if(!value) return;
                lastUserQuery = value; // Store for feedback
                appendUserMessage(value);
                $("#data").val('');
                showTyping();
                $.ajax({
                    url: 'message.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { text: value },
                    success: function(res){
                        if(!res || res.ok === false){
                            appendBotMessage('Sorry, something went wrong.', '', '', '');
                            return;
                        }
                        // Command responses
                        if (res.command) {
                            if (res.type === 'list_replies') {
                                var lines = (res.items || []).map(function(it){
                                    var replyPart = '';
                                    if (it.multiple && Array.isArray(it.reply)) {
                                        replyPart = it.reply.map(function(r, idx){
                                            return '  ['+(idx+1)+'] ' + sanitize(r);
                                        }).join('<br/>');
                                    } else {
                                        replyPart = sanitize(it.reply);
                                    }
                                    var multiLabel = it.multiple ? ' ('+it.count+' variants)' : '';
                                    return '#'+it.id+': '+ sanitize(it.query) + multiLabel + '<br/>→ ' + replyPart;
                                });
                                appendBotMessage(lines.length ? lines.join('<br/><br/>') : 'No matches.', '', '', '');
                                return;
                            }
                            if (res.type === 'add_reply') {
                                appendBotMessage('Added reply variant. Total: ' + (res.total_replies || 0), '', '', '');
                                return;
                            }
                            if (res.type === 'bulk_train') {
                                var msg = 'Bulk training complete: ' + (res.inserted || 0) + ' / ' + (res.total || 0) + ' inserted';
                                if (res.errors && res.errors.length > 0) {
                                    msg += '<br/>Errors: ' + res.errors.join(', ');
                                }
                                appendBotMessage(msg, '', '', '');
                                return;
                            }
                            if (res.type === 'clear_context') {
                                appendBotMessage(res.message || 'Context cleared', '', '', '');
                                return;
                            }
                            if (res.type === 'show_context') {
                                var history = res.history || [];
                                if (history.length === 0) {
                                    appendBotMessage('No conversation history', '', '', '');
                                } else {
                                    var lines = history.map(function(h, idx){
                                        var userMsg = sanitize(h.user || '');
                                        var botMsg = sanitize(h.bot || '');
                                        return '<strong>User:</strong> ' + userMsg + (botMsg ? '<br/><strong>Bot:</strong> ' + botMsg : '');
                                    });
                                    appendBotMessage('Recent conversation (' + res.total + ' total):<br/><br/>' + lines.join('<br/><br/>'), '', '', '');
                                }
                                return;
                            }
                            if (res.type === 'edit_replay') {
                                appendBotMessage('Updated entries: ' + (res.updated || 0), '', '', '');
                                return;
                            }
                            if (res.type === 'show_stats') {
                                var s = res.stats || {};
                                appendBotMessage('Stats - chatbot: '+(s.chatbot||0)+', learning: '+(s.learning||0)+', matched: '+(s.history_matched||0)+', unmatched: '+(s.history_unmatched||0)+', total: '+(s.history_total||0)+', confidence threshold: '+(s.confidence_threshold||60)+'%', '', '', '');
                                return;
                            }
                            if (res.type === 'export_data') {
                                try {
                                    var blob = new Blob([JSON.stringify(res.data || {}, null, 2)], {type: 'application/json'});
                                    var url = URL.createObjectURL(blob);
                                    var a = document.createElement('a');
                                    a.href = url;
                                    var fname = 'chatbot_export_' + (res.what || 'all') + '.json';
                                    a.download = fname;
                                    document.body.appendChild(a);
                                    a.click();
                                    document.body.removeChild(a);
                                    URL.revokeObjectURL(url);
                                    appendBotMessage('Export prepared: ' + fname, '', '', '');
                                } catch(e) {
                                    appendBotMessage('Export error: ' + sanitize(String(e)), '', '', '');
                                }
                                return;
                            }
                            if (res.type === 'import_data') {
                                var ins = res.inserted || {};
                                appendBotMessage('Imported - chatbot: '+(ins.chatbot||0)+', learning: '+(ins.learning||0), '', '', '');
                                return;
                            }
                            // Fallback render
                            appendBotMessage(res.message || 'Command executed.', '', '', '');
                            return;
                        }
                        if(res.found){
                            var msg = res.reply || '';
                            var confHtml = '';
                            if (res.confidence !== undefined) {
                                confHtml = '<span style="font-size:10px; color:#999; display:block; margin-top:4px;">Confidence: ' + sanitize(String(res.confidence)) + '%</span>';
                            }
                            appendBotMessage(msg, confHtml, lastUserQuery, msg);
                        } else {
                            // Unknown: ask to teach
                            appendBotMessage(res.message || "I don't know that yet. Teach me a reply?", '', lastUserQuery, res.message || "I don't know that yet. Teach me a reply?");
                            // Render a small teach box
                            var ts = getTimestamp();
                            var teachBox = $('<div class="bot-inbox inbox">\
                                <div class="icon"><i class="fas fa-user"></i></div>\
                                <div class="msg-header">\
                                  <input type="text" class="teach-input" placeholder="Type the correct reply" data-learn-id="'+ res.learn_id +'" style="width: 70%; padding: 6px;"/>\
                                  <button class="teach-send" style="margin-left:8px; height:30px;">Teach</button>\
                                  <div class="msg-timestamp">'+ ts +'</div>\
                                </div>\
                              </div>');
                            $(".form").append(teachBox);
                            $(".form").scrollTop($(".form")[0].scrollHeight);
                        }
                    },
                    error: function(xhr){
                        hideTyping();
                        var status = xhr && xhr.status ? (' ' + xhr.status) : '';
                        var text = (xhr && xhr.responseText) ? xhr.responseText : '';
                        appendBotMessage('Network error' + status + (text ? ': ' + sanitize(text).slice(0,200) : '.'), '', '', '');
                    }
                });
            }

            $("#send-btn").on("click", function(){
                sendMessage();
            });

            $("#data").on('keydown', function(e){
                if(e.key === 'Enter'){
                    e.preventDefault();
                    sendMessage();
                }
            });

            $(document).on('click', '.teach-send', function(){
                var container = $(this).closest('.msg-header');
                var input = container.find('.teach-input');
                var reply = input.val();
                var learnId = input.data('learn-id');
                if(!reply) return;
                $.ajax({
                    url: 'message.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { teach_for: learnId, reply: reply },
                    success: function(res){
                        if(res && res.ok){
                            appendBotMessage('Thanks! I learned it.', '', '', '');
                            input.prop('disabled', true);
                            container.find('.teach-send').prop('disabled', true);
                        } else {
                            appendBotMessage('Could not save training.', '', '', '');
                        }
                    },
                    error: function(xhr){
                        var status = xhr && xhr.status ? (' ' + xhr.status) : '';
                        var text = (xhr && xhr.responseText) ? xhr.responseText : '';
                        appendBotMessage('Network error while training' + status + (text ? ': ' + sanitize(text).slice(0,200) : '.'), '', '', '');
                    }
                });
            });

            // Handle reaction buttons (thumbs up/down)
            $(document).on('click', '.reaction-btn', function(e){
                e.preventDefault();
                var btn = $(this);
                var messageId = btn.data('message-id');
                var query = btn.data('query') || '';
                var reply = btn.data('reply') || '';
                var feedbackType = btn.hasClass('thumbs-up') ? 'thumbs_up' : 'thumbs_down';
                
                // Disable both buttons temporarily
                btn.siblings('.reaction-btn').prop('disabled', true);
                btn.prop('disabled', true);
                
                $.ajax({
                    url: 'message.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        feedback: feedbackType,
                        message_id: messageId,
                        query: query,
                        reply: reply
                    },
                    success: function(res){
                        if(res && res.ok){
                            // Mark button as active
                            btn.addClass('active');
                            btn.siblings('.reaction-btn').removeClass('active');
                        } else {
                            // Re-enable on error
                            btn.siblings('.reaction-btn').prop('disabled', false);
                            btn.prop('disabled', false);
                        }
                    },
                    error: function(){
                        // Re-enable on error
                        btn.siblings('.reaction-btn').prop('disabled', false);
                        btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
    
</body>
</html>