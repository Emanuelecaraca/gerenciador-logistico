<?php
	// Verifica se existe um endereço selecionado para edição.
	$enderecoEdicao = $_SESSION['endereco_edicao'] ?? null;
	
?>

<!-- PRINCIPAL -->
<div class="container mt-4">

	<h2>Cadastro de Endereços</h2>

	<!-- FORMULÁRIO -->
	<div class="card mt-3">

		<!-- CABEÇALHO -->
		<div class="card-header">
			<h5>Cadastrar Endereço</h5>
		</div>


		<!-- CORPO FORMULARIO -->
		<div class="card-body">
			<form action="../php/endereco.php" method="post">

				<input type="hidden" name="id" value="<?php echo $enderecoEdicao['id'] ?? ''; ?>">

				<!-- cep -->
				<div class="mb-3">
					<label for="cep" class="form-label">Cep</label>
					<input type="text" name="cep" id="cep" class="form-control" placeholder="Digite o CEP" value="<?php echo $enderecoEdicao['cep'] ?? ''; ?>" required>
				</div>

				<!-- Rua -->
				<div class="mb-3">
					<label for="rua" class="form-label">Rua</label>
					<input type="text" name="rua" id="rua" class="form-control" placeholder="Digite o nome da Rua" value="<?php echo htmlspecialchars($enderecoEdicao['rua'] ?? ''); ?>" required>
				</div>

				<!-- numero -->
				<div class="mb-3">
					<label for="numero" class="form-label">Número</label>
					<input type="text" name="numero" id="numero" class="form-control" placeholder="Digite o Número" value="<?php echo htmlspecialchars($enderecoEdicao['numero'] ?? ''); ?>" required>
				</div>

				<!-- BAIRRO -->
				<div class="mb-3">
					<label for="bairro" class="form-label">Bairro</label>
					<input type="text" name="bairro" id="bairro" class="form-control" placeholder="Digite o Bairro" value="<?php echo htmlspecialchars($enderecoEdicao['bairro'] ?? ''); ?>" required>
				</div>

				<!-- CIDADE -->
				<div class="mb-3">
					<label for="cidade" class="form-label">Cidade</label>
					<input type="text" name="cidade" id="cidade" class="form-control" placeholder="Digite a Cidade" value="<?php echo htmlspecialchars($enderecoEdicao['cidade'] ?? ''); ?>" required>
				</div>

				<!-- ESTADO -->
				<div class="mb-3">
					<label for="estado" class="form-label">Estado</label>
					<input type="text" name="estado" id="estado" class="form-control" placeholder="Ex: PR" maxlength="2" value="<?php echo htmlspecialchars($enderecoEdicao['estado'] ?? ''); ?>" required>
				</div>

				<button type="submit" class="btn btn-primary">
					<?php echo $enderecoEdicao ? 'Atualizar Endereço' : 'Cadastrar Endereço'; ?>
				</button>


			</form>
			
		</div>
	</div>


	<!-- LISTAGEM DE ENDEREÇOS -->
	<div class="card mt-4">

	    <div class="card-header">
	        <h5>Endereços Cadastrados</h5>
	    </div>

	    <!-- CORPO -->
	    <div class="card-body">

	    	<!-- CABEÇALHO TABELA -->
	        <table class="table table-striped table-bordered">

	            <thead>
	                <tr>
	                    <th>ID</th>
	                    <th>CEP</th>
	                    <th>Rua</th>
	                    <th>Número</th>
	                    <th>Bairro</th>
	                    <th>Cidade</th>
	                    <th>Estado</th>
						<th>Ações</th>

	                </tr>
	            </thead>

	            <!-- TABELA -->
	            <tbody>

	                <?php if (!empty($_SESSION['endereco'])): ?> <!-- verifica se ja existe algum endereço igual -->

	                	<?php foreach ($_SESSION['endereco'] as $endereco): ?> <!-- passa pelos endereços armazenados -->

	                        <tr>
	                        	<!-- exibe os dados armazenados dentro da tabela 
	                        	htmlspecialchars evita que caracteres HTML apareça na tela-->
	                            <td><?php echo $endereco['id']; ?></td>
	                            <td><?php echo htmlspecialchars($endereco['cep']); ?></td>
	                            <td><?php echo htmlspecialchars($endereco['rua']); ?></td>
	                            <td><?php echo htmlspecialchars($endereco['numero']); ?></td>
	                            <td><?php echo htmlspecialchars($endereco['bairro']); ?></td>
	                            <td><?php echo htmlspecialchars($endereco['cidade']); ?></td>
	                            <td><?php echo htmlspecialchars($endereco['estado']); ?></td>
	                            <td>
								    <a href="../php/endereco.php?id=<?php echo $endereco['id']; ?>" class="btn btn-warning btn-sm">
								        Editar
								    </a>
								</td>
							</tr>

	                    <?php endforeach; ?><!-- ENCERRAR -->

	                <?php else: ?> <!-- se nao cadastrar nenhum endereço, segue abaixo a mensagem -->
	                    <tr>
	                        <td colspan="7" class="text-center">
	                            Nenhum endereço cadastrado.
	                        </td>
	                    </tr>

	                 <?php endif; ?> <!-- ENCERRAR -->

	            </tbody>

	        </table>

	    </div>
	</div>
</div>