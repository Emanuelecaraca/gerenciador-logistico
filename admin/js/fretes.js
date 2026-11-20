
document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // MODAL DE CADASTRO E EDIÇÃO
    // ==========================================

    const modalElemento = document.getElementById('modalFrete');

    if (modalElemento) {

        const modalFrete = new bootstrap.Modal(modalElemento);

        const btnNovo = document.getElementById('btn-novo-frete');
        const formFrete = document.getElementById('formFrete');

        const acaoFrete = document.getElementById('acaoFrete');
        const idFrete = document.getElementById('idFrete');

        const tituloModal = document.getElementById('tituloModalFrete');
        const btnSalvar = document.getElementById('btnSalvarFrete');

        // Produtos
        const buscaProdutos = document.getElementById('busca-produtos-frete');
        const listaProdutos = document.getElementById('lista-produtos-frete');
        const produtosSelecionados = document.getElementById('produtos-selecionados-frete');
        const checkboxesProdutos = document.querySelectorAll('.checkbox-produto-frete');

        // Transportador
        const campoBuscaTransportador = document.getElementById('busca-transportador');
        const transportador = document.getElementById('transportador');
        const listaTransportadores = document.getElementById('lista-transportadores');

        // Veículo e demais campos
        const veiculo = document.getElementById('veiculo');
        const origem = document.getElementById('origem');
        const destino = document.getElementById('destino');
        const dataFrete = document.getElementById('dataFrete');
        const prazoChegada = document.getElementById('prazoChegada');
        const valor = document.getElementById('valor');

        // Botões dos transportadores existentes no PHP
        const itensTransportadores = listaTransportadores
            ? listaTransportadores.querySelectorAll('.item-transportador')
            : [];

        // ==========================================
        // PESQUISA DE TRANSPORTADORES
        // ==========================================

        function mostrarListaTransportadores() {

            if (!listaTransportadores) {
                return;
            }

            listaTransportadores.style.display = 'block';
            listaTransportadores.style.position = 'absolute';
            listaTransportadores.style.top = '100%';
            listaTransportadores.style.left = '0';
            listaTransportadores.style.width = '100%';
            listaTransportadores.style.zIndex = '9999';

        }

        function esconderListaTransportadores() {

            if (listaTransportadores) {
                listaTransportadores.style.display = 'none';
            }

        }



        function filtrarTransportadores() {

            if (!campoBuscaTransportador || !listaTransportadores) {
                return;
            }

            const pesquisa = campoBuscaTransportador.value.trim().toLowerCase();

            let encontrados = 0;

            itensTransportadores.forEach(function (botao) {

                const id = String(botao.dataset.id || '').toLowerCase();
                const nome = String(botao.dataset.nome || '').toLowerCase();

                const corresponde =
                    pesquisa !== '' &&
                    (id.includes(pesquisa) || nome.includes(pesquisa));

                botao.style.display = corresponde ? '' : 'none';

                if (corresponde) {
                    encontrados++;
                }

            });

            if (pesquisa !== '' && encontrados > 0) {
                mostrarListaTransportadores();
            } else {
                esconderListaTransportadores();
            }

        }

        function selecionarTransportador(botao) {

            if (!botao || !transportador || !campoBuscaTransportador) {
                return;
            }

            const id = String(botao.dataset.id);
            const nome = String(botao.dataset.nome);

            // O campo visível recebe ID - Nome.
            campoBuscaTransportador.value = id + ' - ' + nome;

            // O campo oculto envia somente o ID ao PHP.
            transportador.value = id;

            esconderListaTransportadores();

            // Atualiza os veículos vinculados.
            filtrarVeiculos();

        }

        if (campoBuscaTransportador && listaTransportadores) {

            // Pesquisa por ID ou nome.
            campoBuscaTransportador.addEventListener('input', function () {

                // Limpa a seleção anterior enquanto digita.
                transportador.value = '';

                filtrarVeiculos();

                filtrarTransportadores();

            });

            // Mostra os resultados ao clicar no campo.
            campoBuscaTransportador.addEventListener('focus', function () {

                if (!transportador.value) {
                    filtrarTransportadores();
                }

            });

            // Seleção dos transportadores.
            itensTransportadores.forEach(function (botao) {

                // Evita que o campo perca o foco antes do clique.
                botao.addEventListener('mousedown', function (event) {
                    event.preventDefault();
                });

                botao.addEventListener('click', function () {

                    selecionarTransportador(botao);

                });

            });

            // Enter seleciona o primeiro resultado disponível.
            campoBuscaTransportador.addEventListener('keydown', function (event) {

                if (event.key === 'Enter') {

                    const resultados = Array.from(itensTransportadores).filter(
                        function (botao) {
                            return botao.style.display !== 'none';
                        }
                    );

                    if (resultados.length > 0 && !transportador.value) {

                        event.preventDefault();

                        selecionarTransportador(resultados[0]);

                    }

                }

                if (event.key === 'Escape') {
                    esconderListaTransportadores();
                }

            });

            // Fecha a lista ao clicar fora.
            document.addEventListener('click', function (event) {

                if (
                    !campoBuscaTransportador.contains(event.target) &&
                    !listaTransportadores.contains(event.target)
                ) {
                    esconderListaTransportadores();
                }

            });

        }

        // ==========================================
        // FILTRAR VEÍCULOS PELO TRANSPORTADOR
        // ==========================================

        function filtrarVeiculos(idVeiculoSelecionado = '') {

            if (!transportador || !veiculo) {
                return;
            }

            const idTransportador = transportador.value;

            Array.from(veiculo.options).forEach(function (option) {

                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                const idVinculado = option.dataset.transportador;

                const pertenceAoTransportador =
                    idTransportador !== '' &&
                    idVinculado === idTransportador;

                option.hidden = !pertenceAoTransportador;

            });

            if (idVeiculoSelecionado) {

                const opcao = Array.from(veiculo.options).find(function (option) {
                    return option.value === String(idVeiculoSelecionado);
                });

                veiculo.value = (
                    opcao && !opcao.hidden
                ) ? String(idVeiculoSelecionado) : '';

            } else {

                veiculo.value = '';

            }

        }

        // ==========================================
        // ATUALIZAR PRODUTOS SELECIONADOS
        // ==========================================

        function atualizarProdutosSelecionados() {

            if (!produtosSelecionados) {
                return;
            }

            produtosSelecionados.innerHTML = '';

            const selecionados = Array.from(checkboxesProdutos).filter(
                function (checkbox) {
                    return checkbox.checked;
                }
            );

            if (selecionados.length === 0) {

                produtosSelecionados.innerHTML =
                    '<span class="text-muted">Nenhum produto selecionado.</span>';

                return;

            }

            selecionados.forEach(function (checkbox) {

                const label = document.querySelector(
                    'label[for="' + checkbox.id + '"]'
                );

                const nomeProduto = label
                    ? label.textContent.trim()
                    : checkbox.value;

                const etiqueta = document.createElement('span');

                etiqueta.className = 'badge bg-primary p-2';
                etiqueta.textContent = nomeProduto;

                produtosSelecionados.appendChild(etiqueta);

            });

        }

        // ==========================================
        // PESQUISA DE PRODUTOS
        // ==========================================

        function filtrarProdutos() {

            if (!buscaProdutos || !listaProdutos) {
                return;
            }

            const texto = buscaProdutos.value.trim().toLowerCase();

            const itens = listaProdutos.querySelectorAll('.item-produto-frete');

            itens.forEach(function (item) {

                const nome = item.textContent.toLowerCase();

                if (nome.includes(texto)) {
                    item.classList.remove('d-none');
                } else {
                    item.classList.add('d-none');
                }

            });

        }

        if (buscaProdutos) {
            buscaProdutos.addEventListener('input', filtrarProdutos);
        }

        checkboxesProdutos.forEach(function (checkbox) {
            checkbox.addEventListener('change', atualizarProdutosSelecionados);
        });

        // ==========================================
        // LIMPAR FORMULÁRIO
        // ==========================================

        function limparFormulario() {

            if (formFrete) {
                formFrete.reset();
            }

            acaoFrete.value = 'cadastrar';
            idFrete.value = '';

            tituloModal.textContent = 'Cadastrar Frete';
            btnSalvar.textContent = 'Cadastrar Frete';

            checkboxesProdutos.forEach(function (checkbox) {
                checkbox.checked = false;
            });

            if (buscaProdutos) {
                buscaProdutos.value = '';
            }

            if (campoBuscaTransportador) {
                campoBuscaTransportador.value = '';
            }

            if (transportador) {
                transportador.value = '';
            }

            esconderListaTransportadores();

            itensTransportadores.forEach(function (botao) {
                botao.style.display = '';
            });

            filtrarProdutos();
            filtrarVeiculos();
            atualizarProdutosSelecionados();

        }

        // ==========================================
        // NOVO FRETE
        // ==========================================

        if (btnNovo) {

            btnNovo.addEventListener('click', function () {

                limparFormulario();

                modalFrete.show();

            });

        }

        // ==========================================
        // EDITAR FRETE
        // ==========================================

        document.querySelectorAll('.btn-editar-frete').forEach(function (botao) {

            botao.addEventListener('click', function () {

                limparFormulario();

                acaoFrete.value = 'editar';
                idFrete.value = botao.dataset.id;

                tituloModal.textContent = 'Editar Frete';
                btnSalvar.textContent = 'Salvar alterações';

                // Produtos vinculados.
                let produtosDoFrete = [];

                try {

                    produtosDoFrete = JSON.parse(
                        botao.dataset.produtos || '[]'
                    );

                } catch (erro) {

                    produtosDoFrete = [];

                }

                checkboxesProdutos.forEach(function (checkbox) {

                    checkbox.checked = produtosDoFrete.includes(
                        parseInt(checkbox.value, 10)
                    );

                });

                atualizarProdutosSelecionados();

                // Transportador.
                const idTransportador = String(botao.dataset.transportador);

                const botaoTransportador = Array.from(itensTransportadores).find(
                    function (item) {
                        return String(item.dataset.id) === idTransportador;
                    }
                );

                if (botaoTransportador) {
                    selecionarTransportador(botaoTransportador);
                }

                // Veículo.
                filtrarVeiculos(botao.dataset.veiculo);

                // Demais campos.
                origem.value = botao.dataset.origem;
                destino.value = botao.dataset.destino;
                dataFrete.value = botao.dataset.data;
                prazoChegada.value = botao.dataset.prazo;
                valor.value = botao.dataset.valor;

                modalFrete.show();

            });

        });

        // ==========================================
        // VALIDAR CADASTRO
        // ==========================================

        if (formFrete) {

            formFrete.addEventListener('submit', function (event) {

                const existeProdutoSelecionado = Array.from(checkboxesProdutos).some(
                    function (checkbox) {
                        return checkbox.checked;
                    }
                );

                if (!transportador || !transportador.value) {

                    event.preventDefault();

                    alert('Selecione um transportador válido.');

                    if (campoBuscaTransportador) {
                        campoBuscaTransportador.focus();
                    }

                    return;

                }

                if (!existeProdutoSelecionado) {

                    event.preventDefault();

                    alert('Selecione pelo menos um produto para cadastrar o frete.');

                    if (buscaProdutos) {
                        buscaProdutos.focus();
                    }

                }

            });

        }

        // Inicialização.
        atualizarProdutosSelecionados();
        filtrarVeiculos();

    }

    // ==========================================
    // PESQUISA DE FRETES
    // ==========================================

    const busca = document.getElementById('busca-frete');
    const btnPesquisar = document.getElementById('btn-pesquisar-frete');
    const contador = document.getElementById('contador-frete');

    const linhas = document.querySelectorAll('.linha-frete');
    const linhaSemResultado = document.getElementById('linha-sem-resultado-frete');

    if (!busca || !btnPesquisar || !contador) {
        return;
    }

        function filtrarFrete() {

        const texto = busca.value.trim().toLowerCase();

        let encontrados = 0;

        linhas.forEach(function (linha) {

            const idFrete = linha.cells[0].textContent.trim().toLowerCase();
            const transportador = linha.cells[1].textContent.trim().toLowerCase();

            // Sem pesquisa, nenhum frete aparece.
            const corresponde = texto !== '' && (
                idFrete.includes(texto) ||
                transportador.includes(texto)
            );

            if (corresponde) {
                linha.classList.remove('d-none');
                encontrados++;
            } else {
                linha.classList.add('d-none');
            }

        });

        contador.textContent = texto === ''
            ? 'Pesquise pelo ID do frete ou transportador.'
            : encontrados + (encontrados === 1
                ? ' frete encontrado.'
                : ' fretes encontrados.');

        if (linhaSemResultado) {
            linhaSemResultado.classList.toggle(
                'd-none',
                texto === '' || encontrados > 0
            );
        }

    }

    btnPesquisar.addEventListener('click', filtrarFrete);

    busca.addEventListener('input', filtrarFrete);

    busca.addEventListener('keydown', function (event) {

        if (event.key === 'Enter') {

            event.preventDefault();

        }

    });

    filtrarFrete();

});


