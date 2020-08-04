<div class="multi-step d-flex align-items-center">
    <div class="row w-100">


        <div class="col-12 col-lg-4 offset-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="navbar-brand">PurpleWave</div>
                    <p class="card-description mb-3">Панель спамера</p>
                    <div class="form-group">
                        <label for="login">Токен</label>
                        <input type="text" class="form-control" placeholder="Введите токен, предоставленый заказчиком" id="token">
                        <div class="invalid-feedback" id="tokenFeedback"></div>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-lg btn-block btn-dark mb-2" id="sign_in">Вход</button>
                    </div>
                    <div class="text-center mt-5">
                        <a href="/login">Вы являетесь пользователем?</a>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>


<script>
    $("#sign_in").click(function() {
        btn = $(this);
        btn.prop("disabled", true);
        $("#token").removeClass("is-invalid");
        token = $("#token").val();

        setTimeout(function() {
            data = sendData("/check", {"token": token});
            if(data != false) {
                data = data[0];
                btn.removeAttr("disabled");

                if(data.success == true) {
                    location.href = "/statistic/" + token;
                }
                else {
                    $("#token").addClass("is-invalid");
                    $("#tokenFeedback").text(data.error_text);
                }
            }
        }, 1000);
    });
</script>