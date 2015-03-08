<!DOCTYPE html>
<html>
	<head>
		<!-- content type -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Style-Type" content="text/css" />
		
		<!-- open graph -->
		<meta property="og:site_name" content="Onboard" />
		<meta property="og:title" content="Get onboard with your friends!" />
		<meta property="og:description" content="Share your interests. See what your friends are up to. Because activities are more fun with friends. Get onboard!" />
		<meta property="og:image" content="http://<?php echo Utilities::GetMyDomain()?>/images/logo_social.png" />
		<meta property="fb:app_id" content="<?php echo FacebookUtilities::GetFacebookAppId() ?>" />
		
		<!-- layout and styles -->
		<title>Onboard - <?php echo $_PAGE_TITLE; ?></title>
		<link rel="shortcut icon" href="/images/logo.ico" type="image/x-icon"/>
		<?php if ($_MOBILE_DETECT->isMobile()):?>
			<link rel="stylesheet" href="/css/mobile.css" type="text/css" media="screen" />
		<?php else:?>
			<link rel="stylesheet" href="/css/screen.css" type="text/css" media="screen" />
		<?php endif?>
		
		<!-- javascript includes -->
		<script type="text/javascript" src="/js/jquery-1.11.1.js"></script>
		<script type="text/javascript" src="/js/autogrow.min.js"></script>
	</head>
	<body>