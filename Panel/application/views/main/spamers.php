<div class="row">
	<div class="col-12 col-lg-7 stretch-card grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="d-md-flex justify-content-between">
					<h4 class="card-title">Активность спамеров по месяцам</h4>
					<div id="month_chart_legend" class="rounded-legend legend-horizontal"></div>
				</div>
				<div class="spamers-data">
					<canvas id="spamers_month_logs_line_chart"></canvas>
				</div>
				<div class="spamers-warning text-center">
					<p class="py-4">Активных спамеров не найдено</p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-5 stretch-card grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Доля логов от спамеров</h4>

				<div class="spamers-data">
					<canvas id="spamers_logs_pie_chart"></canvas>
					<div id="pie_chart_legend" class="legend-wrapper rounded-legend legend-vertical legend-bottom-left pt-4"></div>
				</div>
				<div class="spamers-warning text-center">
					<p class="py-4">Активных спамеров не найдено</p>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12 col-lg-6 stretch-card grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Управление спамерами <button class="btn btn-outline-secondary btn-circle btn-circle-sm" data-toggle="modal" data-target="#quest_modal_spamers"><i class="fas fa-question"></i></button></h4>
				<p class="card-description">Добавление/изменение/удаление ваших спамеров</p>

				<button class="btn btn-purple" data-toggle="modal" data-target="#add_spamer_modal">Добавить спамера</button>
				
				<div class="table-responsive">
					<table class="table" id="table-spamers">
						<thead>
							<tr>
								<th>Логин</th>
								<th>Токен</th>
								<th>Инструменты</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-6 stretch-card grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Статистика спамеров</h4>
				<p class="card-description">Здесь показаны средние значения данных, которые приходят от спамеров. Так вы можете оценить, чей трафик лучше</p>

				<div class="table-responsive">
					<table class="table" id="table-spamers_stat">
						<thead>
							<tr>
								<th>Логин</th>
								<th>Общее число логов</th>
								<th>Среднее значение данных</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Таблица логов спамеров</h4>
				<p class="card-description">Здесь отображаются только логи, поступившие от спамеров</p>

				<div class="table-responsive">
					<table class="table logs_table" id="table">
						<thead>
							<tr>
								<th scope="col"></th>
								<th scope="col" class="text-center">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="CheckAll">
								  		<label class="custom-control-label" for="CheckAll"></label>
									</div>
								</th>
								<th scope="col">#</th>
								<th scope="col">Страна/IP</th>
								<th scope="col">Данные</th>
								<th scope="col">Дата</th>
								<th scope="col">Вес</th>
								<th scope="col">Скриншот</th>
								<th scope="col">Файл</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add_spamer_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить спамера</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="tag_name">Логин спамера</label>
					<input type="text" class="form-control" placeholder="Придумайте его логин" id="create_spamer_login">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-purple" data-dismiss="modal" onclick="create_spamer();">Создать</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="edit_spamer_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Изменить спамера</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="tag_name">Логин спамера</label>
					<input type="text" class="form-control" placeholder="Придумайте его логин" id="edit_spamer_login">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-purple" onclick="edit_spamer();">Изменить</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="quest_modal_spamers" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Спамеры</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Спамеры - это люди, которых вы нанимаете распространять ваш билд. На данной странице вы сможете легко управлять их трафиком. Создайте аккаунт для спамера и попросите саппорта создать на него билд. Далее передайте билд и токен, который высветится у вас в таблице спамеру, чтобы он смог легко проверять, сколько логов он смог собрать</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Ок</button>
			</div>
		</div>
	</div>
</div>


<div id="custom-table-select" class="d-flex">
	<label>
		Выбран спамер:
		<select id="spamer_select" class="form-control float-right custom-select">
			<option value="0">Все</option>
		</select>
	</label>
</div>

