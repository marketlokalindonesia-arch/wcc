<!DOCTYPE html>
<html>
<head>
    <title>Debug Session & POS API</title>
    <style>
        body { font-family: monospace; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        pre { background: #f5f5f5; padding: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>🔍 Debug Session & POS API</h1>
    
    <h2>1. PHP Session Info</h2>
    <pre><?php
    session_start();
    require_once 'config/session.php';
    
    echo "Session ID: " . session_id() . "\n";
    echo "Logged In: " . (isLoggedIn() ? "YES ✓" : "NO ✗") . "\n";
    
    if (isLoggedIn()) {
        echo "User ID: " . $_SESSION['user_id'] . "\n";
        echo "Username: " . $_SESSION['username'] . "\n";
        echo "Role: " . $_SESSION['user_role'] . "\n";
        echo "\n";
        $user = getUser();
        echo "Full Name: " . $user['first_name'] . ' ' . $user['last_name'] . "\n";
    }
    
    echo "\nAll Session Data:\n";
    print_r($_SESSION);
    ?></pre>
    
    <h2>2. Test POS API via AJAX</h2>
    <button onclick="testSearch()">Test Search API (laptop)</button>
    <button onclick="testBarcode()">Test Barcode API (1234567890123)</button>
    <br><br>
    <div id="result"></div>
    
    <script>
        function testSearch() {
            document.getElementById('result').innerHTML = 'Loading...';
            fetch('/api/pos.php?action=search&q=laptop')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                    if (data.success) {
                        console.log('✓ API SUCCESS:', data);
                    } else {
                        console.error('✗ API FAILED:', data);
                    }
                })
                .catch(err => {
                    document.getElementById('result').innerHTML = '<span class="error">Error: ' + err + '</span>';
                    console.error('✗ FETCH ERROR:', err);
                });
        }
        
        function testBarcode() {
            document.getElementById('result').innerHTML = 'Loading...';
            fetch('/api/pos.php?action=get_by_barcode&barcode=1234567890123')
                .then(res => res.json())
                .then(data => {
                    document.getElementById('result').innerHTML = '<pre>' + JSON.stringify(data, null, 2) + '</pre>';
                    if (data.success) {
                        console.log('✓ API SUCCESS:', data);
                    } else {
                        console.error('✗ API FAILED:', data);
                    }
                })
                .catch(err => {
                    document.getElementById('result').innerHTML = '<span class="error">Error: ' + err + '</span>';
                    console.error('✗ FETCH ERROR:', err);
                });
        }
    </script>
    
    <hr>
    <p><a href="/?url=login">← Back to Login</a> | <a href="/?url=cashier/pos">Go to POS</a></p>
</body>
</html>
