$(document).ready( function() { 

	// close action for alert box
	$('.close').on('click', function () {
		$(this).parent().fadeOut('slow');
	});

	// sortable list
	if ($('#sortable').length > 0 ) {
		$("#sortable").sortable({ 
			update: function() {  
				var order = $('#sortable').sortable('toArray');

				var postData = {
					sort : order
				};
				
				var ajaxUrl = window.location.pathname;

				$.post(ajaxUrl,postData);
			}
  		});

  		$("#sortable").disableSelection();
	}
	
});