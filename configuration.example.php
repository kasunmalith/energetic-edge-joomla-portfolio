<?php
/**
 * Joomla configuration template for local development.
 * Copy this file to configuration.php and provide local, non-production values.
 */
class JConfig
{
    public $offline = false;
    public $sitename = 'Energetic Edge';
    public $dbtype = 'mysqli';
    public $host = 'localhost';
    public $user = 'YOUR_DATABASE_USER';
    public $password = 'YOUR_DATABASE_PASSWORD';
    public $db = 'YOUR_DATABASE_NAME';
    public $dbprefix = 'YOUR_TABLE_PREFIX_';
    public $secret = 'GENERATE_A_NEW_JOOMLA_SECRET';
    public $live_site = '';
    public $log_path = '/path/to/logs';
    public $tmp_path = '/path/to/tmp';
    public $mailonline = false;
    public $fromname = 'Energetic Edge';
    public $mailfrom = 'your-email@example.invalid';
    public $smtpauth = false;
    public $smtpuser = '';
    public $smtppass = '';
    public $smtphost = 'localhost';
    public $smtpport = 25;
    public $smtpsecure = 'none';
}
