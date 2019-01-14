<?php

$portal_root = "http://localhost";
$portal_private_root = "private_html";
if(!defined('SITE_GAFE_DOMAIN')){ define('SITE_GAFE_DOMAIN', '@'); }
if(!defined('DB_HOST')){ define('DB_HOST', 'localhost'); }
if(!defined('DB_NAME')){ define('DB_NAME', 'Abre'); }
if(!defined('DB_USER')){ define('DB_USER', 'root'); }
if(!defined('DB_PASSWORD')){ define('DB_PASSWORD', 'root'); }
if(!defined('DB_KEY')){ define('DB_KEY', 'dq23DICXdCHFi0tstToM3VqE4Kj8dIgr'); }
if(!defined('GOOGLE_CLIENT_ID')){ define('GOOGLE_CLIENT_ID', '655868092525-j7p7gvsemmb6j23u332esgqhcmgo5sm9.apps.googleusercontent.com'); }
if(!defined('GOOGLE_CLIENT_SECRET')){ define('GOOGLE_CLIENT_SECRET', 'e5oWzlsf8_g1hY812gT8MfdT'); }
if(!defined('GOOGLE_API_KEY')){ define('GOOGLE_API_KEY', 'AIzaSyA27_L3TNP-hKFzEUgpCpeLnXx9DlcQzOM'); }
if(!defined('GOOGLE_HD')){ define('GOOGLE_HD', ''); }
if(!defined('PORTAL_COOKIE_KEY')){ define('PORTAL_COOKIE_KEY', '472457acef'); }
if(!defined('PORTAL_COOKIE_NAME')){ define('PORTAL_COOKIE_NAME', 'Abre'); }
ini_set('display_errors', 'off');
$portal_path_root = $_SERVER['DOCUMENT_ROOT'];
if(!defined('GOOGLE_REDIRECT')){ define('GOOGLE_REDIRECT', $portal_root.'/index.php'); }
if(!defined('GOOGLE_SCOPES')){ define('GOOGLE_SCOPES', serialize (array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/gmail.modify', 'https://www.googleapis.com/auth/drive.readonly', 'https://www.googleapis.com/auth/calendar.readonly', 'https://www.googleapis.com/auth/classroom.courses.readonly', 'https://www.googleapis.com/auth/classroom.rosters.readonly'))); }
if(!defined('STREAM_CACHE')){ define('STREAM_CACHE', 'true'); }

?>