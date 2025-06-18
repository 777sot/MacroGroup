BX.ready(function() {
	BX.Vue.create({
		el: '#deals',
		data: {
			componentName: 'namespace:local.components'
		},
		template: `
            <div class="deals-container">
                <div class="deals-header">
                    <h2>Список сделок</h2>
                    <div class="sort-controls">
                        <label>Сортировка:</label>
                        <select v-model="sortField" @change="loadDeals">
                            <option value="TITLE">По названию</option>
                            <option value="DATE_CREATE">По дате</option>
                            <option value="OPPORTUNITY">По сумме</option>
                        </select>
                        <select v-model="sortOrder" @change="loadDeals">
                            <option value="ASC">По возрастанию</option>
                            <option value="DESC">По убыванию</option>
                        </select>
                    </div>
                </div>
                
                <div v-if="loading" class="loading">Загрузка...</div>
                <div v-if="error" class="error">{{ error }}</div>
                
                <table v-if="deals.length > 0" class="deals-table">
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Стадия</th>
                            <th>Сумма</th>
                            <th>Дата создания</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="deal in deals" :key="deal.id">
                            <td>{{ deal.title }}</td>
                            <td>{{ deal.stage }}</td>
                            <td>{{ deal.amount }} {{ deal.currency }}</td>
                            <td>{{ deal.date }}</td>
                        </tr>
                    </tbody>
                </table>
                
                <div v-else-if="!loading" class="no-deals">Нет доступных сделок</div>
            </div>
        `,
		data() {
			return {
				deals: [],
				loading: false,
				error: null,
				sortField: 'DATE_CREATE',
				sortOrder: 'DESC'
			};
		},
		mounted() {
			this.loadDeals();
		},
		methods: {
			async loadDeals() {
				this.loading = true;
				this.error = null;

				try {
					const response = await BX.ajax.runComponentAction(
						'namespace:local.components',
						'getDeals',
						{
							mode: 'class',
							data: {
								sortField: this.sortField,
								sortOrder: this.sortOrder
							}
						}
					);

					this.deals = response.data.deals || [];
				} catch (e) {
					console.error('Deals loading error:', e);
					this.error = 'Ошибка загрузки сделок: ' +
						(e.errors ? JSON.stringify(e.errors) : e.message);
				} finally {
					this.loading = false;
				}
			}
		}
	});
});
