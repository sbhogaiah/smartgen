$(function () {

	var body, loginBox, loginBtns, loginCloseBtn, roleLabel, loginForm;

	body = $('body');
	loginBox = $('.login-box');
	loginBtns = $('.login-box__buttons [data-login-role]');
	loginCloseBtn = $('.login-box__form-close');
	roleLabel = $('.login-box__form-role');
	roleInput = $('#loginRole');
	loginForm = $('#loginForm');

	/* loginBtns */
	loginBtns.not('[data-login-role="guest"]').on('click', function (e) {
		e.preventDefault();

		var $this = $(this);

		/* get role */
		var role = $this.data('login-role');

		if (role !== 'admin') {
			$('.home__top').css('background-image', 'url(' + base_url + 'img/photos/' + role + '.jpg)');
		}

		/* change role data */
		roleLabel.text(role);
		roleInput.val(role);

		/* open form */
		body.addClass('login-form-open');

		/* reset form */
		components.form.reset(loginForm);
		
	});

	/* loginCloseBtn */
	loginCloseBtn.on('click', function (e) {
		e.preventDefault();

		body.removeClass('login-form-open');

		$('.home__top').css('background-image', 'url(' + base_url + 'img/photos/bg.jpg)');
	});

	/* loginForm */
	loginForm.on('submit', function (e) {
		e.preventDefault();

		var $this = $(this);
		var url = $this.attr('action');
		var formData = $this.serialize();

		/* preloader */
		components.loader.on(loginBox);

		/* ajax call */
		$.ajax({
			url: url,
			data: formData,
			method: 'POST',
			type: 'JSON'
		}).done(function ( data ) {

			var d = JSON.parse(data);
			
			/* error */
			if(d.error)	{
				components.nag.show(d.error, 'error');
			} else {
				components.nag.show('Login successful! Please wait while we redirect...', 'success');
				window.location.href = d.url;
			}
			
		}).always(function () {
			/* preloader */
			components.loader.off(loginBox);
		}).fail(function ( data ) {
			components.nag.show('There has been an error! Please contact system administrator.', 'error');
		});
	});


	// guest login
	loginBtns.filter('[data-login-role="guest"]').on('click', function () {
		
		$.ajax({
			method: 'POST',
			url: base_url+'modules/login.php',
			data: 'role=guest',
			dataType: 'JSON'
		}).done(function (d) {
			window.location.href = d.url;
		}).fail(function ( data ) {
			components.nag.show('There has been an error! Please contact system administrator.', 'error');
		});

	});

});