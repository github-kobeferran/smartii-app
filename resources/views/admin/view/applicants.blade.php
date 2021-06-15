
<div class="row no-gutters vh-100">
    <div class="col-5 border-right">

        <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input id="applicant-search" type="text" class="form-control" placeholder="Search by Name">
        </div>    
        
        <div id="applicant-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">                               
    

        </div>
        
    </div>

    

    <div class="col-7">        

        <div id="ripple" class="text-center align-middle d-none">

            <div class="lds-ripple">
                <div></div>
                <div></div>
            </div>

        </div>

   
        <div id="appFilesPanel" class="row no-gutters mw-25 mh-25">                                                               

            <h5 class="mx-auto mt-5">Select an Applicant</h5>

        </div>
        
        <div id="appDataPanel" class="row no-gutters mt-3 ">

                  

        </div>

    </div>


   


</div>

<script>

let applicantList = document.getElementById('applicant-list');
let appFilesPanel = document.getElementById('appFilesPanel');
let appDataPanel = document.getElementById('appDataPanel');

let idPic = document.getElementById("id-pic");
let birthCert = document.getElementById("birt-cert");
let goodMoral = document.getElementById("good-moral");
let reportCard = document.getElementById("report-card");


function fillApplicantList(id = null){

    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/applicants', true);

    xhr.onload = function() {
        if (this.status == 200) {
            
            let applicants = JSON.parse(this.responseText);

            output = `<div id="applicant-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group ">`;

            for(i in applicants){


                if(id != null && id == applicants[i].id){
                    output += '<button id="app-'+ applicants[i].id +'" type="button" onclick="applicantSelect(\'app-'+ applicants[i].id +'\', ' + applicants[i].id + ')" class=" app-button list-group-item list-group-item-action flex-column align-items-start active">';
                    output +='<div class="d-flex w-100 jusstify-content-between">';
                        output +='<h6 style="font-family: \'Raleway\', sans-serif; font-weight: 900px;" class="mb-1">'+ ucfirst(applicants[i].last_name) + ', ' + ucfirst(applicants[i].first_name) + ' ' + ucfirst(applicants[i].middle_name) + '</h6>';

                        if(applicants[i].resubmitted != null && applicants[i].resubmitted != '0000' && applicants[i].resubmitted != 'undefined'){
                            
                            let count = 0;
                            for(let j=0; j<applicants[i].resubmitted.length; j++){
                                if(applicants[i].resubmitted[j] == '1')
                                    ++count;
                            }

                            output +='<span class="badge badge-primary badge-pill">'+ count +'</span>'
                        }
                        
                        output +='<small class="pr-2">'+ applicants[i].days_ago +'</small>';
                    output += '</div>'
                    output += '<p class="mb-1">'+ applicants[i].dept_desc + '</p>'
                    output += '<p class="mb-1">'+ applicants[i].prog_desc +'</p>'                    
                    output+='</button>';

                    applicantSelect('app-'+ id, id, true);

                } else{

                    output += '<button id="app-'+ applicants[i].id +'" type="button" onclick="applicantSelect(\'app-'+ applicants[i].id +'\', ' + applicants[i].id + ')" class=" app-button list-group-item list-group-item-action flex-column align-items-start">';
                    output +='<div class="d-flex w-100 jusstify-content-between">';
                        output +='<h6 style="font-family: \'Raleway\', sans-serif; font-weight: 900px;" class="mb-1 ">'+ ucfirst(applicants[i].last_name) + ', ' + ucfirst(applicants[i].first_name) + ' ' + ucfirst(applicants[i].middle_name) + '</h6>';

                        if(applicants[i].resubmitted != null && applicants[i].resubmitted != '0000' && applicants[i].resubmitted != 'undefined'){
                            
                            let count = 0;
                            for(let j=0; j<applicants[i].resubmitted.length; j++){
                                if(applicants[i].resubmitted[j] == '1')
                                    ++count;
                            }

                            output +='<span class="badge badge-primary badge-pill">'+ count +'</span>'
                        }
                        
                        
                        output +='<small class="pr-2">'+ applicants[i].days_ago +'</small>';
                    output += '</div>'
                    output += '<p class="mb-1">'+ applicants[i].dept_desc +'</p>'
                    output += '<p class="mb-1">'+ applicants[i].prog_desc +'</p>'                    
                    output+='</button>';

                }

                    
                
            }            

            output +='</div>';
            
            
            applicantList.innerHTML = output;

        } else {
            applicantList.innerHTML = "<h5>Huh, No applicant </h5>";
        }
                
    }

    xhr.send();

}

