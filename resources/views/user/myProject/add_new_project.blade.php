@extends('layouts.home')
@section('title')
TRANSLICIO | My Page
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

<!-- start buy credit secltion -->
<div class="translate-document-main personal-main" id="main">
    <div class="container custom-con">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="translate-heading profile-heading">Add A Project</h2>
          <form action="{{url('/submit-project')}}" method="POST" id="add_language_form">
      @if (Session::has('message'))
      <div class="alert alert-info">{{ Session::get('message') }}</div>
      @endif
      @if (Session::has('messageError'))
      <div class="alert alert-danger">{{ Session::get('messageError') }}</div>
      @endif
          @csrf
            <div class="my-project-main">
              <div class="my-project-frm">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="name">Project Name &nbsp;<i class="fa fa-star star"></i></label>
                      @if(!empty($Project))
                      <input type="hidden" name="id" value="{{$Project['id']}}">
                      <input type="text" class="form-control" id="" name="project_name" value="{{$Project['project_name']}}" required>
                      @else
                      <input type="text" class="form-control" id="" name="project_name"  value="{{ old('project_name') }}" autocomplete="project_name" autofocus required>
                      @endif
                      @if ($errors->has('project_name'))
                      <span class="help-block">
                        <strong class="error-msg mb-2">{{ $errors->first('project_name') }}</strong>
                      </span>
                    @endif
                    </div>
                  
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="name">Website URL &nbsp;<i class="fa fa-star star"></i></label>
                      @if(!empty($Project))
                        <input type="text" class="form-control" id="" placeholder="Eg : www.transluc.io" name="website_url" value="{{$Project['website_url']}}" required >  
                      @else
                        <input type="text" class="form-control" id="" placeholder="Eg : www.transluc.io" name="website_url"  value="{{ old('website_url') }}" autocomplete="website_url" autofocus required >
                      @endif
                      @if ($errors->has('website_url'))
                      <span class="help-block">
                        <strong class="error-msg mb-2">{{ $errors->first('website_url') }}</strong>
                      </span>
                    @endif
                    </div>
                  
                  </div>
                  <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                      <label for="exampleFormControlSelect1">Current Website Language &nbsp;<i class="fa fa-star star"></i></label>
                      <input type="hidden" id="currentLanguage_id">
                      <select class="form-control" id="current_website_language" name="current_website_language" required onchange="CurrentLanguageId(this.value)" value="{{ old('current_website_language') }}">
                      <option value="">Choose a website language</option>
                        @foreach ($LanguagesList as $Language)
                            @if(!empty($Project))
                                <option value="{{ $Language->from_language }}" {{ ($Language->from_language == $Project['current_language_id']) ? 'selected' : '' }}>{{ $Language->name }}</option>
                            @else
                          
                                <option value="{{ $Language->from_language }}" >{{ $Language->name }}</option>
                            @endif
                        @endforeach          
                    </select>
                    @if ($errors->has('current_language_id'))
                      <span class="help-block">
                        <strong class="error-msg mb-2">{{ $errors->first('current_language_id') }}</strong>
                      </span>
                    @endif
                    </div>
                  
                  </div>
                  <div class="col-md-12 col-sm-12">
                    <div class="form-group">
                      <label for="exampleFormControlSelect1">Project Category &nbsp;<i class="fa fa-star star"></i></label>
                      <select class="form-control" id="" name="project_category" required>
                      <option value="">Choose a category</option>
                        @foreach ($ProjectCatagories as $ProjectCategory)
                            @if(!empty($Project))
                                <option value="{{ $ProjectCategory->id }}" {{ ($ProjectCategory->id == $Project['project_category']) ? 'selected' : '' }}>{{ $ProjectCategory->catagories }}</option>
                            @else
                            <option value="{{ $ProjectCategory->id }}" {{ ($ProjectCategory->id == old('project_category')) ? 'selected' : '' }}>{{ $ProjectCategory->catagories }}</option>
                            @endif
                        @endforeach          
                    </select>
                    @if ($errors->has('project_category'))
                      <span class="help-block">
                        <strong class="error-msg mb-2">{{ $errors->first('project_category') }}</strong>
                      </span>
                    @endif
                    </div>
                  
                  </div>
                </div>
              
				<div class="row">
					<div class="col-md-12 col-sm-12">
						 <div class="str_project_main">
						  <!-- <div class="form-group">
							<label for="exampleFormControlSelect1">If string corrections must be used from other projects ?</label>
							<div class="all-select-all-section">
							  <dl class="dropdown"> <dt id="check_service_div">
														<a>
														  <span class="hida">Select Project</span> 
														<i class="material-icons">keyboard_arrow_down</i> 
														  <p class="multiSel"></p>  
														</a>
														</dt>                   
													  <dd>
								  <div class="mutliSelect">
								 
									<ul>


									@if(!empty($ProjectLists))
										@foreach($ProjectLists as $pro)
												<li>
												  <div>
												  @if(!empty($Project))
														<input id="checkbox-{{$pro['id']}}" {{ (in_array($pro->id,$arrayPSC) ? 'checked' : '')}} class="checkbox-custom" name="project_select[]" type="checkbox" value="{{$pro['id']}}" data-name="{{$pro['project_name']}}">
														<label for="checkbox-{{$pro['id']}}" class="checkbox-custom-label">{{$pro['project_name']}}</label>
													@else
													  <input id="checkbox-{{$pro['id']}}" class="checkbox-custom" name="project_select[]" type="checkbox" value="{{$pro['id']}}" data-name="{{$pro['project_name']}}">
													  <label for="checkbox-{{$pro['id']}}" class="checkbox-custom-label">{{$pro['project_name']}}</label>
												  @endif
													</div>
												</li>
										  @endforeach
									  @else
										  
										<li>
											<div>
										  
											<label for="checkbox-1" class="checkbox-custom-label">No Project</label>
										  </div>
										  </li>

									  @endif

									  
									</ul>
								  </div>
								</dd>
							  </dl>
							</div>
						  </div> -->
						</div>
               
			   </div>
					<div class="col-md-12">
                  <div class="switch-section">
                    <p>Metadata Translation</p>
                    <label class="switch">
                      @if($Project['metadata_translation'] == 1)
                      <input type="checkbox" checked name="metadata_translation"> <span class="slider round"></span>
                      @else
                      <input type="checkbox" name="metadata_translation"> <span class="slider round"></span>
                      @endif
                    </label>
                  </div>
                  <!-- <div class="switch-section">
                    <p>Media Translations</p>
                    <label class="switch">
                    @if($Project['media_translation'] == 1)
                      <input type="checkbox" checked name="media_translation"> <span class="slider round"></span>
                    @else
                      <input type="checkbox" name="media_translation"> <span class="slider round"></span>
                    @endif
                    </label>
                  </div> -->
                </div>
				</div>
				
				
				 <h3 class="destination-heading">Destination language &nbsp;<i class="fa fa-star star"></i></h3>
				 
				
				
			      
						<div class="add_lang_aa">
							<label for="exampleFormControlSelect1">Add Language &nbsp;<i class="fa fa-star star"></i></label>
							<div class="form-group">
					  
							<input type="hidden" id="lang_sel_id">
							<input type="hidden" id="dest_lang_id">
							
							<select class="form-control lang_diff" id="Language_Pair_Id" onchange="langChange(this.value,this)">
							<option value="">Choose a language</option>

							@if(!empty($Project))
							  @if(!empty($DestinationProjectLanguage))
							
								@foreach($LanguagePairList as $LanList)

								  <option value="{{ $LanList['to_language'] }}">{{ $LanList['name'] }}</option>

								@endforeach

							  @endif
							@endif

									
							</select> 


							<button type="button" class="add_lang" onclick="return AddLanguage(<?php echo $Language['id']; ?>);" required>Add Language</button>
							@if ($errors->has('Visibility'))
							  <span class="help-block">
								<strong class="error-msg mb-2">The destination language field is required.</strong>
							  </span>
							@endif
						  </div>
						  
						</div>
					  <div class="row">
						<div class="col-md-12" id="add_language" required data-value="{{$Project['current_language_id']}}">
							<div id="IfChangeHide">
							@if(!empty($DestinationProjectLanguage))
							  @foreach($DestinationProjectLanguage as $LanList) 

									<div class="visibility_section" id="{{$LanList['language_id']}}" id="IfChangeHide1">
									  <div class="spanish-line" id="LanguageName">
										{{$LanList['name']}}</div>
									  <div class="lang_vis">
										<p>Language Visibility :</p>
										<div class="form-group mb-0 c-cus">
										  <select class="form-control lang_type" id="visibility_status2" name="Visibility[]">
										  <option value="0" {{ ($LanList->visibility_status == "0") ? 'selected' : '' }}>Public</option>
											<option value="1" {{ ($LanList->visibility_status == "1") ? 'selected' : '' }}>Private</option>
											<option value="2" {{ ($LanList->visibility_status == "2") ? 'selected' : '' }}>Hidden</option>
										  
										  </select>
										</div>
									  </div>
									  <div class="cross-section"  onclick="RemoveDiv('{{ $LanList['language_id'] }}')">
										
										  <img src="{{ asset('assets/images/icons/cross.png')}}" class="img-fluid">
										
									  </div>
									</div>
									@endforeach

							@endif
							</div>
						</div>
					  


                </div>
              
			   
               
              

			  <button type="submit" class="btn btn_project_submit" onclick="Submit()">Submit</button>
			  </div>
            </div>


            



            
            
            

            @if(!empty($Project) || (Session::has('message')))
           
          </form>

          <div class="my-project-main integrate-part" id="">
            <div class="my-project-frm">
              <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="search_sec m-search">
                  <form action="">
                    <!-- <div class="form-group">
                      <label>API Key</label>
                      @if (!empty($Project['api_key']))
                      <input type="text" value="{{ $Project['api_key'] }}" name="search" id="GfGInput1" disabled>
                      @elseif((Session::has('message')))
                      <input type="text" value="{{ Session::get('api_key') }}" name="search" id="GfGInput1" disabled>
                      @else
                      <input type="text" value="003026bbc133714df1834b8638bb496e-8f4b3d9a-e9" name="search" id="GfGInput" disabled>
                      @endif
                      
                      <button onclick="CopyAPiKey()" type="text" class="btn_cpy_ser ser_1"><i class="fa fa-clone" aria-hidden="true"></i>Copy</button>
                    </div> -->
                    @php
                        $script='<script type="text/javascript">var chidbx ="MzU=";
                        window.onload=(function(d,s,u){
                        var e=d.getElementsByTagName(s)[0],$=d.createElement(s);
                        $.src=atob(u);
                        e.parentNode.insertBefore($,e) 
                        })(document,"script","aHR0cHM6Ly9kZXYudHJhbnNsdWMuaW8vYXNzZXRzL3RyYW5zbGF0b3IvdHJhbnNsLmpz");
                        </script>';
                        @endphp
                        <input type="text" class="offscreen" id="script_id"  value="{{$script}}" >
                    <div class="form-group mb-0">

                      <label>Show Integration Script</label>
                      <a href="javascript:void(0)" onclick="CopyScript()" type="text"  class="btn_cpy_ser ser_1"><i class="fa fa-clone" aria-hidden="true"></i>Copy</a>
                      <!-- <img src="assets/images/integrate.PNG"> -->
                      <div class="inte-code">
                      <code>
                      <!-- <span><</span>script type="text/javascript" src="https://cdn.weglot.com.min.js"> <span><</span>/script>
                        
                      <span><</span>script>
                      
                              Weglot.initialize({
                       
                         @if (!empty($Project['api_key']))
                       <input type="text" value="{{ $Project['api_key'] }}" name="search" id="GfGInput1" disabled>
                       @elseif((Session::has('message')))
                       <input type="text" value="{{ Session::get('api_key') }}" name="search" id="GfGInput1" disabled>
                       @else
                       <input type="text" value="wg_bb78f5965fe4e789f8126e962ea3d1446" name="search" id="GfGInput" disabled>
                       @endif
                               })
                        
                      <span><</span>/script> -->
                      <span><</span>script type="text/javascript">
                        var chidbx ="MzU=";
                        window.onload=(function(d,s,u){
                        var e=d.getElementsByTagName(s)[0],$=d.createElement(s);
                        $.src=atob(u);
                        e.parentNode.insertBefore($,e) 
                        })(document,"script","aHR0cHM6Ly9kZXYudHJhbnNsdWMuaW8vYXNzZXRzL3RyYW5zbGF0b3IvdHJhbnNsLmpz");
                        <span><</span>/script>
                       
                        
                          </code>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              </div>
            </div>
          </div>

          <!-- If translated time null or up 60 days then delete option display -->
       
          @if($DeleteOption == 1)
          <div class="my-project-main delete-part">
            <div class="my-project-frm">
              <div class="row">
              <div class="col-md-12 col-sm-12">
                <div class="delete-heading">
                      @if((Session::has('message'))) 
                      <p>Delete This Project <span onclick="DeleteProject('{{ Session()->get('id') }}')"><a href="">Delete Project</a></span>
                      @else
                      <p>Delete This Project <span onclick="DeleteProject('{{ $Project['id'] }}')"><a href="">Delete Project</a></span>
                      @endif
                  </p>
                </div>
                <div class="note_area">
                  <div class="note_img">
                    <img src="{{ asset('assets/images/icons/note.png')}}">
                  </div>
                  <div class="note_content">
                    <p><span>Note :</span> before you delete this project!</p> <small>Once you delete this project there is no way to recovering data and all translations associated will be deleted too/</small>
                  </div>
                </div>
              </div>
              </div> 
            </div>
          </div>
         
          @endif

