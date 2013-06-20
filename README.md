Yourls-comscore plugin
======================

## Configuration

The Yourls-comscore plugin is configured using defined constants in 
`user/config.php`. The following options are available:

 Name                     | Description 
--------------------------|--------------------------------------------------------
 COMSCORE_URL             | The ComScore tracking URL. This parameter is mandatory.
 COMSCORE_USE_COOKIES     | TRUE to use cookies when visiting the tracking URL. Default: `true`.
 COMSCORE_COOKIE_FILE     | Path to the file in which the cookies are stored. Default: `user/comscore.cookies`.
 COMSCORE_HTTP_PROXY_HOST | Optional hostname for the http proxy that should be used to get the tracking URL.
 COMSCORE_HTTP_PROXY_PORT | Optional port for the http proxy that should be used to get the tracking URL.

These configuration options should be set in the YOURLS `user/config.php` file.

### The COMSCORE_URL

The COMSCORE_URL is the URL to the tracking image. The COMSCORE_URL is 
formatted (using `sprintf()`) with two additional values: the yourls shorturl, 
and the current `time()`.

### Example configuration

```php

    // The ComScore tracking URL. %1$s is replaced with the shorturl, and 
    // %2$s is replaced with the current time(stamp).
    define('COMSCORE_URL', 'http://nl.sitestat.com/mycorp/mysite/s?page.%1$s&ns__t=%2$s');

    // Enable the use of cookies, default: true.
    // define('COMSCORE_USE_COOKIES', true);

    // Path to the file in which the cookies are stored.
    define('COMSCORE_COOKIE_FILE', '/path/to/the/cookies/file');

    // Optional proxy settings.
    define('COMSCORE_HTTP_PROXY_HOST', 'proxy.mydomain.com');
    define('COMSCORE_HTTP_PROXY_PORT', '80');

```