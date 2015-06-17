<?php if ( ! defined('ABSPATH')) exit; ?>

<div class="main-orcamento">
	<h4> Solicite seu Orçamento</h4>
	<p>Preencha os dados abaixo</p>
	
	<form method="POST">
			
			<p>Nome Solicitante:<p>
			<p>
				<input type="text" name="nome" />	
			</p>
			<p> Email:</p>
			<p>
				<input type="email" name="email" />
			</p>
			<p> Telefone:</p>
			<p>
				<input type="number" name="telefone" />
			</p>
			<p> Empresa:</p>
			<p>
				<input type="text" name="empresa" />
			</p>
			
			<p> Serviço:</p>
			<select required name="servico">
			  <option value="volvo">Transporte Carga</option>
			  <option value="saab">Locação</option>
			  <option value="opel">Outro</option>			  
			</select>
			<p>Detalhes Carga</p> <!-- clique em Transporte Carga -->
			<p>
				<textarea cols="40" rows="5" name="detCarga"></textarea>
			</p>
			<p>Detalhes Locação</p> <!-- clique em Locação -->
			<p>
				<textarea cols="40" rows="5" name="detLocacao"></textarea>
			</p>
			<p> Detalhes Adicionais:</p>
			<p>
				<textarea cols="40" rows="5" name="obs"></textarea>
			</p>
			<p> Data Serviço:</p>
			<p>
				<input type="date" name="dataServico" />
			</p>
			<p> Endereço Coleta:</p>
			<p>
				<input type="text" name="coleta" />
			</p>
			<p> Endereço Entrega:</p>
			<p>
				<input type="text" name="Entrega" />
			</p>
			<input type="submit"/ value="Enviar">
		</form>
		
</div>