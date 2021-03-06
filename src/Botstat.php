<?php

namespace xatBotstat;

class Botstat
{
    /**
     * @var int|string
     */
    private static $roomID;

    /**
     * @var int|string
     */
    private static $userID;

    /**
     * @var String
     */
    private static $userToken;

    /**
     * @var String
     */
    private static $userName;

    /**
     * @var String
     */
    private static $userAvatar;

    /**
     * @var String
     */
    private static $userHome;

    /**
     * @var String
     */
    private static $userStatus;

    /**
     * @var String
     */
    private static $xatEndpoint = 'https://xat.com/api/botstat.php';

    /**
     * Botstat constructor.
     * @param String $token
     * @param int $roomID
     * @param int $userID
     */
    public function __construct(String $token, int $roomID, int $userID)
    {
        if (empty($token) || strlen($token) == 0) {
            throw new \Exception('Token is required to continue.');
        }

        if (empty($roomID) || !is_numeric($roomID)) {
            throw new \Exception('roomID must not be empty and must be integer.');
        }

        if (empty($userID) || !is_numeric($userID)) {
            throw new \Exception('userID must not be empty and must be integer.');
        }

        self::$userToken = $token;
        self::$roomID = $roomID;
        self::$userID = $userID;
    }

    /**
     * Send data to xat API
     * @return array
     * @throws \Exception
     */
    public static function sendToXat(): array
    {
        if (empty(self::getUserToken()) || empty(self::getRoomId()) || empty(self::getUserId())) {
            throw new \Exception('Token/RoomID/UserID are missing and we cannot continue without that.');
        }

        $urlToBuild = self::$xatEndpoint . '?';
        $arrParameters = [
            'r' => self::getRoomId(),
            'u' => self::getUserId(),
            'k' => self::getUserToken()
        ];

        if (!empty(self::getUserName())) {
            $arrParameters['n'] = self::getUserName();
        }
        if (!empty(self::getUserAvatar())) {
            $arrParameters['a'] = self::getUserAvatar();
        }
        if (!empty(self::getHomePage())) {
            $arrParameters['h'] = self::getHomePage();
        }
        if (!empty(self::getUserStatus())) {
            $arrParameters['s'] = self::getUserStatus();
        }

        $urlToBuild .= http_build_query($arrParameters);
        $result = self::getContent($urlToBuild);

        if (empty($result['response'])) {
            throw new \Exception("Error Processing Request");
        }

        $content = json_decode($result['response'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON.');
        }

        $arrContent = [];

        if ($content['error'] === true) {
            $arrContent['error'] = $content['error'];
            if (isset($content['message'])) {
                preg_match("|\d+|", $content['message'], $matches);
                if (!isset($matches[0])) {
                    $arrContent['message'] = $content['message'];
                } else {
                    $arrContent['errorCode'] = $matches[0];
                    $arrContent['message'] = self::getXatError($matches[0]);
                }
            }
        }

        return empty($arrContent) ? $content : $arrContent;
    }

    /**
     * Get the meaning of a xat error.
     * @param int $error
     * @return string
     */
    private static function getXatError(int $error): string
    {
        $err = null;
        switch ($error) {
            case 1:
                $err = 'The selected room was not found.';
                break;
            case 5:
                $err = 'The user was not found on the chatroom.';
                break;
            case 6:
                $err = 'The user needs status power enabled/set.';
                break;
            case 7:
                $err = 'The user needs BOTSTAT power.';
                break;
            case 9:
                $err = 'Too many request. 3 packets each 20 seconds.';
                break;
            case 10:
                $err = 'Nothing to do. You didn\'t provide a correct parameter.';
                break;
            default:
                $err = 'Unknown error.';
                break;
        }
        return $err;
    }

    /**
     * Get content from a URL
     * @param string $url
     * @return array|null
     */
    private static function getContent(string $url): ?array
    {
        if (empty($url) || strlen($url) == 0) {
            throw new \Exception('You must specify a url');
        }

        $curlInit = curl_init();
        curl_setopt($curlInit, CURLOPT_URL, $url);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curlInit);
        curl_close($curlInit);

        return [
            'response' => $output
        ];
    }

    /**
     * Set a name
     * @param String $name
     */
    public static function setUserName(String $name): void
    {
        self::$userName = $name;
    }

    /**
     * Set an avatar
     * @param String $avatar
     */
    public static function setUserAvatar(String $avatar): void
    {
        self::$userAvatar = $avatar;
    }

    /**
     * Set a homepage
     * @param String $homepage
     */
    public static function setHomePage(String $homepage): void
    {
        self::$userHome = $homepage;
    }

    /**
     * Set a status
     * @param String $status
     */
    public static function setUserStatus(String $status): void
    {
        self::$userStatus = $status;
    }

    /**
     * Get the name
     * @return string|null
     */
    public static function getUserName(): ?string
    {
        return self::$userName;
    }

    /**
     * Get the avatar
     * @return string|null
     */
    public static function getUserAvatar(): ?string
    {
        return self::$userAvatar;
    }

    /**
     * Get the homepage
     * @return string|null
     */
    public static function getHomePage(): ?string
    {
        return self::$userHome;
    }

    /**
     * Get the status
     * @return string|null
     */
    public static function getUserStatus(): ?string
    {
        return self::$userStatus;
    }

    /**
     * Get the token
     * @return string
     */
    public static function getUserToken(): string
    {
        return self::$userToken;
    }

    /**
     * Get the roomID
     * @return int
     */
    public static function getRoomId(): int
    {
        return self::$roomID;
    }

    /**
     * Get the userID
     * @return string
     */
    public static function getUserId(): string
    {
        return self::$userID;
    }
}
