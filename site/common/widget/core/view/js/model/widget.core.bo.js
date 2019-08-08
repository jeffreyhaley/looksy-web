/**
 * Main Widget Object, instantiated directly after definition (trailing ()).
 *
 * Currently this object handles a few different jobs:
 *
 *  - Holds some static references to some common values
 *  - Provides a generic error-handling mechanism
 *  - Sets up the default AJAX options for further requests (included callbacks)
 */
define([], function() {

	/**
	 * Widget object.
	 *
	 * @return Widget
	 */
	function Widget() {
		this.options = {};
		this.$el = null;
	}

	/*
	 * Instance properties / methods.
	 */

	Widget.prototype = {

		/**
		 * The default text used by the textReplace() method.
		 *
		 * @type string
		 */
		defaultTextToReplace: 'Type something here...',

		/**
		 * The options for the widget as generated in PHP.
		 *
		 * @type object
		 */
		options: {},

		/**
		 * The selector for the widget as generated in PHP.
		 *
		 * @type string
		 */
		selector: '',

		/**
		 * The skin of the widget.
		 *
		 * @type string
		 */
		skin: '',

		/**
		 * The type of widget (Tab, PopUpMenu, etc.).
		 *
		 * @type string
		 */
		type: '',

		/**
		 * In practice, this is our constructor.
		 *
		 * This method won't be called automatically by creating an object;
		 * however, the widget code generated in PHP will call this method for
		 * you automatically.
		 *
		 * @return Widget
		 */
		initialize: function Widget_initialize() {
			return this;
		},

		/**
		 * This method is called whenever a tab is reloaded, so that any temporary
		 * data relating to widget state with respect to the tab can be reinitialized.
		 *
		 * Simply override in your specific Widget object to do whichever actions
		 * might be necessary.
		 *
		 * NOTE: For this method to be invoked, you must had a call to this.addTabReloadListener()
		 * in your initialize() method.
		 *
		 * @see TreeWidget for example usage.
		 * @return Widget
		 */
		tabReload: function Widget_tabReload() {
			return this;
		},

		/**
		 * See notes on Widget_tabReload for more info.
		 */
		addTabReloadListener: function Widget_addTabReloadListener() {
			var selector = '#' + this.getParentTab().attr('id');
			$(document.body).on('tab.reload', selector, this.proxyMethodCall(this, this.tabReload));
		},

		/**
		 * Using the DOM and starting with the location of our widget within the
		 * DOM, find the parent tab <div>.
		 *
		 * @return jQuery
		 */
		getParentTab: function Widget_getParentTab() {
			/*
			 * We need to get the tab that contains our widget (so that we aren't
			 * forced to hardcode it in as a string):
			 *
			 *   1. Start from our widget node
			 *   2. Traverse up until we hit .areatab
			 *   3. This is the tab we're on.
			 *
			 * Using .closest(), the .areatab that it finds will be the parent tab <div> of our widget!
			 */
			return $(this.getSelector()).closest('.areatab');
		},

		/**
		 * parses string and returns proper namespace if it exists in the DOM (default) or object provided
		 *
		 * this was originally developed for use with widgets that pass information in JSON format from PHP to JavaScript
		 *
		 * @example: this.parseForNamespaces('SimpleNamespace') = SimpleNamespace (if it exists)
		 * @example: this.parseForNamespaces('Complex.Namespace') = Complex.Namespace
		 * @example: this.parseForNamespaces('Complex.Namespace', ObjReally) = ObjReally.Complex.Namespace
		 *
		 * @param string nsName
		 * @param unknown_type obj
		 * @returns object
		 */
		parseForNamespaces: function Widget_parseForNamespaces(nsName, obj) {
			// default to window if nothing passed in
			obj = obj || window;

			// split namespace on ., reverse for use in loop
			var nodes = nsName.split('.').reverse();

			var node;

			// build the proper object from the namespace
			while (node = nodes.pop()) {
				if (node in obj) {
					obj = obj[node];
				} else {
					throw new Error('Namespace ' + nsName + ' not recognized');
				}
			}

			return obj;
		},

		/**
		 * Adds "hover in" and "hover out" to element.
		 *
		 * @param string sel
		 * @param string hoverInClass
		 * @param string hoverOutClass
		 */
		addHoverClasses: function Widget_addHoverClasses(sel, hoverInClass, hoverOutClass) {
			var jqElement = $(sel);

			function hoverIn() {
				jqElement.addClass(hoverInClass).removeClass(hoverOutClass);
			}

			function hoverOut() {
				jqElement.addClass(hoverOutClass).removeClass(hoverInClass);
			}
			return jqElement.hover(hoverIn, hoverOut);
		},

		/**
		 * Hint text swapper for hinting in text inputs.
		 *
		 * Wires up the behavior to a text input whereby the input will originally
		 * say something like "enter text here...", but when clicked, the text is
		 * removed (so that the user doesn't need to remove it). If the focus
		 * leaves the text area and the user didn't type anything in, then it
		 * repopulates it with the "enter text here..." text again, ready for action
		 * again.
		 *
		 * @param string sel
		 * @param string textToReplace
		 * @return jQuery
		 */
		textReplace: function Widget_textReplace(sel, textToReplace) {

			textToReplace = textToReplace || this.defaultTextToReplace;

			var jqElement = $(sel);

			// insert initial text
			if (jqElement.val() === '') {
				jqElement.val(textToReplace);
			}

			// attach focus event: blank out textarea if equal to default text
			jqElement.focus(function() {
				if (jqElement.val() === textToReplace) {
					jqElement.val('');
				}
			});

			// attach blur event: add default text if empty
			jqElement.blur(function() {
				if (jqElement.val() === '') {
					jqElement.val(textToReplace);
				}
			});

			return jqElement;
		},

		/**
		 * Binds click handler with optional parameters to given sel.
		 *
		 * @param string sel
		 * @param object options
		 * @return jQuery
		 */
		addClickHandler: function Widget_addClickHandler() {
			// we are going to pass either the arguments given or an empty construct
			var args = this.getOption('js-args') || {};

			// bind to button with arguments; the handler will unpack the arguments on the other side via args.data.argName1
			var jqEl = $(this.getSelector());
			var method = this.parseForNamespaces(this.getOption('js-handler'));
			jqEl.on('click', args, this.proxyMethodCall(this, method));

			return jqEl;
		},

		/**
		 * Binds change handler with optional parameters to given sel.
		 *
		 * @param string sel
		 * @param object options
		 * @return jQuery
		 */
		addChangeHandler: function Widget_addChangeHandler() {
			// we are going to pass either the arguments given or an empty construct
			var args = this.getOption('js-args') || {};

			// bind to button with arguments; the handler will unpack the arguments on the other side via args.data.argName1
			var jqEl = $(this.getSelector());
			var method = this.parseForNamespaces(this.getOption('js-handler'));
			jqEl.on('change', args, this.proxyMethodCall(this, method));

			return jqEl;
		},


		/**
		 * Given a `method` and a `context`, generate a function that will call
		 * the method within the context provided.
		 *
		 * Why? Because we want to outsource a method to a different object, e.g.
		 * we want to call a method from a foreign object but use the 'this' value
		 * of our current object (or of any other object).
		 *
		 * This method is really just some syntactic sugar for the native apply()
		 * function.
		 *
		 * @param object context
		 * @param function method
		 * @return function
		 */
		proxyMethodCall: function Widget_proxyMethodCall(context, method) {
			return function _Widget_proxyMethodCall() {
				return method.apply(context, arguments);
			};
		},

		/*
		 * Getters / Setters
		 */

		getSkin: function Widget_getSkin() {
			return this.skin;
		},
		setSkin: function Widget_setSkin(skin) {
			this.skin = skin;
			return this;
		},

		getSelector: function Widget_getSelector() {
			return this.selector;
		},
		setSelector: function Widget_setSelector(selector) {
			this.selector = selector;
			this.setElement($(selector));
			return this;
		},

		getOption: function Widget_getOption(option) {
			return this.options[option];
		},
		setOption: function Widget_setOption(option, value) {
			this.options[option] = value;
			return this;
		},

		getOptions: function Widget_getOptions() {
			return this.options;
		},
		setOptions: function Widget_setOptions(options) {
			this.options = options;
			return this;
		},

		getType: function Widget_getType() {
			return this.type;
		},
		setType: function Widget_setType(type) {
			this.type = type;
			return this;
		},

		getElement: function Widget_getElement() {
			return this.$el;
		},
		setElement: function Widget_setElement($element) {
			this.$el = $element;
			this.$el.data('instance', this);
			return this;
		}
	};

	/*
	 * Static properties / methods / pseudo-constants / pseudo-enums
	 */

	Widget.SKIN = {
		REVOLUTION: 'revolution',
		UNIFIED: 'unified'
	};

	/**
	 * Provides a basic mechanism of extending objects, similar to a mixin.
	 *
	 * The `parent` argument is typically going to be an object's prototype, while
	 * the `child` argument should be an object-literal containing properties &
	 * methods which should be mixed into the `parent` object.
	 *
	 * Currently it's nothing but a proxy for jQuery's extend(), but consolidating
	 * here will let us easily alter it in the future if we need to.
	 *
	 * @see jQuery.extend
	 * @param object parent
	 * @param object child
	 * @return mixed
	 */
	Widget.extend = function Widget_extend(parent, child) {
		return $.extend(parent, child);
	};

	return Widget;
});