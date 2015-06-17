<?php

class ServicosController extends MainController{
 
    public function index() {
	$this->title = 'Serviços';
	
	$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
	
	require ABSPATH . '/views/_includes/header.php';
	require ABSPATH . '/views/servicos/servicos-view.php';
    require ABSPATH . '/views/_includes/footer.php';
		
    } 
} 