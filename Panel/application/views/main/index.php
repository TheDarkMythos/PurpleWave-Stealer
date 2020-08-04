<div class="row">
	<div class="col-12 col-md-7 stretch-card grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between">
					<h4 class="card-title">Логи по дате</h4>
					<div id="logs-by-date-legend" class="rounded-legend legend-horizontal"></div>
				</div>

				<canvas id="logs-by-date"></canvas>

				<div class="w-100 text-center no-logs py-4">
					<h5>Активных логов не найдено</h5>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-5 stretch-card grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Windows</h4>

				<canvas id="logs-by-windows"></canvas>

				<div class="w-100 text-center no-logs py-4">
					<h5>Активных логов не найдено</h5>
				</div>

				<div id="logs-by-windows-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4"></div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12 col-md-6 col-lg-3 stretch-card grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between">
					<i class="fas icon-lg fa-history text-danger"></i>
					<div>
						<p class="mb-0 text-right">Всего лога(-ов)</p>
						<h3 class="font-weight-medium text-right mb-0" id="all_time_logs_count">Загрузка...</h3>
					</div>
				</div>
				<p class="text-muted mt-3 mb-0">На данный момент</p>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-md-6 col-lg-3 stretch-card grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between">
					<i class="fas fa-calendar-alt icon-lg text-warning"></i>
					<div>
						<p class="mb-0 text-right">За месяц</p>
						<h3 class="font-weight-medium text-right mb-0" id="month_logs_count">Загрузка...</h3>
					</div>
				</div>
				<p class="text-muted mt-3 mb-0">За последний месяц</p>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-md-6 col-lg-3 stretch-card grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between">
					<i class="fas fa-calendar-week icon-lg text-success"></i>
					<div>
						<p class="mb-0 text-right">За неделю</p>
						<h3 class="font-weight-medium text-right mb-0" id="week_logs_count">Загрузка...</h3>
					</div>
				</div>
				<p class="text-muted mt-3 mb-0">На этой неделе</p>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-md-6 col-lg-3 stretch-card grid-margin">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between">
					<i class="fas fa-calendar-day icon-lg text-primary"></i>
					<div>
						<p class="mb-0 text-right">За 24ч</p>
						<h3 class="font-weight-medium text-right mb-0" id="today_logs_count">Загрузка...</h3>
					</div>
				</div>
				<p class="text-muted mt-3 mb-0">Сегодня</p>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Последние логи</h4>
				<p class="card-description">Последние 5 поступивших логов</p>
		
				<div class="table-responsive">
					<table class="table logs_table" id="table-last_logs">
						<thead>
							<tr>
								<th scope="col"></th>
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


