var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var ProgressView = function (_React$Component) {
    _inherits(ProgressView, _React$Component);

    function ProgressView(props) {
        _classCallCheck(this, ProgressView);

        var _this = _possibleConstructorReturn(this, (ProgressView.__proto__ || Object.getPrototypeOf(ProgressView)).call(this, props));

        _this.coursePlanProgress = [];
        _this.accordion = [];
        _this.tableRows = [];
        _this.competenceDomains = [];
        _this.operationalCompetences = [];
        _this.listbtn = React.createRef();

        _this.refreshComponent = function (e) {
            if (window.innerWidth > 950) {
                _this.setState({
                    mobiledisplay: false
                });
            } else {
                _this.setState({
                    mobiledisplay: true
                });
            }
        };

        _this.state = {
            mobiledisplay: window.innerWidth < 950,
            page: 1
        };
        _this.hideMenu = _this.hideMenu.bind(_this);
        _this.showMenu = _this.showMenu.bind(_this);
        //init courseplanProgress On construct
        Object.values(props.coursePlan.competenceDomains).forEach(function (competenceDomain) {
            var coursePlanProgress = new Object();
            coursePlanProgress.competenceDomain = competenceDomain;
            coursePlanProgress.competenceDomainSymbol = competenceDomain.symbol;
            coursePlanProgress.competenceDomainName = competenceDomain.name;
            Object.values(competenceDomain.operationalCompetences).forEach(function (operationalCompetence) {
                var opComp = new Object();
                opComp.operationnalCompetence = operationalCompetence;
                opComp.symbol = operationalCompetence.symbol;
                opComp.name = operationalCompetence.name;
                coursePlanProgress.operationnalCompetences !== undefined ? coursePlanProgress.operationnalCompetences.push(opComp) : coursePlanProgress.operationnalCompetences = [opComp];
            });
            _this.coursePlanProgress.push(coursePlanProgress);
        });
        return _this;
    }

    _createClass(ProgressView, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            window.innerWidth > 950 ? this.setState({
                mobiledisplay: false
            }) : this.setState({
                mobiledisplay: true
            });

            window.addEventListener('resize', this.refreshComponent);
        }
    }, {
        key: 'componentWillUnmount',
        value: function componentWillUnmount() {
            window.removeEventListener('resize', this.refreshComponent);
        }
    }, {
        key: 'render',
        value: function render() {
            var _this2 = this;

            this.competenceDomains = [];
            var intermediateList = [];
            this.tableRows = [];
            if (this.state.mobiledisplay && !this.props.integrated) {
                return React.createElement(
                    'div',
                    { id: 'detailsFrame' },
                    React.createElement(
                        'div',
                        { id: 'opcompList' },
                        React.createElement(
                            'div',
                            { id: 'leftColumn' },
                            React.createElement(
                                'span',
                                { id: 'listbtn', ref: this.listbtn, onClick: function onClick(e) {
                                        return _this2.hideMenu();
                                    } },
                                React.createElement('i', { className: 'bi bi-list' })
                            ),
                            Object.values(this.props.coursePlan.competenceDomains).forEach(function (competenceDomain) {
                                _this2.competenceDomains.push(React.createElement(CompetenceDomainView, { mobiledisplay: true, competenceDomainDatas: competenceDomain, competenceDomainName: competenceDomain.name, competenceDomainSymbol: competenceDomain.symbol, onClick: function onClick(opcomps) {
                                        _this2.hideMenu();
                                        _this2.setState({ page: competenceDomain.id });
                                    } }));
                                intermediateList = [];
                                Object.values(competenceDomain.operationalCompetences).forEach(function (operationalCompetence) {
                                    intermediateList.push(React.createElement(OperationalCompetenceView, { key: operationalCompetence.id, operationalCompetenceSymbol: operationalCompetence.symbol, operationalCompetenceName: operationalCompetence.name, operationalCompetenceDatas: operationalCompetence, mobiledisplay: true, baseUrl: _this2.props.baseUrl }));
                                });
                                _this2.operationalCompetences[competenceDomain.id] = intermediateList;
                            }),
                            this.competenceDomains
                        ),
                        React.createElement(
                            'span',
                            { id: 'closelbtn', onClick: function onClick(e) {
                                    return _this2.showMenu();
                                } },
                            React.createElement('i', { className: 'bi bi-list' })
                        ),
                        this.operationalCompetences[this.state.page],
                        React.createElement('i', { className: 'bi bi-x-circle-fill', id: 'exitdetails', onClick: function onClick() {
                                _this2.props.callback();
                            } })
                    )
                );
            } else if (!this.state.mobiledisplay && !this.props.integrated) {
                return React.createElement(
                    'div',
                    { id: 'detailsFrame' },
                    React.createElement(
                        'div',
                        { style: { display: 'flex', justifyContent: 'center', width: '90%' } },
                        React.createElement(
                            'table',
                            { id: 'courseplandetails', className: "table",
                                style: { tableLayout: 'fixed', width: '100%' } },
                            React.createElement(
                                'thead',
                                { className: 'table-dark bg-primary' },
                                React.createElement(
                                    'tr',
                                    null,
                                    React.createElement(
                                        'th',
                                        { style: { width: '13%' } },
                                        'competence domain'
                                    ),
                                    React.createElement(
                                        'th',
                                        { colSpan: 21 },
                                        'operational competences'
                                    ),
                                    React.createElement('i', { className: 'bi bi-x-circle-fill', id: 'exitdetails', onClick: function onClick() {
                                            _this2.props.callback();
                                        } })
                                )
                            ),
                            React.createElement(
                                'tbody',
                                null,
                                Object.values(this.props.coursePlan.competenceDomains).forEach(function (competenceDomain) {

                                    var row = new Object();
                                    row.competenceDomain = competenceDomain;
                                    row.competenceDomainSymbol = competenceDomain.symbol;
                                    row.competenceDomainName = competenceDomain.name;
                                    Object.values(competenceDomain.operationalCompetences).forEach(function (operationalCompetence) {
                                        var opComp = new Object();
                                        opComp.operationnalCompetence = operationalCompetence;
                                        opComp.symbol = operationalCompetence.symbol;
                                        opComp.name = operationalCompetence.name;
                                        row.operationnalCompetences !== undefined ? row.operationnalCompetences.push(opComp) : row.operationnalCompetences = [opComp];
                                    });
                                    _this2.tableRows.push(React.createElement(TableRow, { mobiledisplay: _this2.state.mobiledisplay, coursePlanRows: row }));
                                }),
                                this.tableRows
                            )
                        )
                    )
                );
            }
            //when integrated props is true
            else {
                    var accordions = [];
                    return React.createElement(
                        'div',
                        { style: { display: 'contents' } },
                        this.coursePlanProgress.map(function (progress) {
                            accordions.push(
                            //here comes tag to modify display
                            React.createElement(Accordion, { datas: progress, baseUrl: _this2.props.baseUrl, userCourseId: _this2.props.userCourseId }));
                        }),
                        accordions
                    );
                }
        }
    }, {
        key: 'hideMenu',
        value: function hideMenu() {
            document.getElementById('leftColumn').style.transform = 'translateX(-100%)';
            document.getElementById('opcompList').style.setProperty('padding-left', '0', 'important');
            document.getElementById('leftColumn').addEventListener('transitionend', this.closeMenuListener = function (e) {
                document.getElementById('closelbtn').style.setProperty('transform', 'translateX(0)', 'important');
            });
        }
    }, {
        key: 'showMenu',
        value: function showMenu() {
            document.getElementById('leftColumn').removeEventListener('transitionend', this.closeMenuListener);
            document.getElementById('opcompList').style.setProperty('padding-left', null);
            document.getElementById('leftColumn').style.setProperty('transform', 'translateX(0)', 'important');

            document.getElementById('closelbtn').style.setProperty('transform', null, 'important');
        }
    }]);

    return ProgressView;
}(React.Component);