@endif

        
        </div>
      </div>
    </div>
  </div>
  </div>


   


   <!-- start js section -->
   <script src="{{ asset('assets/js/jquery-1.9.1.min.js')}}"></script>
  <script src="{{ asset('assets/js/bootstrap.js')}}"></script>
  <script src="{{ asset('assets/js/custom.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
  <script>
    function readURL(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
              $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
              $('#imagePreview').hide();
              $('#imagePreview').fadeIn(650);
            }
            reader.readAsDataURL(input.files[0]);
          }
        }
        $("#imageUpload").change(function () {
          readURL(this);
        });
    
        $('body').on('click', '.select-plan', function(){
          $('#payment-form').removeClass('collapse');
    
        });
  </script>
  <script>
    $('.date-own').datepicker({
          minViewMode: 2,
          format: 'yyyy'
        });
  </script>
  <script>
    $('#data').datepicker({
          format: "dd-MM",
          todayHighlight: true,
          autoclose: true,
          clearBtn: true
        });
  </script>
  <script>
    $('body').on('click', '.select-project', function(){
          $('#select-check').removeClass('collapse');
    
        });
  </script>
 
 <script>
    $(".dropdown dt a").on('click', function() {
  $(".dropdown dd ul").slideToggle('fast');
});

$(".dropdown dd ul li a").on('click', function() {
  $(".dropdown dd ul").hide();
});

