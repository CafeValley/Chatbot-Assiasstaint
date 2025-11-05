<?php
session_start();
$ADMIN_PASS = getenv('CHATBOT_ADMIN_PASS') ?: 'changeme';
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}
$error = '';
if (isset($_POST['password'])) {
    $provided = (string)$_POST['password'];
    if ($provided !== '' && hash_equals($ADMIN_PASS, $provided)) {
        $_SESSION['is_admin'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Invalid password';
    }
}
if (empty($_SESSION['is_admin'])) {
    ?><!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Chatbot Admin Login</title>
        <link rel="stylesheet" href="style.css">
        <style>
        body { max-width: 420px; margin: 60px auto; font-family: Arial, sans-serif; }
        form { border: 1px solid #ddd; padding: 20px; border-radius: 6px; }
        input[type=password] { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 8px 14px; }
        .error { color: #b00020; margin-top: 8px; }
        .hint { color: #666; font-size: 12px; margin-top: 10px; }
        </style>
    </head>
    <body>
        <h1>Admin Login</h1>
        <form method="post">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Enter admin password" required />
            <button type="submit">Login</button>
            <?php if ($error) { echo '<div class="error">'.htmlspecialchars($error).'</div>'; } ?>
            <div class="hint">Set CHATBOT_ADMIN_PASS in environment to change the password.</div>
        </form>
    </body>
    </html><?php
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot Admin</title>
    <link rel="stylesheet" href="style.css">
    <script src="jquery-3.5.1.min.js"></script>
    <style>
    body { max-width: 1000px; margin: 20px auto; font-family: Arial, sans-serif; }
    h1 { margin-bottom: 10px; }
    .section { margin-top: 24px; }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th, td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
    th { background: #f5f5f5; text-align: left; }
    .controls { margin: 10px 0; display: flex; gap: 8px; flex-wrap: wrap; }
    input[type="text"], textarea { width: 100%; box-sizing: border-box; }
    .row-actions button { margin-right: 6px; }
    .small { font-size: 12px; color: #666; }
    </style>
    <script>
    $(function(){
        function sanitize(text){ return $('<div/>').text(text||'').html(); }

        function fetchChatbot(term, limit){
            term = term || '';
            limit = limit || 50;
            return $.post('message.php', { text: 'list_replies: ' + term + ' limit:' + limit }, null, 'json');
        }
        function fetchLearning(limit){
            limit = limit || 50;
            return $.post('message.php', { text: 'list_learning limit:' + limit }, null, 'json');
        }
        function removeById(id){
            return $.post('message.php', { text: 'remove_replay:id:' + id }, null, 'json');
        }
        function editById(id, reply){
            return $.post('message.php', { text: 'edit_replay:id:' + id + ': ' + reply }, null, 'json');
        }
        function trainLearning(id, reply){
            return $.post('message.php', { teach_for: id, reply: reply }, null, 'json');
        }
        function importJson(payload){
            return $.post('message.php', { text: 'import_data: ' + payload }, null, 'json');
        }
        function bulkTrain(payload){
            return $.post('message.php', { text: 'bulk_train: ' + payload }, null, 'json');
        }
        function exportData(what){
            return $.post('message.php', { text: 'export_data what:' + (what||'all') }, null, 'json');
        }

        function renderChatbot(items){
            var rows = items.map(function(it){
                return '<tr>'+
                    '<td>#'+it.id+'</td>'+
                    '<td>'+sanitize(it.query)+'</td>'+
                    '<td><textarea data-id="'+it.id+'" class="reply-edit">'+sanitize(it.reply)+'</textarea></td>'+
                    '<td class="row-actions">'+
                        '<button class="save-reply" data-id="'+it.id+'">Save</button>'+ 
                        '<button class="delete-reply" data-id="'+it.id+'">Delete</button>'+ 
                    '</td>'+
                '</tr>';
            }).join('');
            $('#chatbot-tbody').html(rows || '<tr><td colspan="4" class="small">No entries</td></tr>');
        }

        function renderLearning(items){
            var rows = items.map(function(it){
                return '<tr>'+
                    '<td>#'+it.id+'</td>'+
                    '<td>'+sanitize(it.query)+'</td>'+
                    '<td><input type="text" class="learn-reply" data-id="'+it.id+'" placeholder="Type reply to train"/></td>'+
                    '<td class="row-actions">'+
                        '<button class="train" data-id="'+it.id+'">Train</button>'+ 
                    '</td>'+
                '</tr>';
            }).join('');
            $('#learning-tbody').html(rows || '<tr><td colspan="4" class="small">No pending learning items</td></tr>');
        }

        function refreshAll(){
            var t = $('#search-term').val();
            var l = parseInt($('#limit').val()||'50', 10);
            $.when(fetchChatbot(t, l), fetchLearning(50)).done(function(a, b){
                var chatbot = (a[0] && a[0].items) ? a[0].items : [];
                var learning = (b[0] && b[0].items) ? b[0].items : [];
                renderChatbot(chatbot);
                renderLearning(learning);
            }).fail(function(){
                alert('Failed to load data.');
            });
        }

        // Init
        refreshAll();

        // Events
        $('#reload').on('click', refreshAll);
        $('#search').on('click', refreshAll);

        $(document).on('click', '.save-reply', function(){
            var id = $(this).data('id');
            var reply = $('textarea.reply-edit[data-id='+id+']').val();
            editById(id, reply).done(function(){
                refreshAll();
            });
        });

        $(document).on('click', '.delete-reply', function(){
            var id = $(this).data('id');
            if (!confirm('Delete reply #' + id + '?')) return;
            removeById(id).done(function(){
                refreshAll();
            });
        });

        $(document).on('click', '.train', function(){
            var id = $(this).data('id');
            var reply = $('input.learn-reply[data-id='+id+']').val();
            if (!reply) { alert('Please type a reply'); return; }
            trainLearning(id, reply).done(function(){
                refreshAll();
            });
        });

        $('#do-export').on('click', function(){
            var what = $('#export-what').val();
            exportData(what).done(function(res){
                try {
                    var blob = new Blob([JSON.stringify(res.data || {}, null, 2)], {type: 'application/json'});
                    var url = URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'chatbot_export_'+(what||'all')+'.json';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                } catch(e) { alert('Export error: ' + e); }
            });
        });

        $('#do-import').on('click', function(){
            var txt = $('#import-json').val();
            if (!txt) { alert('Paste JSON first'); return; }
            importJson(txt).done(function(res){
                alert('Imported - chatbot: '+((res.inserted&&res.inserted.chatbot)||0)+', learning: '+((res.inserted&&res.inserted.learning)||0));
                refreshAll();
                $('#import-json').val('');
            });
        });

        $('#do-bulk-train').on('click', function(){
            var txt = $('#bulk-train-json').val();
            if (!txt) { alert('Paste JSON array first'); return; }
            bulkTrain(txt).done(function(res){
                if(res && res.ok){
                    var msg = 'Bulk training: ' + (res.inserted||0) + ' / ' + (res.total||0) + ' inserted';
                    if(res.errors && res.errors.length > 0){
                        msg += '\nErrors: ' + res.errors.join(', ');
                    }
                    alert(msg);
                    refreshAll();
                    $('#bulk-train-json').val('');
                } else {
                    alert('Error: ' + (res.error || 'Unknown error'));
                }
            }).fail(function(){
                alert('Network error');
            });
        });
    });
    </script>
</head>
<body>
    <h1>Chatbot Admin</h1>
    <div class="small" style="margin-bottom:8px;">
        <a href="admin.php?logout=1">Logout</a>
    </div>
    <div class="controls">
        <input type="text" id="search-term" placeholder="Search queries..." />
        <input type="number" id="limit" value="50" min="1" max="500" />
        <button id="search">Search</button>
        <button id="reload">Reload</button>
        <select id="export-what">
            <option value="all">Export: All</option>
            <option value="chatbot">Export: Chatbot</option>
            <option value="learning">Export: Learning</option>
        </select>
        <button id="do-export">Download Export</button>
    </div>

    <div class="section">
        <h2>Chatbot Entries</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Query</th>
                    <th>Reply</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="chatbot-tbody"></tbody>
        </table>
    </div>

    <div class="section">
        <h2>Learning Queue</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Query</th>
                    <th>Reply to Train</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="learning-tbody"></tbody>
        </table>
    </div>

    <div class="section">
        <h2>Bulk Training</h2>
        <textarea id="bulk-train-json" rows="8" placeholder='[{"q":"hello","a":"hi there!"},{"q":"how are you","a":"I'\''m doing well, thanks!"}]'></textarea>
        <div class="controls">
            <button id="do-bulk-train">Bulk Train</button>
        </div>
        <div class="small">
            <strong>Format:</strong> JSON array of objects. Each object can use:<br/>
            • <code>{"q":"query","a":"reply"}</code> (short format)<br/>
            • <code>{"query":"query","reply":"reply"}</code> (full format)<br/>
            • <code>["query","reply"]</code> (array format)<br/>
            Example: <code>[{"q":"hi","a":"hello"},{"q":"bye","a":"goodbye"}]</code>
        </div>
    </div>

    <div class="section">
        <h2>Import</h2>
        <textarea id="import-json" rows="6" placeholder='{"chatbot":[{"query":"hi","reply":"hello!"}]}'></textarea>
        <div class="controls">
            <button id="do-import">Import JSON</button>
        </div>
        <div class="small">Tip: Use the export above to get a template, edit, then import.</div>
    </div>
</body>
</html>

