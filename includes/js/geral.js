jQuery.noConflict();
jQuery(document).ready(function ($) {



	// ==========================================================================
	//   Sort By
	// ==========================================================================
	function DropDown(el) {
		this.dd = el;
		this.initEvents();
	}
	DropDown.prototype = {
		initEvents : function() {
			var obj = this;

			obj.dd.on('click', function(event){
				jQuery(this).toggleClass('active');
				event.stopPropagation();
			});	
		}
	}

	jQuery(function() {

		var dd = new DropDown( $('#dd') );

		jQuery(document).click(function() {
			// all dropdowns
			jQuery('.menu-filter').removeClass('active');
		});

	});


	// ==========================================================================
	//   Tooltip active
	// ==========================================================================
	jQuery('.tooltip').tooltipster({
		theme: 'onixxx-tooltip',
		contentAsHTML: true,
		position: 'top-left',
	});


	// ==========================================================================
	//   Menu
	// ==========================================================================
	jQuery('ul.sf-menu').superfish({
		delay:       1000,
		animation:   {opacity:'show',height:'show'},
		speed:       'fast',
		autoArrows:  true
	});

});
