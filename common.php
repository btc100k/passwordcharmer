<?php
    include_once('config/config.php');

    // TODO: Build the url from the props.  This is untenable right now for dev since
    // we don't have a per-dev prop file that gets read
    $submitUrl = $submitUrl.constant('SUBMIT_PATH');
    define('SUBMIT_URL', $submitUrl);


    ob_start("ob_gzhandler");

	error_reporting(E_ERROR | E_WARNING | E_PARSE);

	$ALLOW_CROSS_DOMAIN = TRUE;
	
	function isHttps() {
		if ( isset($_SERVER['HTTPS']) ) {
			if ( 'on' == strtolower($_SERVER['HTTPS']) ) {
				return true;
			} else if ( '1' == $_SERVER['HTTPS'] ) {
				return true;
			}
		}
		return false;
	}

    // todo: this should all be done with apache, not php
	function redirectToHttps() {
        $shouldRedirect = false;
        $redirectUrl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

        if (substr($_SERVER['HTTP_HOST'], 0, 4) !== 'www.' && constant('REQUIRES_WWW')) {
            $redirectUrl = 'www.'.$redirectUrl;
            $shouldRedirect = true;
        }

        if (isHttps() == false && constant('REQUIRES_HTTPS')) {
            $redirectUrl = 'https://'.$redirectUrl;
            $shouldRedirect = true;
        } else if ($shouldRedirect) {
            if (isHttps()) {
                $redirectUrl = 'https://'.$redirectUrl;
            } else {
                $redirectUrl = 'http://'.$redirectUrl;
            }
        }

        if ($shouldRedirect) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$redirectUrl);
            exit();
        }
	}
	
	function sendNoCacheHeader() {
		header('Cache-Control: no-store');
	}
	
	function sendCanCacheHeader() {
		//header('Cache-Control: public, max-age=3600');
		header('Cache-Control: no-cache');
	}

function printTwitter() {
?>
<!-- from common.php -->

<!--
<br>
<a href="https://twitter.com/share" class="twitter-share-button" data-via="PasswordCharmer">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
-->
<div>(Disable Twitter For Now)</div>
<?php
}

function printDonations() {
?>

	<!-- from common.php -->

	<br>
	Please support PasswordCharmer with a few Satoshi.
	<br>
	<img src="bitcoin.png" alt="bc1qr5djprqp4zvxhmwztr83undhgukl6lfh4esfzh">
<?php
}
?>

