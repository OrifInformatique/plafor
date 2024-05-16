class ProgressView extends React.Component {
    coursePlanProgress=[];
    accordion=[];
    openMenuListener;
    closeMenuListener;
    tableRows = [];
    competenceDomains=[];
    operationalCompetences=[];
    listbtn=React.createRef();
    refreshComponent=(e) => {
        if (window.innerWidth > 950) {
            this.setState({
                mobiledisplay: false
            });

        } else {
            this.setState({
                mobiledisplay: true
            });
        }
    }

    constructor(props) {
        super(props);
        this.state = {
            mobiledisplay: window.innerWidth < 950,
            page:1
        }
        this.hideMenu=this.hideMenu.bind(this);
        this.showMenu=this.showMenu.bind(this);
        //init courseplanProgress On construct
        Object.values(props.coursePlan.competenceDomains).forEach((competenceDomain) => {
            let coursePlanProgress = new Object();
            coursePlanProgress.competenceDomain = competenceDomain;
            coursePlanProgress.competenceDomainSymbol = competenceDomain.symbol;
            coursePlanProgress.competenceDomainName = competenceDomain.name;
            Object.values(competenceDomain.operationalCompetences).forEach((operationalCompetence) => {
                let opComp = new Object();
                opComp.operationnalCompetence = operationalCompetence;
                opComp.symbol = operationalCompetence.symbol;
                opComp.name = operationalCompetence.name;
                coursePlanProgress.operationnalCompetences !== undefined ? coursePlanProgress.operationnalCompetences.push(opComp) : coursePlanProgress.operationnalCompetences = [opComp];
            })
            this.coursePlanProgress.push(coursePlanProgress);

        })
    }

    componentDidMount() {
        window.innerWidth > 950 ?
            this.setState({
                mobiledisplay: false
            })
            :
            this.setState({
                mobiledisplay: true
            });

        window.addEventListener('resize',this.refreshComponent)
    }
    componentWillUnmount(){
        window.removeEventListener('resize',this.refreshComponent)
    }

    render() {
        this.competenceDomains=[];
        let intermediateList=[];
        this.tableRows=[];
        if (this.state.mobiledisplay &&!this.props.integrated) {
            return (
                <div id={'detailsFrame'}>
                    <div id="opcompList">
                        <div id="leftColumn">
                            <span id={'listbtn'} ref={this.listbtn} onClick={(e)=>this.hideMenu()}><i className={'bi bi-list'}></i></span>
                            {Object.values(this.props.coursePlan.competenceDomains).forEach((competenceDomain) => {
                                this.competenceDomains.push(<CompetenceDomainView mobiledisplay={true} competenceDomainDatas={competenceDomain} competenceDomainName={competenceDomain.name} competenceDomainSymbol={competenceDomain.symbol} onClick={(opcomps)=>{
                                    this.hideMenu();
                                    this.setState({page:competenceDomain.id});
                                }
                                }/>);
                                intermediateList=[];
                                Object.values(competenceDomain.operationalCompetences).forEach((operationalCompetence)=>{
                                    intermediateList.push(<OperationalCompetenceView key={operationalCompetence.id} operationalCompetenceSymbol={operationalCompetence.symbol} operationalCompetenceName={operationalCompetence.name} operationalCompetenceDatas={operationalCompetence} mobiledisplay={true} baseUrl={this.props.baseUrl}/>)
                                })
                                this.operationalCompetences[competenceDomain.id]=intermediateList;
                            })}
                            {this.competenceDomains}




                            </div>
                        <span id={'closelbtn'} onClick={(e)=>this.showMenu()}><i className={'bi bi-list'}></i></span>
                        {this.operationalCompetences[this.state.page]}
                        <i className={'bi bi-x-circle-fill'} id={'exitdetails'} onClick={() => {
                            this.props.callback();
                        }}></i>
                    </div>
                </div>
            );
        } else if(!this.state.mobiledisplay &&!this.props.integrated) {
            return (

                <div id={'detailsFrame'}>
                    <div style={{display: 'flex', justifyContent: 'center', width: '90%'}}>

                        <table id={'courseplandetails'} className={"table"}
                               style={{tableLayout: 'fixed', width: '100%'}}>
                            <thead className={'table-dark bg-primary'}>
                            <tr>
                                <th style={{width: '13%'}}>competence domain</th>
                                <th colSpan={21}>operational competences</th>
                                <i className={'bi bi-x-circle-fill'} id={'exitdetails'} onClick={() => {
                                    this.props.callback();
                                }}></i>

                            </tr>
                            </thead>
                            <tbody>
                            {Object.values(this.props.coursePlan.competenceDomains).forEach((competenceDomain) => {

                                let row = new Object();
                                row.competenceDomain = competenceDomain;
                                row.competenceDomainSymbol = competenceDomain.symbol;
                                row.competenceDomainName = competenceDomain.name;
                                Object.values(competenceDomain.operationalCompetences).forEach((operationalCompetence) => {
                                    let opComp = new Object();
                                    opComp.operationnalCompetence = operationalCompetence;
                                    opComp.symbol = operationalCompetence.symbol;
                                    opComp.name = operationalCompetence.name;
                                    row.operationnalCompetences !== undefined ? row.operationnalCompetences.push(opComp) : row.operationnalCompetences = [opComp];
                                })
                                this.tableRows.push(<TableRow mobiledisplay={this.state.mobiledisplay} coursePlanRows={row}/>);

                            })
                            }
                            {this.tableRows}


                            </tbody>

                        </table>
                    </div>
                </div>


            );
        }
        //when integrated props is true
        else{
            const accordions=[]
            return (
                <div style={{display:'contents'}}>
                    {this.coursePlanProgress.map((progress)=>{
                        accordions.push(
                            //here comes tag to modify display
                            <Accordion datas={progress} baseUrl={this.props.baseUrl} userCourseId={this.props.userCourseId}/>
                        )
                    })}
                    {accordions}
                </div>



            );
        }
    }
    hideMenu(){
        document.getElementById('leftColumn').style.transform='translateX(-100%)';
        document.getElementById('opcompList').style.setProperty('padding-left','0','important')
        document.getElementById('leftColumn').addEventListener('transitionend',this.closeMenuListener=(e)=>{
            document.getElementById('closelbtn').style.setProperty('transform','translateX(0)','important')


        })
    }
    showMenu(){
        document.getElementById('leftColumn').removeEventListener('transitionend',this.closeMenuListener);
        document.getElementById('opcompList').style.setProperty('padding-left',null)
        document.getElementById('leftColumn').style.setProperty('transform','translateX(0)','important')

        document.getElementById('closelbtn').style.setProperty('transform',null,'important');
    }

}


