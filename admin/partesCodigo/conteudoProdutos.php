<?php
// Produto selecionado para edição (se houver) e lista de produtos.
$produtoEdicao = $_SESSION['produto_edicao'] ?? null;
$produtos = $_SESSION['produtos'] ?? [];
?>

<div class="container mt-4">

	<!-- TÍTULO + BOTÃO DE NOVO CADASTRO -->
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h2 class="mb-0">Produtos</h2>
		<button type="button" id="btn-novo" class="btn btn-primary">Novo cadastro</button>
	</div>

	<!-- PESQUISA -->
	<div class="input-group mb-2">
	    <input type="search" id="busca" class="form-control"
	           placeholder="Pesquisar por nome ou ID...">

	    <button type="button" id="btn-pesquisar" class="btn btn-primary">
	        <i class="bi bi-search"></i> Pesquisar
	    </button>
	</div>

	<small id="contador" class="text-muted d-block mb-3"></small>

	<!-- TABELA -->
	<div class="table-responsive">
		<table class="table table-bordered table-striped align-middle">
			<thead>
				<tr>
					 <th>ID</th>
			        <th>Nome do Produto</th>
			        <th>Peso (kg)</th>
			        <th>Comprimento (cm)</th>
			        <th>Largura (cm)</th>
			        <th>Altura (cm)</th>
			        <th>Ações</th>
				</tr>
			</thead>

			<tbody>
				<?php if (!empty($produtos)): ?>

					<?php foreach ($produtos as $produto): ?>
						<tr class="linha-produto">
							<td><?php echo $produto['id']; ?></td>
							<td><?php echo htmlspecialchars($produto['nome']); ?></td>
							<td><?php echo htmlspecialchars($produto['peso'] ?? ''); ?></td>
							<td><?php echo htmlspecialchars($produto['comprimento'] ?? ''); ?></td>
							<td><?php echo htmlspecialchars($produto['largura'] ?? ''); ?></td>
							<td><?php echo htmlspecialchars($produto['altura'] ?? ''); ?></td>

							<td class="text-nowrap">
							    <a href="../php/produtos.php?editar=<?php echo $produto['id']; ?>"
							       class="btn btn-warning btn-sm">
							        <i class="bi bi-pencil-square"></i> Editar
							    </a>

							    <a href="../php/produtos.php?excluir=<?php echo $produto['id']; ?>"
							       class="btn btn-danger btn-sm"
							       onclick="return confirm('Tem certeza que deseja excluir este produto?');">
							        <i class="bi bi-trash"></i> Excluir
							    </a>
							</td>
						</tr>
					<?php endforeach; ?>

					<!-- Aparece só quando a pesquisa não encontra nada (controlada pelo JS) -->
					<tr id="linha-sem-resultado" class="d-none">
						<td colspan="7" class="text-center">Nenhum produto encontrado para essa pesquisa.</td>
					</tr>

				<?php else: ?>

					<tr>
						<td colspan="7" class="text-center">Nenhum produto cadastrado.</td>
					</tr>

				<?php endif; ?>
			</tbody>
		</table>
	</div>

</div>


<!-- JANELA DO FORMULÁRIO (cadastrar / editar) -->
<div class="modal fade" id="modalProduto" tabindex="-1" aria-labelledby="tituloModal" aria-hidden="true"
	 data-editando="<?php echo $produtoEdicao ? '1' : '0'; ?>">
	<div class="modal-dialog">
		<div class="modal-content">

			<form action="../php/produtos.php" method="post">

				<div class="modal-header">
					<h5 class="modal-title" id="tituloModal">
						<?php echo $produtoEdicao ? 'Editar Produto' : 'Cadastrar Produto'; ?>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
				</div>

				<div class="modal-body">
					<!-- Guarda o ID do produto que está sendo editado -->
					<input type="hidden" name="id" id="produto-id" value="<?php echo $produtoEdicao['id'] ?? ''; ?>">

					<div class="mb-3">
					    <label for="nome" class="form-label">Nome do Produto</label>
					    <input type="text" name="nome" id="nome" class="form-control"
					           placeholder="Digite o nome do produto"
					           value="<?php echo htmlspecialchars($produtoEdicao['nome'] ?? ''); ?>" required>
					</div>

					<div class="mb-3">
					    <label for="peso" class="form-label">Peso (kg)</label>
					    <input type="number" name="peso" id="peso" class="form-control"
					           placeholder="Ex.: 2,5" min="0.01" step="0.01"
					           value="<?php echo htmlspecialchars($produtoEdicao['peso'] ?? ''); ?>" required>
					</div>

					<div class="mb-3">
					    <label for="comprimento" class="form-label">Comprimento (cm)</label>
					    <input type="number" name="comprimento" id="comprimento" class="form-control"
					           placeholder="Ex.: 30" min="0.01" step="0.01"
					           value="<?php echo htmlspecialchars($produtoEdicao['comprimento'] ?? ''); ?>" required>
					</div>

					<div class="mb-3">
					    <label for="largura" class="form-label">Largura (cm)</label>
					    <input type="number" name="largura" id="largura" class="form-control"
					           placeholder="Ex.: 20" min="0.01" step="0.01"
					           value="<?php echo htmlspecialchars($produtoEdicao['largura'] ?? ''); ?>" required>
					</div>

					<div class="mb-3">
					    <label for="altura" class="form-label">Altura (cm)</label>
					    <input type="number" name="altura" id="altura" class="form-control"
					           placeholder="Ex.: 15" min="0.01" step="0.01"
					           value="<?php echo htmlspecialchars($produtoEdicao['altura'] ?? ''); ?>" required>
					</div>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
					<button type="submit" id="btn-salvar" class="btn btn-primary">
						<?php echo $produtoEdicao ? 'Salvar Alterações' : 'Cadastrar Produto'; ?>
					</button>
				</div>

			</form>

		</div>
	</div>
</div>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/produtos.js"></script>