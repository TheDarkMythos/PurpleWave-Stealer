<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Настройка бота</h4>

                <div class="form-group">
                    <label for="token">Токен бота</label>
                    <input type="text" class="form-control" placeholder="Введите токен бота от BotFather'а" id="token">
                </div>

                <div class="form-group">
                    <label for="username">Ваш Username</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-dark">@</span>
                        </div>
                        <input type="text" class="form-control" placeholder="Введите ваше имя в телеграм для того, чтобы бот узнал вас" id="username">
                    </div>
                </div>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="use_fa" id="use_fa" class="custom-control-input table-checkbox">
                    <label class="custom-control-label table-checkbox-label" for="use_fa">Использовать 2FA авторизацию</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="use_fa" id="send_log" class="custom-control-input table-checkbox">
                    <label class="custom-control-label table-checkbox-label" for="send_log">Отправлять мне новые логи</label>
                </div>

                <button class="btn btn-purple float-right" id="save">Сохранить</button>
            </div>
        </div>
    </div>
</div>


<script>
    function update_page_data() {
        data = sendData("/settings/info/telegram");
        if(data) {
            $("#token").val(data.token);
            $("#username").val(data.username);
            $("#use_fa").prop('checked', data.fa=="1"?true:false);
            $("#send_log").prop('checked', data.send=="1"?true:false)
        }
    }
    
    $(document).ready(function() {
        update_page_data();
    });

    $("#save").click(function() {
        data = sendData("/settings/save/telegram", {"token": $("#token").val(), "username": $("#username").val(), "fa": $("#use_fa").prop('checked'), "send": $("#send_log").prop('checked')});
        if(data) {
            data = CheckData(data);
            if(data != false) {
                SuccessAlert("Настройки сохранены");
            }
        }
    });
</script>