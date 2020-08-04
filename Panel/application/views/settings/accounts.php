<div class="row">
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Настройки аккаунта</h4>

				<div class="form-group">
					<label for="">Логин</label>
					<input type="text" value="<?=$login?>" id="my_login" class="form-control" placeholder="Придумайте себе логин">
				</div>
				<div class="form-group">
					<label for="">Пароль</label>
					<input type="password" id="my_password" class="form-control" placeholder="Придумайте себе новый пароль">
				</div>
				<div class="form-group">
					<label for="">Повтор пароля</label>
					<input type="password" id="my_password_repeat" class="form-control" placeholder="Повторите введеный пароль">
				</div>

				<button class="btn btn-purple float-right" id="save_my_account">Сохранить</button>
			</div>
		</div>
	</div>
	<div class="col-12 grid-margin">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Управление другими аккаунтами</h4>
				<p class="card-description">Создайте аккаунт для своего друга, если вы хотите разделить логи. Передайте саппорту новый id пользователя для генерации билда</p>

				<button class="btn btn-purple" data-toggle="modal" data-target="#add_account_modal">Добавить аккаунт</button>

				<div class="table-responsive">
					<table id="table-users" class="table">
						<thead>
							<tr>
								<th>id</th>
								<th>Логин</th>
								<th>Права</th>
								<th>Дата создания</th>
								<th>Инструменты</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="add_account_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Добавить аккаунт</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="">
					<div class="form-group">
						<label for="tag_name">Логин</label>
						<input type="text" class="form-control" placeholder="Логин аккаунта" id="add_login">
					</div>
					<div class="form-group">
						<label for="tag_name">Пароль</label>
						<input type="password" class="form-control" placeholder="Пароль аккаунта" id="add_password">
					</div>
					<div class="form-group">
						<label for="tag_name">Повторите пароль</label>
						<input type="password" class="form-control" placeholder="Повтор пароля" id="add_repeat_password">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-purple" id="create_account">Создать</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="edit_account_modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Изменить аккаунт</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="">
					<div class="form-group">
						<label for="tag_name">Логин</label>
						<input type="text" class="form-control" placeholder="Логин аккаунта" id="edit_login">
					</div>
					<div class="form-group">
						<label for="tag_name">Пароль</label>
						<input type="password" class="form-control" placeholder="Пароль аккаунта" id="edit_password">
					</div>
					<div class="form-group">
						<label for="tag_name">Повторите пароль</label>
						<input type="password" class="form-control" placeholder="Повтор пароля" id="edit_repeat_password">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-purple" id="edit_account">Изменять</button>
			</div>
		</div>
	</div>
</div>



<script>
	var table;
	var edit_id = 0;
	$(document).ready(function() {
		table = $("#table-users").DataTable({
			ajax: {
				url: "/settings/info/accounts",
				type: "POST",
			},
			dom: "t",
			columns: [
				{ data: "id" },
				{ data: "login" },
				{ 
					data: "admin",
					render: function(data, type, row) {
						if(data == 1)
							return "Админ";
						else
							return "Пользователь";
					},
				},
				{ data: "date" },
				{ 
					orderable: false,
					data: "data[]",
					render: function(data, type, row) {
						return '<div class="btn-group"><button class="btn btn-danger" onclick="delete_user(\'' + data[0] + '\');"><i class="fas fa-trash-alt"></i></button>' +
	            			'<button class="btn btn-info" onclick="change_user(\'' + data[0] + '\', \'' + data[1] + '\');"><i class="fas fa-info"></i></button></div>';
					},
				},
			],
		});
	});

	function delete_user(id) {
		swal.fire({
			icon: "warning",
			title: "Вы уверены?",
			text: "Если вы продолжите, то выбранный вами пользователь будет удален. Все его логи перейдут вам",
			showCancelButton: true,
	        confirmButtonText: 'Да, удалить',
	        cancelButtonText: 'Назад',
			customClass: {
				popup: 'swal-popup-class',
				title: 'swal-title-class',
			},
	    }).then((result) => {
	        if (result.value) {
	            data = sendData("/settings/delete/accounts", {"id": id});
	            if(data) {
					if(CheckData(data)) {
						table.ajax.reload();
					}
				}
	        }
	    });
	}

	function change_user(id, login) {
		edit_id = id;
		$("#edit_login").val(login);

		$("#edit_account_modal").modal();
	}

	$("#save_my_account").click(function() {
		pass = $("#my_password").val();
		repeat = $("#my_password_repeat").val();

		if(pass == repeat) {
			data = sendData("/settings/save/accounts", {"login": $("#my_login").val(), "password": pass});
			if(data) {
				data = CheckData(data);
				if(data != false) {
					SuccessAlert("Настройки сохранены");
				}
			}
		}
		else {
			swal.fire({
				icon: 'error',
				title: 'Ошибка',
				text: 'Пароли не совпадают',
				customClass: {
					popup: 'swal-popup-class',
					title: 'swal-title-class',
				},
			});
		}
	});

	$("#create_account").click(function() {
		login = $("#add_login").val();
		pass = $("#add_password").val();
		pass_repeat = $("#add_repeat_password").val();

		$("#add_password").val("");
		$("#add_repeat_password").val("");

		if(pass == pass_repeat) {
			data = sendData("/settings/edit/accounts", {"login": login, "password": pass});
			if(data) {
				data = CheckData(data);
				if(data != false) {
					SuccessAlert("Пользователь создан");
					$("#add_login").val("");
					$("#add_account_modal").modal('hide');
					table.ajax.reload();
				}
			}
		}
		else {
			swal.fire({
				icon: 'error',
				title: 'Ошибка',
				text: 'Пароли не совпадают',
				customClass: {
					popup: 'swal-popup-class',
					title: 'swal-title-class',
				},
			});
		}
	});

	$("#edit_account").click(function() {
		pass = $("#edit_password").val();
		repeat = $("#edit_repeat_password").val();

		$("#edit_password").val("");
		$("#edit_repeat_password").val("");

		if(pass == repeat) {
			data = sendData("/settings/save/accounts", {"login": $("#edit_login").val(), "password": pass, "id": edit_id});
			if(data) {
				data = CheckData(data);
				if(data != false) {
					SuccessAlert("Пользователь изменен");
					$("#edit_login").val("");
					$("#edit_account_modal").modal('hide');
					table.ajax.reload();
				}
			}
		}
		else {
			swal.fire({
				icon: 'error',
				title: 'Ошибка',
				text: 'Пароли не совпадают',
				customClass: {
					popup: 'swal-popup-class',
					title: 'swal-title-class',
				},
			});
		}
	});
</script>