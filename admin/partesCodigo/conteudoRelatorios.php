<?php

//recupera lista de fretes cadastrados, se nao tiver utiliza a lista vazia
$fretes=$_SESSION['fretes'] ?? [];

//recupera os demais cadastros correspondentes tbm aos IDS
$produtos = $_SESSION['produtos'] ?? [];
$transportadores = $_SESSION['transportadores'] ?? [];
$enderecos = $_SESSION['endereco'] ?? [];
$veiculos = $_SESSION['veiculos'] ?? [];

//recebe a data enviada e se nao tiver data mantém o campo vazio
$dataRelatorio = $_GET['dataRelatorio'] ?? '';

//cria uma lista pra armazenar os fretes correspondentes a data selecionada
$fretesRelatorio = [];

// Verifica se o usuário selecionou alguma data.
if ($dataRelatorio !== '') {

	// Percorre todos os fretes cadastrados
	    foreach ($fretes as $frete) {

		// Compara a data do frete com a data selecionada. Se forem iguais, o frete será incluído no relatório.
	        if ($frete['dataFrete'] === $dataRelatorio) {

	            $fretesRelatorio[] = $frete;

	        }

	    }
}

// Localiza o nome de um cadastro através do seu ID.
function nomeCadastroRelatorio($lista, $id, $campo = 'nome') {

	foreach ($lista as $item) {

        // Verifica se o ID do cadastro corresponde ao ID informado.
        if ((int)$item['id'] === (int)$id) {

            // Retorna o campo solicitado, caso ele exista.
            return $item[$campo] ?? '';

        }

    }

    return 'Não encontrado';
}


//localiza um endereço pelo ID
function enderecoCadastroRelatorio($lista, $id) {

    foreach ($lista as $item) {

        // Compara o ID do endereço com o ID recebido.
        if ((int)$item['id'] === (int)$id) {

            // Junta as informações para formar o endereço completo.
            return trim(
                ($item['rua'] ?? '') . ', ' .
                ($item['numero'] ?? '') . ' - ' .
                ($item['bairro'] ?? '') . ', ' .
                ($item['cidade'] ?? '') . '/' .
                ($item['estado'] ?? '')
            );

        }

    }

    return 'Não encontrado';
}

// Localiza um veículo pelo ID e retorna sua placa e tipo.
function veiculoCadastroRelatorio($lista, $id) {

    foreach ($lista as $item) {

        // Verifica se encontrou o veículo correspondente.
        if ((int)$item['id'] === (int)$id) {

            // Retorna a placa e o tipo do veículo.
            return ($item['placa'] ?? '') . ' - ' .
                   ($item['tipo'] ?? '');

        }

    }

    return 'Não encontrado';
}

?>

