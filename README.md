# xatBotstat
A PHP library for Illuxat.com. The goal of this repository is to give the ability for developers to use botstat api properly and easier.
## Requirements
- PHP 7.2
- Composer
## Getting Started
To get started, clone this repository and run ``composer install``.
## How to use?

Here is an example of use:

```
require_once 'vendor/autoload.php';

use xatBotstat\Botstat;

$botStat = new Botstat('MYTOKEN', 5, 110110);
$botStat::setUserName('SLOOM');
$botStat::setUserAvatar('https://i.imgur.com/mwfzvKw.png');
$result = $botStat::sendToXat();

echo ($result['error'] ? 'An error occured: ' . $result['message'] : 'OK');
```

Botstat class requires 3 arguments:
- Token - You can get it on xat.com/login and replace "MYTOKEN" by your token
- Room ID
- User ID

# Functions
> setUsername($name)

This allows you to set a new name.

> setUserAvatar($avatar)

This allows you to set a new avatar.

> setUserHomepage($home)

This allows you to set a new homepage.

> setUserStatus($status)

This allows you to set a new status. Note: You cannot set a name and a status in same time.

## Errors

As every apis in the world, they often return errors when something went wrong. The error code is often at the beginnig of the error message.

Here is the errors list:

- ERROR 5: The user was not found on the chatroom
- ERROR 6: The user needs status power enabled/set
- ERROR 7: The user needs botstat power
- ERROR 9: Too many request. 3 packets each 20 seconds.
- ERROR 10: Nothing to do. You didn't provide a correct parameter.

## Contribution
We know that things are never perfect. If you find an issue or if you have a suggestion to improve, feel free to open a pull request and i'll take a look at it quickly.
## Authors
* **Clément** - [xSlOom](https://github.com/xSlOom)
## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
