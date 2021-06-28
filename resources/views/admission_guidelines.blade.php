@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row mt-3">

        <div>

            <h2 style="font-family: 'Cinzel', serif; background-color:#05551b; color: white;" id="reqs">FILE REQUIREMENTS GUIDELINES</h2>

        </div>              

    </div>

    <div class="row">

        <div>
            <hr class="footer-line-1">
            <h4 id="idpic">ID PICTURE</h4>

                <ul>
                    <li>
                        1 x 1 inch, colored photo with white background
                    </li>
                    <li>
                        Formal pose with collar and no eyeglasses or any accessories that may cover the facial features
                    </li>
                    <li>
                        Taken in the past seven (7) days prior to filing of online application
                    </li>                

                </ul>


        </div>       

    </div>


    <div class="row">

        <div>
            <hr class="footer-line-1">
            <h4 id="docs">DOCUMENTS (Birth Certificate/Good Moral Certificate/Report Card</h4>
            <ul>
                <li>
                    Your ID photo must be saved as a <strong> JPEG </strong>file format (.jpg or .jpeg)
                </li>
                <li>
                    File size must not be more than 300 kilobytes
                </li>

                <li>
                    You must save your photo in your computer or USB device/drive
                </li>                

                <li>
                    Photo must be clear
                </li>                

            </ul>

        </div>
    </div>

    <div class="row">

        <div>
            <hr class="footer-line-1">
            <h4 id="docs">Here are some helpful articles on how to crop/resize/save your photo into a JPEG file:</h4>
            <ul>
                <li>
                    Using Paint in <strong>Windows XP/7/8</strong>: How to <a href="https://www.wikihow.tech/Crop-an-Image-with-Microsoft-Paint">crop</a> / <a href="https://www.wikihow.com/Resize-an-Image-in-Microsoft-Paint">resize</a> / <a href="https://www.wikihow.com/Convert-BMP-to-JPEG-Using-Microsoft-Paint">save</a>
                </li>
                <li>
                    Using <strong>Mac OS</strong>: How to <a href="https://machicolate.wordpress.com/2012/01/04/crop-an-image-using-preview/">crop</a> / <a href="https://www.wikihow.com/Resize-Pictures-(for-Macs)">resize</a> / <a href="https://www.wikihow.com/Convert-Pictures-to-JPEG-or-Other-Picture-File-Extensions">save</a>
                </li>                              

            </ul>

        </div>
    </div>


    <hr class="footer-line-1 mt-5">
    <div id="shs" class="row mt-2 border border-warning">

        <div class="">

            <h5>SENIOR HIGH SCHOOL ADMISSION GUIDELINES</h5>

            <ol>
                <li>
                    Register <strong> <a href="/register">here</a> </strong>

                </li>
                <li>

                    Fill up the Application Form                    

                </li>
                <li>

                    Senior High School Students are required to submit/upload scanned 1x1 ID picture, NSO-Birth Certificate, Good Moral Certificate and Grade 10 Report Card, take a look at file requirements guidelines here<a href="#reqs">here</a>

                </li>
                <li>

                    Wait for the Admission Officer to analyze your application. You will be notified in your email.

                </li>


            </ol>


        </div>


    </div>

    <hr class="footer-line-1 mt-5">



    <div id="college" class="row border border-success">

        <div class="">

            <h5>COLLEGE ADMISSION GUIDELINES</h5>

            <ol>
                <li>
                    Register <strong> <a href="/register">here</a> </strong>

                </li>
                <li>

                    Fill up the Application Form

                </li>
                <li>

                    College Students are required to submit/upload scanned 1x1 ID picture, NSO-Birth Certificate, Good Moral Certificate and Grade 12 Report Card, take a look at file requirements guidelines here<a href="#reqs">here</a>

                </li>
                <li>

                    Wait for the Admission Officer to analyze your application. You will be notified in your email.

                </li>


            </ol>


        </div>

       
        

    </div>

    <hr class="footer-line-1 mt-5">

    <div id="transferee" class="row ">

        <div>

            <h5>TRANSFEREES</h5>

        <ol>

            <li>

             Take a look at file requirements <a href="#reqs">here</a>

            </li>
            <li>

                <a href="/contactus">Contact</a> Admin for manual admission. 

            </li>


        </ol>

        </div>
        
    </div>

    <hr class="footer-line-1 mt-5">

    <div>

        reference: <a target="_blank" href="https://www.pup.edu.ph/iapply/photoguidelines">https://www.pup.edu.ph/iapply/photoguidelines</a>

    </div>
    
</div>
@endsection
