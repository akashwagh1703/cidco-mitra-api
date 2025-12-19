<?php
// fresh-setup.php - Complete fresh setup with table drop

echo "<h2>CIDCO Mitra API - Fresh Setup</h2>";

try {
    // Set working directory
    chdir(__DIR__);
    
    echo "<p>1. Dropping all tables...</p>";
    $output = shell_exec('php artisan migrate:fresh --force 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<p>2. Generating application key...</p>";
    $output = shell_exec('php artisan key:generate --force 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<p>3. Running fresh migrations...</p>";
    $output = shell_exec('php artisan migrate --force 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<p>4. Seeding default data...</p>";
    $output = shell_exec('php artisan db:seed --force 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<p>5. Adding test data...</p>";
    $output = shell_exec('php artisan db:seed --class=FreshTestDataSeeder --force 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<p>6. Caching configuration...</p>";
    $output = shell_exec('php artisan config:cache 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<p>7. Caching routes...</p>";
    $output = shell_exec('php artisan route:cache 2>&1');
    echo "<pre>$output</pre>";
    
    echo "<h3 style='color: green;'>✅ Fresh Setup Completed!</h3>";
    echo "<div style='background: #f0f8ff; padding: 15px; border-radius: 5px;'>";
    echo "<h4>Login Credentials:</h4>";
    echo "<p><strong>Email:</strong> admin@cidcomitra.gov.in</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "<h4>Test Data Created:</h4>";
    echo "<ul>";
    echo "<li>✅ Users with Roles & Permissions</li>";
    echo "<li>✅ Services with Schedules</li>";
    echo "<li>✅ Sample Leads</li>";
    echo "<li>✅ Sample Appointments</li>";
    echo "<li>✅ Basic Settings</li>";
    echo "</ul>";
    echo "</div>";
    echo "<p style='color: red;'><strong>⚠️ Delete this file after setup for security!</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>