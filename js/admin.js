$(document).ready( function() { 

	// close action for alert box
	$('.close').on('click', function () {
		$(this).parent().fadeOut('slow');
	});

	// section container
	if ($('.section-container').length > 0) {
		$(document).foundation('section');
	}

	// sortable list
	if ($('.list-sortable').length > 0 ) {
		$(".list-sortable").sortable({ 
			update: function() {
				var category = $(this).data('list');

				var order = $('#sortable-' + category).sortable('toArray');

				var postData = {
					type : category,
					sort : order
				};
				
				var ajaxUrl = window.location.pathname;

				$.post(ajaxUrl,postData);
			}
  		});

  		$("#sortable").disableSelection();
	}
	
});