var CompetenceDomainView = function (_React$Component2) {
    _inherits(CompetenceDomainView, _React$Component2);

    function CompetenceDomainView(props) {
        _classCallCheck(this, CompetenceDomainView);

        var _this3 = _possibleConstructorReturn(this, (CompetenceDomainView.__proto__ || Object.getPrototypeOf(CompetenceDomainView)).call(this, props));

        _this3.competenceDomainProgress = getCompDomainProgress(_this3.props.competenceDomainDatas);
        return _this3;
    }

    _createClass(CompetenceDomainView, [{
        key: 'render',
        value: function render() {
            var _this4 = this;

            if (this.props.mobiledisplay) {
                return React.createElement(
                    'div',
                    { className: 'compdom', onClick: function onClick() {
                            _this4.props.onClick(_this4.props.competenceDomainDatas.operationalCompetences);
                        } },
                    React.createElement(
                        'span',
                        { className: 'competenceDomainSymbol' },
                        this.props.competenceDomainSymbol
                    ),
                    React.createElement(
                        'span',
                        { className: 'competenceDomainName' },
                        this.props.competenceDomainName
                    ),
                    React.createElement(Progressbar, { colors: ['#6ca77f', '#AE9B70', '#d9af47', '#D9918D'],
                        elements: this.competenceDomainProgress,
                        timeToRefresh: '10', elementToGroup: 1, disabled: false
                    })
                );
            } else return React.createElement(
                'span',
                { style: { display: 'contents' } },
                React.createElement(
                    'td',
                    { className: 'competenceDomainSymbol', colSpan: 1 },
                    this.props.competenceDomainSymbol
                ),
                React.createElement(
                    'td',
                    { className: 'competenceDomainName', colSpan: 3 },
                    React.createElement(
                        'div',
                        { style: {
                                display: 'flex',
                                flexDirection: 'column',
                                justifyContent: 'space-between',
                                height: '100%'
                            } },
                        this.props.competenceDomainName,
                        React.createElement(Progressbar, { colors: ['#6ca77f', '#AE9B70', '#d9af47', '#D9918D'],
                            elements: this.competenceDomainProgress,
                            timeToRefresh: '10', elementToGroup: 1, disabled: false
                        })
                    )
                )
            );
        }
    }]);

    return CompetenceDomainView;
}(React.Component);

