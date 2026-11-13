<!-- container mt-4 serve para centralizar, organizar e adionar margem superir, puxando do bootstrap -->


<div class="container mt-4">

	<h2>Cadastro de Produtos</h2>

	<div class="card mt-3"> <!--cria um quadro visual com margem acima do quadro    -->

		<!-- CABEÇALHO -->
		<div class="card-header">
			<h5>Cadastrar Produto</h5>
		</div>

		<!-- CORPO FORMULÁRIO -->
		<div class="card-body">

			<?php
				// Verifica se existe um produto selecionado para edição.
				$produtoEdicao = $_SESSION['produto_edicao'] ?? null;
			?>
			<form action="../php/produtos.php" method="post">

				<!-- Guarda o ID do produto que está sendo editado -->
			    <input
			        type="hidden"
			        name="id"
			        value="<?php echo $produtoEdicao['id'] ?? ''; ?>"
			    >

				<div class="mb-3">
	        		<label for="nome" class="form-label">Nome do Produto</label>
	        		<input type="text" name="nome" id="nome" class="form-control" placeholder="Digite o nome do produto" value="<?php echo htmlspecialchars($produtoEdicao['nome'] ?? ''); ?>" required>
	        	</div>

	        	<button type="submit" class="btn btn-primary">
			        <?php echo $produtoEdicao ? 'Salvar Alterações' : 'Cadastrar Produto'; ?>
			    </button>
			</form>


		</div>	
	</div>
</div>


<!-- PRODUTOS CADASTRADOS -->
<div class="container mt-4">
	
	<h4>Produtos Cadastrados</h4>

	<div class="table-responsive">  <!-- tabelas HTML responsivas-->

		<!-- class="table table-bordered table-striped" serve pra tabela ter bordas e cria um efeito zebra -->
		<table class="table table-bordered table-striped">

			<!-- CABEÇALHO TABELA-->
			<thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Produto</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <!-- CORPO TABELA -->
            <tbody>

            	<?php if (!empty($_SESSION['produtos'])): ?>

            	 <?php foreach ($_SESSION['produtos'] as $produto): ?>  <!-- Percorre lista de produtos armazenados -->

            	<tr>
                    <td><?php echo $produto['id']; ?></td>

                    <!-- htmlspecialchars protege a exibição do texto -->
                    <td><?php echo htmlspecialchars($produto['nome']); ?></td>

                    <td>
                    	<a href="../php/produtos.php?editar=<?php echo $produto['id']; ?>" class="btn btn-warning btn-sm">
                    		Editar
                    	</a>
                    	
                    </td>
                </tr>

                <?php endforeach; ?> <!--- REPETIÇÃO/TERMINAR --->

                <!-- Mensagem exibida quando não existem produtos -->
                    <tr>
                        <td colspan="3" class="text-center">
                            Nenhum produto cadastrado.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>
			
		</table>
		
	</div>

</div>