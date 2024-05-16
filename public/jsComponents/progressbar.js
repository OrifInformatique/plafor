var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Progressbar = function (_React$Component) {
    _inherits(Progressbar, _React$Component);

    function Progressbar(props) {
        _classCallCheck(this, Progressbar);

        var _this = _possibleConstructorReturn(this, (Progressbar.__proto__ || Object.getPrototypeOf(Progressbar)).call(this, props));

        _this.displayProgress = _this.displayProgress.bind(_this);
        _this.removeProgress = _this.removeProgress.bind(_this);

        _this.progressContainer = React.createRef();
        return _this;
    }

    _createClass(Progressbar, [{
        key: 'componentDidMount',
        value: function componentDidMount() {
            this.displayProgress();
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement('div', { id: 'progressContainer', className: this.props.disabled ? 'disabled' : null, ref: this.progressContainer });
        }
        /**
         * this function is called when need to load progressBar
         * @param props
         */

    }, {
        key: 'displayProgress',
        value: function displayProgress() {
            var _this2 = this;

            //Get container for progressbar
            this.progressContainer.current.style.animation = '';
            //set value of container to empty
            this.progressContainer.current.innerHTML = '';

            var total = 0;
            //element color is a map indexed by color
            var elementColor = new Map();
            //add total number of objectives to total variable
            this.props.colors.forEach(function (color, idx) {
                total += parseInt(_this2.props.elements[idx]);
                //if there is many element with same color add to number
                if (elementColor.get(color) != null) {
                    elementColor.set(color, elementColor.get(color) + parseInt(_this2.props.elements[idx]));
                } else {
                    elementColor.set(color, parseInt(_this2.props.elements[idx]));
                }
            });
            //width to use for one element
            var percentUnit = Math.round(100 / total * 100) / 100;
            // array containing dom node
            var elementArray = [];
            var elementToGroup = this.props.elementToGroup != null ? this.props.elementToGroup : 10;
            elementColor.forEach(function (number, color) {
                var i = 0;
                for (var _i = 0; _i < number; _i++) {
                    var node = document.createElement('div');
                    node.style.backgroundColor = color.toString();
                    node.classList.add('positionedElement');
                    node.style.getPropertyValue('width') == "" ? node.style.setProperty('width', percentUnit + '%', 'important') : '';
                    if (node.isEqualNode(elementArray[elementArray.length - 1]) && (_i + 1) % elementToGroup === 0) {
                        node.style.setProperty('width', elementToGroup * percentUnit + '%', 'important');
                        for (var nbr = 0; nbr < elementToGroup - 1; nbr++) {
                            elementArray.pop();
                        }
                    }
                    elementArray.push(node);
                }
            });

            var i = 0;
            var interval = setInterval(function (e) {
                if (i <= elementArray.length) {
                    //i===elementArray.length-1?elementArray[i-1].style.setProperty('width',parseFloat(percentUnit) +'%','important'):i-1>=0?elementArray[i-1].style.setProperty('width',parseFloat(percentUnit)  + '%','important'):'';

                    i === elementArray.length - 1 ? elementArray[i !== 0 ? i - 1 : 0].style.setProperty('transform', 'translate(0)', 'important') : i - 1 >= 0 ? elementArray[i !== 0 ? i - 1 : 0].style.setProperty('transform', 'translate(0)', 'important') : '';

                    try {
                        _this2.progressContainer.current.appendChild(elementArray[i]);
                    } catch (e) {
                        i++;
                    }
                    i++;
                } else {
                    clearInterval(interval);
                    var testedColor = void 0;
                    var arraywidth = new Map();
                    for (var _i2 = 0; _i2 < _this2.progressContainer.current.childNodes.length; _i2++) {
                        var child = _this2.progressContainer.current.childNodes[_i2];
                        //When the child has the same bkcolor as last element, increase width
                        if (child.style.backgroundColor == testedColor) {
                            arraywidth.set(testedColor, arraywidth.get(testedColor) + Math.round(parseFloat(child.style.width) * 10) / 10);
                        }
                        //else add element to arraywidth
                        else {
                                testedColor = child.style.backgroundColor;
                                arraywidth.set(testedColor, Math.round(parseFloat(child.style.width) * 10) / 10);
                            }
                        testedColor = child.style.backgroundColor;
                    }
                    _this2.progressContainer.current.innerHTML = '';
                    arraywidth.forEach(function (size, color) {
                        var node = document.createElement('div');
                        node.classList.add('positionedElement');
                        node.style.backgroundColor = color;

                        node.style.setProperty('width', size + 1 + '%', 'important');

                        node.style.setProperty('transform', 'translate(0)', 'important');
                        _this2.progressContainer.current.appendChild(node);
                    });
                }
            }, this.props.timeToRefresh != null ? this.props.timeToRefresh : 10);
        }
        /**
         * this function is called when need to reset progressBar
         * @param props
         */

    }, {
        key: 'removeProgress',
        value: function removeProgress() {
            var nodeList = document.querySelectorAll('.positionedElement');
            var i = nodeList.length - 1;
            var interval = setInterval(function () {
                if (i >= 0) {
                    nodeList.item(i).style = '';
                    nodeList.item(i).style.transition = 'all 1s';
                } else {
                    clearInterval(interval);
                }
                i--;
            }, this.props.timeToRefresh != null ? this.props.timeToRefresh : 10);
        }
    }]);

    return Progressbar;
}(React.Component);