@extends('layouts.home')

@section('content')

<!-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="translate-document-main">
        <div class="container custom-con">
          <div class="row">
            <div class="col-md-12 text-center">
              <h2 class="translate-heading mb-36">Translate Your Document</h2>
            </div>

            <div class="col-md-12">
              <div class="drag_main">
                    <form class="">
                      <div class="drop_content text-center" id="demo-upload" action="/upload">
                        <div class="language_div">
                            <form method="get" >
                            <div class="choose_title">
                              <h2>Choose <span style="color: #6B6AEC;">From</span> Language</h2>
                                <div class="group">
                                 <select class="lang_span">
                                   <option>Spanish</option>
                                   <option>Spanish</option>
                                   <option>Spanish</option>
                                   <option>Spanish</option>
                                   <option>Spanish</option>
                                   <option>Spanish</option>
                                 </select>
                                </div>
                          </div>
                            <div class="choose_title">
                               <h2>Choose <span style="color: #6B6AEC;">To</span> Language</h2>
                                <div class="group">
                                 <select class="lang_span">
                                   <option>English</option>
                                   <option>Spanish</option>
                                   <option>Spanish</option>
                                   <option>Spanish</option>
                                   <option>Spanish</option>
                                   <option>Spanish</option>
                                 </select>
                                </div>
                            </div>
                            <button type="button" onclick="location.href='translate.html';" class="btn_tran_directory_next">Next</button>
                        </form>
                        </div>
                      </div>
                    </form>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- end translate your document section -->

    <!-- start how to convert your document section -->
      <div class="document_convert_main">
        <div class="container custom-con">
          <div class="row">
          <div class="col-md-12 text-center">
              <h2 class="translate-heading translate_2">How To Convert our Document</h2>
          </div>
         
          <div class="convert_sub">
            <div class="row">
            <div class="col-lg-3 col-md-6">
              <div class="conver-process m-auto">
                <img src="{!! asset('assets/images/document/document1.png') !!}" class="img-fluid">
                <p class="docum_details">1. To begin, drag and drop 
                  your file in the converter.</p>
              </div>
          </div>

            <div class="col-lg-3 col-md-6">
              <div class="conver-process m-auto">
                <img src="{!! asset('assets/images/document/document2.png') !!}" class="img-fluid">
                <p class="docum_details">2. Translate the file to your preferred language</p>
              </div>
          </div>

            <div class="col-lg-3 col-md-6">
              <div class="conver-process m-auto">
                <img src="{!! asset('assets/images/document/document3.png') !!}" class="img-fluid">
                <p class="docum_details">3. Credit required to translation process</p>
              </div>
          </div>

            <div class="col-lg-3 col-md-6">
              <div class="conver-process m-auto">
                <img src="{!! asset('assets/images/document/document4.png') !!}" class="img-fluid">
                <p class="docum_details">4. Click the download button to save your new file.</p>
              </div>
           </div>
            </div>
            </div>
          </div>
        </div>
      </div>
@endsection
