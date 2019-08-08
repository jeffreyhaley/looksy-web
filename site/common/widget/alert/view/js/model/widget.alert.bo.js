define(['widget'], function(Widget) {

	function Widget_Alert() {}

	Widget_Alert.prototype = new Widget();

	Widget.extend(Widget_Alert.prototype, {
		message: '',
		status: '',
		$element: null,

		initialize: function Widget_Alert_initialize() {
			/*
			 * Classes are used to create custom Alert visual design.
			 * Example: Alerts with backgrounds such as the
			 * login/logout Alert.
			 *
			 * When defining a class to the Alert, the jquery ui Alert option
			 * is not applied. If you want to use the default
			 * browser Alert desgin, you can disable the jquery
			 * us Alert option by setting the jq option to false.
			 */
			this.$element = $(this.getSelector());

			if (this.getOption('message')) {
				this.message = this.getOption('message');
			}

			if (this.getOption('status')) {
				this.status = this.getOption('status');
			}
		},

		addMessage: function Widget_Alert_addMessage(data, id) {			

			// Check if the data is in JSON format.
			var content;
			if (data.status) {
				content = data;
			} else {
				content = JSON.parse(data);
			}

			/* 
			 * @todo: I'm torn here...this is not the right place for this and it needs to be moved out into ajax framework function
			 * We're not at a point yet were all content is being handled properly when returning back for display.
			 * For now we'll keep this content parsing and placement in place.  If it proves to be a problem before the framework request handling
			 * has been addressed we will move it.  Please do not add anymore logic for content placement here.
			 * 
			 * Added a check for content.html as not every alert message is going 
			 * to be the result of an AJAX call.
			 */
			if (content.html) {
				var $html = (id instanceof $) ? id : $(id);
				$html.html(content.html);
			}

			if (content.message && content.status) {
				// See if there are any duplicate messages.  Some messages can have HTML, so we need to cleanse to match on a stable id.
				var messageId = this.toId(content.message),
					isDupe = $("#" + messageId).size() > 0;

				if (!isDupe) {
					var notificationHtml = this.toHtml({
						id: messageId,
						message: content.message,
						status: content.status
					});
					this.$element.append(notificationHtml);
				}

				// Auto hide after 3 seconds
				var close = _.bind(this.closeFirstMessage, this);
				_.delay(close, 2000);
			}
		},

		/**
		 * Transform a string into a [mostly] DOM-compliant ID.
		 * 
		 * @param {String} str
		 * @return {String}
		 */
		toId: function Widget_Alert_toId(str) {
			return str.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
		},

		/**
		 * Build a chunk of HTML to represent a notification message.
		 * 
		 * @param {Object} data
		 * @return {String}
		 */
		toHtml: function Widget_Alert_toHtml(data) {
			var status = data.status;
			if (data.status === 'error') {
				status = 'danger';
			}
			
			var html = '<div class="alert alert-' + status + '">';
			html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
			html += '<span id="' + data.id + '">' + data.message + '</span>';
			html += '</div>';
			return html;
		},

		// Find the first success message and close it out.
		closeFirstMessage: function Widget_Alert_closeMessage() {
			this.$element.find('.alert-success').eq(0).fadeOut(1000, function() {
				$(this).alert('close');
			});
		}
	});

	return Widget_Alert;
});