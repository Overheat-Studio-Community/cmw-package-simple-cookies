<?php

namespace CMW\Controller\SimpleCookies;

use CMW\Manager\Env\EnvManager;
use CMW\Manager\Package\AbstractController;
use CMW\Manager\Theme\Loader\ThemeLoader;
use function setcookie;

class SimpleCookiesController extends AbstractController
{
    /**
     *
     * This method securely stores a cookie on the client's side, we are also checking if the client accepted the cookie.
     *
     * <b>--- PHP setcookie() method documentation below: ---</b>
     * @link https://php.net/manual/en/function.setcookie.php
     * @param string $name <p>
     * The name of the cookie.
     * </p>
     * @param string $value [optional] <p>
     * The value of the cookie. This value is stored on the clients
     * computer; do not store sensitive information.
     * Assuming the name is 'cookiename', this
     * value is retrieved through $_COOKIE['cookiename']
     * </p>
     * @param int $expire [optional] <p>
     * The time the cookie expires. This is a Unix timestamp so is
     * in number of seconds since the epoch. In other words, you'll
     * most likely set this with the time function
     * plus the number of seconds before you want it to expire. Or
     * you might use mktime.
     * time()+60*60*24*30 will set the cookie to
     * expire in 30 days. If set to 0, or omitted, the cookie will expire at
     * the end of the session (when the browser closes).
     * </p>
     * <p>
     * <p>
     * You may notice the expire parameter takes on a
     * Unix timestamp, as opposed to the date format Wdy, DD-Mon-YYYY
     * HH:MM:SS GMT, this is because PHP does this conversion
     * internally.
     * </p>
     * <p>
     * expire is compared to the client's time which can
     * differ from server's time.
     * </p>
     * </p>
     * @param string $path [optional] <p>
     * The path on the server in which the cookie will be available on.
     * If set to '/', the cookie will be available
     * within the entire domain. If set to
     * '/foo/', the cookie will only be available
     * within the /foo/ directory and all
     * sub-directories such as /foo/bar/ of
     * domain. The default value is the
     * current directory that the cookie is being set in.
     * </p>
     * @param string $domain [optional] <p>
     * The domain that the cookie is available.
     * To make the cookie available on all subdomains of example.com
     * then you'd set it to '.example.com'. The
     * . is not required but makes it compatible
     * with more browsers. Setting it to www.example.com
     * will make the cookie only available in the www
     * subdomain. Refer to tail matching in the
     * spec for details.
     * </p>
     * @param bool $secure [optional] <p>
     * Indicates that the cookie should only be transmitted over a
     * secure HTTPS connection from the client. When set to true, the
     * cookie will only be set if a secure connection exists.
     * On the server-side, it's on the programmer to send this
     * kind of cookie only on secure connection (e.g. with respect to
     * $_SERVER["HTTPS"]).
     * </p>
     * @param bool $httpOnly [optional] <p>
     * When true the cookie will be made accessible only through the HTTP
     * protocol. This means that the cookie won't be accessible by
     * scripting languages, such as JavaScript. This setting can effectively
     * help to reduce identity theft through XSS attacks (although it is
     * not supported by all browsers). Added in PHP 5.2.0.
     * true or false
     * </p>
     * @return bool If output exists prior to calling this function,
     * storeCookie will fail and return false. If
     * storeCookie successfully runs, it will return true.
     * This does not indicate whether the user accepted the cookie.
     */
    public function storeCookie(string $name, string $value, int $expire = 0, string $path = '/', string $domain = "", bool $secure = true, bool $httpOnly = true): bool
    {
        if (!$this->hasClientConsent()) {
            return false;
        }

        return setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    /**
     * We are checking if we have the client consent.
     * @return bool
     */
    public function hasClientConsent(): bool
    {
        return isset($_COOKIE['simplecookies_consent']);
    }

    /**
     * @return bool
     * @desc We are storing cookie consent for 1 year in cookies.
     */
    public function storeClientConsent(): bool
    {
        //Store cookie consent for 1 year
        return setcookie('simplecookies_consent', '1', time() + 60 * 60 * 24 * 365, '/', '', true, true);
    }

    /**
     * Display or not the cookie consent banner.
     * We include the cookies banner from the current theme, path:
     * <b>Public/Themes/$theme/Views/SimpleCookies/cookies.banner.php</b>
     * @return void
     */
    public function showCookieConsent(): void
    {
        if (!$this->hasClientConsent()) {
            $currentThemeName = ThemeLoader::getInstance()->getCurrentTheme()->name();

            $path = EnvManager::getInstance()->getValue('DIR')
                . "Public/Themes/$currentThemeName/Views/SimpleCookies/cookies.banner.php";

            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    /**
     * @return callable
     * @desc Return a callable to show the cookie consent banner.
     */
    public function showCookieConsentCallable(): callable
    {
        return function () {
            $this->showCookieConsent();
        };
    }
}