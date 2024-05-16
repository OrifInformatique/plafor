var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProgressStats = function (_React$Component) {
    _inherits(ProgressStats, _React$Component);

    function ProgressStats(props) {
        _classCallCheck(this, ProgressStats);

        var _this = _possibleConstructorReturn(this, (ProgressStats.__proto__ || Object.getPrototypeOf(ProgressStats)).call(this, props));

        _this.element = [];

        _this.mainframe = React.createRef();
        _this.playanim = _this.playanim.bind(_this);
        for (var i = 0; i < _this.props.elements.length; i++) {
            _this.element.push(React.createElement(Barelement, { animatableImagePath: _this.props.animatableImagePath, transitionDelay: i + 's', backgroundColor: _this.props.colors[i], elements: _this.props.elements[i] }));
        }

        return _this;
    }

    _createClass(ProgressStats, [{
        key: 'render',
        value: function render() {
            var _this2 = this;

            return React.createElement(
                'div',
                { id: 'proressStats' },
                React.createElement(
                    'div',
                    { className: 'frame' },
                    React.createElement(
                        'main',
                        { ref: this.mainframe },
                        this.element,
                        React.createElement('input', { type: 'button', className: 'btn btn-primary', onClick: function onClick() {
                                _this2.playanim();
                            }, value: 'salut' })
                    )
                )
            );
        }
    }, {
        key: 'playanim',
        value: function playanim() {
            var _this3 = this;

            var i = 0;
            var interval = setInterval(function () {
                _this3.mainframe.current.querySelectorAll('.progress-root')[i].addEventListener('transitionrun', function (e) {
                    e.target.childNodes.forEach(function (element) {
                        if (element.tagName === "IMG") {
                            element.style.setProperty('width', '150%', 'important');
                            element.style.setProperty('height', '150%', 'important');
                            element.addEventListener('transitionend', function (e) {
                                e.target.style = '';

                                e.target.style.setProperty('transition', 'all 0.2s', 'important');
                                e.target.style.setProperty('transition-delay', '0.1s', 'important');
                            });
                        }
                    });
                });
                _this3.mainframe.current.querySelectorAll('.progress-root')[i].style.transform = 'unset';
                //delete interval
                i++;
                if (i === 4) {
                    clearInterval(interval);
                }
            }, 200);
        }
    }]);

    return ProgressStats;
}(React.Component);

var Meter = function Meter() {
    return React.createElement(
        'div',
        { className: 'meter', style: { display: 'flex', flexDirection: 'column' } },
        React.createElement(
            'h5',
            null,
            '100'
        ),
        React.createElement('span', null),
        React.createElement(
            'h5',
            null,
            '90'
        ),
        React.createElement('span', null),
        React.createElement(
            'h5',
            null,
            '80'
        ),
        React.createElement('span', null),
        React.createElement(
            'h5',
            null,
            '70'
        ),
        React.createElement('span', null),
        React.createElement(
            'h5',
            null,
            '60'
        ),
        React.createElement('span', null),
        React.createElement(
            'h5',
            null,
            '50'
        ),
        React.createElement('span', null),
        React.createElement(
            'h5',
            null,
            '40'
        ),
        React.createElement('span', null),
        React.createElement(
            'h5',
            null,
            '30'
        ),
        React.createElement('span', null),
        React.createElement(
            'h5',
            null,
            '20'
        ),
        React.createElement('span', null),
        React.createElement(
            'h5',
            null,
            '10'
        ),
        React.createElement('span', null),
        React.createElement('span', { style: { opacity: '0' } })
    );
};

var Barelement = function (_React$Component2) {
    _inherits(Barelement, _React$Component2);

    function Barelement(props) {
        _classCallCheck(this, Barelement);

        var _this4 = _possibleConstructorReturn(this, (Barelement.__proto__ || Object.getPrototypeOf(Barelement)).call(this, props));

        _this4.progressContainer = React.createRef();
        _this4.intervalArray = [];
        _this4.state = {
            elements: [],
            hlayouts: []
        };
        return _this4;
    }

    _createClass(Barelement, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            var _this5 = this;

            var i = this.intervalArray.length;

            this.intervalArray.push({ index: 0,
                interval: setInterval(function () {
                    if (_this5.intervalArray[i].index >= _this5.props.elements) {
                        if (_this5.state.elements.length % 5 !== 0) _this5.setState({ hlayouts: [React.createElement(
                                Hlayout,
                                null,
                                _this5.state.elements.slice(_this5.state.elements.length - _this5.state.elements.length % 5)
                            )].concat(_this5.state.hlayouts) });

                        clearInterval(_this5.intervalArray[i].interval);
                    } else {
                        if (_this5.state.elements.length % 5 === 0 && _this5.state.elements.length !== 0) {
                            _this5.setState({ hlayouts: _this5.state.hlayouts.concat(React.createElement(
                                    Hlayout,
                                    null,
                                    _this5.state.elements.slice(_this5.state.elements.length - 5)
                                )) });
                        }
                        _this5.setState({
                            elements: _this5.state.elements.concat(React.createElement('span', { style: { backgroundColor: _this5.props.backgroundColor, transitionDelay: _this5.props.transitionDelay, width: '20%', height: '100%', display: 'block', borderRadius: '4px', boxSizing: 'border-box', padding: '0.5px', backgroundClip: 'content-box' } }))
                        });
                    }
                    _this5.intervalArray[i].index++;
                }, 20) });
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { className: 'progress-root iprogress-root' },
                React.createElement(Meter, null),
                React.createElement('img', { src: this.props.animatableImagePath }),
                React.createElement(
                    'div',
                    { className: 'hbar', ref: this.progressContainer },
                    React.createElement(
                        'div',
                        { style: { display: 'flex', flexDirection: 'column', justifyContent: 'flex-end', width: '100%', height: '100%' } },
                        this.state.hlayouts
                    )
                )
            );
        }
    }]);

    return Barelement;
}(React.Component);

var Hlayout = function Hlayout(props) {
    return React.createElement(
        'span',
        { style: { width: '100%', height: '5%', display: 'flex', flexDirection: 'row', transition: 'all 0.5s' } },
        props.children
    );
};