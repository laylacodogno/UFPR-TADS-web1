<?php if ( ! defined('ABSPATH')) exit; ?>
 
<!DOCTYPE html>
<html lang="pt-BR">

 
<head>
	<meta charset="UTF-8">
 
	<link href="/web1-trans/web/views/_styles/stylesheets/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
 
	<!--[if lt IE 9]>
	<script src="<?php echo HOME_URI;?>/views/_js/scripts.js"></script>
	<![endif]-->
 
	<title><?php echo $this->title; ?></title>
</head>
<body>
 
<div class="main-page">
	
<?php if ( $this->login_required && ! $this->logged_in ) return; ?>
 
<nav class="main-header">
	<div id="mainHeader">
		<ul class="pull-left">
			<li><a href="<?php echo HOME_URI;?>">Home</a></li>
			<li><a href="<?php echo HOME_URI;?>/login/">Login</a></li>
		</ul>
		<ul class="pull-right">
			<li><a href="<?php echo HOME_URI;?>/orcamentos/">Orçamentos</a></li>
			<li><a href="<?php echo HOME_URI;?>/servicos/">Serviços</a></li>
			<li><a href="<?php echo HOME_URI;?>/contato/">Contato</a></li>
		</ul>
	</div>
</nav>