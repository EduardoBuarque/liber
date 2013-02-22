<?php
/**
 * Helper for AJAX operations.
 *
 * Helps doing AJAX using the jQuery library.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2009, Damian Jóźwiak
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 */

/**
 * AjaxHelper helper library.
 *
 * Helps doing AJAX using the Prototype library.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.view.helpers
 */
class AjaxHelper extends AppHelper {
/**
 * Included helpers.
 *
 * @var array
 */
	var $helpers = array('Html', 'Form', 'Javascript');

/**
 * Names of Javascript callback functions.
 *
 * @var array
 */
	var $callbacks = array(
		'beforeSend', 'complete', 'error', 'success', 'dataFilter'
	);
/**
 * Names of AJAX options.
 *
 * @var array
 */
	var $ajaxOptions = array(
		'after', 'async', 'before', 'confirm', 'condition', 'contentType',
		'form', 'beforeSend', 'complete', 'error',
		'success', 'parameters',
		'cache', 'data', 'dataFilter', 'dataType', 'global', 'ifModified', 
		'jsonp', 'password', 'processData', 'scriptCharset', 'timeout', 'type',
		'url', 'username', 'xhr', 'with'
	);

/**
 * Output buffer for Ajax update content
 *
 * @var array
 */
	var $__ajaxBuffer = array();
/**
 * Returns link to remote action
 *
 * Returns a link to a remote action defined by <i>options[url]</i>
 * (using the url() format) that's called in the background using
 * XMLHttpRequest. The result of that request can then be inserted into a
 * DOM object whose id can be specified with <i>options[update]</i>.
 *
 * Examples:
 * <code>
 *  link("Delete this post",
 * array("update" => "posts", "url" => "delete/{$postid->id}"));
 *  link(imageTag("refresh"),
 *		array("update" => "emails", "url" => "list_emails" ));
 * </code>
 *
 * By default, these remote requests are processed asynchronous during
 * which various callbacks can be triggered (for progress indicators and
 * the likes).
 *
 * Example:
 * <code>
 *	link (word,
 *		array("url" => "undo", "n" => word_counter),
 *		array("complete" => "undoRequestCompleted(request)"));
 * </code>
 *
 * The callbacks that may be specified are:
 *
 * - <i>loading</i>::		Called when the remote document is being
 *							loaded with data by the browser.
 * - <i>loaded</i>::		Called when the browser has finished loading
 *							the remote document.
 * - <i>interactive</i>::	Called when the user can interact with the
 *							remote document, even though it has not
 *							finished loading.
 * - <i>complete</i>:: Called when the XMLHttpRequest is complete.
 *
 * If you for some reason or another need synchronous processing (that'll
 * block the browser while the request is happening), you can specify
 * <i>options[type] = synchronous</i>.
 *
 * You can customize further browser side call logic by passing
 * in Javascript code snippets via some optional parameters. In
 * their order of use these are:
 *
 * - <i>confirm</i>:: Adds confirmation dialog.
 * -<i>condition</i>::	Perform remote request conditionally
 *                      by this expression. Use this to
 *                      describe browser-side conditions when
 *                      request should not be initiated.
 * - <i>before</i>::		Called before request is initiated.
 * - <i>after</i>::		Called immediately after request was
 *						initiated and before <i>loading</i>.
 *
 * @param string $title Title of link
 * @param string $href Href string "/products/view/12"
 * @param array $options		Options for JavaScript function
 * @param string $confirm		Confirmation message. Calls up a JavaScript confirm() message.
 * @param boolean $escapeTitle  Escaping the title string to HTML entities
 *
 * @return string				HTML code for link to remote action
 */
	function link($title, $href = null, $options = array(), $confirm = null, $escapeTitle = true) {
		if (!isset($href)) {
			$href = $title;
		}
		if (!isset($options['url'])) {
			$options['url'] = $href;
		}

		if (isset($confirm)) {
			$options['confirm'] = $confirm;
			unset($confirm);
		}
		$htmlOptions = $this->__getHtmlOptions($options, array('url'));

		$htmlDefaults = array('id' => 'link' . intval(mt_rand()), 'onclick' => '');
		$htmlOptions = array_merge($htmlDefaults, $htmlOptions);

		$htmlOptions['onclick'] .= ' return false;';
		$return = $this->Html->link($title, $href, $htmlOptions, null, $escapeTitle);
		$callback = $this->remoteFunction($options);
		$script = $this->Javascript->event("'#{$htmlOptions['id']}'", "click", $callback);

		if (is_string($script)) {
			$return .= $script;
		}
		return $return;
	}
/**
 * Creates JavaScript function for remote AJAX call
 *
 * This function creates the javascript needed to make a remote call
 * it is primarily used as a helper for AjaxHelper::link.
 *
 * @param array $options options for javascript
 * @return string html code for link to remote action
 * @see AjaxHelper::link() for docs on options parameter.
 */
	function remoteFunction($options) {
		if (isset($options['update'])) {
		    if (isset($options['position'])){
		        $position = $options['position'];
		        unset($options['position']);
		    }
		    else{
		        $position = 'html';
		    }
			if (!is_array($options['update'])) {
			    $func = "$.ajax("; 
			    if (!isset($options['complete'])){
			        $options['complete'] = '';
		        }
		        $options['complete'] = "$('#" . $options['update'] . "').$position(request.responseText); " . $options['complete'];
			} else {
				$func = "$.ajax(";
				if (!isset($options['complete'])){
			        $options['complete'] = '';
		        }
		        $selectors = '';
		        foreach($options['update'] as $selector){
		            $selectors .= '#' . $selector . ', ';
		        }
		        $options['complete'] = "$('" . $selectors . "').$position(request.responseText); " . $options['complete'];
			    }
			if (is_array($options['update'])) {
				$options['update'] = join(' ', $options['update']);
			}
		} else {
			$func = "$.ajax(";
		}
        $options['url'] = $this->url(isset($options['url']) ? $options['url'] : "");
		$func .= $this->__optionsForAjax($options) . ")";

		if (isset($options['before'])) {
			$func = "{$options['before']}; $func";
		}
		if (isset($options['after'])) {
			$func = "$func; {$options['after']};";
		}
		if (isset($options['condition'])) {
			$func = "if ({$options['condition']}) { $func; }";
		}

		if (isset($options['confirm'])) {
			$func = "if (confirm('" . $this->Javascript->escapeString($options['confirm'])
				. "')) { $func; } else { return false; }";
		}
		return $func;
	}

/**
 * Returns form tag that will submit using Ajax.
 *
 * Returns a form tag that will submit using XMLHttpRequest in the background instead of the regular
 * reloading POST arrangement. Even though it's using Javascript to serialize the form elements,
 * the form submission will work just like a regular submission as viewed by the receiving side
 * (all elements available in params).  The options for defining callbacks is the same
 * as AjaxHelper::link().
 *
 * @param mixed $params Either a string identifying the form target, or an array of method parameters, including:
 *  - 'params' => Acts as the form target
 *  - 'type' => 'post' or 'get'
 *  - 'options' => An array containing all HTML and script options used to
 *  generate the form tag and Ajax request.
 * @param array $type How form data is posted: 'get' or 'post'
 * @param array $options Callback/HTML options
 * @return string JavaScript/HTML code
 * @see AjaxHelper::link()
 */
	function form($params = null, $type = 'post', $options = array()) {
		$model = false;
		if (is_array($params)) {
			extract($params, EXTR_OVERWRITE);
		}

		if (empty($options['url'])) {
			$options['url'] = array('action' => $params);
		}

		$htmlDefaults = array(
			'id' => 'form' . intval(mt_rand()),
			'onsubmit'	=> "return false;",
			'type' => $type,
			'url' => $options['url']
		);
		
		$htmlOptions = $this->__getHtmlOptions($options, array('model', 'with'));
		$htmlOptions = array_merge($htmlDefaults, $htmlOptions);

		$defaults = array('model' => $model, 'with' => "$('#{$htmlOptions['id']}').serialize()", 'type' => $type);
		$options = array_merge($defaults, $options);
		$callback = $this->remoteFunction($options);

		$form = $this->Form->create($options['model'], $htmlOptions);
		
		$script = $this->Javascript->event("'#" . $htmlOptions['id']. "'", 'submit', $callback);
		return $form . $script;
	}
/**
 * Returns a button input tag that will submit using Ajax
 *
 * Returns a button input tag that will submit form using XMLHttpRequest in the background instead
 * of regular reloading POST arrangement. <i>options</i> argument is the same as
 * in AjaxHelper::form().
 *
 * @param string $title Input button title
 * @param array $options Callback options
 * @return string Ajaxed input button
 * @see AjaxHelper::form()
 */
	function submit($title = 'Submit', $options = array()) {
		$htmlOptions = $this->__getHtmlOptions($options);
		$htmlOptions['value'] = $title;

		if (!isset($options['with'])) {
			$options['with'] = "$(this).parents('form:first').serialize()";
		}
		if (!isset($htmlOptions['id'])) {
			$htmlOptions['id'] = 'submit' . intval(mt_rand());
		}

		$htmlOptions['onclick'] = "return false;";
		$callback = $this->remoteFunction($options);

		$form = $this->Form->submit($title, $htmlOptions);
		$script = $this->Javascript->event('"#' . $htmlOptions['id'] . '"', 'click', $callback);
		return $form . $script;
	}

/**
 * Detects Ajax requests
 *
 * @return boolean True if the current request is a Prototype Ajax update call
 */
	function isAjax() {
		return (isset($this->params['isAjax']) && $this->params['isAjax'] === true);
	}
/**
 * Private helper function for Javascript.
 *
 * @param array $options Set of options
 * @access private
 */
	function __optionsForAjax($options) {
	    if (isset($options['indicator'])) {
			if (isset($options['beforeSend'])) {
				$loading = $options['beforeSend'];

				if (!empty($loading) && substr(trim($loading), -1, 1) != ';') {
					$options['beforeSend'] .= '; ';
				}
				$options['beforeSend'] .= "$('#{$options['indicator']}').show()";
			} else {
				$options['beforeSend'] = "$('#{$options['indicator']}').show();";
			}
			if (isset($options['complete'])) {
				$complete = $options['complete'];

				if (!empty($complete) && substr(trim($complete), -1, 1) != ';') {
					$options['complete'] .= '; ';
				}
				$options['complete'] .= "$('#{$options['indicator']}').hide()";
			} else {
				$options['complete'] = "$('#{$options['indicator']}').hide()";
			}
			unset($options['indicator']);
		}

		$jsOptions = array_merge(
			array('async' => 'true', 'type' => '\'post\''),
			$this->_buildCallbacks($options)
		);
		
		$options = $this->_optionsToString($options, array(
			'contentType', 'dataType', 'jsonp', 'password', 'scriptCharset', 'type', 'url', 'username'
		));
		foreach ($options as $key => $value) {
			switch ($key) {
				case 'async':
					$jsOptions['async'] = ($value == 'synchronous') ? 'false' : 'true';
				break;
				case 'with':
					$jsOptions['data'] = $options['with'];
				break;
				case 'form':
					$jsOptions['data'] = '$(this).serialize()';
				break;
				default:
				if (!in_array($key, $this->callbacks) && !in_array($key, array('before', 'after', 'confirm', 'condition', 'interactive', 'update')))
				    $jsOptions[$key] = $value;
				break;
			}
		}
		return $this->_buildOptions($jsOptions, $this->ajaxOptions);
	}
/**
 * Private Method to return a string of html options
 * option data as a JavaScript options hash.
 *
 * @param array $options	Options in the shape of keys and values
 * @param array $extra	Array of legal keys in this options context
 * @return array Array of html options
 * @access private
 */
	function __getHtmlOptions($options, $extra = array()) {
		foreach (array_merge($this->ajaxOptions, $this->callbacks, $extra) as $key) {
			if (isset($options[$key])) {
				unset($options[$key]);
			}
		}
		return $options;
	}
/**
 * Returns a string of JavaScript with the given option data as a JavaScript options hash.
 *
 * @param array $options	Options in the shape of keys and values
 * @param array $acceptable	Array of legal keys in this options context
 * @return string	String of Javascript array definition
 */
	function _buildOptions($options, $acceptable) {
		if (is_array($options)) {
			$out = array();

			foreach ($options as $k => $v) {
				if (in_array($k, $acceptable)) {
					if ($v === true) {
						$v = 'true';
					} elseif ($v === false) {
						$v = 'false';
					}
					$out[] = "$k:$v";
				} elseif ($k === 'with' && in_array('parameters', $acceptable)) {
					$out[] = "parameters:${v}";
				}
			}

			$out = join(', ', $out);
			$out = '{' . $out . '}';
			return $out;
		} else {
			return false;
		}
	}
/**
 * Return Javascript text for callbacks.
 *
 * @param array $options Option array where a callback is specified
 * @return array Options with their callbacks properly set
 * @access protected
 */
	function _buildCallbacks($options) {
		$callbacks = array();

		foreach ($this->callbacks as $callback) {
			if (isset($options[$callback])) {
				$name = $callback;
				$code = $options[$callback];
				switch ($name) {
					case 'complete':
						$callbacks[$name] = "function(request, json) {" . $code . "}";
						break;
					case 'success':
						$callbacks[$name] = "function(request, xhr) {" . $code . "}";
						break;
					case 'error':
						$callbacks[$name] = "function(request, textStatus, exception) {" . $code . "}";
						break;
					case 'dataFilter':
						$callbacks[$name] = "function(data, type) {" . $code . "}";
						break;
					default:
						$callbacks[$name] = "function(request) {" . $code . "}";
						break;
				}
				if (isset($options['bind'])) {
					$bind = $options['bind'];

					$hasBinding = (
						(is_array($bind) && in_array($callback, $bind)) ||
						(is_string($bind) && strpos($bind, $callback) !== false)
					);

					if ($hasBinding) {
						$callbacks[$name] .= ".bind(this)";
					}
				}
			}
		}
		return $callbacks;
	}
	
