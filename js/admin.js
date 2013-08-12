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

	// add new program to new channel
	if ($('.programSelect-Template').length > 0) {
		$('.add-program').click(function(e){
			e.preventDefault();

			var programSelect = $('.programSelect-Template').clone();

			programSelect.removeClass('programSelect-Template').addClass('programSelect');
			programSelect.children().children('select').attr('name', 'channel[programs][]')

			$('.programSelect-Container').append(programSelect);
		});

		$('.channel-form-container').on("click", ".remove-program", function(e) {
			e.preventDefault();

			$(this).parent().parent().remove();
		});
	}

	// status ajax
	if ($('.status-switch').length > 0) {
		$('.status-switch').change(function(e) {

			var postData = {
				action : 'status',
				channel : $(this).data('channelid'),
				status : $('.status:checked').data('status')
			}

			var ajaxUrl = window.location.pathname;

			$.post(ajaxUrl,postData);
		});
	}

});