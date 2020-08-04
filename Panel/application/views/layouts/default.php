<html>
	<head>
		<meta charset="UTF-8">
		<link rel="shortcut icon" href="/public/img/favicon.ico" type="image/x-icon" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?=$title?></title>

		<link rel="stylesheet" href="/public/css/libs/bootstrap-datepicker.min.css" type="text/css">
		<link rel="stylesheet" href="/public/css/libs/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="/public/css/libs/all.min.css" type="text/css">
		<link rel="stylesheet" href="/public/css/libs/dataTables.bootstrap4.min.css" type="text/css">
		<link rel="stylesheet" href="/public/css/libs/responsive.bootstrap4.min.css" type="text/css">
		<link rel="stylesheet" href="/public/css/libs/toastr.min.css" type="text/css">
		<link rel="stylesheet" href="/public/css/libs/magnific-popup.css" type="text/css">

		
		<link rel="stylesheet" href="/public/css/form_controls.css?<?=time()?>" type="text/css">
		<link rel="stylesheet" href="/public/css/sweet_alerts.css?<?=time()?>" type="text/css">
		<link rel="stylesheet" href="/public/css/world.css?<?=time()?>" type="text/css">

		<link rel="stylesheet" href="/public/css/styles.css?<?=time()?>" type="text/css">
		<link rel="stylesheet" href="/public/css/media.css?<?=time()?>" type="text/css">
		
		<script type="text/javascript" src="/public/js/libs/jquery-3.3.1.min.js"></script>
		<script type="text/javascript" src="/public/js/libs/jquery.form.min.js"></script>

	</head>
	<body>



		<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

			<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
				<a href="/" class="navbar-brand brand-logo">
					PurpleWave
				</a>
				<a href="/" class="navbar-brand brand-logo-mini">
					<img src="/public/img/icon.png" alt="PW" class="img-fluid" style="width: 70%!important">
				</a>
			</div>
			<div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-lg-between">
				<button class="navbar-toggler align-self-left d-none d-lg-block" data-toggle="minimize">
                    <i class="fas fa-bars fa-1x"></i>
				</button>
				
				<div class="dropdown nav-profile-text nav-item">
					<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" id="profileDropdown">
						<p class="mb-1 font-weight-bold"><?=$this->user->login?></p>
					</a>

					<div class="dropdown-menu" aria-labelledby="profileDropdown">
						<a href="/settings/accounts" class="dropdown-item">
							<i class="fa fa-cogs mr-2"></i> 
	                 		Настройки 
						</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="/logout">
	                 		<i class="fa fa-sign-out mr-2"></i> 
	                 		Выйти 
	                 	</a>
					</div>
				</div>

				<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" data-toggle="offcanvas">
                    <i class="fas fa-bars fa-1x"></i>
				</button>
			</div>
		</nav>

		<div class="content-fluid page-body-wrapper">
			<nav id="sidebar" class="sidebar sidebar-offcanvas">
				<ul class="nav">
					<li class="nav-item">
						<a class="nav-link" href="/index">
							<span class="menu-title">Главная</span>
							<i class="fas fa-home menu-icon"></i>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/logs">
							<span class="menu-title">Логи</span>
							<i class="fas fa-database menu-icon"></i>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="collapse" href="#settings-dropdown">
							<span class="menu-title">Настройки</span>
							<i class="menu-arrow"></i>
							<i class="fas fa-cogs menu-icon"></i>
						</a>

						<div id="settings-dropdown" class="collapse">
							<ul class="nav flex-column sub-menu">
								<li class="nav-item">
									<a href="/settings/config" class="nav-link">Конфига</a>
								</li>
								<li class="nav-item">
									<a href="/settings/accounts" class="nav-link">Аккаунтов</a>
								</li>
								<li class="nav-item">
									<a href="/settings/telegram" class="nav-link">Телеграм-бота <div class="badge badge-danger">beta</div></a>
								</li>
								<li class="nav-item">
									<a href="/settings/domains" class="nav-link">Доменов</a>
								</li>
								<li class="nav-item">
									<a href="/settings/loader" class="nav-link">Лоадера</a>
								</li>
							</ul>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="#" data-toggle="modal" data-target="#netscape-modal">
							<span class="menu-title">Netscape В Json</span>
							<i class="fas fa-brackets-curly menu-icon"></i>
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/spamers">
							<span class="menu-title">Спамеры</span>
							<i class="fas fa-user-hard-hat menu-icon"></i>
						</a>
					</li>
				</ul>
			</nav>

			<div class="main-panel">
				<div class="content-wrapper">
					<div class="page-header">
						<h3 class="page-title">
							<span class="page-title-icon text-white mr-2">
								<i class="fas fa-home"></i>
							</span>
						</h3>
					</div>
					
					<div id="server-side"></div>
					<?=$content?>
				</div>
			</div>
		</div>



		<div class="modal fade" id="passwordsModal" tabindex="-1" role="dialog">
		    <div class="modal-dialog modal-xl" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <h5 class="modal-title">Пароли</h5>
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true" class="text-light">&times;</span>
		                </button>
		            </div>
		            <div class="modal-body">
		                <div class="table-responsive">
		                    <table class="table table-sm table-striped w-100 passwordsTable">
		                        <thead>
			                        <tr>
			                            <th>Сайт</th>
			                            <th>Логин</th>
			                            <th>Пароль</th>
			                            <th>Браузер</th>
			                            <th>Информация</th>
			                        </tr>
		                        </thead>
		                    </table>
		                </div>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
		            </div>
		        </div>
		    </div>
		</div>

		<div class="modal fade" id="informationModal" tabindex="-1" role="dialog">
		    <div class="modal-dialog modal-dialog-scrollable" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <h5 class="modal-title">Информация</h5>
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true" class="text-light">&times;</span>
		                </button>
		            </div>
		            <div class="modal-body">
		                <div class="table-responsive" id="log_info_table_parent">

		                </div>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
		            </div>
		        </div>
		    </div>
		</div>

		<div class="modal fade" id="netscape-modal" tabindex="-1" role="dialog">
		    <div class="modal-dialog" role="document">
		        <div class="modal-content">
		            <div class="modal-header">
		                <h5 class="modal-title">Netscape в json </h5>
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true" class="text-light">&times;</span>
		                </button>
		            </div>
		            <div class="modal-body">
		                <p>Здесь вы можете сконвертировать формат Cookies Netscape в формат Json</p>

		                <textarea name="" id="netscape_text" cols="30" rows="10" class="form-control" placeholder="Netscape данные..."></textarea>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
		                <button type="button" class="btn btn-purple" onclick="ConvertNetscape(this);">Сконвертировать</button>
		            </div>
		        </div>
		    </div>
		</div>






		<script type="text/javascript" src="/public/js/libs/bootstrap-datepicker.min.js"></script>
		<script type="text/javascript" src="/public/js/libs/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="/public/js/libs/Chart.min.js"></script>
		<script type="text/javascript" src="/public/js/libs/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="/public/js/libs/dataTables.bootstrap4.min.js"></script>
		<script type="text/javascript" src="/public/js/libs/dataTables.responsive.min.js"></script>
		<script type="text/javascript" src="/public/js/libs/responsive.bootstrap4.min.js"></script>
	    <script type="text/javascript" src="/public/js/libs/dataTables.buttons.min.js"></script>
	    <script type="text/javascript" src="/public/js/libs/sweetalert29.js"></script>
	    <script type="text/javascript" src="/public/js/libs/toastr.min.js"></script>
	    <script type="text/javascript" src="/public/js/libs/jquery.magnific-popup.js"></script>

		<script src="/public/js/app.js?<?=time()?>"></script>

		<script>
			var current_path;
			var body;

			jQuery.fn.justtext = function() {
				return $(this).clone().children().remove().end().text();
			};
			// element - .navlink
			function addActiveClass(element) {
				if (current_path === "") {
				//for root url
					if (element.attr('href').indexOf("index") !== -1) {
						element.parents('.nav-item').last().addClass('active');

						$(".page-title").append(element.children("span").justtext());

						if (element.parents('.sub-menu').length) {
							element.closest('.collapse').addClass('show');
							element.addClass('active');

							$(".page-title").append(element.justtext());
						}
					}
				} 
				else {
					//for other url
					if (element.attr('href').indexOf(current_path) !== -1) {
						element.parents('.nav-item').last().addClass('active');

						$(".page-title").append(element.children("span").justtext());

						if (element.parents('.sub-menu').length) {
							element.closest('.collapse').addClass('show');
							element.addClass('active');

							$(".page-title").append(element.parents('.nav-item').last().find(".menu-title").text() + ": " + element.justtext().toLowerCase());
						}
						if (element.parents('.submenu-item').length) {
							element.addClass('active');
						}
					}
				}
			}

			function long_pooling() {
				$.ajax({
	                async: true,
					url: "/sockets",
	                type: "POST",
	                cache: false,
	                data: null,
	                success: function(data) {	                	
						data = JSON.parse(data);

						if(data.length == 0) {
							return;
						}

						if(typeof update_page_data == 'function') 
							update_page_data();
	                }
				}).done(function(data, statusText, jqXHR) {
					setTimeout(long_pooling, 10000); 
				});
			}

			$(document).ready(function() {
				body = $('body');
				sidebar = $('#sidebar');
				
				long_pooling();


				$('[data-toggle="minimize"]').on("click", function() {
				    if ((body.hasClass('sidebar-toggle-display')) || (body.hasClass('sidebar-absolute'))) {
					    body.toggleClass('sidebar-hidden');
				    } else {
				        body.toggleClass('sidebar-icon-only');
				    }
			    });
				sidebar.on('show.bs.collapse', '.collapse', function() {
					sidebar.find('.collapse.show').collapse('hide');
				});

				temp = [];
				temp = location.pathname.split("/");
				temp.shift();
				
				current_path = temp.join('/').replace(/^\/|\/$/g, '');

				$('.nav li a', sidebar).each(function() {
					var $this = $(this);
					addActiveClass($this);
				});
				applyStyles();

				$(document).on('mouseenter mouseleave', '.sidebar .nav-item', function(ev) {
				var sidebarIconOnly = body.hasClass("sidebar-icon-only");
					var sidebarFixed = body.hasClass("sidebar-fixed");
					if (!('ontouchstart' in document.documentElement)) {
						if (sidebarIconOnly) {
							if (sidebarFixed) {
								if (ev.type === 'mouseenter') {
									body.removeClass('sidebar-icon-only');
								}
							}
							else {
								var $menuItem = $(this);
								if (ev.type === 'mouseenter') {
									$menuItem.addClass('hover-open')
								} else {
									$menuItem.removeClass('hover-open')
								}
							}
						}
					}
				});
			});

			function applyStyles() {
				//Applying perfect scrollbar
				if (!body.hasClass("rtl")) {
					if ($('.settings-panel .tab-content .tab-pane.scroll-wrapper').length) {
						const settingsPanelScroll = new PerfectScrollbar('.settings-panel .tab-content .tab-pane.scroll-wrapper');
					}
					if ($('.chats').length) {
						const chatsScroll = new PerfectScrollbar('.chats');
					}
					if (body.hasClass("sidebar-fixed")) {
						var fixedSidebarScroll = new PerfectScrollbar('#sidebar .nav');
					}
				}
			}

			$('[data-toggle="offcanvas"]').on("click", function() {
				$('.sidebar-offcanvas').toggleClass('active')
			});
		</script>
	</body>
</html>
