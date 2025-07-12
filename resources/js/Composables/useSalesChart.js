export default function useSalesChart(rowData = []) {
  const categories = rowData.map(item => item.date); // ou item.alcunha
  const data = rowData.map(item => item.total);

  const type = 'line';

  const options = {
    chart: { id: 'sales' },
    xaxis: { categories }, // Datas ou Alcunhas
    dataLabels: { enabled: false },
  };

  const series = [
    {
      name: 'Total de Vendas',
      data
    }
  ];

  return { type, options, series };
}