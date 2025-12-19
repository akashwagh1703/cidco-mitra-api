<?php
// setup.php - Run this via browser to setup Laravel

echo "<h2>CIDCO Mitra API Setup</h2>";

try {
    // Set working directory
    chdir(__DIR__);
    
    echo "<p>1. Generating application key...</p>";
    $output = shell_exec('php artisan key:generate --force 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<p>2. Running migrations...</p>";
    $output = shell_exec('php artisan migrate --force 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<p>3. Seeding database...</p>";
    $output = shell_exec('php artisan db:seed --force 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<p>4. Adding test data...</p>";
    $output = shell_exec('php artisan db:seed --class=TestDataSeeder --force 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<p>5. Caching config...</p>";
    $output = shell_exec('php artisan config:cache 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<h3 style='color: green;'>Setup completed!</h3>";
    echo "<p><strong>Test Data Added:</strong></p>";
    echo "<ul>";
    echo "<li>Admin User: admin@cidcomitra.gov.in / admin123</li>";
    echo "<li>3 Test Leads</li>";
    echo "<li>2 Test Services</li>";
    echo "<li>2 Test Appointments</li>";
    echo "<li>Basic Settings</li>";
    echo "</ul>";
    echo "<p><strong>Delete this file after setup!</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?>