function getSelectedValue(id) {
  return $("#" + id).find("dt a span.value").html();
}

$(document).bind('click', function(e) {
  var $clicked = $(e.target);
  if (!$clicked.parents().hasClass("dropdown")) $(".dropdown dd ul").hide();
});

$('.mutliSelect input[type="checkbox"]').on('click', function() {

  var title = $(this).attr('data-name');
  //alert(title)
    title = $(this).attr('data-name') + ",";
    console.log(title);

    if ($(this).is(':checked')) {

    var html = '<span title="' + title + '">' + title + '</span>';
    $('.multiSel').append(html);
    $(".hida").hide();
    } else {
    $('span[title="' + title + '"]').remove();
    var ret = $(".hida");
    $('.dropdown dt a').append(ret);

    }
});
  </script>
  
  <script>
    function GeeksForGeeks() { 
  /* Get the text field */
 
  var copyGfGText = document.getElementById("GfGInput1"); 
  
  /* Select the text field */
  copyGfGText.select(); 
  copyGfGText.setSelectionRange(0, 99999)
  /* Copy the text inside the text field */
  document.execCommand("copy"); 
  alert("Copied the text: " + copyGfGText.value);
});
}  
  </script>
  @endsection

  <script>


var AddLanguages=[];
var existsDestArr=[];
var DestinationProjectLanguage=[];
var TemexistsDestArr=[];
var TemVisibility=[];

