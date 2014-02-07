(function() {
    var $, DEFAULT_INDENT_WIDTH, DEFAULT_PROMPT_CONINUE_LABEL, DEFAULT_PROMPT_LABEL, JQConsole, KEY_BACKSPACE, KEY_DELETE, KEY_DOWN, KEY_END, KEY_ENTER, KEY_HOME, KEY_LEFT, KEY_PAGE_DOWN, KEY_PAGE_UP, KEY_RIGHT, KEY_TAB, KEY_UP, STATE_INPUT, STATE_OUTPUT, STATE_PROMPT;
    var __bind = function(fn, me){
        return function(){
            return fn.apply(me, arguments);
        };    
}, __slice = Array.prototype.slice;
$ = jQuery;
/** Turtle Academy **/
var locale      = $.Storage.get('locale');
    var left , right ;
    if ($.Storage.get('locale') == "he_IL")
    {
        left = 39;
        right = 37;
    }
    else
    {
        left = 37;
        right = 39;
    }
    STATE_INPUT = 0;
    STATE_OUTPUT = 1;
    STATE_PROMPT = 2;
    KEY_ENTER = 13;
    KEY_TAB = 9;
    KEY_DELETE = 46;
    KEY_BACKSPACE = 8;
    KEY_LEFT = left;
    KEY_RIGHT = right;
    //  KEY_LEFT = 37; 
    //  KEY_RIGHT = 39;
    KEY_UP = 38;
    KEY_DOWN = 40;
    KEY_HOME = 36;
    KEY_END = 35;
    KEY_PAGE_UP = 33;
    KEY_PAGE_DOWN = 34;
    DEFAULT_PROMPT_LABEL = '>>> ';
    DEFAULT_PROMPT_CONINUE_LABEL = '... ';
    DEFAULT_INDENT_WIDTH = 2;
    JQConsole = (function() {
        function JQConsole(container, header, prompt_label, prompt_continue_label) {
            this._ProcessMatch = __bind(this._ProcessMatch, this);
            this.isMobile = !!navigator.userAgent.match(/iPhone|iPad|iPod|Android/i);
            this.isIos = !!navigator.userAgent.match(/iPhone|iPad|iPod/i);
            this.isAndroid = !!navigator.userAgent.match(/Android/i);
            this.$window = $(window);
            this.header = header || '';
            this.prompt_label_main = prompt_label || DEFAULT_PROMPT_LABEL;
            this.prompt_label_continue = ' \n' + (prompt_continue_label || DEFAULT_PROMPT_CONINUE_LABEL);
            this.indent_width = DEFAULT_INDENT_WIDTH;
            this.state = STATE_OUTPUT;
            this.input_queue = [];
            this.input_callback = null;
            this.multiline_callback = null;
            this.history = [];
            this.history_index = 0;
            this.history_new = '';
            this.history_active = false;
            this.shortcuts = {};
            this.$console = $('<pre class="jqconsole"/>').appendTo(container);
            this.$console_focused = true;
            this.$input_container = $('<div/>').appendTo(this.$console);
            this.$input_container.css({
                position: 'relative',
                width: 1,
                height: 0,
                overflow: 'hidden'
            });
            this.$input_source = $('<textarea/>');
            this.$input_source.attr('wrap', 'off').css({
                position: 'absolute',
                width: 2
            });
            this.$input_source.appendTo(this.$input_container);
            this.matchings = {
                openings: {},
                closings: {},
                clss: []
            };
            this._InitPrompt();
            this._SetupEvents();
            this.Write(this.header, 'jqconsole-header');
            $(container).data('jqconsole', this);
        }
        JQConsole.prototype.Reset = function() {
            if (this.state !== STATE_OUTPUT) {
                this.ClearPromptText(true);
            }
            this.state = STATE_OUTPUT;
            this.input_queue = [];
            this.input_callback = null;
            this.multiline_callback = null;
            this.history = [];
            this.history_index = 0;
            this.history_current = '';
            this.shortcuts = {};
            this.matchings = {
                openings: {},
                closings: {},
                clss: []
            };
            this.$prompt.detach();
            this.$input_container.detach();
            this.$console.html('');
            this.$prompt.appendTo(this.$console);
            this.$input_container.appendTo(this.$console);
            this.Write(this.header, 'jqconsole-header');
        };
        /*------------------------ Shortcut Methods -----------------------------*/
        JQConsole.prototype._CheckKeyCode = function(key_code) {
            if (isNaN(key_code)) {
                key_code = key_code.charCodeAt(0);
            } else {
                key_code = parseInt(key_code, 10);
            }
            if (!((0 < key_code && key_code < 256)) || isNaN(key_code)) {
                throw new Error('Key code must be a number between 0 and 256 exclusive.');
            }
            return key_code;
        };
        JQConsole.prototype._LetterCaseHelper = function(key_code, callback) {
            callback(key_code);
            if ((65 <= key_code && key_code <= 90)) {
                callback(key_code + 32);
            }
            if ((97 <= key_code && key_code <= 122)) {
                return callback(key_code - 32);
            }
        };
        JQConsole.prototype.RegisterShortcut = function(key_code, callback) {
            var addShortcut;
            key_code = this._CheckKeyCode(key_code);
            if (!callback instanceof Function) {
                throw new Error('Callback must be a function, not ' + callback + '.');
            }
            addShortcut = __bind(function(key) {
                if (!(key in this.shortcuts)) {
                    this.shortcuts[key] = [];
                }
                return this.shortcuts[key].push(callback);
            }, this);
            this._LetterCaseHelper(key_code, addShortcut);
        };
        JQConsole.prototype.UnRegisterShortcut = function(key_code, handler) {
            var removeShortcut;
            key_code = this._CheckKeyCode(key_code);
            removeShortcut = __bind(function(key) {
                if (key in this.shortcuts) {
                    if (handler) {
                        return this.shortcuts[key].splice(this.shortcuts[key].indexOf(handler), 1);
                    } else {
                        return delete this.shortcuts[key];
                    }
                } 
            }, this);
            this._LetterCaseHelper(key_code, removeShortcut);
        };
        /*---------------------- END Shortcut Methods ---------------------------*/
        JQConsole.prototype.GetColumn = function() {
            var lines;
            this.$prompt_cursor.text('');
            lines = this.$console.text().split('\n');
            this.$prompt_cursor.html('&nbsp;');
            return lines[lines.length - 1].length;
        };
        JQConsole.prototype.GetLine = function() {
            return this.$console.text().split('\n').length - 1;
        };
        JQConsole.prototype.ClearPromptText = function(clear_label) {
            if (this.state === STATE_OUTPUT) {
                throw new Error('ClearPromptText() is not allowed in output state.');
            }
            this.$prompt_before.html('');
            this.$prompt_after.html('');
            this.$prompt_label.text(clear_label ? '' : this._SelectPromptLabel(false));
            this.$prompt_left.text('');
            this.$prompt_right.text('');
        };
        JQConsole.prototype.GetPromptText = function(full) {
            var after, before, current, getPromptLines, text;
            if (this.state === STATE_OUTPUT) {
                throw new Error('GetPromptText() is not allowed in output state.');
            }
            if (full) {
                this.$prompt_cursor.text('');
                text = this.$prompt.text();
                this.$prompt_cursor.html('&nbsp;');
                return text;
            } else {
                getPromptLines = function(node) {
                    var buffer;
                    buffer = [];
                    node.children().each(function() {
                        return buffer.push($(this).children().last().text());
                    });
                    return buffer.join('\n');
                };
                before = getPromptLines(this.$prompt_before);
                if (before) {
                    before += '\n';
                }
                current = this.$prompt_left.text() + this.$prompt_right.text();
                after = getPromptLines(this.$prompt_after);
                if (after) {
                    after = '\n' + after;
                }
                return before + current + after;
            }
        };
        JQConsole.prototype.SetPromptText = function(text) {
            if (this.state === STATE_OUTPUT) {
                throw new Error('SetPromptText() is not allowed in output state.');
            }
            this.ClearPromptText(false);
            this._AppendPromptText(text);
            this._ScrollToEnd();
        };
        JQConsole.prototype.Write = function(text, cls, escape) {
            var span;
            if (escape == null) {
                escape = true;
            }
            span = $('<span/>')[escape ? 'text' : 'html'](text);
            if (cls != null) {
                span.addClass(cls);
            }
            span.insertBefore(this.$prompt);
            this._ScrollToEnd();
            this.$prompt_cursor.detach().insertAfter(this.$prompt_left);
        };
        JQConsole.prototype.Input = function(input_callback) {
            var current_history_active, current_input_callback, current_multiline_callback;
            if (this.state === STATE_PROMPT) {
                current_input_callback = this.input_callback;
                current_multiline_callback = this.multiline_callback;
                current_history_active = this.history_active;
                this.AbortPrompt();
                this.input_queue.unshift(__bind(function() {
                    return this.Prompt(current_history_active, current_input_callback, current_multiline_callback);
                }, this));
            } else if (this.state !== STATE_OUTPUT) {
                this.input_queue.push(__bind(function() {
                    return this.Input(input_callback);
                }, this));
                return;
            }
            this.history_active = false;
            this.input_callback = input_callback;
            this.multiline_callback = null;
            this.state = STATE_INPUT;
            this.$prompt.attr('class', 'jqconsole-input');
            this.$prompt_label.text(this._SelectPromptLabel(false));
            this.Focus();
            this._ScrollToEnd();
        };
        JQConsole.prototype.Prompt = function(history_enabled, result_callback, multiline_callback, async_multiline) {
            if (this.state !== STATE_OUTPUT) {
                this.input_queue.push(__bind(function() {
                    return this.Prompt(history_enabled, result_callback, multiline_callback);
                }, this));
                return;
            }
            this.history_active = history_enabled;
            this.input_callback = result_callback;
            this.multiline_callback = multiline_callback;
            this.async_multiline = async_multiline;
            this.state = STATE_PROMPT;
            this.$prompt.attr('class', 'jqconsole-prompt');
            this.$prompt_label.text(this._SelectPromptLabel(false));
            this.Focus();
            this._ScrollToEnd();
        };
        JQConsole.prototype.AbortPrompt = function() {
            if (this.state !== STATE_PROMPT) {
                throw new Error('Cannot abort prompt when not in prompt state.');
            }
            this.Write(this.GetPromptText(true) + '\n', 'jqconsole-old-prompt');
            this.ClearPromptText(true);
            this.state = STATE_OUTPUT;
            this.input_callback = this.multiline_callback = null;
            this._CheckInputQueue();
        };
        JQConsole.prototype.Focus = function() {
            this.$input_source.focus();
        };
        JQConsole.prototype.SetIndentWidth = function(width) {
            return this.indent_width = width;
        };
        JQConsole.prototype.GetIndentWidth = function() {
            return this.indent_width;
        };
        JQConsole.prototype.RegisterMatching = function(open, close, cls) {
            var match_config;
            match_config = {
                opening_char: open,
                closing_char: close,
                cls: cls
            };
            this.matchings.clss.push(cls);
            this.matchings.openings[open] = match_config;
            return this.matchings.closings[close] = match_config;
        };
        JQConsole.prototype.UnRegisterMatching = function(open, close) {
            var cls;
            cls = this.matchings.openings[open].cls;
            delete this.matchings.openings[open];
            delete this.matchings.closings[close];
            return this.matchings.clss.splice(this.matchings.clss.indexOf(cls), 1);
        };
        /*------------------------ Private Methods -------------------------------*/
        JQConsole.prototype._CheckInputQueue = function() {
            if (this.input_queue.length) {
                return this.input_queue.shift()();
            }
        };
        JQConsole.prototype._InitPrompt = function() {
            this.$prompt = $('<span class="jqconsole-input"/>').appendTo(this.$console);
            this.$prompt_before = $('<span/>').appendTo(this.$prompt);
            this.$prompt_current = $('<span/>').appendTo(this.$prompt);
            this.$prompt_after = $('<span/>').appendTo(this.$prompt);
            this.$prompt_label = $('<span/>').appendTo(this.$prompt_current);
            this.$prompt_left = $('<span/>').appendTo(this.$prompt_current);
            this.$prompt_right = $('<span/>').appendTo(this.$prompt_current);
            this.$prompt_right.css({
                position: 'relative'
            });
            this.$prompt_cursor = $('<span class="jqconsole-cursor">&nbsp;</span>');
            this.$prompt_cursor.insertBefore(this.$prompt_right);
            this.$prompt_cursor.css({
                color: 'transparent',
                display: 'inline',
                zIndex: 0
            });
            if (!this.isMobile) {
                return this.$prompt_cursor.css('position', 'absolute');
            }
        };
        JQConsole.prototype._SetupEvents = function() {
            var key_event, mouse_pos, paste_event;
            mouse_pos = {
                X: null,
                Y: null
            };
            this.$console.mousedown(__bind(function(e) {
                mouse_pos.X = e.pageX;
                return mouse_pos.Y = e.pageY;
            }, this));
            this.$console.mouseup(__bind(function(e) {
                if (mouse_pos.X === e.pageX && mouse_pos.Y === e.pageY) {
                    e.preventDefault();
                    return this.Focus();
                }
            }, this));
            this.$input_source.focus(__bind(function() {
                var hideTextInput, removeClass;
                this.$console_focused = true;
                this.$console.removeClass('jqconsole-blurred');
                removeClass = __bind(function() {
                    if (this.$console_focused) {
                        return this.$console.removeClass('jqconsole-blurred');
                    }
                }, this);
                setTimeout(removeClass, 100);
                hideTextInput = __bind(function() {
                    if (this.isIos && this.$console_focused) {
                        return this.$input_source.hide();
                    }
                }, this);
                return setTimeout(hideTextInput, 500);
            }, this));
            this.$input_source.blur(__bind(function() {
                var addClass;
                this.$console_focused = false;
                if (this.isIos) {
                    this.$input_source.show();
                }
                addClass = __bind(function() {
                    if (!this.$console_focused) {
                        return this.$console.addClass('jqconsole-blurred');
                    }
                }, this);
                return setTimeout(addClass, 100);
            }, this));
            paste_event = $.browser.opera ? 'input' : 'paste'; 
            this.$input_source.bind(paste_event, __bind(function() {
                var handlePaste;
                handlePaste = __bind(function() {
                    this._AppendPromptText(this.$input_source.val());
                    this.$input_source.val('');
                    return this.Focus();
                }, this);
                return setTimeout(handlePaste, 0);
            }, this));
            this.$input_source.keypress(__bind(function(e) {
                return this._HandleChar(e);
            }, this));
            key_event = $.browser.mozilla ? 'keypress' : 'keydown';
            this.$input_source[key_event](__bind(function(e) {
                return this._HandleKey(e);
            }, this));
            if (this.isMobile) {
                this.$console.bind('touchend', __bind(function() {
                    return this.$console[0].ontouchmove = null;
                }, this));
                return this.$console.bind('touchstart', __bind(function(e) {
                    var first_change;
                    first_change = {
                        distanceX: null,
                        distanceY: null
                    };
                    return this.$console[0].ontouchmove = __bind(function(e) {
                        var check, diffX, diffY, distanceX, distanceY;
                        if (e.touches.length !== 2) {
                            return true;
                        }
                        distanceX = Math.abs(e.touches[0].pageX - e.touches[1].pageX);
                        distanceY = Math.abs(e.touches[0].pageX - e.touches[1].pageY);
                        if (first_change.distanceX && first_change.distanceY) {
                            diffX = Math.abs(distanceX - first_change.distanceX);
                            diffY = Math.abs(distanceY - first_change.distanceY);
                            check = diffX > diffY ? distanceX > first_change.distanceX : distanceY > first_change.distanceY;
                            if (check) {
                                this._HistoryPrevious();
                            } else {
                                this._HistoryNext();
                            }
                            return this.$console[0].ontouchmove = null;
                        } else {
                            first_change.distanceX = distanceX;
                            return first_change.distanceY = distanceY;
                        }
                    }, this);
                }, this));
            }
        };
        JQConsole.prototype._HandleChar = function(event) {
            var char_code;
            if (this.state === STATE_OUTPUT) {
                return true;
            }
            char_code = event.which;
            if (char_code === 13 || char_code === 9) {
                return false;
            }
            if ($.browser.mozilla ) { //Before was only $.browser.mozilla
                if (event.keyCode || event.metaKey || event.ctrlKey || event.altKey) {
                    return true;
                }
            }
            if ($.browser.opera) {
                if (event.keyCode || event.which || event.metaKey || event.ctrlKey || event.altKey) {
                    return true;
                }
            }
            if ( (event.metaKey || event.ctrlKey || event.altKey) && (!(char_code === 91 || char_code === 93))) { 
                return false;
            }
            this.$prompt_left.text(this.$prompt_left.text() + String.fromCharCode(char_code));
            this._ScrollToEnd();
            return false;
        };
        JQConsole.prototype._HandleKey = function(event) {
            var key;
            if (this.state === STATE_OUTPUT) {
                return true;
            }
            key = event.keyCode || event.which;
            setTimeout($.proxy(this._CheckMatchings, this), 0);
            if (event.altKey || event.metaKey && !event.ctrlKey) {
                return true;
            } else if (event.ctrlKey) {
                return this._HandleCtrlShortcut(key);
            } else if (event.shiftKey) {
                switch (key) {
                    case KEY_ENTER:
                        this._HandleEnter(true);
                        break;
                    case KEY_TAB:
                        this._Unindent();
                        break;
                    case KEY_UP:
                        this._MoveUp();
                        break;
                    case KEY_DOWN:
                        this._MoveDown();
                        break;
                    case KEY_PAGE_UP:
                        this._ScrollUp();
                        break;
                    case KEY_PAGE_DOWN:
                        this._ScrollDown();
                        break;
                    default:
                        return true;
                }
                return false;
            } else {
                switch (key) {
                    case KEY_ENTER:
                        this._HandleEnter(false);
                        break;
                    case KEY_TAB:
                        this._Indent();
                        break;
                    case KEY_DELETE:
                        this._Delete(false);
                        break;
                    case KEY_BACKSPACE:
                        this._Backspace(false);
                        break;
                    case KEY_LEFT:
                        this._MoveLeft(false);
                        break;
                    case KEY_RIGHT:
                        this._MoveRight(false);
                        break;
                    case KEY_UP:
                        this._HistoryPrevious();
                        break;
                    case KEY_DOWN:
                        this._HistoryNext();
                        break;
                    case KEY_HOME:
                        this.MoveToStart(false);
                        break;
                    case KEY_END:
                        this.MoveToEnd(false);
                        break;
                    case KEY_PAGE_UP:
                        this._ScrollUp();
                        break;
                    case KEY_PAGE_DOWN:
                        this._ScrollDown();
                        break;
                    default:
                        return true;
                }
                return false;
            }
        };
        JQConsole.prototype._HandleCtrlShortcut = function(key) {
            var handler, _i, _len, _ref;
            switch (key) {
                case KEY_DELETE:
                    this._Delete(true);
                    break;
                case KEY_BACKSPACE:
                    this._Backspace(true);
                    break;
                case KEY_LEFT:
                    this._MoveLeft(true);
                    break;
                case KEY_RIGHT:
                    this._MoveRight(true);
                    break;
                case KEY_UP:
                    this._MoveUp();
                    break;
                case KEY_DOWN:
                    this._MoveDown();
                    break;
                case KEY_END:
                    this.MoveToEnd(true);
                    break;
                case KEY_HOME:
                    this.MoveToStart(true);
                    break;
                default:
                    if (key in this.shortcuts) {
                        _ref = this.shortcuts[key];
                        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                            handler = _ref[_i];
                            handler.call(this);
                        }
                        return false;
                    } else {
                        return true;
                    }
            }
            return false;
        };
        JQConsole.prototype._HandleEnter = function(shift) {
            var continuation, text;
            if (shift) {
                return this._InsertNewLine(true);
            } else {
                text = this.GetPromptText();
                continuation = __bind(function(indent) {
                    var callback, cls_suffix, _, _ref, _results;
                    if (indent !== false) {
                        this.MoveToEnd(true);
                        this._InsertNewLine(true);
                        _results = [];
                        for (_ = 0, _ref = Math.abs(indent); 0 <= _ref ? _ < _ref : _ > _ref; 0 <= _ref ? _++ : _--) {
                            _results.push(indent > 0 ? this._Indent() : this._Unindent());
                        }
                        return _results;
                    } else {
                        cls_suffix = this.state === STATE_INPUT ? 'input' : 'prompt';
                        this.Write(this.GetPromptText(true) + '\n', 'jqconsole-old-' + cls_suffix);
                        this.ClearPromptText(true);
                        if (this.history_active) {
                            if (!this.history.length || this.history[this.history.length - 1] !== text) {
                                this.history.push(text);
                            }
                            this.history_index = this.history.length;
                        }
                        this.state = STATE_OUTPUT;
                        callback = this.input_callback;
                        this.input_callback = null;
                        if (callback) {
                            callback(text);
                        }
                        return this._CheckInputQueue();
                    }
                }, this);
                if (this.multiline_callback) {
                    if (this.async_multiline) {
                        return this.multiline_callback(text, continuation);
                    } else {
                        return continuation(this.multiline_callback(text));
                    }
                } else {
                    return continuation(false);
                }
            }
        };
        JQConsole.prototype._GetDirectionals = function(back) {
            var $prompt_opposite, $prompt_rel_opposite, $prompt_relative, $prompt_which, MoveDirection, MoveToLimit, where_append, which_end;
            $prompt_which = back ? this.$prompt_left : this.$prompt_right;
            $prompt_opposite = back ? this.$prompt_right : this.$prompt_left;
            $prompt_relative = back ? this.$prompt_before : this.$prompt_after;
            $prompt_rel_opposite = back ? this.$prompt_after : this.$prompt_before;
            MoveToLimit = back ? $.proxy(this.MoveToStart, this) : $.proxy(this.MoveToEnd, this);
            MoveDirection = back ? $.proxy(this._MoveLeft, this) : $.proxy(this._MoveRight, this);
            which_end = back ? 'last' : 'first';
            where_append = back ? 'prependTo' : 'appendTo';
            return {
                $prompt_which: $prompt_which,
                $prompt_opposite: $prompt_opposite,
                $prompt_relative: $prompt_relative,
                $prompt_rel_opposite: $prompt_rel_opposite,
                MoveToLimit: MoveToLimit,
                MoveDirection: MoveDirection,
                which_end: which_end,
                where_append: where_append
            };
        };
        JQConsole.prototype._VerticalMove = function(up) {
            var $prompt_opposite, $prompt_relative, $prompt_which, MoveDirection, MoveToLimit, pos, text, _ref;
            _ref = this._GetDirectionals(up), $prompt_which = _ref.$prompt_which, $prompt_opposite = _ref.$prompt_opposite, $prompt_relative = _ref.$prompt_relative, MoveToLimit = _ref.MoveToLimit, MoveDirection = _ref.MoveDirection;
            if ($prompt_relative.is(':empty')) {
                return;
            }
            pos = this.$prompt_left.text().length;
            MoveToLimit();
            MoveDirection();
            text = $prompt_which.text();
            $prompt_opposite.text(up ? text.slice(pos) : text.slice(0, pos));
            return $prompt_which.text(up ? text.slice(0, pos) : text.slice(pos));
        };
        JQConsole.prototype._MoveUp = function() {
            return this._VerticalMove(true);
        };
        JQConsole.prototype._MoveDown = function() {
            return this._VerticalMove();
        };
        JQConsole.prototype._HorizontalMove = function(whole_word, back) {
            var $opposite_line, $prompt_opposite, $prompt_rel_opposite, $prompt_relative, $prompt_which, $which_line, len, regexp, text, tmp, where_append, which_end, word, _ref;
            _ref = this._GetDirectionals(back), $prompt_which = _ref.$prompt_which, $prompt_opposite = _ref.$prompt_opposite, $prompt_relative = _ref.$prompt_relative, $prompt_rel_opposite = _ref.$prompt_rel_opposite, which_end = _ref.which_end, where_append = _ref.where_append;
            regexp = back ? /\w*\W*$/ : /^\w*\W*/;
            text = $prompt_which.text();
            if (text) {
                if (whole_word) {
                    word = text.match(regexp);
                    if (!word) {
                        return;
                    }
                    word = word[0];
                    tmp = $prompt_opposite.text();
                    $prompt_opposite.text(back ? word + tmp : tmp + word);
                    len = word.length;
                    return $prompt_which.text(back ? text.slice(0, -len) : text.slice(len));
                } else {
                    tmp = $prompt_opposite.text();
                    $prompt_opposite.text(back ? text.slice(-1) + tmp : tmp + text[0]);
                    return $prompt_which.text(back ? text.slice(0, -1) : text.slice(1));
                }
            } else if (!$prompt_relative.is(':empty')) {
                $which_line = $('<span/>')[where_append]($prompt_rel_opposite);
                $which_line.append($('<span/>').text(this.$prompt_label.text()));
                $which_line.append($('<span/>').text($prompt_opposite.text()));
                $opposite_line = $prompt_relative.children()[which_end]().detach();
                this.$prompt_label.text($opposite_line.children().first().text());
                $prompt_which.text($opposite_line.children().last().text());
                return $prompt_opposite.text('');
            }
        };
        JQConsole.prototype._MoveLeft = function(whole_word) {
            return this._HorizontalMove(whole_word, true);
        };
        JQConsole.prototype._MoveRight = function(whole_word) {
            return this._HorizontalMove(whole_word);
        };
        JQConsole.prototype._MoveTo = function(all_lines, back) {
            var $prompt_opposite, $prompt_relative, $prompt_which, MoveDirection, MoveToLimit, _ref, _results;
            _ref = this._GetDirectionals(back), $prompt_which = _ref.$prompt_which, $prompt_opposite = _ref.$prompt_opposite, $prompt_relative = _ref.$prompt_relative, MoveToLimit = _ref.MoveToLimit, MoveDirection = _ref.MoveDirection;
            if (all_lines) {
                _results = [];
                while (!($prompt_relative.is(':empty') && $prompt_which.text() === '')) {
                    MoveToLimit(false);
                    _results.push(MoveDirection(false));
                }
                return _results;
            } else {
                $prompt_opposite.text(this.$prompt_left.text() + this.$prompt_right.text());
                return $prompt_which.text('');
            }
        };
        JQConsole.prototype.MoveToStart = function(all_lines) {
            this._MoveTo(all_lines, true);
        };
        JQConsole.prototype.MoveToEnd = function(all_lines) {
            this._MoveTo(all_lines, false);
        };
        JQConsole.prototype._Delete = function(whole_word) {
            var $lower_line, text, word;
            text = this.$prompt_right.text();
            if (text) {
                if (whole_word) {
                    word = text.match(/^\w*\W*/);
                    if (!word) {
                        return;
                    }
                    word = word[0];
                    return this.$prompt_right.text(text.slice(word.length));
                } else {
                    return this.$prompt_right.text(text.slice(1));
                }
            } else if (!this.$prompt_after.is(':empty')) {
                $lower_line = this.$prompt_after.children().first().detach();
                return this.$prompt_right.text($lower_line.children().last().text());
            }
        };
        JQConsole.prototype._Backspace = function(whole_word) {
            var $upper_line, text, word;
            setTimeout($.proxy(this._ScrollToEnd, this), 0);
            text = this.$prompt_left.text();
            if (text) {
                if (whole_word) {
                    word = text.match(/\w*\W*$/);
                    if (!word) {
                        return;
                    }
                    word = word[0];
                    return this.$prompt_left.text(text.slice(0, -word.length));
                } else {
                    return this.$prompt_left.text(text.slice(0, -1));
                }
            } else if (!this.$prompt_before.is(':empty')) {
                $upper_line = this.$prompt_before.children().last().detach();
                this.$prompt_label.text($upper_line.children().first().text());
                return this.$prompt_left.text($upper_line.children().last().text());
            }
        };
        JQConsole.prototype._Indent = function() {
            var _;
            return this.$prompt_left.prepend(((function() {
                var _ref, _results;
                _results = [];
                for (_ = 1, _ref = this.indent_width; 1 <= _ref ? _ <= _ref : _ >= _ref; 1 <= _ref ? _++ : _--) {
                    _results.push(' ');
                }
                return _results;
            }).call(this)).join(''));
        };
        JQConsole.prototype._Unindent = function() {
            var line_text, _, _ref, _results;
            line_text = this.$prompt_left.text() + this.$prompt_right.text();
            _results = [];
            for (_ = 1, _ref = this.indent_width; 1 <= _ref ? _ <= _ref : _ >= _ref; 1 <= _ref ? _++ : _--) {
                if (!/^ /.test(line_text)) {
                    break;
                }
                if (this.$prompt_left.text()) {
                    this.$prompt_left.text(this.$prompt_left.text().slice(1));
                } else {
                    this.$prompt_right.text(this.$prompt_right.text().slice(1));
                }
                _results.push(line_text = line_text.slice(1));
            }
            return _results;
        };
        JQConsole.prototype._InsertNewLine = function(indent) {
            var $old_line, match, old_prompt;
            if (indent == null) {
                indent = false;
            }
            old_prompt = this._SelectPromptLabel(!this.$prompt_before.is(':empty'));
            $old_line = $('<span/>').appendTo(this.$prompt_before);
            $old_line.append($('<span/>').text(old_prompt));
            $old_line.append($('<span/>').text(this.$prompt_left.text()));
            this.$prompt_label.text(this._SelectPromptLabel(true));
            if (indent && (match = this.$prompt_left.text().match(/^\s+/))) {
                this.$prompt_left.text(match[0]);
            } else {
                this.$prompt_left.text('');
            }
            return this._ScrollToEnd();
        };
        JQConsole.prototype._AppendPromptText = function(text) {
            var line, lines, _i, _len, _ref, _results;
            lines = text.split('\n');
            this.$prompt_left.text(this.$prompt_left.text() + lines[0]);
            _ref = lines.slice(1);
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                line = _ref[_i];
                this._InsertNewLine();
                _results.push(this.$prompt_left.text(line));
            }
            return _results;
        };
        JQConsole.prototype._ScrollUp = function() {
            var target;
            target = this.$console[0].scrollTop - this.$console.height();
            return this.$console.stop().animate({
                scrollTop: target
            }, 'fast');
        };
        JQConsole.prototype._ScrollDown = function() {
            var target;
            target = this.$console[0].scrollTop + this.$console.height();
            return this.$console.stop().animate({
                scrollTop: target
            }, 'fast');
        };
        JQConsole.prototype._ScrollToEnd = function() {
            var doc_height, line_height, optimal_pos, pos, rel_pos, screen_left, screen_top;
            line_height = this.$prompt_cursor.height();
            screen_top = this.$window.scrollTop();
            screen_left = this.$window.scrollLeft();
            doc_height = document.documentElement.clientHeight;
            pos = this.$prompt_cursor.offset();
            rel_pos = this.$prompt_cursor.position();
            this.$console.scrollTop(this.$console[0].scrollHeight);
            this.$input_container.css({
                left: rel_pos.left,
                top: rel_pos.top
            });
            optimal_pos = pos.top - (2 * line_height);
            if (this.isMobile && (typeof orientation !== "undefined" && orientation !== null)) {
                if (screen_top < pos.top || screen_top > pos.top) {
                    return this.$window.scrollTop(optimal_pos);
                }
            } else {
                if (screen_top + doc_height < pos.top || screen_top > optimal_pos) {
                    return this.$window.scrollTop(optimal_pos);
                }
            }
        };
        JQConsole.prototype._SelectPromptLabel = function(continuation) {
            if (this.state === STATE_PROMPT) {
                if (continuation) {
                    return this.prompt_label_continue;
                } else {
                    return this.prompt_label_main;
                }
            } else {
                if (continuation) {
                    return '\n ';
                } else {
                    return ' ';
                }
            }
        };
        JQConsole.prototype._outerHTML = function($elem) {
            if (document.body.outerHTML) {
                return $elem.get(0).outerHTML;
            } else {
                return $('<div/>').append($elem.eq(0).clone()).html();
            }
        };
        JQConsole.prototype._Wrap = function($elem, index, cls) {
            var html, text;
            text = $elem.html();
            html = text.slice(0, index) + ("<span class=\"" + cls + "\">" + text[index] + "</span>") + text.slice(index + 1);
            return $elem.html(html);
        };
        JQConsole.prototype._WalkCharacters = function(text, char, opposing_char, current_count, back) {
            var ch, index, read_char;
            index = back ? text.length : 0;
            text = text.split('');
            read_char = function() {
                var ret, _i, _ref, _ref2;
                if (back) {
                    _ref = text, text = 2 <= _ref.length ? __slice.call(_ref, 0, _i = _ref.length - 1) : (_i = 0, []), ret = _ref[_i++];
                } else {
                    _ref2 = text, ret = _ref2[0], text = 2 <= _ref2.length ? __slice.call(_ref2, 1) : [];
                }
                if (ret) {
                    index = index + (back ? -1 : +1);
                }
                return ret;
            };
            while (ch = read_char()) {
                if (ch === char) {
                    current_count++;
                } else if (ch === opposing_char) {
                    current_count--;
                }
                if (current_count === 0) {
                    return {
                        index: index,
                        current_count: current_count
                    };
                }
            }
            return {
                index: -1,
                current_count: current_count
            };
        };
        JQConsole.prototype._ProcessMatch = function(config, back, before_char) {
            var $collection, $prompt_relative, $prompt_which, char, current_count, found, index, opposing_char, text, _ref, _ref2, _ref3;
            _ref = back ? [config['closing_char'], config['opening_char']] : [config['opening_char'], config['closing_char']], char = _ref[0], opposing_char = _ref[1];
            _ref2 = this._GetDirectionals(back), $prompt_which = _ref2.$prompt_which, $prompt_relative = _ref2.$prompt_relative;
            current_count = 1;
            found = false;
            text = $prompt_which.html();
            if (!back) {
                text = text.slice(1);
            }
            if (before_char && back) {
                text = text.slice(0, -1);
            }
            _ref3 = this._WalkCharacters(text, char, opposing_char, current_count, back), index = _ref3.index, current_count = _ref3.current_count;
            if (index > -1) {
                this._Wrap($prompt_which, index, config.cls);
                found = true;
            } else {
                $collection = $prompt_relative.children();
                $collection = back ? Array.prototype.reverse.call($collection) : $collection;
                $collection.each(__bind(function(i, elem) {
                    var $elem, _ref4;
                    $elem = $(elem).children().last();
                    text = $elem.html();
                    _ref4 = this._WalkCharacters(text, char, opposing_char, current_count, back), index = _ref4.index, current_count = _ref4.current_count;
                    if (index > -1) {
                        if (!back) {
                            index--;
                        }
                        this._Wrap($elem, index, config.cls);
                        found = true;
                        return false;
                    }
                }, this));
            }
            return found;
        };
        JQConsole.prototype._CheckMatchings = function(before_char) {
            var cls, config, current_char, found, _i, _len, _ref;
            current_char = before_char ? this.$prompt_left.text().slice(this.$prompt_left.text().length - 1) : this.$prompt_right.text()[0];
            _ref = this.matchings.clss;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                cls = _ref[_i];
                $('.' + cls, this.$console).contents().unwrap();
            }
            if (config = this.matchings.closings[current_char]) {
                found = this._ProcessMatch(config, true, before_char);
            } else if (config = this.matchings.openings[current_char]) {
                found = this._ProcessMatch(config, false, before_char);
            } else if (!before_char) {
                this._CheckMatchings(true);
            }
            if (before_char) {
                if (found) {
                    return this._Wrap(this.$prompt_left, this.$prompt_left.html().length - 1, config.cls);
                }
            } else {
                if (found) {
                    return this._Wrap(this.$prompt_right, 0, config.cls);
                }
            }
        };
        JQConsole.prototype._HistoryPrevious = function() {
            if (!this.history_active) {
                return;
            }
            if (this.history_index <= 0) {
                return;
            }
            if (this.history_index === this.history.length) {
                this.history_new = this.GetPromptText();
            }
            return this.SetPromptText(this.history[--this.history_index]);
        };
        JQConsole.prototype._HistoryNext = function() {
            if (!this.history_active) {
                return;
            }
            if (this.history_index >= this.history.length) {
                return;
            }
            if (this.history_index === this.history.length - 1) {
                this.history_index++;
                return this.SetPromptText(this.history_new);
            } else {
                return this.SetPromptText(this.history[++this.history_index]);
            }
        };
        JQConsole.prototype.Dump = function() {
            var $elems, elem;
            $elems = this.$console.find('.jqconsole-header').nextUntil('.jqconsole-prompt');
            return ((function() {
                var _i, _len, _results;
                _results = [];
                for (_i = 0, _len = $elems.length; _i < _len; _i++) {
                    elem = $elems[_i];
                    _results.push($(elem).is('.jqconsole-old-prompt') ? $(elem).text().replace(/^\s+/, '>>> ') : $(elem).text());
                }
                return _results;
            })()).join(' ');
        };
        JQConsole.prototype.GetState = function() {
            if (this.state === STATE_INPUT) {
                return 'input';
            } else if (this.state === STATE_OUTPUT) {
                return 'output';
            } else {
                return 'prompt';
            }
        };
        return JQConsole;
    })();
    $.fn.jqconsole = function(header, prompt_main, prompt_continue) {
        return new JQConsole(this, header, prompt_main, prompt_continue);
    };
}).call(this);


