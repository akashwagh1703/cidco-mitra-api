<?php
echo "<h2>Laravel Setup Check</h2>";

echo "<h3>1. Vendor folder:</h3>";
echo file_exists(__DIR__ . '/vendor/autoload.php') ? "✅ EXISTS" : "❌ MISSING";

echo "<h3>2. Bootstrap cache:</h3>";
echo is_writable(__DIR__ . '/bootstrap/cache') ? "✅ WRITABLE" : "❌ NOT WRITABLE";

echo "<h3>3. Storage folder:</h3>";
echo is_writable(__DIR__ . '/storage') ? "✅ WRITABLE" : "❌ NOT WRITABLE";

echo "<h3>4. .env file:</h3>";
echo file_exists(__DIR__ . '/.env') ? "✅ EXISTS" : "❌ MISSING";

echo "<h3>5. Database connection:</h3>";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo "✅ Laravel loads successfully";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>