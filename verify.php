<?php
session_start();

$correct_code = "12345678";
$max_attempts = 10;
$lockout_time = 300;

if (!isset($_SESSION['attempts'])) $_SESSION['attempts'] = 0;
if (!isset($_SESSION['locked_until'])) $_SESSION['locked_until'] = 0;

$now = time();

if ($now < $_SESSION['locked_until']) {
    $wait = $_SESSION['locked_until'] - $now;
    echo "<h2>Locked Out</h2><p>Please wait {$wait} seconds and try again.</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST["passcode"];

    if ($input === $correct_code) {
        $_SESSION['attempts'] = 0;
        // Show an image instead of text
        echo '<!DOCTYPE html>
        <html>
        <head><title>Welcome</title></head>
        <body style="text-align:center; margin-top:50px;">
          <h2>The REAL MANGO!!!</h2>
          <img src="mango.jpeg" alt="Welcome Image" style="max-width: 80%; height:auto;">
        </body>
        </html>';
    } else {
        $_SESSION['attempts']++;
        if ($_SESSION['attempts'] >= $max_attempts) {
            $_SESSION['locked_until'] = $now + $lockout_time;
            $_SESSION['attempts'] = 0;
            echo "<h2>Too Many Attempts</h2><p>Please wait 5 minutes and try again.</p>";
        } else {
            $remaining = $max_attempts - $_SESSION['attempts'];
            echo "<h2>Wrong Code</h2><p>{$remaining} attempts left.</p>";
        }
    }
}
?>
