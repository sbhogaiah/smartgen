/*
 * A collection of reusable functions
*/

var components = { };

/* PreLoader */
components.loader = {
	'on' : function ( selector ) {
		var $el = typeof selector == 'string' ? $( selector ) : selector;

		$el.append('<div class="preloader"><img src="' + base_url + 'img/loader.gif" alt="" /></div>');
		$el.addClass('parent-blur');

		setTimeout(function () {
			$('.preloader').fadeIn();
		}, 10);
	}, 

	'off' : function ( selector ) {
		var $el = typeof selector == 'string' ? $( selector ) : selector;

		$('.preloader').fadeOut(function () {
			$el.find('.preloader').remove();
			$el.removeClass('parent-blur');	
		});
		
	}
}

/* Nag */
components.nag = {
	'show' : function ( text, type ) {
		
		var cur = this;
		type = type ? type : '';

		// function for adding nag
		function addNag() {
			// add element
			$('body').append('<div class="nag"><p>' + text + '</p><button class="nag-close">&times;</button></div>');

			// check type
			switch( type ) {
				case 'error':
					$('.nag').addClass('nag-error');
					break;
				case 'warn':
					$('.nag').addClass('nag-warn');
					break;
				case 'success':
					$('.nag').addClass('nag-success');
					break;
			};

			// animate in
			setTimeout(function () {
				$('.nag').addClass('show');
			}, 10);
		}

		// hide existing nag
		if($('.nag')) {
			cur.hide();

			setTimeout(function () {
				addNag();
			}, 200);
		} else {
			addNag();
		}

		// auto off
		setTimeout(function() {
			cur.hide();
		}, 3000);

	},

	'hide' : function () {

		// animate out
		$('.nag').removeClass('show');
		
		// remove element
		setTimeout(function () {
			$('.nag:not(.show)').remove();
		}, 200);

	},

	'close' : function () {

		var cur = this;
		
		// close functionality
		$(document).on('click', '.nag-close', function () {
			console.log('cur');
			cur.hide();
		});

	}
}

/* tooltips */
components.tooltip = {
	'template' : '<div class="tt"><span class="tt-arrow"></span><div class="tt-contents"></div></div>',

	'show' : function (el) {
		
		var cur = components.tooltip;
		var content = $(el).attr('title');

		// remove title
		$(el).data('content', content)
				.removeAttr('title');

		// insert html
		$(el).after(cur.template);

		// insert content
		var tt = $(el).next('.tt');

		tt.find('.tt-contents').html(content);

		// position
		var placement = $(el).data('placement');
		var x = tt.position().left;
		var wid = tt.outerWidth();
		var tX, tY;

		// x axis
		tX = ($(el).position().left + $(el).outerWidth()/2) - wid/2;

		// y axis
		if(placement == 'top') {
			tY = $(el).position().top - tt.outerHeight() - 6;
		} else {
			tY = $(el).position().top + $(el).outerHeight() + 6;
		}

		// x axis
		// check placement
		if((tX + wid) > $(window).width()) {
			tX = $(el).position().left + $(el).outerWidth() - wid;
			tt.addClass('tt-right');
			tt.find('.tt-arrow').css({ right: $(el).outerWidth()/2, left: 'auto' });
		}

		if(tX < 0) {
			tX = $(el).position().left;
			tt.addClass('tt-left');
			tt.find('.tt-arrow').css({ left: $(el).outerWidth()/2, right: 'auto' });
		}

		tt.css({ top: tY, left: tX });

		// animate in
		setTimeout(function () {
			tt.addClass('show');
		}, 50);

	},

	'hide' : function (el) {

		var cur = components.tooltip;
		var $this = $(el);
		var tt = $(el).next('.tt');

		// animate out
		tt.removeClass('show');
			
		// remove tooltip
		setTimeout(function () {
			tt.remove();
			$this.attr('title', $this.data('content'));
		}, 50);

	},

	'init' : function ( el ) {

		var cur = this;

		// check if el is a string or an jquery element object
		var el = typeof el === 'string' ? $(el) : el; 
		
		// events
		el.on('mouseenter', function() { cur.show(this) });
		el.on('mouseleave', function() { cur.hide(this) });
			
	}
}

