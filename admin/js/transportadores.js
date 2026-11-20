document.addEventListener('DOMContentLoaded', function () {

    // Localiza o modal de transportadores.
    const modalElemento = document.getElementById('modalTransportadores');

    // Cria o modal do Bootstrap.
    const modalTransportadores = new bootstrap.Modal(modalElemento);

    // Localiza o botão Novo cadastro.
    const btnNovo = document.getElementById('btn-novo-transportador');

    // Abre o modal ao clicar em Novo cadastro.
    btnNovo.addEventListener('click', function () {
        modalTransportadores.show();
    });

    // Verifica se a página está em modo de edição.
    if (modalElemento.dataset.editando === '1') {
        modalTransportadores.show();
    }

        // Localiza o modal de veículos.
    const modalVeiculoElemento = document.getElementById('modalVeiculo');

    // Cria o modal do Bootstrap.
    const modalVeiculo = new bootstrap.Modal(modalVeiculoElemento);

    // Localiza o botão Cadastrar Veículo.
    const btnNovoVeiculo = document.getElementById('btn-novo-veiculo');

    // Abre o modal ao clicar em Cadastrar Veículo.
    btnNovoVeiculo.addEventListener('click', function () {
        modalVeiculo.show();
    });

    // Verifica se a página está em modo de edição de veículo.
    if (modalVeiculoElemento.dataset.editando === '1') {
        modalVeiculo.show();
    }

    // Verifica se existe o modal de erro de exclusão.
    const modalErroElemento = document.getElementById('modalErroExclusao');

    // Abre o modal automaticamente quando ele existir.
    if (modalErroElemento) {
        const modalErro = new bootstrap.Modal(modalErroElemento);
        modalErro.show();
    }

    // PESQUISA DE TRANSPORTADORES
        const campoPesquisa = document.getElementById('pesquisaTransportadorModal');

        if (campoPesquisa) {

            campoPesquisa.addEventListener('input', function () {

                const termo = this.value
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .trim();

                const linhas = document.querySelectorAll('.linha-transportador');
                const mensagem = document.getElementById('mensagemPesquisaTransportador');

                let encontrados = 0;

                linhas.forEach(function (linha) {

                    const dados = linha.dataset.pesquisa
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '');

                    const corresponde = termo !== '' && dados.includes(termo);

                    linha.style.display = corresponde ? '' : 'none';

                    if (corresponde) {
                        encontrados++;
                    }

                });

                if (termo === '') {

                    mensagem.innerHTML = 'Digite a matrícula, o nome ou o CPF/CNPJ para pesquisar.';
                    mensagem.style.display = '';

                } else if (encontrados === 0) {

                    mensagem.innerHTML = 'Nenhum transportador encontrado.';
                    mensagem.style.display = '';

                } else {

                    mensagem.style.display = 'none';

                }

            });

        }
});


