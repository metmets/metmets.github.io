<html>
<head>
    <title>phpVMS Install Checker</title>
    <style>
        body {
            font-family: "Lucida Grande", Verdana, Geneva, Sans-serif;
            font-size: 11px;
            line-height: 1.8em;
        }

        span {
            font-weight: bold;
        }

        .style1 {
            color: #F60;
            font-size: x-large;
            filter: DropShadow(Color=#000, OffX=5, OffY=5, Positive=10);
        }

        .style2 {
            font-size: small;
        }
    </style>
</head>
<body>
<strong><span class="style1">phpVMS</span> <span
        class="style2">Virtual Airline Administration Software</span></strong><br/>
<strong>Install Check</strong>
<br/><br/>

<?php

/* Check install
	This checks a set of directories against a hash list generated by md5sum

*/
error_reporting(E_ALL);
ini_set('display_errors', 'on');

# Pure laziness
define('DS', DIRECTORY_SEPARATOR);

# Path to this file
define('ROOT_PATH', dirname(dirname(__FILE__)));

# Path to the hash list
define('HASH_LIST', ROOT_PATH . DS . 'install' . DS . 'hashlist');
define('PHPVMS_API_SERVER', 'http://api.phpvms.net');

/* includes
*/
include ROOT_PATH . DS . 'core' . DS . 'classes' . DS . 'CodonWebService.class.php';


function error($title, $txt)
{
    echo "<span style=\"color: red\">[{$title}]</span> {$txt}<br />";
}

function success($title, $txt)
{
    echo "<span style=\"color: #006600\">[{$title}]</span> {$txt}<br />";
}


/* Rest of the script begins here */
echo "<strong>phpVMS Build Number: </strong> " . file_get_contents(ROOT_PATH . '/core/version');
echo '<br /><br />';

echo '<strong>Checking PHP version</strong><br />';
$version = phpversion();
success('OK', "PHP version is {$version}.x");
echo '<br />';

echo '<strong>ASP Tags</strong><br />';

$val = ini_get('asp_tags');
if (!empty($val)) {
    error('Error!', 'The setting "asp_tags" in php.ini must be off!');
} else {
    success('OK', 'ASP-style tags are disabled');
}


echo '<br />';
echo '<strong>Checking connectivity...</strong><br />';
$file = new CodonWebService();
$contents = @$file->get(PHPVMS_API_SERVER . '/version');

if ($contents == '') {
    $error = $file->errors[count($file->errors) - 1];
    error('Connection failed', 'Could not connect to remote server - error is "' . $error . '"');
} else {
    success('OK', 'Can contact outside servers');
}

unset($file);


/* Simple XML? */

echo '<br />';
echo '<strong>Checking for SimpleXML module...</strong><br />';

if (function_exists('simplexml_load_string') == true) {
    success('OK', 'SimpleXML module exists!');
} else {
    error('Fail', 'SimpleXML module doesn\'t exist or is not installed. Contact your host');
}

/* File hashes check */

echo '<br >';
echo '<strong>Checking file hashes for corrupt or mismatched files</strong><br />';

$fp = fopen(HASH_LIST, 'r');

if (!$fp) {
    error('Fatal', 'Could not read ' . HASH_LIST);
    exit;
}

$total = 0;
$errors = 0;
while (!feof($fp)) {
    $line = fgets($fp);

    $line = trim($line);
    if (empty($line)) {
        continue;
    }

    fscanf($fp, '%s %s', $checksum, $file);
    $total++;
    $file = str_replace('*./', '../', $file);

    if ($file == '../core/local.config.php' || substr_count($file, 'unittest') > 0 || empty($file)) {
        continue;
    }

    if (!file_exists($file)) {
        $errors++;
        error('Error', "{$file} doesn't exist");
        continue;
    }

    $calc_sum = md5_file($file);
    $file = str_replace('../', '/', $file); # make pretty
    if ($calc_sum === false) {
        $errors++;
        error('Checksum failed', "{$file} - permissions might be incorrect!");
        continue;
    }

    if ($calc_sum != $checksum) {
        $errors++;
        error('Checksum failed', "{$file} did not match, possibly corrupt or out of date");
        continue;
    }

    $file = '';
}

if ($errors == 0) {
    success('OK', 'No errors found!');
}

echo "<br /><strong> -- Checked {$total} files, found {$errors} errors</strong><br />";
?>
</body>
</html>
