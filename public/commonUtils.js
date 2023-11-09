function getCoursePlanProgress(coursePlan){
    const unitCounter=[0,0,0,0];
    const courseplanStatus=coursePlan.fk_acquisition_status;
    Object.values(coursePlan.competenceDomains).forEach((competenceDomain)=>{
        Object.values(competenceDomain.operationalCompetences).forEach((operationalCompetence)=>{
            Object.values(operationalCompetence.objectives).forEach((objective)=>{
                switch (objective.fk_acquisition_level){
                    case '1':
                        unitCounter[3]===0?unitCounter[3]=1:unitCounter[3]++
                        break;
                    case '2':
                        unitCounter[2]===0?unitCounter[2]=1:unitCounter[2]++
                        break;
                    case '3':
                        unitCounter[1]===0?unitCounter[1]=1:unitCounter[1]++
                        break;
                    default:
                        unitCounter[0]===0?unitCounter[0]=1:unitCounter[0]++

                }
            })
        })

    });
    let coursePlanStats={
        status:courseplanStatus,
        progress:unitCounter
    };
    return coursePlanStats;
}
function getCompDomainProgress(competenceDomain){
    const unitCounter=[0,0,0,0];

    Object.values(competenceDomain.operationalCompetences).forEach((operationalCompetence)=>{
        Object.values(operationalCompetence.objectives).forEach((objective)=>{
            switch (objective.fk_acquisition_level){
                case '1':
                    unitCounter[3]===0?unitCounter[3]=1:unitCounter[3]++
                    break;
                case '2':
                    unitCounter[2]===0?unitCounter[2]=1:unitCounter[2]++
                    break;
                case '3':
                    unitCounter[1]===0?unitCounter[1]=1:unitCounter[1]++
                    break;
                default:
                    unitCounter[0]===0?unitCounter[0]=1:unitCounter[0]++

            }
        })
})
    return unitCounter;
}
function getOpCompProgress(operationalCompetence){
    const unitCounter=[0,0,0,0];
    Object.values(operationalCompetence.objectives).forEach((objective)=>{
        switch (objective.fk_acquisition_level){
            case '1':
                unitCounter[3]===0?unitCounter[3]=1:unitCounter[3]++
                break;
            case '2':
                unitCounter[2]===0?unitCounter[2]=1:unitCounter[2]++
                break;
            case '3':
                unitCounter[1]===0?unitCounter[1]=1:unitCounter[1]++
                break;
            default:
                unitCounter[0]===0?unitCounter[0]=1:unitCounter[0]++

        }
    })
    return unitCounter;
}
async function initProgress(getCourseplanProgressUrl,detailsLang) {
    $(document).ready(async () => {
        //execute jquery code under
        const nodeList = document.querySelectorAll('.progressContainer');
        //for each elements
        nodeList.forEach((node) => {
            $.get(getCourseplanProgressUrl + node.getAttribute('apprentice_id')+'/'+(node.getAttribute('course_plan_id')!=null?node.getAttribute('course_plan_id'):''), function () {

            }).done((response) => {
                //response received is json format
                let coursePlans = Object.values(response);
                coursePlans.forEach((coursePlan) => {
                    const coursePlanStats = getCoursePlanProgress(coursePlan)
                    //in the case of multiple coursePlans
                    let div = document.createElement('div');
                    node.appendChild(div);
                    ReactDOM.render(<div><Progressbar colors={['#6ca77f', '#AE9B70', '#d9af47', '#D9918D']}
                                                      elements={coursePlanStats.progress}
                                                      timeToRefresh="10" elementToGroup={3}
                                                      disabled={coursePlan.archive!==null}
                    />
                        {
                            coursePlanStats.status <= 2 ?
                                <button style={{marginLeft: '5px'}} onClick={(e) => {
                                    displayDetails(coursePlan);
                                }} className="btn btn-secondary">{detailsLang}</button>
                                : null
                        }</div>, div);

                })


                //render progressbar for each apprentice


                //count all objectives by acquisition status

            })
            //use ~5% of items for group

        })
    });
}
async function displayDetails(coursePlan,userCourse=null,integrated=false,getCourseplanProgressUrl=null,baseUrl){
    const detailsPanel=document.createElement('div');
    detailsPanel.id='details';
    if(integrated&&coursePlan==null){
        const node=document.getElementById('detailsArray');
        await $.get(getCourseplanProgressUrl + node.getAttribute('apprentice_id')+'/'+(node.getAttribute('course_plan_id')!=null?node.getAttribute('course_plan_id'):''), function () {}).done((response)=>{
            coursePlan=(response[0]);
        })

    }
    else{
        document.body.append(detailsPanel);
    }
    ReactDOM.render(<ProgressView coursePlan={coursePlan} callback={closeDetails} integrated={integrated} baseUrl={baseUrl} userCourseId={userCourse!=null?userCourse.id:null} key={Math.random()}></ProgressView>,integrated===false?detailsPanel:document.getElementById('detailsArray'));


}
function closeDetails(){
    ReactDOM.unmountComponentAtNode(document.getElementById('details'));
    document.body.removeChild(document.getElementById('details'));
}