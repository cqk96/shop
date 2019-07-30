<html>
<head>
	<title><?php echo $title;?></title>
	<meta name=viewport content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
	<style type="text/css">
	html,body {
		padding: 0px;
		margin: 0px;
	}
	.my-container {
		overflow-x: hidden;
	}
	.my-iframe {
		width: 100%;
	    height: 100%;
	    border: none;
	}
	</style>
</head>
<body class="my-container">
	<iframe class="my-iframe" src="<?php echo $url; ?>"></iframe>
</body>
</html>