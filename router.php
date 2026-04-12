<?php

declare(strict_types=1);

/**
 * Router script for PHP Built-in Server
 */

// Check if the request is for a static file
$requested_resource = $_SERVER["REQUEST_URI"];

error_log("DEBUG: Request for $requested_resource");

// Remove query string
$path_without_query = explode('?', $requested_resource)[0];

// Check if it's a static file
if (preg_match('/\.(js|css|png|jpg|gif|svg|ico|woff|woff2)$/', $path_without_query)) {
    error_log("DEBUG: Detected static file");
    return false;  // Let the server handle static files
}

// Check if file exists (for things like /index.php, /public/*, etc.)
if ($requested_resource !== '/' && file_exists(__DIR__ . $path_without_query)) {
    error_log("DEBUG: File exists at " . __DIR__ . $path_without_query);
    return false;
}

// Route everything else through index.php
error_log("DEBUG: Routing through index.php");
require_once __DIR__ . '/index.php';
