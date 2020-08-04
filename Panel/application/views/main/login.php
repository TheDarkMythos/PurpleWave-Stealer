<div class="multi-step d-flex align-items-center">
    <div class="row w-100">

        <div class="col-12 col-lg-4 offset-lg-4 login-form">
            <div class="card">
                <div class="card-body">
                    <div class="navbar-brand">PurpleWave</div>
                    <p class="card-description mb-3">Авторизация в панели управления стилером</p>
                    <div class="form-group">
                        <label for="login">Логин от аккаунта</label>
                        <input type="text" class="form-control" placeholder="Логин" id="login">
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" placeholder="Пароль" id="password">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-lg btn-block btn-dark mb-2" id="sign_in">Вход</button>
                    </div>
                    <div id="alert">
                        <div class="alert alert-danger fade show in text-center" role="alert">
                            
                        </div>
                    </div>
                    <div class="text-center mt-5">
                        <a href="/check">Вы являетесь спамером?</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 offset-lg-4 fa-form">
            <div class="card">
                <div class="card-body">
                    <div class="navbar-brand">PurpleWave</div>
                    <p class="card-description mb-3">Последний шаг: введите код, отправленый вам телеграм-ботом</p>
        
                    <div class="form-group">
                        <label for="code">Код</label>
                        <input type="text" class="form-control" placeholder="2FA код" id="code">
                        <div class="invalid-feedback" id="codeFeedback"></div>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-lg btn-block btn-dark mb-2" id="fa_sign_in">Вход</button>
                    </div>
                </div>
            </div>
        </div>

	</div>
</div>

<script>
	$(document).ready(function() {
        $("#alert > .alert").css({"display": "none"});
	});

    $("#sign_in").click(function() {
        btn = $(this);
        btn.prop("disabled", true);
        $("#alert > .alert").css({"display": "none"});

        setTimeout(function() {
            data = sendData("/login", {"login": $("#login").val(), "password": $("#password").val()});
            if(data != false) {
                data = data[0];
                btn.removeAttr("disabled");
                if(data.success == true) {
                    location.href = "/";
                }
                else if(data.use_fa == true) {
                    fa_step();
                }
                else {
                    $("#alert > .alert").html(data.error_text);
                    $("#alert > .alert").css({"display": "block"});
                }
            }
        }, 1000);
    });

    $("#fa_sign_in").click(function() {
        btn = $(this);
        btn.prop("disabled", true);
        $("#code").removeClass("is-invalid");

        data = sendData("/login", {"login": $("#login").val(), "password": $("#password").val(), "fa_code": $("#code").val()});
        if(data != false) {
            data = data[0];
            btn.removeAttr("disabled");
            if(data.success == true) {
                location.href = "/";
            }
            else {
                $("#code").addClass("is-invalid");
                $("#codeFeedback").text(data.error_text);
            }
        }
    });

    function fa_step() {
        current_form = $(".login-form");
        next_form = $(".fa-form");

        next_form.show();

        current_form.animate(
        {
            opacity: 0
        }, 
        {
            step: function (now, mx) {
                scale = 1 - (1 - now) * 0.2;
                left = (now * 50) + "%";
                opacity = 1 - now;
                current_form.css({
                    'transform': 'scale(' + scale + ')',
                    'position': 'absolute'
                });
                next_form.css({
                    'left': left,
                    'opacity': opacity
                });
            },
            duration: 800,
            complete: function () {
                current_form.hide();
                animating = false;
                current_form.css({"position": "relative"});
            },
        });
    }

    $("#password").keypress(function(event) {
        if(event.which == 13)
            $("#sign_in").click();
    });
</script>