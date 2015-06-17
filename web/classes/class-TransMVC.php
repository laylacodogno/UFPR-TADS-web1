<?php
/**
 * @package transMVC
 * @since 0.1
 */
class TransMVC
{
 
	private $controlador;
	private $acao;
	private $parametros;
	private $not_found = '/views/_includes/404.php';
	
	public function __construct () {
		$this->get_url_data();
		if ( ! $this->controlador ) {
			
			// Adiciona o controlador padrão
			require_once ABSPATH . '/controllers/home-controller.php';
			//Este controlador deverá ter uma classe chamada HomeController
			$this->controlador = new HomeController();
			$this->controlador->index();
			
			return;
		}
		
		if ( ! file_exists( ABSPATH . '/controllers/' . $this->controlador . '.php' ) ) {
			require_once ABSPATH . $this->not_found;
			
			return;
		}
		
		// Inclui o arquivo do controlador
		require_once ABSPATH . '/controllers/' . $this->controlador . '.php';
		$this->controlador = preg_replace( '/[^a-zA-Z]/i', '', $this->controlador );
		
		if ( ! class_exists( $this->controlador ) ) {
			require_once ABSPATH . $this->not_found;
 
			return;
		}
		
		// Cria o objeto da classe do controlador e envia os parâmetros
		$this->controlador = new $this->controlador( $this->parametros );
 
		if ( method_exists( $this->controlador, $this->acao ) ) {
			$this->controlador->{$this->acao}( $this->parametros );
			
			return;
		} 
		
		if ( ! $this->acao && method_exists( $this->controlador, 'index' ) ) {
			$this->controlador->index( $this->parametros );		
			
			return;
		} 
		
		require_once ABSPATH . $this->not_found;
		
		return;
	} 
	
	
	public function get_url_data () {
		
		// Verifica se o parâmetro path foi enviado
		if ( isset( $_GET['path'] ) ) {
			$path = $_GET['path'];
			
			// Limpa os dados
            $path = rtrim($path, '/');
            $path = filter_var($path, FILTER_SANITIZE_URL);
            
			$path = explode('/', $path);
			
			// Configuracao
			$this->controlador  = chk_array( $path, 0 );
			$this->controlador .= '-controller';
			$this->acao         = chk_array( $path, 1 );
			
			if ( chk_array( $path, 2 ) ) {
				unset( $path[0] );
				unset( $path[1] );
				
				// Os parâmetros sempre virão após a ação
				$this->parametros = array_values( $path );
			}
			
			
			// teste
			//
			// echo $this->controlador . '<br>';
			// echo $this->acao        . '<br>';
			// echo '<pre>';
			// print_r( $this->parametros );
			// echo '</pre>';
		}
	
	} 
	
} 