/* card */
components.card = {
	'loading' : function ( el ) {
		var el = typeof el === 'string' ? $(el) : el;
		el.addClass('loading');
	},

	'loaded' : function ( el ) {
		var el = typeof el === 'string' ? $(el) : el;

		el.removeClass('loading');	
	}
}

/* Help Block */
components.help = function ( el, type, content ) {
	var el = typeof el === 'string' ? $(el) : el;

	switch(type) {
		case 'success' : 
			el.removeClass('help-block__error help-block__warn')
				.addClass('help-block__success'); 
			break;
		case 'error' : 
			el.removeClass('help-block__success help-block__warn')
				.addClass('help-block__error'); 
			break;
		case 'warn' : 
			el.removeClass('help-block__error help-block__success')
				.addClass('help-block__warn'); 
			break;
		case 'info' : 
			el.removeClass('help-block__success help-block__error help-block__warn'); 
			break;
	}

	if (content) {
		el.html('<i class="fa fa-info-circle"></i> ' + content);
	}
}

/* form */
components.form = {
	'reset' : function( el ) {
		var el = typeof el === 'string' ? $(el) : el;
		el[0].reset();
	}
}

/* Admin Sidebar */
components.sidebar = {
	'init' : function () {
		$('.admin__sidebar-btn').off('click');
		$('.admin__sidebar-btn').on('click', function () {
			$('body').toggleClass('admin__sidebar-collapse');
		});
	}
}

/* Modal */
// modal
components.modal = {

	'init': function () {
		this.openEvent('[data-modal-open]');
		this.closeEvent('[data-modal-close]');
		this.overlayEvent();
	},

	'openEvent': function (el) {
		$this = this;
		$(el).on('click', function(e) {
			e.preventDefault();
			var id = $(this).data('modal-open');
			$this.openModal(id);
			
			//activate close btn
			$this.closeEvent($(id).find('[data-modal-close]'));
			$this.overlayEvent();

			if($(id).find('form').length !== 0) {
				$(id).find('form').get()[0].reset();
			}
		}); 
	},

	'closeEvent': function (el) {
		$this = this;
		$(el).on('click', function(e) {
			e.preventDefault();
			var id = $(this).data('modal-close');
			$this.closeModal(id);
		});
	},

	'overlayEvent': function () {
		$('.modal').on('click', function (e) {
			if(e.target.className == "modal") {
				$(this).fadeOut(300);
			}
		});
	},
 
	'openModal': function (el, callback) {
		$this = this;
		$(el).fadeIn(300, callback);
		
		$this.confirm(el);
		//activate close btn
		$this.closeEvent($(el).find('[data-modal-close]'));
		$this.overlayEvent();
	},

	'closeModal': function (el, callback) {
		$(el).fadeOut(300, callback);
	},

	'confirm': function (el) {
		$this = this;
		var confirmButtons = $(el).find('[data-modal-confirm]');

		if(confirmButtons.is('.button')) {

			confirmButtons.click(function() {
				var check = $(this).data('modal-confirm');
				if(check) {
					return true;
				} else {
					$this.closeModal(el);
				}
			});
			
		}
	}
};

/* numbers */
components.number = {
	'leftPad': function (number) {  
	    return ((number < 10 && number >= 0) ? '0' : '') + number;
	},

	'rightPad': function (number) {  
	    return number + ((number < 10 && number >= 0) ? '0' : '');
	}
};

/* time */
components.time = {
	'toHHmm' : function (mins) {
		mins = parseInt(mins);
		var hours = Math.floor( mins / 60);          
	    var minutes = components.number.rightPad(mins % 60);

	    return hours+':'+minutes;
	}
};

/* Globally initialize the settings */
components.init = function () {
	var cur = this;

	cur.nag.close();

	cur.tooltip.init('[data-toggle="tooltip"]');

};

/* init */
$(function() {
	components.init();
});