class CompetenceDomainView extends React.Component {
    competenceDomainProgress;

    constructor(props) {
        super(props);
        this.competenceDomainProgress = getCompDomainProgress(this.props.competenceDomainDatas);
    }

    render() {
        if (this.props.mobiledisplay){
            return(
                <div className="compdom" onClick={()=>{
                    this.props.onClick(this.props.competenceDomainDatas.operationalCompetences)
                }}>
                    <span className="competenceDomainSymbol">{this.props.competenceDomainSymbol}</span>
                    <span className="competenceDomainName">{this.props.competenceDomainName}</span>
                    <Progressbar colors={['#6ca77f', '#AE9B70', '#d9af47', '#D9918D']}
                                 elements={this.competenceDomainProgress}
                                 timeToRefresh="10" elementToGroup={1} disabled={false}
                    />
                </div>
            )
        }
        else
            return (
                <span style={{display: 'contents'}}>
                <td className="competenceDomainSymbol" colSpan={1}>{this.props.competenceDomainSymbol}</td>
                <td className="competenceDomainName" colSpan={3}><div style={{
                    display: 'flex',
                    flexDirection: 'column',
                    justifyContent: 'space-between',
                    height: '100%'
                }}>{this.props.competenceDomainName}
                    <Progressbar colors={['#6ca77f', '#AE9B70', '#d9af47', '#D9918D']}
                                 elements={this.competenceDomainProgress}
                                 timeToRefresh="10" elementToGroup={1} disabled={false}
                    />
                </div></td>
                </span>
            );
    }

}
class OperationalCompetenceView extends React.Component {
    operationalCompetenceProgress;

    constructor(props) {
        super(props);
        this.operationalCompetenceProgress = getOpCompProgress(props.operationalCompetenceDatas);
    }