var OperationalCompetenceView = function (_React$Component3) {
    _inherits(OperationalCompetenceView, _React$Component3);

    function OperationalCompetenceView(props) {
        _classCallCheck(this, OperationalCompetenceView);

        var _this5 = _possibleConstructorReturn(this, (OperationalCompetenceView.__proto__ || Object.getPrototypeOf(OperationalCompetenceView)).call(this, props));

        _this5.operationalCompetenceProgress = getOpCompProgress(props.operationalCompetenceDatas);
        return _this5;
    }

    _createClass(OperationalCompetenceView, [{
        key: 'render',
        value: function render() {
            if (this.props.mobiledisplay) {
                return React.createElement(
                    'div',
                    { className: 'operationalCompetence' },
                    React.createElement(
                        'span',
                        { className: 'operationalCompetenceSymbol' },
                        this.props.operationalCompetenceSymbol
                    ),
                    React.createElement(
                        'span',
                        { className: 'operationalCompetenceName' },
                        this.props.operationalCompetenceName
                    ),
                    React.createElement(Progressbar, { colors: ['#6ca77f', '#AE9B70', '#d9af47', '#D9918D'],
                        elements: this.operationalCompetenceProgress,
                        timeToRefresh: '10', elementToGroup: 1, disabled: false,
                        key: this.props.operationalCompetenceDatas.id
                    })
                );
            } else {
                return React.createElement(
                    'span',
                    { style: { display: 'contents' } },
                    React.createElement(
                        'td',
                        { className: 'operationalCompetenceSymbol', colSpan: 1 },
                        this.props.operationalCompetenceSymbol
                    ),
                    React.createElement(
                        'td',
                        { className: 'operationalCompetenceName', colSpan: 2 },
                        React.createElement(
                            'div',
                            {
                                style: { display: 'flex', flexDirection: 'column', justifyContent: 'space-between', height: '100%' } },
                            this.props.operationalCompetenceName,
                            React.createElement(Progressbar, { colors: ['#6ca77f', '#AE9B70', '#d9af47', '#D9918D'],
                                elements: this.operationalCompetenceProgress,
                                timeToRefresh: '10', elementToGroup: 1, disabled: false,
                                key: this.props.operationalCompetenceDatas.id
                            })
                        )
                    )
                );
            }
        }
    }]);

    return OperationalCompetenceView;
}(React.Component);