var MaxNumberLanguagesCounter = 0;

window.onload = function() {
  var DestinationProjectLanguage = {!! json_encode($DestinationProjectLanguage, JSON_HEX_TAG) !!};

  for(var i=0; i<DestinationProjectLanguage.length; i++){
    var lang_id = DestinationProjectLanguage[i]['language_id'];
    var status = DestinationProjectLanguage[i]['visibility_status'];
    existsDestArr.push(lang_id);
    TemVisibility.push(status);
  }
}
function AddLanguage(language_id){

  
 
    var $form = $('#add_language_form');
     
     var lang_id=$('#lang_sel_id').val();

     var lang_text=$('#dest_lang_id').val();
  
     /* please select a language*/
   
   
    var n = existsDestArr.includes(lang_id);
  //  if(MaxNumberLanguagesCounter<{!! json_encode($NumberOfLanguage, JSON_HEX_TAG) !!}){

  
    if(n==false){

    
     if(lang_id){
      
      
      MaxNumberLanguagesCounter++;
        //$form.append($('<input type="hidden" name="AddLanguages[]">').val(existsDestArr));
      
        countCheck=1;

        var html='<div class="visibility_section" id='+lang_id+'><div class="spanish-line" id="LanguageName">'+lang_text+'</div><div class="lang_vis"><p>Language Visibility :</p><div class="form-group mb-0 c-cus"><select class="form-control lang_type" id="visibility_status1" name="Visibility[]"><option value="0">Public</option><option value="1">Private</option><option value="2">Hidden</option></select></div></div><div class="cross-section"  onclick="RemoveDiv('+lang_id+')"><img src="{{ asset('assets/images/icons/cross.png')}}" class="img-fluid"></div></div>';

        $('#add_language').append(html);
        //langChange(null);
        existsDestArr.push(lang_id); 

     }
     else{
        alert("Please select a language");
     }
    }
    else{
      alert("Already added in your List");
    }
  // }
  // else{
  //   alert("Your subscribed maximum language support reached ! ");
  // }
}

