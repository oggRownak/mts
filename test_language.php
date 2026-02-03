<!DOCTYPE html>
<html>
<head>
    <title>Language Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .test-box {
            background: #f5f5f5;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Language Switching Test</h1>
    
    <div class="test-box">
        <h2>Test Instructions</h2>
        <ol>
            <li>Click the "Test English" button</li>
            <li>You should see index.php load in English immediately</li>
            <li>Click the Myanmar (🇲🇲 MY) button on the page</li>
            <li>Page should switch to Myanmar immediately with NO white screen</li>
            <li>Click the English (🇬🇧 EN) button on the page</li>
            <li>Page should switch to English immediately with NO white screen</li>
        </ol>
    </div>
    
    <div class="test-box">
        <h2>Quick Tests</h2>
        <button onclick="testEnglish()">Test English</button>
        <button onclick="testMyanmar()">Test Myanmar</button>
        <button onclick="testDirect()">Test Direct URL with ?lang=my</button>
    </div>
    
    <div class="test-box">
        <h2>Expected Results</h2>
        <div class="success">
            ✓ No white blank screens<br>
            ✓ Instant language switching<br>
            ✓ URL should be clean (no ?lang= parameter after redirect)<br>
            ✓ Language persists across page reloads
        </div>
    </div>
    
    <script>
        function testEnglish() {
            window.location.href = 'index.php?lang=en';
        }
        
        function testMyanmar() {
            window.location.href = 'index.php?lang=my';
        }
        
        function testDirect() {
            window.location.href = 'index.php?lang=my';
        }
    </script>
</body>
</html>
