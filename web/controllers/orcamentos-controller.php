<?php

class OrcamentosController extends MainController{
 
    public function index() {
	$this->title = 'Orçamentos';
	
	$parametros = ( func_num_args() >= 1 ) ? func_get_arg(0) : array();
	
	require ABSPATH . '/views/_includes/header.php';
	require ABSPATH . '/views/orcamentos/orcamentos-view.php';
    require ABSPATH . '/views/_includes/footer.php';
		
    } 
} 