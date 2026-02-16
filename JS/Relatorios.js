var db = supabase.createClient(SUPABASE_URL, SUPABASE_KEY);
document.addEventListener('DOMContentLoaded', () => {
            const btnStatus = document.getElementById('btnStatusReport');
            const btnFaturamento = document.getElementById('btnFaturamentoReport');
            const btnDetalhado = document.getElementById('btnDetalhadoReport');
            const loadingSpinner = document.getElementById('loading-spinner');

            /**
             * Exibe ou oculta o spinner de carregamento.
             * @param {boolean} show - Se deve exibir (true) ou ocultar (false).
             */
            function toggleLoading(show) {
                if (show) {
                    loadingSpinner.classList.remove('hidden');
                } else {
                    loadingSpinner.classList.add('hidden');
                }
            }

            /**
             * Converte um array de objetos JSON para uma string CSV.
             * @param {Array<Object>} data 
             * @returns {string} 
             */
            function convertToCsv(data) {
                if (data.length === 0) return 'Nenhum dado encontrado';

                const headers = Object.keys(data[0]);
                
              
                const headerCsv = headers.join(';');

               
                const rows = data.map(row => {
                    return headers.map(fieldName => {
                        let value = row[fieldName] === null || row[fieldName] === undefined ? '' : row[fieldName];
                        
                        value = String(value).replace(/"/g, '""').replace(/\n/g, ' ');
                       
                        if (value.includes(',') || value.includes(';')) {
                            return `"${value}"`;
                        }
                        return value;
                    }).join(';');
                });

                return [headerCsv, ...rows].join('\n');
            }

            /**
             * Inicia o download de um arquivo CSV.
             * @param {string} csvString 
             * @param {string} filename 
             */
            function downloadCsv(csvString, filename) {
                try {

                    const blob = new Blob(["\uFEFF", csvString], { type: 'text/csv;charset=utf-8;' }); 
                    const url = URL.createObjectURL(blob);
                  
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    
                    document.body.appendChild(a);
                    a.click();
                    
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                } catch (error) {
                    console.error("Erro ao tentar baixar o arquivo:", error);
                   
                    console.error("Erro ao tentar baixar o arquivo. Verifique o console para mais detalhes.");
                }
            }



            // FUNÇÕES DE RELATÓRIO 

           
             // Relatório 1: FRETES POR STATUS

            async function generateStatusReport() {
                const { data, error } = await db.rpc('get_fretes_por_status');

                if (error) {
                    console.error('Erro ao buscar Relatório de Status:', error);
                    throw new Error('Falha ao carregar dados de Status do Supabase.');
                }
                
                return data || [];
            }

        //Relatório 2: FATURAMENTO DO DIA
          
            async function generateFaturamentoReport() {
                const { data, error } = await db.rpc('get_faturamento_diario');

                if (error) {
                    console.error('Erro ao buscar Relatório de Faturamento:', error);
                    throw new Error('Falha ao carregar dados de Faturamento do Supabase.');
                }
                
                return data || [];
            }

            
              //Relatório 3: RELATÓRIO DETALHADO DE FRETES
             
            async function generateDetailedReport() {
                const { data, error } = await db
                    .from('relatorio_fretes_detalhado')
                    .select('*')
                    .order('data_hora', { ascending: false });

                if (error) {
                    console.error('Erro ao buscar Relatório Detalhado:', error);
                    throw new Error('Falha ao carregar dados detalhados do Supabase.');
                }
                

                return data || [];
            }


            btnStatus.addEventListener('click', async () => {
                toggleLoading(true);
                try {
                    const data = await generateStatusReport();
                    const csv = convertToCsv(data);
                    downloadCsv(csv, 'relatorio_fretes_status.csv');
                } catch (e) {
                    console.error(e);
                } finally {
                    toggleLoading(false);
                }
            });

            btnFaturamento.addEventListener('click', async () => {
                toggleLoading(true);
                try {
                    const data = await generateFaturamentoReport();
                    const csv = convertToCsv(data);
                    downloadCsv(csv, 'relatorio_faturamento_diario.csv');
                } catch (e) {
                    console.error(e);
                } finally {
                    toggleLoading(false);
                }
            });

            btnDetalhado.addEventListener('click', async () => {
                toggleLoading(true);
                try {
                    const data = await generateDetailedReport();
                    const csv = convertToCsv(data);
                    downloadCsv(csv, 'relatorio_fretes_detalhado.csv');
                } catch (e) {
                    console.error(e);
                } finally {
                    toggleLoading(false);
                }
            });
            
        });