<?php 
function siteURL() {
	$protocol = (! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
	$domainName = $_SERVER ['HTTP_HOST'];
// 	$domainName = getcwd();
// 	$domainName = __DIR__;
	return $protocol . $domainName;
}
define ( 'SITE_URL', siteURL () );
?>