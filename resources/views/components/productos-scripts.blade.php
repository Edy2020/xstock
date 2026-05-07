<script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputBuscar = document.getElementById('filter-buscar');
            const selectCategoria = document.getElementById('filter-categoria');
            const selectProveedor = document.getElementById('filter-proveedor');
            const selectEstado = document.getElementById('filter-estado');
            const btnLimpiar = document.getElementById('btn-limpiar');
            
            const rowElements = document.querySelectorAll('.producto-row');
            const tableContainer = document.getElementById('table-container');
            const noResultsState = document.getElementById('no-results-state');

            function filterTable() {
                const term = inputBuscar.value.toLowerCase().trim();
                const cat = selectCategoria.value.toLowerCase();
                const prov = selectProveedor.value;
                const est = selectEstado.value;

                let hasVisibleRows = false;
                const isFiltered = term !== '' || cat !== '' || prov !== '' || est !== '';

                btnLimpiar.style.display = isFiltered ? 'inline-flex' : 'none';

                rowElements.forEach(row => {
                    const rowName = row.dataset.nombre;
                    const rowCat = row.dataset.categoria;
                    const rowProv = row.dataset.proveedor;
                    const rowEst = row.dataset.estado;

                    const matchesTerm = term === '' || rowName.includes(term);
                    const matchesCat = cat === '' || rowCat === cat;
                    const matchesProv = prov === '' || rowProv === prov;
                    const matchesEst = est === '' || rowEst === est;

                    if (matchesTerm && matchesCat && matchesProv && matchesEst) {
                        row.style.display = '';
                        hasVisibleRows = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (tableContainer && noResultsState) {
                    tableContainer.style.display = hasVisibleRows ? '' : 'none';
                    noResultsState.style.display = hasVisibleRows ? 'none' : 'block';
                }
            }

            inputBuscar.addEventListener('input', filterTable);
            selectCategoria.addEventListener('change', filterTable);
            selectProveedor.addEventListener('change', filterTable);
            selectEstado.addEventListener('change', filterTable);

            btnLimpiar.addEventListener('click', (e) => {
                e.preventDefault();
                inputBuscar.value = '';
                selectCategoria.value = '';
                selectProveedor.value = '';
                selectEstado.value = '';
                filterTable();
            });
        });
</script>