<script>

	function update_page_data() {
		table.ajax.reload(function() {
			$(".logs-screen").magnificPopup({type:"image"});
		});
		update_main_stat();
		update_chart();
	}

	$(document).ready(function() {
		logs_last = "last";
		TableLogsInit('t');

		update_main_stat();
		update_chart();
	});

	function update_main_stat() {
		data = sendData("/logs/statistic/count");
		if(data != false) {
			$("#all_time_logs_count").text(data.data[0]["all"] + " шт.");
			$("#month_logs_count").text(data.data[0]["month"] + " шт.");
			$("#week_logs_count").text(data.data[0]["week"] + " шт.");
			$("#today_logs_count").text(data.data[0]["today"] + " шт.");

			if(data.data[0]["all"] == 0) {
				$(".no-logs").each(function() {
					$(this).removeClass("d-none");
					$("canvas").each(function() {
						$(this).addClass("d-none");
					})
				});
			}
			else {
				$(".no-logs").each(function() {
					$(this).addClass("d-none");
					$("canvas").each(function() {
						$(this).removeClass("d-none");
					})
				});
			}
		}
	}

	function update_chart() {
		var ctx = document.getElementById('logs-by-date').getContext("2d");

		var gradientStrokeViolet = ctx.createLinearGradient(0, 0, 0, 181);
		gradientStrokeViolet.addColorStop(0, 'rgba(218, 140, 255, 1)');
		gradientStrokeViolet.addColorStop(1, 'rgba(154, 85, 255, 1)');
		var gradientLegendViolet = 'linear-gradient(to right, rgba(218, 140, 255, 1), rgba(154, 85, 255, 1))';

		var gradientStrokeBlue = ctx.createLinearGradient(0, 0, 0, 360);
		gradientStrokeBlue.addColorStop(0, 'rgba(54, 215, 232, 1)');
		gradientStrokeBlue.addColorStop(1, 'rgba(177, 148, 250, 1)');
		var gradientLegendBlue = 'linear-gradient(to right, rgba(54, 215, 232, 1), rgba(177, 148, 250, 1))';

		var gradientStrokeRed = ctx.createLinearGradient(0, 0, 0, 300);
		gradientStrokeRed.addColorStop(0, 'rgba(255, 191, 150, 1)');
		gradientStrokeRed.addColorStop(1, 'rgba(254, 112, 150, 1)');
		var gradientLegendRed = 'linear-gradient(to right, rgba(255, 191, 150, 1), rgba(254, 112, 150, 1))';

		var gradientStrokeGreen = ctx.createLinearGradient(0, 0, 0, 300);
		gradientStrokeGreen.addColorStop(0, 'rgba(6, 185, 157, 1)');
		gradientStrokeGreen.addColorStop(1, 'rgba(132, 217, 210, 1)');
		var gradientLegendGreen = 'linear-gradient(to right, rgba(6, 185, 157, 1), rgba(132, 217, 210, 1))';      

		data = sendData('/logs/statistic/logs');
		if(data != false) {
			data = CheckData(data);
			if(data != false) {

				titles = [];
				values = [];

				data.data.forEach(function(item, i, arr) {
				  titles.push(item.title);
				  values.push(item.value);
				});

				var myChart = new Chart(ctx, {
					type: 'line',
					data: {
						labels: titles,
						datasets: [
						{
							label: "Логи",
							borderColor: gradientStrokeViolet,
							backgroundColor: gradientStrokeViolet,
							hoverBackgroundColor: gradientStrokeViolet,
							legendColor: gradientLegendViolet,
							pointRadius: 1,
							fill: false,
							borderWidth: 3,
								data: values
						}]
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
				$("#logs-by-date-legend").html(myChart.generateLegend());
			}
		}


		data = sendData('/logs/statistic/windows');
		if(data != false) {
			data = CheckData(data);
			if(data != false) {

				all_logs = 0;
				titles = [];
				values = [];

				data.data.forEach(function(item, i, arr) {
				  titles.push(item.title);
				  values.push(item.value);
				  all_logs = item.all_logs;
				});

				var windowsChartData = {
					datasets: [{
						data: values,
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
					labels: titles
				};
			}
			var windowsChartOptions = {
				responsive: true,
				animation: {
					animateScale: true,
					animateRotate: true
				},
				legend: false,
				legendCallback: function(chart) {
					var text = []; 
					text.push('<ul>'); 
					for (var i = 0; i < windowsChartData.datasets[0].data.length; i++) { 
						text.push('<li><span class="legend-dots" style="background:' + 
						windowsChartData.datasets[0].legendColor[i] + 
												'"></span>'); 
						if (windowsChartData.labels[i]) { 
								text.push(windowsChartData.labels[i]); 
						}
						text.push('<span class="float-right">'+ Math.floor((100 / (all_logs / windowsChartData.datasets[0].data[i])) * 100) / 100 +"%"+'</span>')
						text.push('</li>'); 
					} 
					text.push('</ul>'); 
					return text.join('');
				}
			};
			var windowsChartCanvas = $("#logs-by-windows").get(0).getContext("2d");
			var windowsChart = new Chart(windowsChartCanvas, {
				type: 'doughnut',
				data: windowsChartData,
				options: windowsChartOptions
			});
			$("#logs-by-windows-legend").html(windowsChart.generateLegend()); 
		}
	}
</script>