<?php

class UserLogin{
	//Usuario logado
	public $logged_in;
	public $userdata;
	//Msg de erro pro formulario login
	public $login_error;
	
	public function check_userlogin(){
	
		if ( isset( $_SESSION['userdata'] )
			 && ! empty( $_SESSION['userdata'] )
			 && is_array( $_SESSION['userdata'] ) 
			 && ! isset( $_POST['userdata'] )
			) { 
			// Configura os dados do usuário
			$userdata = $_SESSION['userdata'];
			
			// Garante que não é HTTP POST
			$userdata['post'] = false;
		}
		
		if ( isset( $_POST['userdata'] )
			 && ! empty( $_POST['userdata'] )
			 && is_array( $_POST['userdata'] ) 
			) {
			
			$userdata = $_POST['userdata'];
			
			$userdata['post'] = true;
		}
 
		// Verifica se existe algum dado de usuário para conferir
		if ( ! isset( $userdata ) || ! is_array( $userdata ) ) {
			$this->logout();
			return;
		}
 
		// Passa os dados do post para uma variável
		if ( $userdata['post'] === true ) {
			$post = true;
		} else {
			$post = false;
		}
		
		unset( $userdata['post'] );
		
		if ( empty( $userdata ) ) {
			$this->logged_in = false;
			$this->login_error = null;
		
			// Remove qualquer sessão que possa existir sobre o usuário
			$this->logout();
			return;
		}
		
		// Extrai variáveis dos dados do usuário
		extract( $userdata );
		
		// Verifica se existe um usuário e senha
		if ( ! isset( $user ) || ! isset( $user_password ) ) {
			$this->logged_in = false;
			$this->login_error = null;
		
			// Remove qualquer sessão que possa existir sobre o usuário
			$this->logout();
			return;
		}
		
		// Verifica se o usuário existe na base de dados
		$query = $this->db->query( 
			'SELECT * FROM users WHERE user = ? LIMIT 1', 
			array( $user ) 
		);
		
		// Verifica a consulta
		if ( ! $query ) {
			$this->logged_in = false;
			$this->login_error = 'ERRO INTERNO.';
		
			// Remove qualquer sessão que possa existir sobre o usuário
			$this->logout();
			return;
		}
		
		// Obtém os dados da base de usuário
		$fetch = $query->fetch(PDO::FETCH_ASSOC);
		
		// Obtém o ID do usuário
		$user_id = (int) $fetch['user_id'];
		
		// Verifica se o ID existe
		if ( empty( $user_id ) ){
			$this->logged_in = false;
			// Usuário inexistente
			$this->login_error = 'Usuário não existe.';
		
			// Remove qualquer sessão que possa existir sobre o usuário
			$this->logout();
		
			return;
		}
		
		// Confere se a senha enviada pelo usuário bate com o hash do BD
		if ( $this->phpass->CheckPassword( $user_password, $fetch['user_password'] ) ) {
			
			// Se for uma sessão, verifica se a sessão bate com a sessão do BD
			if ( session_id() != $fetch['user_session_id'] && ! $post ) { 
				$this->logged_in = false;
				$this->login_error = 'Sessão Incorreta (id).';
				
				// Remove qualquer sessão que possa existir sobre o usuário
				$this->logout();
				return;
			}
			
			// Se for um post
			if ( $post ) {
				// Recria o ID da sessão
				session_regenerate_id();
				$session_id = session_id();
				
				// Envia os dados de usuário para a sessão
				$_SESSION['userdata'] = $fetch;
				
				// Atualiza a senha
				$_SESSION['userdata']['user_password'] = $user_password;
				
				// Atualiza o ID da sessão
				$_SESSION['userdata']['user_session_id'] = $session_id;
				
				// Atualiza o ID da sessão na base de dados
				$query = $this->db->query(
					'UPDATE users SET user_session_id = ? WHERE user_id = ?',
					array( $session_id, $user_id )
				);
			}
				
			// Obtém um array com as permissões de usuário
			$_SESSION['userdata']['user_permissions'] = unserialize( $fetch['user_permissions'] );
 			$this->logged_in = true;
			$this->userdata = $_SESSION['userdata'];
			
			// Verifica se existe uma URL para redirecionar o usuário
			if ( isset( $_SESSION['goto_url'] ) ) {
				$goto_url = urldecode( $_SESSION['goto_url'] );
				unset( $_SESSION['goto_url'] );
				// Redireciona para a página
				echo '<meta http-equiv="Refresh" content="0; url=' . $goto_url . '">';
				echo '<script type="text/javascript">window.location.href = "' . $goto_url . '";</script>';
				//header( 'location: ' . $goto_url );
			}
			return;
		} else {
			$this->logged_in = false;
			//Senha errada
			$this->login_error = 'Senha Incorreta.';
			$this->logout();
			return;
		}
	}
	
	/**
	 * Logout
	 *
	 * Remove tudo do usuário.
	 */
	protected function logout( $redirect = false ) {
		// Remove all data from $_SESSION['userdata']
		$_SESSION['userdata'] = array();
		unset( $_SESSION['userdata'] );
		session_regenerate_id();
		
		if ( $redirect === true ) {
			// Redireciona p/ pagina de login
			$this->goto_login();
		}
	}
	
	/**
	 * Redireciona para Login
	 */
	protected function goto_login() {
		// Verifica se a URL da HOME está configurada
		if ( defined( 'HOME_URI' ) ) {
			$login_uri  = HOME_URI . '/login/';
			$_SESSION['goto_url'] = urlencode( $_SERVER['REQUEST_URI'] );
			
			// Redireciona
			echo '<meta http-equiv="Refresh" content="0; url=' . $login_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $login_uri . '";</script>';
			// header('location: ' . $login_uri);
		}
		return;
	}
	
	/**
	 * Redireciona para uma página qualquer
	 *
	 */
	final protected function goto_page( $page_uri = null ) {
		if ( isset( $_GET['url'] ) && ! empty( $_GET['url'] ) && ! $page_uri ) {
			$page_uri  = urldecode( $_GET['url'] );
		}
		
		if ( $page_uri ) { 
			// Redireciona
			echo '<meta http-equiv="Refresh" content="0; url=' . $page_uri . '">';
			echo '<script type="text/javascript">window.location.href = "' . $page_uri . '";</script>';
			//header('location: ' . $page_uri);
			return;
		}
	}
	
	
	final protected function check_permissions( 
		$required = 'any', 
		$user_permissions = array('any')
	) {
		if ( ! is_array( $user_permissions ) ) {
			return;
		}
 
		// Se o usuário não tiver permissão
		if ( ! in_array( $required, $user_permissions ) ) {
			return false;
		} else {
			return true;
		}
	}
}