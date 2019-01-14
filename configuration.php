<?php

$portal_root = "http://abre.abre.io";
$portal_private_root = "private_html";
if(!defined('SITE_GAFE_DOMAIN')){ define('SITE_GAFE_DOMAIN', '@abre.io'); }
if(!defined('DB_HOST')){ define('DB_HOST', 'localhost'); }
if(!defined('DB_NAME')){ define('DB_NAME', 'Abre'); }
if(!defined('DB_USER')){ define('DB_USER', 'root'); }
if(!defined('DB_PASSWORD')){ define('DB_PASSWORD', 'root'); }
if(!defined('DB_KEY')){ define('DB_KEY', 'VyMmpWEZXXUrmrTMZoAwbaYGnE0ysUkn'); }
if(!defined('GOOGLE_CLIENT_ID')){ define('GOOGLE_CLIENT_ID', '1022398225357-jf315ollk61koa33ubj49orf98beiopn.apps.googleusercontent.com'); }
if(!defined('GOOGLE_CLIENT_SECRET')){ define('GOOGLE_CLIENT_SECRET', 'V47rLbq3306dG1NW7efgxDRK'); }
if(!defined('GOOGLE_API_KEY')){ define('GOOGLE_API_KEY', 'AIzaSyA5-WL0PBH9TWKGJA1Tg5aIlYk97q1YmBU'); }
if(!defined('GOOGLE_HD')){ define('GOOGLE_HD', 'abre.io'); }
if(!defined('PORTAL_COOKIE_KEY')){ define('PORTAL_COOKIE_KEY', '344c0b1e14'); }
if(!defined('PORTAL_COOKIE_NAME')){ define('PORTAL_COOKIE_NAME', 'Abre'); }
ini_set('display_errors', 'off');
$portal_path_root = $_SERVER['DOCUMENT_ROOT'];
if(!defined('GOOGLE_REDIRECT')){ define('GOOGLE_REDIRECT', $portal_root.'/index.php'); }
if(!defined('GOOGLE_SCOPES')){ define('GOOGLE_SCOPES', serialize (array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/gmail.modify', 'https://www.googleapis.com/auth/drive.readonly', 'https://www.googleapis.com/auth/calendar.readonly', 'https://www.googleapis.com/auth/classroom.courses.readonly', 'https://www.googleapis.com/auth/classroom.rosters.readonly'))); }
if(!defined('STREAM_CACHE')){ define('STREAM_CACHE', 'true'); }
if(!defined('SITE_MODE')){ define('SITE_MODE', 'PRODUCTION'); }
if(!defined('DB_SOCKET')){ define('DB_SOCKET', ''); }
if(!defined('USE_GOOGLE_CLOUD')){ define('USE_GOOGLE_CLOUD', ''); }

?>
