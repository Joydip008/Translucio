@extends('layouts.home')
@section('title')
TRANSLICIO | Help&Feq
@endsection
@section('add-meta')
<style type="text/css">
  .t-red{
     font-size: 80%;
     color: #dc3545;
  }
</style>
@endsection

@section('content')

  <!-- start copy secktion design -->
  <div class="start-help-faq">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
        <div class="help-sub">
          <h2 class="question">Questions? Look Here </h2>
          <p class="contact_p">Can't find an answer? Call us at (855) 692-5326 or email contact@transluc.io!</p>
        </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end copy section design -->
  <!-- start faq tab section -->
  <div class="faq-tab-main">
    <div class="container">
      <div class="row">
        <div class="col-md-3 mb-3">
          <div class="tab_left">
            <h3>Table of Contents</h3>
            <ul class="nav nav-pills flex-column custom_tab_list" id="myTab" role="tablist">
              <li class="nav-item"> <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><span><img src="assets/images/icons/general.png" class="img-responsive"></span>General</a>
              </li>
              <li class="nav-item"> <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><span><img src="assets/images/icons/safty.png" class="img-responsive"></span>Trust & Safety</a>
              </li>
              <li class="nav-item"> <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"><span><img src="assets/images/icons/payment.png" class="img-responsive"></span>Payment</a>
              </li>
              <li class="nav-item"> <a class="nav-link" id="usage-tab" data-toggle="tab" href="#usage" role="tab" aria-controls="contact" aria-selected="false"><span><img src="assets/images/icons/usage.png" class="img-responsive"></span>Usage</a>
              </li>
              <li class="nav-item"> <a class="nav-link" id="subscription-tab" data-toggle="tab" href="#subscription" role="tab" aria-controls="contact" aria-selected="false"><span><img src="assets/images/icons/subscription.png" class="img-responsive"></span>Subscription</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-9">
          <div class="tab-right">
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="bs-example">
                  <div class="accordion" id="accordionExample">
                    <div class="card">
                      <div class="card-header padding-custom" id="headingOne">
                        <h2 class="mb-0">
                         <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa fa-plus"></i>   I've forgotten my password. What should I do?</button>                  
                </h2>
                      </div>
                      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"><i class="fa fa-plus"></i> How can I change my email address?</button>
                </h2>
                      </div>
                      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"><i class="fa fa-plus"></i> I've forgotten my password. What should I do?</button>                     
                </h2>
                      </div>
                      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                         <div class="card">
                      <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour"><i class="fa fa-plus"></i> How can I add accounting information to my invoices?</button>                     
                </h2>
                      </div>
                      <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  <div class="card">
                      <div class="card-header" id="headingFive">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive"><i class="fa fa-plus"></i> How can I change the customer reference on my invoices?</button>                     
                </h2>
                      </div>
                      <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="card">
                      <div class="card-header" id="headingSix">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix"><i class="fa fa-plus"></i> What is the purpose of the VAT ID?</button>                     
                </h2>
                      </div>
                      <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                      <div class="card">
                      <div class="card-header" id="headingSeven">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven"><i class="fa fa-plus"></i>How can I change my payment details?</button>                     
                </h2>
                      </div>
                      <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                  <div class="bs-example">
                  <div class="accordion" id="accordionExample">
                    <div class="card">
                      <div class="card-header padding-custom" id="headingOne">
                        <h2 class="mb-0">
                         <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa fa-plus"></i>   I've forgotten my password. What should I do?</button>                  
                </h2>
                      </div>
                      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"><i class="fa fa-plus"></i> How can I change my email address?</button>
                </h2>
                      </div>
                      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"><i class="fa fa-plus"></i> I've forgotten my password. What should I do?</button>                     
                </h2>
                      </div>
                      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                         <div class="card">
                      <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour"><i class="fa fa-plus"></i> How can I add accounting information to my invoices?</button>                     
                </h2>
                      </div>
                      <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  <div class="card">
                      <div class="card-header" id="headingFive">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive"><i class="fa fa-plus"></i> How can I change the customer reference on my invoices?</button>                     
                </h2>
                      </div>
                      <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="card">
                      <div class="card-header" id="headingSix">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix"><i class="fa fa-plus"></i> What is the purpose of the VAT ID?</button>                     
                </h2>
                      </div>
                      <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                      <div class="card">
                      <div class="card-header" id="headingSeven">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven"><i class="fa fa-plus"></i>How can I change my payment details?</button>                     
                </h2>
                      </div>
                      <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>

              </div>
              <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                     <div class="bs-example">
                  <div class="accordion" id="accordionExample">
                    <div class="card">
                      <div class="card-header padding-custom" id="headingOne">
                        <h2 class="mb-0">
                         <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa fa-plus"></i>   I've forgotten my password. What should I do?</button>                  
                </h2>
                      </div>
                      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"><i class="fa fa-plus"></i> How can I change my email address?</button>
                </h2>
                      </div>
                      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"><i class="fa fa-plus"></i> I've forgotten my password. What should I do?</button>                     
                </h2>
                      </div>
                      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                         <div class="card">
                      <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour"><i class="fa fa-plus"></i> How can I add accounting information to my invoices?</button>                     
                </h2>
                      </div>
                      <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  <div class="card">
                      <div class="card-header" id="headingFive">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive"><i class="fa fa-plus"></i> How can I change the customer reference on my invoices?</button>                     
                </h2>
                      </div>
                      <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="card">
                      <div class="card-header" id="headingSix">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix"><i class="fa fa-plus"></i> What is the purpose of the VAT ID?</button>                     
                </h2>
                      </div>
                      <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                      <div class="card">
                      <div class="card-header" id="headingSeven">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven"><i class="fa fa-plus"></i>How can I change my payment details?</button>                     
                </h2>
                      </div>
                      <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="usage" role="tabpanel" aria-labelledby="usage-tab">
                  <div class="bs-example">
                  <div class="accordion" id="accordionExample">
                    <div class="card">
                      <div class="card-header padding-custom" id="headingOne">
                        <h2 class="mb-0">
                         <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa fa-plus"></i>   I've forgotten my password. What should I do?</button>                  
                </h2>
                      </div>
                      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"><i class="fa fa-plus"></i> How can I change my email address?</button>
                </h2>
                      </div>
                      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"><i class="fa fa-plus"></i> I've forgotten my password. What should I do?</button>                     
                </h2>
                      </div>
                      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                         <div class="card">
                      <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour"><i class="fa fa-plus"></i> How can I add accounting information to my invoices?</button>                     
                </h2>
                      </div>
                      <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  <div class="card">
                      <div class="card-header" id="headingFive">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive"><i class="fa fa-plus"></i> How can I change the customer reference on my invoices?</button>                     
                </h2>
                      </div>
                      <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="card">
                      <div class="card-header" id="headingSix">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix"><i class="fa fa-plus"></i> What is the purpose of the VAT ID?</button>                     
                </h2>
                      </div>
                      <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                      <div class="card">
                      <div class="card-header" id="headingSeven">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven"><i class="fa fa-plus"></i>How can I change my payment details?</button>                     
                </h2>
                      </div>
                      <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="subscription" role="tabpanel" aria-labelledby="subscription-tab">
                 <div class="bs-example">
                  <div class="accordion" id="accordionExample">
                    <div class="card">
                      <div class="card-header padding-custom" id="headingOne">
                        <h2 class="mb-0">
                         <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><i class="fa fa-plus"></i>   I've forgotten my password. What should I do?</button>                  
                </h2>
                      </div>
                      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"><i class="fa fa-plus"></i> How can I change my email address?</button>
                </h2>
                      </div>
                      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card">
                      <div class="card-header" id="headingThree">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"><i class="fa fa-plus"></i> I've forgotten my password. What should I do?</button>                     
                </h2>
                      </div>
                      <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                         <div class="card">
                      <div class="card-header" id="headingFour">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour"><i class="fa fa-plus"></i> How can I add accounting information to my invoices?</button>                     
                </h2>
                      </div>
                      <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  <div class="card">
                      <div class="card-header" id="headingFive">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive"><i class="fa fa-plus"></i> How can I change the customer reference on my invoices?</button>                     
                </h2>
                      </div>
                      <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>


                    <div class="card">
                      <div class="card-header" id="headingSix">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix"><i class="fa fa-plus"></i> What is the purpose of the VAT ID?</button>                     
                </h2>
                      </div>
                      <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                      <div class="card">
                      <div class="card-header" id="headingSeven">
                        <h2 class="mb-0">
                    <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven"><i class="fa fa-plus"></i>How can I change my payment details?</button>                     
                </h2>
                      </div>
                      <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                        <div class="card-body">
                             <div class="card_inside_content"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, 
                            when an unknown printer took a galley of type and scrambled it to make a type 
                            specimen book. It has survived not only five centuries, but also the leap into electronic 
                            typesetting, remaining essentially unchanged.
                          </p>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end faq tab section -->

@endsection