class Progressbar extends React.Component{
    constructor(props) {
        super(props);
        this.displayProgress=this.displayProgress.bind(this);
        this.removeProgress=this.removeProgress.bind(this);

        this.progressContainer=React.createRef()
    }
    componentDidMount(){
        this.displayProgress();


    }
    render(){
    return (
            <div id="progressContainer" className={this.props.disabled?'disabled':null }ref={this.progressContainer}>
            </div>
    )
}
    /**
     * this function is called when need to load progressBar
     * @param props
     */

    displayProgress() {
        //Get container for progressbar
        this.progressContainer.current.style.animation = '';
        //set value of container to empty
        this.progressContainer.current.innerHTML = '';



        let total = 0;
        //element color is a map indexed by color
        let elementColor = new Map();
        //add total number of objectives to total variable
        this.props.colors.forEach((color, idx) => {
            total += parseInt(this.props.elements[idx]);
            //if there is many element with same color add to number
            if (elementColor.get(color) != null) {
                elementColor.set(color, elementColor.get(color) + parseInt(this.props.elements[idx]));
            } else {
                elementColor.set(color, parseInt(this.props.elements[idx]));
            }
        });
        //width to use for one element
        const percentUnit = Math.round(100 / total * 100)/100;
        // array containing dom node
        let elementArray = [];
        const elementToGroup=this.props.elementToGroup!=null?this.props.elementToGroup:10;
        elementColor.forEach((number, color) => {
            let i = 0;
            for (let i = 0; i < number; i++) {
                let node = document.createElement('div');
                node.style.backgroundColor = (color.toString());
                node.classList.add('positionedElement');
                node.style.getPropertyValue('width')==""?node.style.setProperty('width',percentUnit+'%','important'):''
                if (node.isEqualNode(elementArray[elementArray.length-1])&&(i+1)%elementToGroup===0){
                    node.style.setProperty('width',(elementToGroup*percentUnit)+'%','important')
                    for (let nbr=0;nbr<elementToGroup-1;nbr++){
                        elementArray.pop();
                    }
                }
                elementArray.push(node);
            }
        });

        let i = 0;
        const interval = setInterval((e) => {
            if (i <= elementArray.length) {
                //i===elementArray.length-1?elementArray[i-1].style.setProperty('width',parseFloat(percentUnit) +'%','important'):i-1>=0?elementArray[i-1].style.setProperty('width',parseFloat(percentUnit)  + '%','important'):'';

                i===elementArray.length-1?elementArray[i!==0?i-1:0].style.setProperty('transform','translate(0)','important'):i-1>=0?elementArray[i!==0?i-1:0].style.setProperty('transform','translate(0)','important'):'';

                try {
                    this.progressContainer.current.appendChild(elementArray[i]);
                }catch (e){
                    i++;
                }
                i++;
            }
            else{
                clearInterval(interval);
                let testedColor;
                const arraywidth=new Map();
                for (let i=0;i<this.progressContainer.current.childNodes.length;i++){
                    const child=this.progressContainer.current.childNodes[i];
                    //When the child has the same bkcolor as last element, increase width
                    if (child.style.backgroundColor==testedColor){
                        arraywidth.set(testedColor,arraywidth.get(testedColor)+Math.round(parseFloat(child.style.width)*10)/10)
                    }
                    //else add element to arraywidth
                    else{
                        testedColor=child.style.backgroundColor;
                        arraywidth.set(testedColor,Math.round(parseFloat(child.style.width)*10)/10);
                    }
                    testedColor=child.style.backgroundColor;

                }
                this.progressContainer.current.innerHTML='';
                arraywidth.forEach((size,color)=>{
                    const node=document.createElement('div');
                    node.classList.add('positionedElement');
                    node.style.backgroundColor=color;

                    node.style.setProperty('width',(size+1)+'%','important');

                    node.style.setProperty('transform','translate(0)','important');
                    this.progressContainer.current.appendChild(node);
                })


            }
        }, this.props.timeToRefresh!=null?this.props.timeToRefresh:10);

    }
    /**
     * this function is called when need to reset progressBar
     * @param props
     */
    removeProgress(){
        let nodeList=document.querySelectorAll('.positionedElement');
        let i=nodeList.length-1;
        const interval=setInterval(()=>{
            if (i>=0){
                nodeList.item(i).style='';
                nodeList.item(i).style.transition='all 1s';
            }
            else{
                clearInterval(interval);
            }
            i--;
        },this.props.timeToRefresh!=null?this.props.timeToRefresh:10)
    }
}
