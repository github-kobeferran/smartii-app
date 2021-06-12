<div class="row no-gutters vh-100">
    <div class="col-5 border-right">

        <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control" placeholder="Search by Name">
        </div>    
        
        <div id="applicant-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group">                               
    

        </div>
        
    </div>

    <div class="col-7 ">

        <div class="row no-gutters">
            
            <div class="col ">

                <div class="bg-secondary text-white text-center">

                    <img src="{{url('/storage/applicants/id_pics/noimage.jpg')}}" alt="..." class="img-thumbnail float-none ">    
                    ID Picture
                </div>
                <div class="bg-secondary text-white text-center">

                    <img src="{{url('/storage/applicants/id_pics/noimage.jpg')}}" alt="..." class="img-thumbnail float-none ">    
                    Birth Certiicate
                </div>
                
                   
            </div>

            <div class="col justify-content-center">
                
                <div class="bg-secondary text-white text-center">

                    <img src="{{url('/storage/applicants/id_pics/noimage.jpg')}}" alt="..." class="img-thumbnail float-none ">    
                    Good Moral
                </div>
                <div class="bg-secondary text-white text-center">

                    <img src="{{url('/storage/applicants/id_pics/noimage.jpg')}}" alt="..." class="img-thumbnail float-none ">    
                    Report Card
                </div>
                
            </div>

        </div>
   

    </div>


</div>

<script>

let applicantList = document.getElementById('applicant-list');

function fillApplicantList(){

    let xhr = new XMLHttpRequest();
    xhr.open('GET', APP_URL + '/admin/view/applicants', true);

    xhr.onload = function() {
        if (this.status == 200) {
            
            let applicants = JSON.parse(this.responseText);

            output = `<div id="applicant-list" style="max-height: 100vh; margin-bottom: 10px; overflow:auto; -webkit-overflow-scrolling: touch;" class="list-group ">`;

            for(i in applicants){

                output += '<button id="app-'+ applicants[i].id +'" type="button" onclick="applicantSelect(document.getElementById(\'app-'+ applicants[i].id +'\'))" class=" app-button list-group-item list-group-item-action flex-column align-items-start">';
                    output +='<div class="d-flex w-100 jusstify-content-between">';
                        output +='<h6 style="font-family: \'Raleway\', sans-serif; font-weight: 900px;" class="mb-1">'+ ucfirst(applicants[i].last_name) + ', ' + ucfirst(applicants[i].first_name) + ' ' + ucfirst(applicants[i].middle_name) + '</h6>';
                        output +='<small>'+ applicants[i].days_ago +'</small>';
                    output += '</div>'
                    output += '<p class="mb-1">'+ applicants[i].dept_desc +'</p>'
                    output += '<p class="mb-1">'+ applicants[i].prog_desc +'</p>'                    
                    output+='</button>';
                
            }            

            output +='</div>';
            
            
            applicantList.innerHTML = output;

        } else {
            applicantList.innerHTML = "<h5>Huh, No applicants.. </h5>";
        }
    }

    xhr.send();

}

function applicantSelect(btn){

    let buttons = document.getElementsByClassName('app-button');

    for(i=0; i<buttons.length; i++){
        buttons[i].classList.remove('active');   
    }    

    btn.classList.add('active');
    
}

function ucfirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

</script>