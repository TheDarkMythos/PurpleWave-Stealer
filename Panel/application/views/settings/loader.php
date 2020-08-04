<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">Установка подгружаемого файла</h4>

				<form action="#" method="POST">
					<div class="form-group">
						<label for="">Файл</label>

						<input type="file" name="loader-file[]" multiple class="file-upload-default">
						<div class="input-group col-xs-12">
							<input type="text" class="form-control file-upload-info" disabled="" id="loaders" placeholder="Файлы лоадера">
							<span class="input-group-append">
								<button class="file-upload-browse btn btn-purple" type="button">Выбрать</button>
							</span>
						</div>
					</div>

					<div class="form-group">
						<div class="custom-control custom-checkbox">
		                    <input type="checkbox" name="use_loader" id="use_loader" checked="" class="custom-control-input table-checkbox">
		                    <label class="custom-control-label table-checkbox-label" for="use_loader">Использовать лоадер (при выключении удаляет все файлы и ссылки)</label>
		                </div>
					</div>

					<button type="submit" class="btn btn-purple" id="save_loader">Сохранить</button>
				</form>
				
			</div>
		</div>
	</div>
</div>

<script>
	function update_page_data() {
		data = sendData('/settings/info/loader');

		if(data != null) {
			$("#loaders").val(data.join(', '));
			$("#use_loader").prop('checked', true);
		}
		else {
			$("#loaders").val("");
			$("#use_loader").prop('checked', false);
		}
	}

	$(document).ready(function() {
		update_page_data();
	});

	$("#save_loader").click(function(e) {
		e.preventDefault();

		$.ajax({
			url: '/settings/save/loader',
		    data: new FormData($("form")[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    success: function(data) {
		    	data = CheckData(data);
		    	if(data) {
		    		SuccessAlert("Настройки сохранены");
		    		update_page_data();
		    	}
		    },
		    error: function(jqXHR, textStatus, errorThrown) {
				swal.fire({
					icon: "error",
					title: "Ошибка",
					text: "Произошла ошибка при попытке соединения с сервером (" + textStatus + "). Попробуйте перезагрузить страницу",
					customClass: {
						popup: 'swal-popup-class',
						title: 'swal-title-class',
					}
				});
			},
		});
	});

	$("input[type='file']").change(function() {
		$("#use_loader").prop('checked', true);
	});
</script>