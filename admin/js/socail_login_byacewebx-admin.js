(function( $ ) {
	'use strict';
	$(document).ready(function () {
			function openTab(evt, tabName) {
				$(".tab-content").hide();
				$(".tab").removeClass("active");
				$("#" + tabName).show();
				$(evt.currentTarget).addClass("active");
			}
			$(".tab").on("click", function (event) {
				var tabName = $(this).attr("id").replace("_tab", "");
				openTab(event, tabName);
			});
			$("#Google_tab").trigger("click");
		});
	// copy button jquery to copy the short cod on click button 
	
		jQuery(document).ready(function($) {
			$('.socical-login-shortcode-copy-btn').on('click', function(event) {
				event.preventDefault(); // Prevent default form submission
				var shortcode = $(this).data('shortcode');
				copyToClipboard(shortcode, $(this));
			});
		
			function copyToClipboard(text, button) {
				var textarea = document.createElement('textarea');
				textarea.value = text;
				textarea.style.position = 'fixed';
				textarea.style.opacity = 0;
				document.body.appendChild(textarea);
				textarea.focus();
				textarea.select();
				document.execCommand('copy');
				document.body.removeChild(textarea);
		
				// Change button text and style after copy
				button.text('Copied');
				button.addClass('copied');
		
				// Reset button after 2 seconds
				setTimeout(function() {
					button.text('Copy');
					button.removeClass('copied');
				}, 2000);
			}
		});
	
})( jQuery );