var TableRow = function (_React$Component4) {
    _inherits(TableRow, _React$Component4);

    function TableRow(props) {
        _classCallCheck(this, TableRow);

        var _this6 = _possibleConstructorReturn(this, (TableRow.__proto__ || Object.getPrototypeOf(TableRow)).call(this, props));

        _this6.state = {
            operationnalCompetences: []
        };
        return _this6;
    }

    _createClass(TableRow, [{
        key: 'render',
        value: function render() {
            var _this7 = this;

            return React.createElement(
                'tr',
                null,
                React.createElement(CompetenceDomainView, { mobiledisplay: this.props.mobiledisplay,
                    competenceDomainSymbol: this.props.coursePlanRows.competenceDomainSymbol,
                    competenceDomainName: this.props.coursePlanRows.competenceDomainName,
                    competenceDomainDatas: this.props.coursePlanRows.competenceDomain }),
                this.props.coursePlanRows.operationnalCompetences.forEach(function (operationalCompetence) {
                    _this7.state.operationnalCompetences.push(React.createElement(OperationalCompetenceView, {
                        mobiledisplay: _this7.props.mobiledisplay,
                        operationalCompetenceSymbol: operationalCompetence.symbol,
                        operationalCompetenceName: operationalCompetence.name,
                        operationalCompetenceDatas: operationalCompetence.operationnalCompetence }));
                }),
                this.state.operationnalCompetences
            );
        }
    }]);

    return TableRow;
}(React.Component);

var CompetenceDomainAccordion = function (_React$Component5) {
    _inherits(CompetenceDomainAccordion, _React$Component5);

    function CompetenceDomainAccordion(props) {
        _classCallCheck(this, CompetenceDomainAccordion);

        return _possibleConstructorReturn(this, (CompetenceDomainAccordion.__proto__ || Object.getPrototypeOf(CompetenceDomainAccordion)).call(this, props));
    }

    _createClass(CompetenceDomainAccordion, [{
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                null,
                React.createElement(
                    'div',
                    { className: 'compdomContainer' },
                    React.createElement(
                        'span',
                        { className: 'compdomSymbol text-primary' },
                        this.props.competenceDomain.symbol
                    ),
                    React.createElement(
                        'p',
                        null,
                        this.props.competenceDomain.name
                    )
                ),
                React.createElement(
                    'span',
                    { style: { width: '100%', display: 'flex', justifyContent: 'center', paddingLeft: '3rem', paddingRight: '3rem' } },
                    React.createElement(Progressbar, { colors: ['#6ca77f', '#AE9B70', '#d9af47', '#D9918D'],
                        elements: getCompDomainProgress(this.props.competenceDomain),
                        timeToRefresh: '10', elementToGroup: 1, disabled: false
                    })
                )
            );
        }
    }]);

    return CompetenceDomainAccordion;
}(React.Component);

