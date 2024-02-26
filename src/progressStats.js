class ProgressStats extends React.Component{
    element=[];
    constructor(props) {
        super(props);
        this.mainframe=React.createRef();
        this.playanim=this.playanim.bind(this);
        for(let i=0;i<this.props.elements.length;i++){
            this.element.push(<Barelement animatableImagePath={this.props.animatableImagePath} transitionDelay={`${i}s`} backgroundColor={this.props.colors[i]} elements={this.props.elements[i]}></Barelement>)
        }




    }
    render(){
        return (
            <div id={'proressStats'}>
            <div className={'frame'}>
                <main ref={this.mainframe}>
                    {this.element}
                    <input type={'button'} className={'btn btn-primary'} onClick={()=>{this.playanim()}} value={'salut'}/>
                </main>
            </div>
            </div>
        );
    }
    playanim(){
        let i=0
        const interval=setInterval(()=> {
            this.mainframe.current.querySelectorAll('.progress-root')[i]
                .addEventListener('transitionrun', (e) => {
                    e.target.childNodes.forEach((element) => {
                        if (element.tagName === "IMG") {
                            element.style.setProperty('width', '150%', 'important');
                            element.style.setProperty('height', '150%', 'important');
                            element.addEventListener('transitionend', (e) => {
                                e.target.style = '';

                                e.target.style.setProperty('transition', 'all 0.2s', 'important');
                                e.target.style.setProperty('transition-delay', '0.1s', 'important')

                            })


                        }
                    })
                })
            this.mainframe.current.querySelectorAll('.progress-root')[i].style.transform = 'unset'
            //delete interval
            i++;
            if (i===4){
                clearInterval(interval);
            }
        },200);

    }


}
const Meter = ()=>{
    return (
        <div className={'meter'} style={{display:'flex',flexDirection:'column'}}><h5>100</h5><span></span><h5>90</h5><span></span><h5>80</h5><span></span><h5>70</h5><span></span><h5>60</h5><span></span><h5>50</h5><span></span><h5>40</h5><span></span><h5>30</h5><span></span><h5>20</h5><span></span><h5>10</h5><span></span><span style={{opacity:'0'}}></span></div>

    )
}
class Barelement extends React.Component{

    constructor(props){
        super(props);
        this.progressContainer=React.createRef();
        this.intervalArray=[];
        this.state={
            elements:[],
            hlayouts:[]
        }
    }


    componentDidMount(){
            const i=this.intervalArray.length;

            this.intervalArray.push({index:0,
                interval:setInterval(()=>{
                    if (this.intervalArray[i].index>=this.props.elements){
                        if(this.state.elements.length%5!==0)
                            this.setState({hlayouts:[<Hlayout>{this.state.elements.slice(this.state.elements.length-(this.state.elements.length%5))}</Hlayout>].concat(this.state.hlayouts)});

                        clearInterval(this.intervalArray[i].interval);
                    }
                    else{
                        if (this.state.elements.length%5===0 && this.state.elements.length!==0){
                            this.setState({hlayouts:this.state.hlayouts.concat(<Hlayout>{this.state.elements.slice(this.state.elements.length-5)}</Hlayout>)});
                        }
                        this.setState({
                            elements:this.state.elements.concat(<span style={{backgroundColor: this.props.backgroundColor, transitionDelay: this.props.transitionDelay,width:'20%',height:'100%',display:'block',borderRadius:'4px', boxSizing:'border-box',padding:'0.5px',backgroundClip:'content-box'}}></span>)
                        })

                    }
                    this.intervalArray[i].index++;

                },20)});



    }
    render() {
        return (
            <div className={'progress-root iprogress-root'}>
                <Meter></Meter>
                <img src={this.props.animatableImagePath}/>
                <div className={'hbar'} ref={this.progressContainer}>
                    <div style={{display:'flex',flexDirection:'column',justifyContent:'flex-end',width:'100%',height:'100%'}}>
                    {this.state.hlayouts}
                    </div>
                </div>


            </div>
        );
    }
}
const Hlayout=(props)=>{
    return (
        <span style={{width: '100%', height: '5%', display: 'flex', flexDirection: 'row',transition:'all 0.5s'}}>
            {props.children}
        </span>
    )
}