    render() {
        console.log(this.props.operationalCompetenceDatas)
        if (this.props.mobiledisplay) {
            return (
                <div className="operationalCompetence">
                    <span className="operationalCompetenceSymbol">{this.props.operationalCompetenceSymbol}</span>
                    <span className="operationalCompetenceName">{this.props.operationalCompetenceName}</span>
                    <Progressbar colors={['#6ca77f', '#AE9B70', '#d9af47', '#D9918D']}
                                 elements={this.operationalCompetenceProgress}
                                 timeToRefresh="10" elementToGroup={1} disabled={false}
                                 key={this.props.operationalCompetenceDatas.id}
                    />
                </div>
            )
        } else {
            return (
                <span style={{display: 'contents'}}>
            <td className="operationalCompetenceSymbol" colSpan={1}>{this.props.operationalCompetenceSymbol}</td>
            <td className="operationalCompetenceName" colSpan={2}>
                <div
                    style={{display: 'flex', flexDirection: 'column', justifyContent: 'space-between', height: '100%'}}>
                    {this.props.operationalCompetenceName}
                    <Progressbar colors={['#6ca77f', '#AE9B70', '#d9af47', '#D9918D']}
                                 elements={this.operationalCompetenceProgress}
                                 timeToRefresh="10" elementToGroup={1} disabled={false}
                                 key={this.props.operationalCompetenceDatas.id}
                    />
                </div></td>
            </span>
            )
        }
    }
}
class TableRow extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            operationnalCompetences: []
        }
    }

    render() {
        return (<tr>

                <CompetenceDomainView mobiledisplay={this.props.mobiledisplay}
                                      competenceDomainSymbol={this.props.coursePlanRows.competenceDomainSymbol}
                                      competenceDomainName={this.props.coursePlanRows.competenceDomainName}
                                      competenceDomainDatas={this.props.coursePlanRows.competenceDomain}/>
                {this.props.coursePlanRows.operationnalCompetences.forEach((operationalCompetence) => {
                    this.state.operationnalCompetences.push(<OperationalCompetenceView
                        mobiledisplay={this.props.mobiledisplay}
                        operationalCompetenceSymbol={operationalCompetence.symbol}
                        operationalCompetenceName={operationalCompetence.name}
                        operationalCompetenceDatas={operationalCompetence.operationnalCompetence}/>);

                })}

                {this.state.operationnalCompetences}

            </tr>
        )

    }

}
class CompetenceDomainAccordion extends React.Component {
    constructor(props) {
        super(props);
    }
    render(){
        return(
            <div>
            <div className="compdomContainer">

                    <span className="compdomSymbol text-primary">
                        {this.props.competenceDomain.symbol}
                    </span>
                <p>{this.props.competenceDomain.name}
                </p>
            </div>
                <span style={{width:'100%',display:'flex',justifyContent:'center',paddingLeft:'3rem',paddingRight:'3rem'}}>
                <Progressbar colors={['#6ca77f', '#AE9B70', '#d9af47', '#D9918D']}
                             elements={getCompDomainProgress(this.props.competenceDomain)}
                             timeToRefresh="10" elementToGroup={1} disabled={false}
                />
                </span>
            </div>


        )




}
}
class OperationalCompetenceAccordion extends React.Component{
    constructor(props) {
        super(props);
    }
    render(){
        return(
            <div className="opcompContainer">
                <a className="opcompSymbol text-secondary" href={this.props.baseUrl+`/${this.props.userCourseId}?operationalCompetenceId=${this.props.operationnalCompetence.operationnalCompetence.id}`}>
                    {this.props.operationnalCompetence.symbol}
                </a>
                <p style={{paddingLeft:'2rem',paddingRight:'2rem'}}>
                    {this.props.operationnalCompetence.name}
                    <Progressbar colors={['#6ca77f', '#AE9B70', '#d9af47', '#D9918D']}
                                 elements={getOpCompProgress(this.props.operationnalCompetence.operationnalCompetence)}
                                 timeToRefresh="10" elementToGroup={1} disabled={false}
                    />
                </p>

            </div>
        );
    }
}
class Accordion extends React.Component{
    operationnalCompetencesAccordion=[];
    constructor(props) {
        super(props);
        this.openAccordion=this.openAccordion.bind(this);
        props.datas.operationnalCompetences.forEach((operationalCompetence)=>{
            this.operationnalCompetencesAccordion.push(<OperationalCompetenceAccordion operationnalCompetence={operationalCompetence} baseUrl={this.props.baseUrl} userCourseId={this.props.userCourseId}/>);
        })

    }
    openAccordion(event){
        event=event.currentTarget;
        if (event.classList.contains('bi-arrow-down-square')){
            event.classList.remove('bi-arrow-down-square');
            event.classList.add('bi-arrow-up-square')
        }
        else{
            event.classList.remove('bi-arrow-up-square')
            event.classList.add('bi-arrow-down-square');
        }

        event.parentElement.nextElementSibling.classList.toggle('ac-hidden');

    }
    render(){
        return(
        <div className="accordionContainer">
            <header className="compdomContainerHeader bg-primary text-white"><b>Domaine de competence</b></header>
                <CompetenceDomainAccordion competenceDomain={this.props.datas.competenceDomain}></CompetenceDomainAccordion>
            <div className="opcompContainerList">
                <header className="opcompContainerHeader bg-secondary text-white"><b>Competences operationnelles </b>
                    <i className="bi bi-arrow-down-square openLogo" onClick={(e)=>this.openAccordion(e)}></i>
                </header>
                <div className="opcompList ac-hidden">
                    {this.operationnalCompetencesAccordion}
                </div>
            </div>
        </div>
        );
    }
}