var OperationalCompetenceAccordion = function (_React$Component6) {
    _inherits(OperationalCompetenceAccordion, _React$Component6);

    function OperationalCompetenceAccordion(props) {
        _classCallCheck(this, OperationalCompetenceAccordion);

        return _possibleConstructorReturn(this, (OperationalCompetenceAccordion.__proto__ || Object.getPrototypeOf(OperationalCompetenceAccordion)).call(this, props));
    }

    _createClass(OperationalCompetenceAccordion, [{
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { className: 'opcompContainer' },
                React.createElement(
                    'a',
                    { className: 'opcompSymbol text-secondary', href: this.props.baseUrl + ('/' + this.props.userCourseId + '?operationalCompetenceId=' + this.props.operationnalCompetence.operationnalCompetence.id) },
                    this.props.operationnalCompetence.symbol
                ),
                React.createElement(
                    'p',
                    { style: { paddingLeft: '2rem', paddingRight: '2rem' } },
                    this.props.operationnalCompetence.name,
                    React.createElement(Progressbar, { colors: ['#6ca77f', '#AE9B70', '#d9af47', '#D9918D'],
                        elements: getOpCompProgress(this.props.operationnalCompetence.operationnalCompetence),
                        timeToRefresh: '10', elementToGroup: 1, disabled: false
                    })
                )
            );
        }
    }]);

    return OperationalCompetenceAccordion;
}(React.Component);

var Accordion = function (_React$Component7) {
    _inherits(Accordion, _React$Component7);

    function Accordion(props) {
        _classCallCheck(this, Accordion);

        var _this10 = _possibleConstructorReturn(this, (Accordion.__proto__ || Object.getPrototypeOf(Accordion)).call(this, props));

        _this10.operationnalCompetencesAccordion = [];

        _this10.openAccordion = _this10.openAccordion.bind(_this10);
        props.datas.operationnalCompetences.forEach(function (operationalCompetence) {
            _this10.operationnalCompetencesAccordion.push(React.createElement(OperationalCompetenceAccordion, { operationnalCompetence: operationalCompetence, baseUrl: _this10.props.baseUrl, userCourseId: _this10.props.userCourseId }));
        });

        return _this10;
    }

    _createClass(Accordion, [{
        key: 'openAccordion',
        value: function openAccordion(event) {
            event = event.currentTarget;
            if (event.classList.contains('bi-arrow-down-square')) {
                event.classList.remove('bi-arrow-down-square');
                event.classList.add('bi-arrow-up-square');
            } else {
                event.classList.remove('bi-arrow-up-square');
                event.classList.add('bi-arrow-down-square');
            }

            event.parentElement.nextElementSibling.classList.toggle('ac-hidden');
        }
    }, {
        key: 'render',
        value: function render() {
            var _this11 = this;

            return React.createElement(
                'div',
                { className: 'accordionContainer' },
                React.createElement(
                    'header',
                    { className: 'compdomContainerHeader bg-primary text-white' },
                    React.createElement(
                        'b',
                        null,
                        'Domaine de competence'
                    )
                ),
                React.createElement(CompetenceDomainAccordion, { competenceDomain: this.props.datas.competenceDomain }),
                React.createElement(
                    'div',
                    { className: 'opcompContainerList' },
                    React.createElement(
                        'header',
                        { className: 'opcompContainerHeader bg-secondary text-white' },
                        React.createElement(
                            'b',
                            null,
                            'Competences operationnelles '
                        ),
                        React.createElement('i', { className: 'bi bi-arrow-down-square openLogo', onClick: function onClick(e) {
                                return _this11.openAccordion(e);
                            } })
                    ),
                    React.createElement(
                        'div',
                        { className: 'opcompList ac-hidden' },
                        this.operationnalCompetencesAccordion
                    )
                )
            );
        }
    }]);

    return Accordion;
}(React.Component);