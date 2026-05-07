<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    Chart.defaults.font.family = 'Inter, Figtree, system-ui, sans-serif';
    Chart.defaults.color = '#71717a';
    Chart.defaults.scale.grid.color = '#e4e4e7';
    Chart.defaults.animation = false;
    Chart.defaults.plugins.tooltip.backgroundColor = '#ffffff';
    Chart.defaults.plugins.tooltip.titleColor = '#18181b';
    Chart.defaults.plugins.tooltip.bodyColor = '#71717a';
    Chart.defaults.plugins.tooltip.borderColor = '#e4e4e7';
    Chart.defaults.plugins.tooltip.borderWidth = 1;
    Chart.defaults.plugins.tooltip.padding = 10;
    Chart.defaults.plugins.tooltip.displayColors = false;

    const cd = window.chartData || {};
    let chartVentasInst = null;
    let chartCatInst = null;
    let chartTenInst = null;

    function initCharts() {
        const ctxVentas = document.getElementById('chart-ventas');
        if (ctxVentas && cd.dias) {
            const labels = cd.dias.map(d => d.dia);
            const dataVentas = cd.dias.map(d => d.ventas);
            const isToday = cd.dias.map(d => d.is_today ? '#2563eb' : '#bfdbfe');

            chartVentasInst = new Chart(ctxVentas, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ventas',
                        data: dataVentas,
                        backgroundColor: isToday,
                        borderRadius: 4,
                        barThickness: 24
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, border: { display: false } }
                    }
                }
            });
        }

        const ctxCat = document.getElementById('chart-categorias');
        if (ctxCat && cd.categorias) {
            const labels = cd.categorias.map(c => c.categoria);
            const dataStock = cd.categorias.map(c => c.stock_total);
            const bgColors = ['#2563eb', '#10b981', '#f59e0b', '#dc2626', '#8b5cf6', '#64748b', '#0ea5e9'];

            chartCatInst = new Chart(ctxCat, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataStock,
                        backgroundColor: bgColors.slice(0, labels.length),
                        borderWidth: 0,
                        hoverOffset: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } }
                    }
                }
            });
        }

        const ctxTen = document.getElementById('chart-tendencia');
        if (ctxTen && cd.semanas) {
            const labels = cd.semanas.map(s => s.sem);
            const dataVentas = cd.semanas.map(s => s.ventas);

            chartTenInst = new Chart(ctxTen, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ventas Semanales',
                        data: dataVentas,
                        borderColor: '#10b981',
                        borderWidth: 2,
                        backgroundColor: '#10b981',
                        pointRadius: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 4,
                        fill: false,
                        tension: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, border: { display: false } }
                    }
                }
            });
        }
    }

    function setPeriodData(period) {
        document.querySelectorAll('.filter-btn-period').forEach(btn => {
            btn.classList.remove('btn-primary');
            btn.classList.add('btn-secondary');
            if(btn.dataset.period === period) {
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-primary');
            }
        });

        const data = (window.chartData.periodData || {})[period];
        if(data) {
            document.getElementById('lbl-ventas').innerText = data.labelVentas;
            document.getElementById('val-ventas').innerText = data.ventas;
            document.getElementById('lbl-ingresos').innerText = data.labelIngresos;
            document.getElementById('val-ingresos').innerText = data.ingresos;
            document.getElementById('lbl-gastos').innerText = data.labelGastos;
            document.getElementById('val-gastos').innerText = data.gastos;
            document.getElementById('lbl-balance').innerText = data.labelBalance;
            document.getElementById('val-balance').innerText = data.balance;

            const balanceBg = document.getElementById('icon-balance-bg');
            const balanceSvg = document.getElementById('icon-balance-svg');
            
            if (data.balance_raw >= 0) {
                balanceBg.style.background = '#f0fdf4';
                balanceSvg.setAttribute('stroke', '#16a34a');
            } else {
                balanceBg.style.background = '#fef2f2';
                balanceSvg.setAttribute('stroke', '#dc2626');
            }
        }
    }

    function switchStatsTab(tabName) {
        document.getElementById('tab-pane-general').style.display = 'none';
        document.getElementById('tab-pane-ventas').style.display = 'none';
        document.getElementById('tab-pane-inventario').style.display = 'none';

        document.getElementById('tab-btn-general').className = 'btn-secondary';
        document.getElementById('tab-btn-ventas').className = 'btn-secondary';
        document.getElementById('tab-btn-inventario').className = 'btn-secondary';

        document.getElementById('tab-pane-' + tabName).style.display = 'block';
        document.getElementById('tab-btn-' + tabName).className = 'btn-primary';
    }

    document.addEventListener('DOMContentLoaded', initCharts);
</script>

<style>
    @media (min-width: 769px) {
        #stats-tabs-container { display: none !important; }
        #tab-pane-general, #tab-pane-ventas, #tab-pane-inventario { display: block !important; }
    }
    @media (max-width: 1024px) {
        #tab-pane-general > div:first-child {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
    @media (max-width: 600px) {
        #tab-pane-general > div:first-child {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
</style>
