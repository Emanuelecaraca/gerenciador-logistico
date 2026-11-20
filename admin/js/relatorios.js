document.addEventListener('DOMContentLoaded', function () {
    const botaoDownload = document.getElementById('baixarRelatorio');

    if (!botaoDownload) {
        return;
    }

    botaoDownload.addEventListener('click', function () {
        const tabela = document.getElementById('tabelaRelatorio');

        if (!tabela) {
            return;
        }

        const linhas = tabela.querySelectorAll('tr');
        const conteudoCSV = [];

        linhas.forEach(function (linha) {
            const colunas = linha.querySelectorAll('th, td');
            const dadosLinha = [];

            colunas.forEach(function (coluna) {
                const valor = coluna.innerText
                    .trim()
                    .replace(/\r?\n|\r/g, ' ')
                    .replace(/"/g, '""');

                dadosLinha.push('"' + valor + '"');
            });

            conteudoCSV.push(dadosLinha.join(';'));
        });

        // BOM UTF-8 para preservar acentos ao abrir no Excel.
        const conteudo = '\uFEFF' + conteudoCSV.join('\r\n');
        const arquivo = new Blob(
            [conteudo],
            { type: 'text/csv;charset=utf-8;' }
        );

        const url = URL.createObjectURL(arquivo);
        const link = document.createElement('a');
        const dataRelatorio = botaoDownload.dataset.dataRelatorio || '';

        link.href = url;
        link.download = 'relatorio_fretes_' + dataRelatorio + '.csv';

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        URL.revokeObjectURL(url);
    });
});