<script>
	var table_spamers;
	var table_spamers_stat;
	var old_spamer_name;

	$(document).ready(function() {

		logs_spamer = "0";
		TableLogsInit('<"d-sm-flex justify-content-between align-items-center"<"#logs_spamer">f>t<"d-sm-flex justify-content-between"<"#logs_action">p>');


		table_spamers_stat = $("#table-spamers_stat").DataTable({
			dom: 't',
			ajax: {
				type: 'POST',
				url: '/getTable/spamers_stat',
			},
			columns: [
				{
					data: 'login'
				},
				{
					data: 'logs',
				},
				{
					data: 'data[]',
					render: function(data, type, row) {
						if(type == 'display')
							return "<i class='fal fa-key table-data-icon' data-placement=\"top\" data-toggle='tooltip' title='Пароли'></i> " + data[0] + " " +
	                        	"<i class='fal fa-cookie-bite table-data-icon' data-placement=\"bottom\" data-toggle='tooltip' title='Куки'></i> " + data[1] + " " +
	                        	"<i class='fal fa-credit-card-front table-data-icon' data-placement=\"top\" data-toggle='tooltip' title='Карты'></i> " + data[2] + " " +
	                        	"<i class='fal fa-address-card table-data-icon' data-placement=\"bottom\" data-toggle='tooltip' title='Формы'></i> " + data[3];
	                    else
	                    	return data[0];
					},
				},
			],
		});


		table_spamers = $("#table-spamers").DataTable({
			dom: 't',
			ajax: {
				type: 'POST',
				url: '/getTable/spamers',
			},
			columns: [
				{
					data: 'login'
				},
				{
					data: 'hash'
				},
				{
					data: 'actions[]',
					orderable: false,
					render: function(data, type, row) {
						return "<div class=\"btn-group\">" + 
							"<button class=\"btn btn-info\" onclick=\"spamer_info('" + data[1] + "');\"><i class=\"fas fa-info\"></i></button>" +
							"<button class=\"btn btn-danger\" onclick=\"spamer_delete('" + data[0] + "');\"><i class=\"fas fa-trash-alt\"></i></button>" + 
							"</div>";
					}
				}
			],
		});
		
		update_page_data(false);
	    $("#custom-table-select").appendTo("#logs_spamer");
	    $("#logs_action").html(get_instrument_to_log(0, 0, false, false, false));
	});

	$("#spamer_select").change(function() {
		logs_spamer = $("#spamer_select").val();
		table.ajax.reload();
	});

	$("#table-spmears_stat").on( 'init.dt', function () {
	    $("#table-spamers_stat [data-toggle=\"tooltip\"]").tooltip({
	        delay: { "show": 100, "hide": 1000 }
	    });
	});
	
	function spamer_info(login) {
		old_spamer_name = login;
		$("#edit_spamer_login").val(login);
		$("#edit_spamer_modal").modal();
	}

	function spamer_delete(id) {
		swal.fire({
			type: "warning",
			title: "Вы уверены?",
			text: "Если вы продолжите, то выбранный вами спамер будет удален, но его логи остануться активными",
			showCancelButton: true,
	        confirmButtonText: 'Да, удалить',
	        cancelButtonText: 'Назад',
	        customClass: {
				popup: 'swal-popup-class',
				title: 'swal-title-class',
			},
	    }).then((result) => {
	        if (result.value) {
				data = sendData("/spamer/delete", {"id": id});
				if(data) {
					if(CheckData(data)) {
						update_page_data();
					}
				}
			}
		});
	}

	function edit_spamer() {
		data = sendData('/spamer/edit', {'o_name': old_spamer_name, 'n_name': $("#edit_spamer_login").val()});
		if(data) {
			data = CheckData(data);
			if(data != false) {
				SuccessAlert('Спамер сохранен');
				$("#edit_spamer_modal").modal('hide');
				update_page_data();
			}
		}
	}

	function create_spamer() {
		data = sendData("/spamer/add", {'login': $("#create_spamer_login").val()});
		if(data) {
			data = CheckData(data);
			if(data) {
				$("#add_spamer_modal").modal('hide');
				update_page_data();
			}
		}
	}

	function update_page_data(ajax) {
		if(ajax != false) {
			table_spamers_stat.ajax.reload();
			table_spamers.ajax.reload();
			table.ajax.reload();
		}
		

		data = sendData('/spamer/alldata');
		if(data) {
			$("#spamer_select").html("");
			$("#spamer_select").append(new Option("Все", '0'));
			
			for(var i in data) {
				$("#spamer_select").append(new Option(data[i].login, data[i].hash));
			}
			data.sort((prev, next) => next.logs - prev.logs);
			data = data.slice(0, 3);

			if(data.length == 0) {
				$(".spamers-data").each(function() {
					$(this).addClass('d-none');
					$(this).removeClass('d-block');
				});
				$(".spamers-warning").each(function() {
					$(this).addClass('d-block');
					$(this).removeClass('d-none');
				});
				return;
			}
			else {
				$(".spamers-data").each(function() {
					$(this).addClass('d-block');
					$(this).removeClass('d-none');
				});
				$(".spamers-warning").each(function() {
					$(this).addClass('d-none');
					$(this).removeClass('d-block');
				});
			}

			var lineChartDatasets = [];
			var dates_chart = [];

			var legend_colors = [];
			var chart_colors = [];


			var ctx = document.getElementById('spamers_month_logs_line_chart').getContext("2d");

			var gradientStrokeViolet = ctx.createLinearGradient(0, 0, 0, 181);
			gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
			gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');
			var gradientLegendViolet = 'linear-gradient(to right, rgba(218, 140, 255, 1), rgba(154, 85, 255, 1))';

			chart_colors.push(gradientStrokeViolet);
			legend_colors.push(gradientLegendViolet);

			var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 360);
			gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
			gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
			var gradientLegendBlue = 'linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))';

			chart_colors.push(gradientStrokeBlue);
			legend_colors.push(gradientLegendBlue);

			var gradientStrokeRed = ctx.createLinearGradient(0, 0, 0, 300);
			gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
			gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
			var gradientLegendRed = 'linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))';

			chart_colors.push(gradientStrokeRed);
			legend_colors.push(gradientLegendRed);

			var gradientStrokeGreen = ctx.createLinearGradient(0, 0, 0, 300);
			gradientStrokeGreen.addColorStop(0, 'rgba(6, 185, 157, 1)');
			gradientStrokeGreen.addColorStop(1, 'rgba(132, 217, 210, 1)');
			var gradientLegendGreen = 'linear-gradient(to right, rgba(6, 185, 157, 1), rgba(132, 217, 210, 1))';   

			chart_colors.push(gradientStrokeGreen);
			legend_colors.push(gradientLegendGreen);

			activity = sendData('/spamer/activity');
			if(activity) {
				dates_filled = false;

				// console.log(activity);

				activity.forEach(function(item, i, activity) {
					logs = [];

					values = item.data;

					if(dates_filled == false) {
						values.forEach(function(payload, j, values) {
							dates_chart.push(payload.month);
						});
						dates_filled = true;
					}

					values.forEach(function(payload, j, values) {
						logs.push(payload.logs);
					});

					lineChartDatasets.push({
						label: item.spamer,
						pointRadius: 1,
						fill: false,
						borderColor: chart_colors[i],
	                    backgroundColor: chart_colors[i],
	                    hoverBackgroundColor: chart_colors[i],
	                    legendColor: legend_colors[i],
						borderWidth: 3,
						data: logs
					});
				});


				var lineChart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: dates_chart,
						datasets: lineChartDatasets,
					},
					options: {
						responsive: true,
						legend: false,
						legendCallback: function(chart) {
							var text = []; 
							text.push('<ul>'); 
							for (var i = 0; i < chart.data.datasets.length; i++) { 
								text.push('<li><span class="legend-dots" style="background:' + 
											chart.data.datasets[i].legendColor + 
											'"></span>'); 
								if (chart.data.datasets[i].label) { 
									text.push(chart.data.datasets[i].label); 
								} 
								text.push('</li>'); 
							} 
							text.push('</ul>'); 
							return text.join('');
						},
						scales: {
							yAxes: [{
								ticks: {
									display: true,
									min: 0,
									stepSize: 1,
								},
								gridLines: {
									drawBorder: false,
									color: '#322f2f',
									zeroLineColor: '#322f2f'
								}
							}],
							xAxes: [{
								gridLines: {
									display: false,
									drawBorder: false,
									color: 'rgba(0,0,0,1)',
									zeroLineColor: 'rgba(235,237,242,1)'
								},
								ticks: {
									padding: 20,
									fontColor: "#9c9fa6",
									autoSkip: true,
								},
								categoryPercentage: 0.5,
								barPercentage: 0.5
							}]
						}
					},
					elements: {
						point: {
							radius: 0
						}
					}
				})
				$("#month_chart_legend").html(lineChart.generateLegend());
			}

			spamers_names = [];
			spamers_logs = [];

			all_logs = 0;

			data.forEach(function(item, i, data) {
				spamers_names.push(item.login);
				all_logs += item.logs;
			});

			data.forEach(function(item, i, data) {
				if(item.logs != 0)
					spamers_logs.push(100 / (all_logs / item.logs));
				else
					spamers_logs.push(0);
			});



			var PieChartCanvas = $("#spamers_logs_pie_chart").get(0).getContext("2d");

			var PieChartData = {
				datasets: [{
					data: spamers_logs,
					backgroundColor: [
						gradientStrokeBlue,
						gradientStrokeGreen,
						gradientStrokeRed
					],
					hoverBackgroundColor: [
						gradientStrokeBlue,
						gradientStrokeGreen,
						gradientStrokeRed
					],
					borderColor: [
						gradientStrokeBlue,
						gradientStrokeGreen,
						gradientStrokeRed
					],
					legendColor: [
						gradientLegendBlue,
						gradientLegendGreen,
						gradientLegendRed
					]
				}],
				labels: spamers_names
			};

			var PieChartOptions = {
				responsive: true,
				animation: {
					animateScale: true,
					animateRotate: true
				},
				legend: false,
				legendCallback: function(chart) {
					var text = []; 
					text.push('<ul>'); 
					for (var i = 0; i < PieChartData.datasets[0].data.length; i++) { 
						text.push('<li><span class="legend-dots" style="background:' + 
						PieChartData.datasets[0].legendColor[i] + 
												'"></span>'); 
						if (PieChartData.labels[i]) { 
								text.push(PieChartData.labels[i]); 
						}
						text.push('<span class="float-right">'+ PieChartData.datasets[0].data[i]+"%"+'</span>')
						text.push('</li>'); 
					} 
					text.push('</ul>'); 
					return text.join('');
				}
			};
			var PieChart = new Chart(PieChartCanvas, {
				type: 'doughnut',
				data: PieChartData,
				options: PieChartOptions
			});
			$("#pie_chart_legend").html(PieChart.generateLegend()); 
		}
	}
</script>