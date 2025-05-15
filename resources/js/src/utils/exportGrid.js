// src/utils/exportCsv.js
import { showLoading, showSuccess, showError, close } from './alerts';

export const exportGridToCsv = (gridApi, filename = 'export.csv') => {
    if (!gridApi) {
        showError('Ops...', 'Tabela não está disponível');
        return;
    }

    try {
        showLoading('Carregando', 'Exportando para CSV...');

        gridApi.exportDataAsCsv({
            columnSeparator: ';',
            fileName: filename,
        });

        close();
        showSuccess('Feito!', 'Arquivo CSV exportado com sucesso!');
    } catch (error) {
        close();
        console.error(error);
        showError('Erro', 'Não foi possível exportar o CSV.');
    }
};
