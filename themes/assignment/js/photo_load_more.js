jQuery(function ($) { // use jQuery code inside this to avoid "$ is not defined" error
	$('.photos_loadmore').click(function () {
		var button = $(this),
			data = {
				'action': 'loadmore',
				'query': photos_loadmore_params.posts, // that's how we get params from wp_localize_script() function
				'page': photos_loadmore_params.current_page,
				'max_page': photos_loadmore_params.max_page
			};

		$.ajax({ // you can also use $.post here
			url: photos_loadmore_params.ajaxurl, // AJAX handler
			data: data,
			type: 'POST',
			beforeSend: function (xhr) {
				button.text('Loading...'); // change the button text, you can also add a preloader image
			},
			success: function (data) {
				if (data) {
					button.html('<span class="load-more-lower">Show more</span>').prev().before(data); // insert new posts
					//	button.text('More posts');
					//$('.grid_photos').appendTo(data);
					//	button.text('More posts').prev().before(data); // insert new posts
					//$('.grid_photos').html(data);
					$('.grid_photos').append(data);
					photos_loadmore_params.current_page++;

					if (photos_loadmore_params.current_page == photos_loadmore_params.max_page) {
						button.remove(); // if last page, remove the button
						$('#js-loadMore-projects').html('NO MORE PHOTOS');
						// you can also fire the "post-load" event here if you use a plugin that requires it
					}
				} else {
					button.remove(); // if no data, remove the button as well
					$('#js-loadMore-projects').html('NO MORE PHOTOS');
				}
			}
		});
	});
});
