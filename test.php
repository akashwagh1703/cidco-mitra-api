<?php
echo json_encode([
    'status' => 'API folder is working',
    'timestamp' => date('Y-m-d H:i:s'),
    'path' => __DIR__
]);
?>