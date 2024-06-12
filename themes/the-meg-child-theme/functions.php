<?php

// Function to recursively include PHP files from a directory and its subdirectories
function include_php_files($dir) {
    // Loop through each file and folder in the directory
    foreach (glob($dir . '/*') as $file) {
        // If it's a directory, recurse into it
        if (is_dir($file)) {
            include_php_files($file);
        } 
        // If it's a PHP file, include it
        elseif (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            include_once $file;
        }
    }
}

// Start the inclusion from the 'inc' folder in the stylesheet directory
include_php_files(get_stylesheet_directory() . '/inc');