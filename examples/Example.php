<?php

require_once __DIR__ . '/../vendor/autoload.php';

use xatBotstat\Botstat;

$botStat = new Botstat('MYTOKEN', 5, 110110);
$botStat::setUserName('SLOOM');
$botStat::setUserAvatar('https://i.imgur.com/mwfzvKw.png');
$result = $botStat::sendToXat();

echo ($result['error'] ? 'An error occured: ' . $result['message'] : 'OK');
