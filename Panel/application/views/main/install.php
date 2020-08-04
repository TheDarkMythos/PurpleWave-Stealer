<div class="multi-step d-flex align-items-center">
	<div class="row w-100">
		<div class="col-md-5 mx-auto py-5">
            <div class="navbar-brand text-center">PurpleWave</div>
			<h4 class="text-center">Установка панели на сайт</h4>

			<form class="step-form">
				<ul id="progressbar" class="step-progress">
					<li class="active">Настройка базы</li>
					<li>Настройка пользователей</li>
				</ul>

				<fieldset>
					<div class="form-group">
						<label for="hostname">Хост базы данных</label>
						<input class="form-control" type="text" name="hostname" id="hostname" placeholder="Сервер (хост) базы данных">
					</div>
					<div class="form-group">
						<label for="db_name">Имя базы данных</label>
						<input class="form-control" type="text" name="db_name" placeholder="Название базы данных">
					</div>
					<div class="form-group">
                        <label for="db_login">Логин подключения к БД</label>
                        <input type="text" class="form-control" name="db_login" id="db_login" placeholder="Логин от базы" />
                    </div>
                    <div class="form-group">
                        <label for="db_pass">Пароль подключения к БД</label>
                        <input type="password"class="form-control" name="db_pass" id="db_pass" placeholder="Пароль от базы" />
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="#quest_modal_install" data-toggle="modal">Почему я вижу это окно?</a>
                        <button class="btn btn-purple next action-button float-right" type="button">Далее</button>
                    </div>
				</fieldset>

				<fieldset>
					<div class="form-group">
                        <label for="db_login">Логин администратора</label>
                        <input type="text" class="form-control" name="user_login" id="user_login" placeholder="Логин админа" />
                    </div>
                    <div class="form-group">
                        <label for="db_pass">Пароль администратора</label>
                        <input type="password" class="form-control" name="user_pass" id="user_pass" placeholder="Пароль админа" />
                    </div>

                    <div class="float-right">
						<button class="btn btn-secondary action-button previous mr-2" type="button">Назад</button>
						<button class="btn btn-purple action-button submit" type="button">Сохранить</button>
                    </div>
				</fieldset>
			</form>
		</div>
	</div>
</div>
	
<div class="modal fade" id="quest_modal_install" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Установка панели на сайт</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Данное окно показывается пользователю в случае невозможности подключения к базе данных. Обычно пользователь встречает его единажды - при первом запуске. Также оно может быть показано при изменении параметров работы с базой. Укажите в соответствующих графах данные для подключения к БД, а на следующей странице - логин и пароль администратора сайта (если аккаунт уже существует, то повторите данные пользователя - он будет обновлен)</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Ок</button>
            </div>
        </div>
    </div>
</div>	



<script>
	var current_fs, next_fs, previous_fs; //fieldsets
  	var left, opacity, scale; //fieldset properties which we will animate
  	var animating; //flag to prevent quick multi-click glitches

	$(".next").click(function () {
  		if (animating) return false;
  		animating = true;

  		current_fs = $(this).parents('fieldset').first();
  		next_fs = $(this).parents('fieldset').first().next();

  		//activate next step on progressbar using the index of next_fs
  		$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

  		//show the next fieldset
  		next_fs.show();
  		//hide the current fieldset with style
  		current_fs.animate(
  		{
  			opacity: 0
  		}, 
  		{
  			step: function (now, mx) {
  				//as the opacity of current_fs reduces to 0 - stored in "now"
  				//1. scale current_fs down to 80%
  				scale = 1 - (1 - now) * 0.2;
  				//2. bring next_fs from the right(50%)
  				left = (now * 50) + "%";
  				//3. increase opacity of next_fs to 1 as it moves in
  				opacity = 1 - now;
  				current_fs.css({
  					'transform': 'scale(' + scale + ')',
  					'position': 'absolute'
  				});
  				next_fs.css({
  					'left': left,
  					'opacity': opacity
  				});
  			},
  			duration: 800,
  			complete: function () {
  				current_fs.hide();
  				animating = false;
		  		current_fs.css({"position": "relative"});
  			},
  		});
  	});

  	$(".previous").click(function () {
  		if (animating) return false;
  		animating = true;

  		current_fs = $(this).parents("fieldset");
  		previous_fs = $(this).parents("fieldset").prev();

  		//de-activate current step on progressbar
  		$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

  		//show the previous fieldset
  		previous_fs.show();
  		//hide the current fieldset with style
  		current_fs.animate({
  			opacity: 0
  		}, {
  			step: function (now, mx) {
  				//as the opacity of current_fs reduces to 0 - stored in "now"
  				//1. scale previous_fs from 80% to 100%
  				scale = 0.8 + (1 - now) * 0.2;
  				//2. take current_fs to the right(50%) - from 0%
  				left = ((1 - now) * 50) + "%";
  				//3. increase opacity of previous_fs to 1 as it moves in
  				opacity = 1 - now;
  				current_fs.css({
  					'left': left
  				});
  				previous_fs.css({
  					'transform': 'scale(' + scale + ')',
  					'opacity': opacity
  				});
  			},
  			duration: 800,
  			complete: function () {
  				current_fs.hide();
  				animating = false;
  			},
  		});
  	});

  	$(".submit").click(function(){
        btn = $(this);
  		btn.prop("disabled", true);
  		data = CheckData(sendData("/install", $("form").serialize())); 
        if(data) {
			window.location.href = "/";
		}
        btn.removeAttr('disabled');
  	});
</script>