<main class="container mt-4">
	
	<h2>Relatórios</h2>
    <p>Consulte e baixe relatórios dos fretes cadastrados.</p>

    <div class="card mb-4">

    	<div class="card mb-4">

    		<div class="card-header">
    			<h5>Gerar relatório de fretes</h5>
    		</div>

    		<div class="card-body">
    			
    			<form method="get">

    				<div class="mb-3">

    					<label for="dataRelatorio" class="form-label">Selecione a data</label>

    					<input type="date" name="dataRelatorio" id="dataRelatorio" class="form-control" value="<?= htmlspecialchars($_GET['dataRelatorio'] ?? '') ?>" required>
    					
    				</div>

    				<button type="submit" class="btn btn-primary">Pesquisar</button>
    				
    			</form>
    			
    		</div>
    		
    	</div>

    	<a href="fretes.php" class="btn btn-outline-secondary"> Voltar para fretes </a>


    	<!-- verifica se o usuario selecionou alguma data -->
    	<?php if ($dataRelatorio !== ''): ?>
    		
    		<div class="card mb-4">

    			<div class="card-header">
    				<h5>Resultado do relatório</h5>
    			</div>

    			<div class="card-body">

    				<!-- apresenta data escolhida pt-br -->
    				<p>
						<strong>Data selecionada:</strong>
	                	<?= date('d/m/Y', strtotime($dataRelatorio)) ?>
    				</p>

    				<!-- Conta quantos fretes foram encontrados. -->
	                <p>
	                    <strong>Total de fretes encontrados:</strong>
	                    <?= count($fretesRelatorio) ?>
	                </p>

	                <!-- verifica se tem pelos menos um frete e apresenta a tabela -->
	                <?php if (count($fretesRelatorio) > 0): ?>

                    <div class="table-responsive">

                        <table id="tabelaRelatorio" class="table table-bordered table-striped">

                            <!-- Cabeçalho da tabela -->
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Produto</th>
                                    <th>Origem</th>
                                    <th>Destino</th>
                                    <th>Transportador</th>
                                    <th>Veículo</th>
                                    <th>Data</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>

                            <!--Percorre somente os fretes encontrados na data selecionada e ler um item de cada vez-->
                            <?php foreach ($fretesRelatorio as $frete): ?>

                                <tr>

                                    <!-- ID do frete. -->
                                    <td>
                                        <?= htmlspecialchars((string)$frete['id']) ?>
                                    </td>

                                    <!-- Busca o nome do produto pelo ID. -->
                                    <td>
                                        <?= htmlspecialchars(nomeCadastroRelatorio($produtos, $frete['produto'])) ?>
                                    </td>

                                    <!-- Busca o endereço de origem pelo ID. -->
                                    <td>
                                        <?= htmlspecialchars(enderecoCadastroRelatorio($enderecos, $frete['origem'])) ?>
                                    </td>

                                    <!-- Busca o endereço de destino pelo ID. -->
                                    <td>
                                        <?= htmlspecialchars(enderecoCadastroRelatorio($enderecos, $frete['destino'])) ?>
                                    </td>

                                    <!-- Busca o nome do transportador pelo ID. -->
                                    <td>
                                        <?= htmlspecialchars(nomeCadastroRelatorio($transportadores, $frete['transportador'])) ?>
                                    </td>

                                    <!-- Busca a placa e o tipo do veículo pelo ID. -->
                                    <td>
                                        <?= htmlspecialchars(veiculoCadastroRelatorio($veiculos, $frete['veiculo'])) ?>
                                    </td>

                                    <!-- Converte a data para o formato dia/mês/ano. -->
                                    <td>
                                        <?= date('d/m/Y', strtotime($frete['dataFrete'])) ?>
                                    </td>

                                    <!-- Formata o valor do frete em reais. -->
                                    <td>
                                        R$ <?= number_format($frete['valor'], 2, ',', '.') ?>
                                    </td>

                                    <!-- Apresenta o status atual do frete. -->
                                    <td>
                                        <?= htmlspecialchars($frete['status']) ?>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                    <!-- o botão só aparece quando tiver fretes. O JS será apenas para transformar e baixar o arquivo CSV com os dados -->
                    <button type="button" class="btn btn-sucess" id="baixarRelatorio">Baixar</button>

                    <!-- o php vai servir para quando nao tiver nenhum frete bloquear o download -->
                    <?php else: ?>
                    	<div class="alert alert-warning">
                    		Nenhum frete encontrado para a data selecionada.	
                    	</div>

    				<?php endif; ?>
    			</div>
    			
    		</div>
    	<?php endif; ?>

    	<!-- Retorna o usuário à página de cadastro e consulta de fretes. -->
	    <a href="fretes.php" class="btn btn-outline-secondary">
	        Voltar para Fretes
	    </a>
	</div>

</main>

<script>

//aguarda o carregamento completo
document.addEventListener('DOMContentLoaded', function () {
	
	//localiza o botão de download
	const botaoDownload = document.getElementById('baixarRelatorio');

	//verifica se o botão existe
	if (botaoDownload) {

		//quando o usuario clicar ele executa
		botaoDownload.addEventListener('click', function () {

			// localiza a tabela com os resultados de pesquisa...
			const tabela = document.getElementById('tabelaRelatorio');

			//recupera as linhas da tabela
			const linhas = tabela.querySelectorAll('tr');

			//cria uma lista vazia pra armazenar em CVS
			 let conteudoCSV = [];

			//percorre cada linha
			linhas.forEach(function (linha) {

				//recupera cabeçalho e conteudo
				const colunas = linha.querySelectorAll('th, td');

				//cria uma lista pra armazenar valores da linha atual
				 const dadosLinha = [];

				 //percorre cada celular da linha
				 colunas.forEach(function (coluna) {

				 	//evita problemas na estrutura CSV
				 	let valor = coluna.innerText.replace(/"/g, '""');

				 	// Coloca cada valor entre aspas.
                    dadosLinha.push('"' + valor + '"');
				});

				// facilita a abertura do arquivo no Excel.
                conteudoCSV.push(dadosLinha.join(';'));

            });

            // adiciona o UTF-8 para acentos no Excel. Depois, junta todas as linhas usando uma quebra de linha.
            const conteudo = '\uFEFF' + conteudoCSV.join('\r\n');

            // transforma o conteúdo em um arquivo CSV.
            const arquivo = new Blob(
                [conteudo],
                { type: 'text/csv;charset=utf-8;' }
            );

            // cria um endereço temporário para o arquivo.
            const url = URL.createObjectURL(arquivo);

            // cria um link temporário para iniciar o download.
            const link = document.createElement('a');

            // define o endereço do arquivo.
            link.href = url;

            // define o nome do arquivo com a data pesquisada.
            link.download = 'relatorio_fretes_<?= htmlspecialchars($dataRelatorio) ?>.csv';

            // adiciona o link temporariamente ao documento.
            document.body.appendChild(link);

            // simula o clique para iniciar o download.
            link.click();

            // remove o link temporário depois do download.
            document.body.removeChild(link);

            // libera o endereço temporário criado para o arquivo.
            URL.revokeObjectURL(url);

        });

    }

});

</script>

	