function applicantSelect(btnId, id, isdefault = false ){

    let buttons = document.getElementsByClassName('app-button');

    let btn = null;

    if(!isdefault){

        btn = document.getElementById(btnId);

        for(i=0; i<buttons.length; i++){
            buttons[i].classList.remove('active');           
        }  

        btn.classList.add('active'); 

    }                 
    
    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/applicants/' + id, true);
    
    document.getElementById('ripple').className="text-center align-middle";
    appFilesPanel.innerHTML = '';
    appDataPanel.innerHTML = '';


    xhr.onload = function() {
        if (this.status == 200) {
            
    let applicant = JSON.parse(this.responseText);

        let idpicResub = false;
        let birthcertResub = false;
        let goodmoralResub = false;
        let reportcardResub = false;
        let resubmitted = false;
          
        if(applicant.resubmit_file != undefined && applicant.resubmit_file != null){

            if(applicant.resubmit_file[0] == '1')
                idpicResub = true;

            if(applicant.resubmit_file[1] == '1')
                birthcertResub = true;

            if(applicant.resubmit_file[2] == '1')
                goodmoralResub = true;

            if(applicant.resubmit_file[3] == '1')
                reportcardResub = true;

        
        }

        if(applicant.resubmitted != undefined && applicant.resubmitted != null){
            resubmitted = true;
        }

        
            

        

    output=`
   
    
            <div class="col">
        
                <div class="bg-light text-white text-center p-2">

                    <button type="button" class="border-0" data-toggle="modal" data-target="#idpic-modal" >
                        <img id="id-pic" class="img-thumbnail float-none w-25 h-25" src="{{url('/storage/images/applicants/id_pics/`+ applicant.id_pic +`')}}"  >`;

                        if(idpicResub)
                            output+=`<span class="resubmit" data-toggle="tooltip" data-placement="right" title="Still waiting for resubmission" ><i class="fa fa-hourglass-half" aria-hidden="true"></i> </span>`;
                        else if(resubmitted){

                            if(applicant.resubmitted[0] == '1')
                                output+=`<span class="resubmit" data-toggle="tooltip" data-placement="right" title="Applicant Resubmitted, check it!" ><i class="fa fa-check" aria-hidden="true"></i> </span>`;

                        }

                output+=`</button>
                                                
                </div>

                <div class="text-center">
                    <h5>ID Picture</h5>
                
                </div>

                {{--  --}}

                <div class="bg-light text-white text-center p-2">

                    <button type="button" class="border-0" data-toggle="modal" data-target="#birthcert-modal">
                        <img id="birth-cert" class="img-thumbnail float-none w-25 h-25" src="{{url('/storage/images/applicants/birth_certs/`+ applicant.birth_cert +`')}}"  >`;
                        
                        if(birthcertResub)
                            output+=`<span class="resubmit" data-toggle="tooltip" data-placement="right" title="Still waiting for resubmission" ><i class="fa fa-hourglass-half" aria-hidden="true"></i></span>`;
                            else if(resubmitted){

                            if(applicant.resubmitted[1] == '1')
                                output+=`<span class="resubmit" data-toggle="tooltip" data-placement="right" title="Applicant Resubmitted, check it!" ><i class="fa fa-check" aria-hidden="true"></i> </span>`;

                        }

                output+=`                        
                    </button>                    
                    

                </div>
                <div class="text-center">
                    <h5>Birth Certificate</h5>                    
                </div>

            
            </div>

            <div class="col justify-content-center">

                <div class="bg-light text-white text-center p-2">

                    <button type="button" class="border-0" data-toggle="modal" data-target="#goodmoral-modal">
                        <img id="good-moral" class="img-thumbnail float-none w-25 h-25" src="{{url('/storage/images/applicants/good_morals/`+ applicant.good_moral +`')}}"  >`;
                    
                    if(goodmoralResub)
                        output+=`<span class="resubmit" data-toggle="tooltip" data-placement="right" title="Still waiting for resubmission" ><i class="fa fa-hourglass-half" aria-hidden="true"></i> </span>`;
                        else if(resubmitted){

                            if(applicant.resubmitted[2] == '1')
                                output+=`<span class="resubmit" data-toggle="tooltip" data-placement="right" title="Applicant Resubmitted, check it!" ><i class="fa fa-check" aria-hidden="true"></i> </span>`;

                        }

                    output+=`    
                        
                    </button>   
                    
                    
                </div>
                <div class="text-center">
                    <h5>Good Moral</h5>
                
                </div>

        


                <div class="bg-light text-white text-center p-2">

                    <button type="button" class="border-0" data-toggle="modal" data-target="#reportcard-modal">
                        <img id="report-card" class="img-thumbnail float-none w-25 h-25" src="{{url('/storage/images/applicants/report_cards/`+ applicant.report_card +`')}}"  >`;
                    
                    if(reportcardResub)
                        output+=`<span class="resubmit" data-toggle="tooltip" data-placement="right" title="Still waiting for resubmission" ><i class="fa fa-hourglass-half" aria-hidden="true"></i> </span>`;
                    else if(resubmitted){

                        if(applicant.resubmitted[3] == '1')
                            output+=`<span class="resubmit" data-toggle="tooltip" data-placement="right" title="Applicant Resubmitted, check it!" ><i class="fa fa-check" aria-hidden="true"></i> </span>`;

                    }

                output+=`    
                        
                    </button>   
                    
                </div>

                <div class="text-center">
                    <h5>Report Card</h5>                
                </div>

            </div>

           


            {{------------------------------------------------------------- MODALS --}}

            {!! Form::open(['url' => 'admin/requestupload']) !!}

            <div class="modal fade " id="idpic-modal" tabindex="-1" role="dialog" aria-labelledby="idpic-modal-title" aria-hidden="true">

                <div class="modal-dialog modal-full" role="document">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="idpic-modal-title">ID picture of `+ ucfirst(applicant.first_name) + ' ' +  ucfirst(applicant.last_name) +`</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        
                        <div class="modal-body">
                            <div class="container-fluid text-center">
                                <img id="id-pic" class="img-fluid" src="{{url('/storage/images/applicants/id_pics/`+ applicant.id_pic +`')}}"  >
                                
                            </div>
                        </div>

                        <input type="hidden" name="req_type" value="idpic">
                        <input type="hidden" name="app_id" value="`+ applicant.id +`">
                        
                                 
                        <div class="modal-footer">
                            <a href="download/idpic/`+ applicant.id_pic +`"  class="btn btn-warning text-dark">Download</a>                            
                            <button type="submit" class="btn btn-info text-white">Request Resubmission</button>                                                 
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>    
                        </div>
                        
                    </div>
                </div>
            </div>

            {!! Form::close() !!}


            {!! Form::open(['url' => 'admin/requestupload']) !!}
            <div class="modal fade" id="birthcert-modal" tabindex="-1" role="dialog" aria-labelledby="birthcert-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-full" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="birthcert-modal-title">Birth Certificate of `+ ucfirst(applicant.first_name) + ' ' +  ucfirst(applicant.last_name) +`</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>


                        
                        <div class="modal-body text-center">
                            <img id="birth-cert" class="img-fluid" src="{{url('/storage/images/applicants/birth_certs/`+ applicant.birth_cert +`')}}"  >    
                        </button>   

                        <input type="hidden" name="req_type" value="birthcert">
                        <input type="hidden" name="app_id" value="`+ applicant.id +`">

                        </div>
                        <div class="modal-footer">
                                <a href="download/birthcert/`+ applicant.birth_cert +`"  class="btn btn-warning text-dark">Download</a>
                                <button type="submit" class="btn btn-info text-white">Request Resubmission</button>                                             
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>   
                        </div>

                    </div>
                </div>
            </div>
            {!! Form::close() !!}

            {!! Form::open(['url' => 'admin/requestupload']) !!}
            <div class="modal fade" id="goodmoral-modal" tabindex="-1" role="dialog" aria-labelledby="goodmoral-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-full" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="goodmoral-modal-title">Good Moral Certificate of `+ ucfirst(applicant.first_name) + ' ' +  ucfirst(applicant.last_name) +`</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>


                        <div class="modal-body text-center">
                            <img id="good-moral" class="img-fluid" src="{{url('/storage/images/applicants/good_morals/`+ applicant.good_moral +`')}}"  >    
                        </div>

                        <input type="hidden" name="req_type" value="goodmoral">
                        <input type="hidden" name="app_id" value="`+ applicant.id +`">

                        
                        <div class="modal-footer">
                                <a href="download/goodmoral/`+ applicant.good_moral +`"  class="btn btn-warning text-dark">Download</a>
                                <button type="submit" class="btn btn-info text-white">Request Resubmission</button>                                             
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>   
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

            {!! Form::open(['url' => 'admin/requestupload']) !!}
            <div class="modal fade" id="reportcard-modal" tabindex="-1" role="dialog" aria-labelledby="reportcard-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-full" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="reportcard-modal-title">Report Card of `+ ucfirst(applicant.first_name) + ' ' +  ucfirst(applicant.last_name) +`</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>


                        <div class="modal-body text-center">
                            <img id="report-card" class="img-fluid" src="{{url('/storage/images/applicants/report_cards/`+ applicant.report_card +`')}}"  >    
                        </div>

                        <input type="hidden" name="req_type" value="reportcard">
                        <input type="hidden" name="app_id" value="`+ applicant.id +`">


                        <div class="modal-footer">
                                <a href="download/reportcard/`+ applicant.report_card +`"  class="btn btn-warning text-dark">Download</a>
                                <button type="submit" class="btn btn-info text-white">Request Resubmission</button>                                             
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>   
                        </div>
                    </div>
                </div>        
            </div>
            {!! Form::close() !!}

       


        {{------------------------------------------------------------- END OF MODALS --}}`;            


        output2 = ` 
        

                    <div class="card mx-auto w-75" >
                        <div class="card-header">
                            <h5 class="text-center">PERSONAL DATA </h5>                            
                        </div>
                        {!! Form::open(['url' => 'admin/approveapplicant/', 'id' => 'approveApplicantForm']) !!}
                        <ul class="list-group list-group-flush ">
                            <li class="list-group-item">Last Name: <strong>`+ ucfirst(applicant.last_name)  + `</strong></li>                            
                            <li class="list-group-item">First Name: <strong>`+ ucfirst(applicant.first_name)  + `</strong></li>                            
                            <li class="list-group-item">Middle Name: <strong>`+ ucfirst(applicant.middle_name)  + `</strong></li>                            
                            <li class="list-group-item">Age: <strong>`+ applicant.age + ' years ' + `</strong></li>                            
                            <li class="list-group-item">Gender: <strong>`+ ucfirst(applicant.gender) + `</strong></li>                            
                            <li class="list-group-item">Living in: <strong>`+ applicant.present_address + `</strong></li>                            
                            <li class="list-group-item">Previous School: <strong>`+ applicant.last_school + `</strong></li>                                                        
                            {{ Form::hidden('app_id','`+ applicant.id  + `') }}
                            <li class="list-group-item"><button type="submit" class="btn btn-success btn-block">Approve</button> </li>
                        {!! Form::close() !!}
                        </ul>
                    </div>

        
                    `;

            document.getElementById('ripple').className="text-center align-middle d-none";
            
            appFilesPanel.innerHTML = output;
            appDataPanel.innerHTML = output2;

        } else {
            appFilesPanel.innerHTML = "<h5>Huh, No Data </h5>";
            appDataPanel.innerHTML = "<h5>Huh, No Data </h5>";
        }
    }

    xhr.send();
    
}


</script>