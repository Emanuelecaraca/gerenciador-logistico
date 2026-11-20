<?php



$enderecoEdicao = $_SESSION['endereco_edicao'] ?? null;

$_SESSION['transportadores'] = $_SESSION['transportadores'] ?? [];
$_SESSION['endereco'] = $_SESSION['endereco'] ?? [];

?>

<div class="container mt-4">

	 <!-- TÍTULO + BOTÃO DE NOVO CADASTRO -->
	<div class="d-flex justify-content-between align-items-center mb-3">

	    <h2 class="mb-0">Endereços</h2>

	    <button type="button" id="btn-novo-endereco" class="btn btn-primary">
	        Novo cadastro
	    </button>

	</div>

	<!-- MODAL DO FORMULÁRIO -->
	<div class="modal fade" id="modalEndereco" tabindex="-1"
	     aria-labelledby="tituloModalEndereco" aria-hidden="true"
	     data-editando="<?php echo $enderecoEdicao ? '1' : '0'; ?>">

	    <div class="modal-dialog modal-lg">
	        <div class="modal-content">

	            <form action="../php/endereco.php" method="post">

	                <div class="modal-header">
	                    <h5 class="modal-title" id="tituloModalEndereco">
	                        <?php echo $enderecoEdicao ? 'Editar Endereço' : 'Cadastrar Endereço'; ?>
	                    </h5>

	                    <button type="button" class="btn-close"
	                            data-bs-dismiss="modal" aria-label="Fechar"></button>
	                </div>

                <div class="modal-body">

				<input type="hidden" name="id" value="<?php echo $enderecoEdicao['id'] ?? ''; ?>">


				<div class="mb-3 position-relative">
				    <label for="pesquisaTransportador" class="form-label">
				        Transportador
				    </label>

				    <input
				        type="text"
				        id="pesquisaTransportador"
				        class="form-control"
				        placeholder="Pesquise pela matrícula ou nome..."
				        autocomplete="off"
				        value=""
				    >

				    <input
				        type="hidden"
				        name="transportador"
				        id="idTransportador"
				        value="<?php echo (int)($enderecoEdicao['transportador'] ?? 0); ?>"
				    >

				    <div
				        id="resultadosTransportadores"
				        class="list-group position-absolute w-100"
				        style="z-index: 1000; display: none;"
				    ></div>
				</div>

				<!-- Dados dos transportadores para a pesquisa -->
				<div id="listaTransportadores" style="display: none;">

				    <?php foreach ($_SESSION['transportadores'] as $transportador): ?>

				        <div
				            class="transportador-item"
				            data-id="<?php echo (int)$transportador['id']; ?>"
				            data-nome="<?php echo htmlspecialchars($transportador['nome'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
				        ></div>

				    <?php endforeach; ?>

				</div>
				<!-- cep -->
				<div class="mb-3">
					<label for="cep" class="form-label">Cep</label>
					<input type="text" name="cep" id="cep" class="form-control" placeholder="Digite o CEP" value="<?php echo $enderecoEdicao['cep'] ?? ''; ?>" required>
				</div>

				<div class="row">

				    <div class="col-md-8 mb-3">
				        <label for="rua" class="form-label">Rua</label>
				        <input type="text" name="rua" id="rua" class="form-control"
				               placeholder="Digite o nome da rua"
				               value="<?php echo htmlspecialchars($enderecoEdicao['rua'] ?? ''); ?>"
				               required>
				    </div>

				    <div class="col-md-4 mb-3">
				        <label for="numero" class="form-label">Número</label>
				        <input type="text" name="numero" id="numero" class="form-control"
				               placeholder="Nº"
				               value="<?php echo htmlspecialchars($enderecoEdicao['numero'] ?? ''); ?>"
				               required>
				    </div>

				</div>

				<div class="row">

				    <div class="col-md-4 mb-3">
				        <label for="bairro" class="form-label">Bairro</label>
				        <input type="text" name="bairro" id="bairro" class="form-control"
				               placeholder="Digite o bairro"
				               value="<?php echo htmlspecialchars($enderecoEdicao['bairro'] ?? ''); ?>"
				               required>
				    </div>

				    <div class="col-md-5 mb-3">
				        <label for="cidade" class="form-label">Cidade</label>
				        <input type="text" name="cidade" id="cidade" class="form-control"
				               placeholder="Digite a cidade"
				               value="<?php echo htmlspecialchars($enderecoEdicao['cidade'] ?? ''); ?>"
				               required>
				    </div>

				    <div class="col-md-3 mb-3">
				        <label for="estado" class="form-label">Estado</label>
				        <input type="text" name="estado" id="estado" class="form-control"
				               placeholder="PR" maxlength="2"
				               value="<?php echo htmlspecialchars($enderecoEdicao['estado'] ?? ''); ?>"
				               required>
				    </div>

				</div>
			</div>

				<div class="modal-footer">

				    <button type="button" class="btn btn-secondary"
				            data-bs-dismiss="modal">
				        Cancelar
				    </button>

				    <button type="submit" class="btn btn-primary">
				        <?php echo $enderecoEdicao ? 'Salvar Alterações' : 'Cadastrar Endereço'; ?>
				    </button>

				</div>

				</form>

            </div>
        </div>
    </div>



	<!-- LISTAGEM DE ENDEREÇOS -->
	
			<div class="input-group mb-2">

			    <input type="search" id="busca-endereco" class="form-control"
			           placeholder="Pesquisar por CEP, rua, cidade ou ID...">

			    <button type="button" id="btn-pesquisar-endereco" class="btn btn-primary">
			        <i class="bi bi-search"></i> Pesquisar
			    </button>

			</div>

			<small id="contador-endereco" class="text-muted d-block mb-3"></small>

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

	                        <tr class="linha-endereco">
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
								    <a href="../php/endereco.php?id=<?php echo $endereco['id']; ?>"
								       class="btn btn-warning btn-sm">
								        <i class="bi bi-pencil-square"></i> Editar
								    </a>

								    <a href="../php/endereco.php?excluir=<?php echo $endereco['id']; ?>"
								       class="btn btn-danger btn-sm"
								       onclick="return confirm('Deseja realmente excluir este endereço?');">
								        <i class="bi bi-trash"></i> Excluir
								    </a>
								</td>
							</tr>

	                    <?php endforeach; ?><!-- ENCERRAR -->

					<tr id="linha-sem-resultado-endereco" class="d-none">
					    <td colspan="8" class="text-center">
					        Nenhum endereço encontrado para essa pesquisa.
					    </td>
					</tr>

	                <?php else: ?> <!-- se nao cadastrar nenhum endereço, segue abaixo a mensagem -->
	                    <tr>
	                        <td colspan="8" class="text-center">
	                            Nenhum endereço cadastrado.
	                        </td>
	                    </tr>

	                 <?php endif; ?> <!-- ENCERRAR -->

	            </tbody>

	        </table>

	    </div>
	

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/endereco.js"></script>
<script src="../js/pesquisaTransportadorEndereco.js"></script>