// ==========================================
// ANEXAR DOCUMENTO DO TRANSPORTADOR
// ==========================================

document.addEventListener('DOMContentLoaded', function () {

    const botaoDocumento = document.getElementById('btn-novo-documento');
    const inputDocumento = document.getElementById('input-documento');

    if (!inputDocumento) return;

    function enviarDocumento(arquivo) {

        const extensao = arquivo.name.split('.').pop().toLowerCase();

        const permitidos = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'csv'];

        if (!permitidos.includes(extensao)) {
            alert('Formato de arquivo não permitido.');
            return;
        }

        const nomeOriginal = arquivo.name.substring(
            0,
            arquivo.name.lastIndexOf('.')
        );

        const novoNome = prompt(
            'Informe o nome do documento:',
            nomeOriginal
        );

        if (novoNome === null || novoNome.trim() === '') return;

        const arquivoRenomeado = new File(
            [arquivo],
            novoNome.trim() + '.' + extensao,
            { type: arquivo.type }
        );

        const dados = new FormData();

        dados.append('acao', 'anexar_documento');
        dados.append('idTransportador', new URLSearchParams(window.location.search).get('matricula'));
        dados.append('documento', arquivoRenomeado);

        fetch('../php/transportadores.php', {
            method: 'POST',
            body: dados
        })
        .then(async resposta => {
            const texto = await resposta.text();
            console.log('Resposta do PHP:', texto);

            return JSON.parse(texto);
        })
        .then(resultado => {

            if (resultado.sucesso) {
                const url = new URL(window.location.href);
                url.searchParams.set('aba', 'documentos');
                window.location.href = url.toString();
            } else {
                alert(resultado.erro || 'Erro ao anexar documento.');
            }

        })
        .catch(() => {
            alert('Não foi possível enviar o documento.');
        });
    }

    if (botaoDocumento) {
        botaoDocumento.addEventListener('click', function () {
            inputDocumento.click();
        });
    }

    inputDocumento.addEventListener('change', function () {

        if (this.files.length > 0) {
            enviarDocumento(this.files[0]);
            this.value = '';
        }

    });

    window.addEventListener('dragover', function (event) {
        event.preventDefault();
    }, true);

    window.addEventListener('drop', function (event) {

        event.preventDefault();
        event.stopPropagation();

        if (event.dataTransfer.files.length > 0) {
            enviarDocumento(event.dataTransfer.files[0]);
        }

    }, true);

});