function langChange(val,all)
{
  var dest_lang = document.getElementById("Language_Pair_Id");
    console.log(dest_lang.options[dest_lang.selectedIndex].text);
    $('#lang_sel_id').val(val);
    $('#dest_lang_id').val(dest_lang.options[dest_lang.selectedIndex].text);
}

function RemoveDiv(val){

  //alert(val);
    var elem = document.getElementById(val);
     elem.parentNode.removeChild(elem);
     MaxNumberLanguagesCounter--;
    
  for(var i=0; i<existsDestArr.length; i++){
    if(existsDestArr[i] == val){
      var ind = i;
    } 
  }
  existsDestArr.splice(ind, 1);
  
}




</script>


<script>
function CopyAPiKey() {
  var copyText = document.getElementById("GfGInput1");
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  alert("Copied the text: " + copyText.value);
}

</script>


<script>
  var count=0;
function CurrentLanguageId(val)
{
  
  var check = document.getElementById("add_language").getAttribute("data-value"); 
 
  if(val!=check && count==0){
    count=1;
    document.getElementById("IfChangeHide").style.display = "none";
    
    var divsToHide = document.getElementsByClassName("visibility_section"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++){
        divsToHide[i].style.visibility = "hidden"; // or
        divsToHide[i].style.display = "none"; // depending on what you're doing
    }

    TemexistsDestArr = existsDestArr;
    existsDestArr.length= 0;
    $("#visibility_status1").val([]);
    $("#visibility_status2").val([]);

  }
  else{
    document.getElementById("IfChangeHide").style.display = "block";
    var divsToHide = document.getElementsByClassName("visibility_section"); //divsToHide is an array
    for(var i = 0; i < divsToHide.length; i++){
        divsToHide[i].style.visibility = "hidden"; // or
        divsToHide[i].style.display = "none"; // depending on what you're doing   form-group mb-0 c-cus
    }

    existsDestArr.length= 0;
    $("#visibility_status1").val([]);
    $("#visibility_status2").val([]);
    
  }
     
      $.ajax({
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        url:"{{url('/get-language-pair')}}",
		    type: "POST",
		    data: {'id':val},
        dataType : 'json',
		   success: function(response){
		    console.log(response);
      
      
       var obj = response;
         $("#Language_Pair_Id").empty();
         $('#lang_sel_id').val(response[0].id);
      $('#dest_lang_id').val(response[0].name);
         $.each(obj, function(index, element) {
           $("#Language_Pair_Id").append("<option value='"+ element.id +"' >" + element.name + "</option>");
         });
      
		    }
		});

}
function CopyScript()
{
  // // document.getElementById("script_id").style.display = "block";
  // $('#script_id').show();

  var copyText = document.getElementById("script_id");
    
    /* Select the text field */
    copyText.select();
    copyText.setSelectionRange(0, 99999); /*For mobile devices*/
  
    /* Copy the text inside the text field */
    document.execCommand("copy");
  
    /* Alert the copied text */
    // alert("Copied the text: " + copyText.value);
}


</script>

<script>

function Submit(){

  var $form = $('#add_language_form');
 
  $form.submit(function(event) {
    /* Check If Origin Language Id Change*/
    var NewOriginLanguageId = document.getElementById('current_website_language').value;
    var OldOriginLanguageId = document.getElementById('add_language').value;
    if(NewOriginLanguageId==OldOriginLanguageId){
      $form.append($('<input type="hidden" name="AddLanguages[]" required>').val(TemexistsDestArr));
    }
    else{
      $form.append($('<input type="hidden" name="AddLanguages[]" required>').val(existsDestArr));
    }
    
    $form.find('.submit').prop('disabled', true);

    document.getElementById("add_language_form").submit();
  });

}
  </script>

  <script>

function DeleteProject(id){
  
  
  var r = confirm("Are you sure to delete!");
  
	        if (r == true) {
           
                  $.ajax({
                type: "GET", 
                headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
                dataType: "json",
                data: {'id' :  id },
                url: "{{url('/delete-project')}}", 
                success:function(message){ 
                  if(message.success == true){
                      location.reload();
                  }
                  else{
                    alert("can not delete project within 60days of create date!");
                  }
              });
          }
          else{
            txt = "You pressed Cancel!"; 
          }
         
}
    </script>