	function _buildUICallbacks($options, $ui_calbacks = array()) {	
		$callbacks = array();
		foreach ($ui_calbacks as $callback) {
		    if (isset($options[$callback])) {
				$name = $callback;
				$code = $options[$callback];
		        $callbacks[$name] = "function(event, ui) {" . $code . "}";
	        }
	    }
		return $callbacks;
	}
	
/**
 * Returns a string of JavaScript with a string representation of given options array.
 *
 * @param array $options	Ajax options array
 * @param array $stringOpts	Options as strings in an array
 * @access private
 * @return array
 */
	function _optionsToString($options, $stringOpts = array()) {
		foreach ($stringOpts as $option) {
			$hasOption = (
				isset($options[$option]) && !empty($options[$option]) &&
				is_string($options[$option]) && $options[$option][0] != "'"
			);

			if ($hasOption) {
				if ($options[$option] === true || $options[$option] === 'true') {
					$options[$option] = 'true';
				} elseif ($options[$option] === false || $options[$option] === 'false') {
					$options[$option] = 'false';
				} else {
					$options[$option] = "'{$options[$option]}'";
				}
			}
		}
		return $options;
	}
/**
 * Executed after a view has rendered, used to include bufferred code
 * blocks.
 *
 * @access public
 */
 //TODO Moze jednak to kiedys naprawics
	function afterRender($viewFile) {
		if (env('HTTP_X_UPDATE') != null && !empty($this->__ajaxBuffer)) {
			@ob_end_clean();

			$data = array();
			$divs = explode(' ', env('HTTP_X_UPDATE'));
			$keys = array_keys($this->__ajaxBuffer);

			if (count($divs) == 1 && in_array($divs[0], $keys)) {
				echo $this->__ajaxBuffer[$divs[0]];
			} else {
				foreach ($this->__ajaxBuffer as $key => $val) {
					if (in_array($key, $divs)) {
						$data[] = $key . ':"' . rawurlencode($val) . '"';
					}
				}
				$out  = 'var __ajaxUpdater__ = {' . join(", \n", $data) . '};' . "\n";
				$out .= 'for (n in __ajaxUpdater__) { if (typeof __ajaxUpdater__[n] == "string" && jQuery(n)) jQuery(\'#n\').html(unescape(decodeURIComponent(__ajaxUpdater__[n])));}';
				echo $this->Javascript->codeBlock($out, false);
			}
			$scripts = $this->Javascript->getCache();

			if (!empty($scripts)) {
				echo $this->Javascript->codeBlock($scripts, false);
			}
			$this->_stop();
		}
	}
}

?>