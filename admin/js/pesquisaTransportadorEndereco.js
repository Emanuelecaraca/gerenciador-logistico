document.addEventListener('DOMContentLoaded', function () {

    const pesquisa = document.getElementById('pesquisaTransportador');
    const campoId = document.getElementById('idTransportador');
    const resultados = document.getElementById('resultadosTransportadores');
    const lista = document.querySelectorAll('.transportador-item');

    if (!pesquisa || !campoId || !resultados) {
        return;
    }

    function normalizar(texto) {
        return texto
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function selecionarTransportador(id, nome) {
        campoId.value = id;
        pesquisa.value = id + ' - ' + nome;
        resultados.style.display = 'none';
    }

    function pesquisarTransportadores() {

        const termo = normalizar(pesquisa.value.trim());

        resultados.innerHTML = '';
        resultados.style.display = 'none';

        campoId.value = '';

        if (termo.length === 0) {
            return;
        }

        let encontrados = 0;

        lista.forEach(function (item) {

            const id = item.dataset.id;
            const nome = item.dataset.nome;

            const texto = normalizar(id + ' ' + nome);

            if (texto.includes(termo) && encontrados < 20) {

                const opcao = document.createElement('button');

                opcao.type = 'button';
                opcao.className = 'list-group-item list-group-item-action';
                opcao.textContent = id + ' - ' + nome;

                opcao.addEventListener('click', function () {
                    selecionarTransportador(id, nome);
                });

                resultados.appendChild(opcao);

                encontrados++;
            }
        });

        if (encontrados > 0) {
            resultados.style.display = 'block';
        } else {
            resultados.style.display = 'block';
            resultados.innerHTML =
                '<div class="list-group-item text-muted">Nenhum transportador encontrado.</div>';
        }
    }

    pesquisa.addEventListener('input', pesquisarTransportadores);

    document.addEventListener('click', function (evento) {

        if (
            !pesquisa.contains(evento.target) &&
            !resultados.contains(evento.target)
        ) {
            resultados.style.display = 'none';
        }

    });

    // Recupera o nome do transportador quando estiver editando.
    if (campoId.value !== '') {

        lista.forEach(function (item) {

            if (item.dataset.id === campoId.value) {
                selecionarTransportador(
                    item.dataset.id,
                    item.dataset.nome
                );
            }

        });

    }

    // Impede salvar sem selecionar um transportador.
    const formulario = pesquisa.closest('form');

    if (formulario) {
        formulario.addEventListener('submit', function (evento) {

            if (campoId.value === '') {
                evento.preventDefault();
                alert('Selecione um transportador antes de cadastrar o endereço.');
                pesquisa.focus();
            }

        });
    }

});