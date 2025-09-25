<?php
/**
 * Root index file - redirects to public folder
 * This file handles cases where .htaccess doesn't work
 */

// Redirect to public folder
header('Location: /public/');
exit;