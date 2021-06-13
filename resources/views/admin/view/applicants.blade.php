
<div class="row no-gutters vh-100">
    <div class="col-5 border-right">

        <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control" placeholder="Search by Name">
        </div>    
        
        <div id="applicant-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">                               
    

        </div>
        
    </div>

    <div class="col-7">

        <div id="appFilesPanel" class="row no-gutters">
        
            <h5 class="m-auto">Select an Applicant</h5>

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


function fillApplicantList(){

    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/applicants', true);

    xhr.onload = function() {
        if (this.status == 200) {
            
            let applicants = JSON.parse(this.responseText);

            output = `<div id="applicant-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group ">`;

            for(i in applicants){

                output += '<button id="app-'+ applicants[i].id +'" type="button" onclick="applicantSelect(document.getElementById(\'app-'+ applicants[i].id +'\'), ' + applicants[i].id + ')" class=" app-button list-group-item list-group-item-action flex-column align-items-start">';
                    output +='<div class="d-flex w-100 jusstify-content-between">';
                        output +='<h6 style="font-family: \'Raleway\', sans-serif; font-weight: 900px;" class="mb-1">'+ ucfirst(applicants[i].last_name) + ', ' + ucfirst(applicants[i].first_name) + ' ' + ucfirst(applicants[i].middle_name) + '</h6>';
                        output +='<small class="pr-2">'+ applicants[i].days_ago +'</small>';
                    output += '</div>'
                    output += '<p class="mb-1">'+ applicants[i].dept_desc +'</p>'
                    output += '<p class="mb-1">'+ applicants[i].prog_desc +'</p>'                    
                    output+='</button>';
                
            }            

            output +='</div>';
            
            
            applicantList.innerHTML = output;

        } else {
            applicantList.innerHTML = "<h5>Huh, No applicant </h5>";
        }
    }

    xhr.send();

}


function applicantSelect(btn, id){

    let buttons = document.getElementsByClassName('app-button');

    for(i=0; i<buttons.length; i++){
        buttons[i].classList.remove('active');   
        
    }    

    btn.classList.add('active');    
    
    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/applicants/' + id, true);

    xhr.onload = function() {
        if (this.status == 200) {
            
            let applicant = JSON.parse(this.responseText);



    output=`<div class="col ">

            <div class="bg-light text-white text-center">

                <button type="button" class="border-0" data-toggle="modal" data-target="#idpic-modal" >
                    <img id="id-pic" class="img-thumbnail float-none w-50 " src="{{url('/storage/applicants/id_pics/`+ applicant.id_pic +`')}}"  >    
                </button>
                                                
            </div>

            <div class="text-center">
                <h5>ID Picture</h5>
            
            </div>

            {{--  --}}

            <div class="bg-light text-white text-center">

                <button type="button" class="border-0" data-toggle="modal" data-target="#birthcert-modal">
                    <img id="birth-cert" class="img-thumbnail float-none w-50" src="{{url('/storage/applicants/birth_certs/`+ applicant.birth_cert +`')}}"  >    
                </button>                    
                

            </div>
            <div class="text-center">
                <h5>Birth Certificate</h5>                    
            </div>

            
            </div>

            <div class="col justify-content-center">

                <div class="bg-light text-white text-center">

                    <button type="button" class="border-0" data-toggle="modal" data-target="#goodmoral-modal">
                        <img id="good-moral" class="img-thumbnail float-none w-50" src="{{url('/storage/applicants/good_morals/`+ applicant.good_moral +`')}}"  >    
                    </button>   
                    
                </div>
                <div class="text-center">
                    <h5>Good Moral</h5>
                
                </div>

        


                <div class="bg-light text-white text-center">

                    <button type="button" class="border-0" data-toggle="modal" data-target="#reportcard-modal">
                        <img id="report-card" class="img-thumbnail float-none w-50" src="{{url('/storage/applicants/report_cards/`+ applicant.report_card +`')}}"  >    
                    </button>   
                    
                </div>

                <div class="text-center">
                    <h5>Report Card</h5>                
                </div>

            </div>

           


            {{------------------------------------------------------------- MODALS --}}

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
                                <img id="good-moral" class="img-fluid" src="{{url('/storage/applicants/id_pics/`+ applicant.id_pic +`')}}"  >                            
                            </div>
                        </div>



                        <div class="modal-footer">
                            <a href="download/idpic/`+ applicant.id_pic +`"  class="btn btn-warning text-dark">Download</a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                        
                    </div>
                </div>
            </div>

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
                    <img id="good-moral" class="img-fluid" src="{{url('/storage/applicants/birth_certs/`+ applicant.birth_cert +`')}}"  >    
                </button>   



                </div>
                <div class="modal-footer">
                    <a href="download/birthcert/`+ applicant.birth_cert +`"  class="btn btn-warning text-dark">Download</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            </div>
            </div>

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
                    <img id="good-moral" class="img-fluid" src="{{url('/storage/applicants/good_morals/`+ applicant.good_moral +`')}}"  >    
                </div>

                
                <div class="modal-footer">
                    <a href="download/goodmoral/`+ applicant.good_moral +`"  class="btn btn-warning text-dark">Download</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            </div>
            </div>

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
                    <img id="good-moral" class="img-fluid" src="{{url('/storage/applicants/report_cards/`+ applicant.report_card +`')}}"  >    
                </div>


                <div class="modal-footer">
                    <a href="download/reportcard/`+ applicant.report_card +`"  class="btn btn-warning text-dark">Download</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            </div>
            
        </div>

       


        {{------------------------------------------------------------- END OF MODALS --}}`;            


        output2 = ` <div class="card mx-auto w-75" >
                        <div class="card-header">
                            <h5 class="text-center">PERSONAL DATA</h5>
                        </div>
                        <ul class="list-group list-group-flush text-right">
                            <li class="list-group-item">Last Name: <strong>`+ ucfirst(applicant.last_name)  + `</strong></li>
                            <li class="list-group-item">First Name: <strong>`+ ucfirst(applicant.first_name)  + `</strong></li>
                            <li class="list-group-item">Middle Name: <strong>`+ ucfirst(applicant.middle_name)  + `</strong></li>
                            <li class="list-group-item">Age: <strong>`+ applicant.age + ' years ' + `</strong></li>
                            <li class="list-group-item">Gender: <strong>`+ ucfirst(applicant.gender) + `</strong></li>
                            <li class="list-group-item">Living in: <strong>`+ ucfirst(applicant.present_address) + `</strong></li>
                            <li class="list-group-item">Previous School: <strong>`+ ucfirst(applicant.last_school) + `</strong></li>
                        </ul>
                    </div>`;
            
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