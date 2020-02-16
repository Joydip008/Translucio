<?php $__env->startSection('title'); ?>
TRANSLICIO | My Page
<?php $__env->stopSection(); ?>
<?php $__env->startSection('add-meta'); ?>

<?php $__env->stopSection(); ?>
<style type="text/css">
  .t-red{
     font-size: 80%;
     color: #dc3545;
  }
 .custom-data strong{
     
     color: #ff0000;
  }
  /* img{
    display: contents;
  } */
</style>
<?php $__env->startSection('content'); ?> 
<center>
<div id='loadingmessage' style='display:none'>
  <img src="<?php echo e(asset('/assets/upload/loading.gif')); ?>">
</div> 
<center>


<div class="translate-document-main personal-main" id="main">

    <div class="container custom-con">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="translate-heading profile-heading">Proofreading</h2>
         
          <?php if(session()->has('message')): ?>
          <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
        <?php echo e(session()->get('message')); ?>

    </div>
<?php endif; ?>
<?php if(session()->has('messageError')): ?>
          <div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
        <?php echo e(session()->get('messageError')); ?>

    </div>
<?php endif; ?>
 
<?php if($errors->any()): ?>
  						<div class="alert alert-danger">
    							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    							<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    								<p><?php echo $error; ?></p>
    							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  						</div>
  					<?php endif; ?> 
          <div class="credit_history">
            <div class="col-sm-12 col-md-12">
              <form method="" action="" class="frm_proof_sub">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="selectbox proof">
                        <label for="content">Content Type</label>
                        <select class="form-control" id="">
                          <option >Visible text</option>
                          <option>Metadata</option>
                          <option>Media</option>
                        </select> <i class="material-icons">keyboard_arrow_down</i>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group"> 
                      <div class="selectbox proof">
                        <label for="status">Status</label>
                        <select class="form-control" id="FilterData" onchange="FilterDataList(this.value,'<?php echo e($ProjectDetails['id']); ?>','<?php echo e($DestinationLanguage['id']); ?>')">
                          <option value="1" <?php if($check==1): ?> selected <?php endif; ?>>All</option>
                          <option value="2" <?php if($check==2): ?> selected <?php endif; ?>>Approved</option>
                          <option value="3" <?php if($check==3): ?> selected <?php endif; ?>>Not approved</option>
                        </select> <i class="material-icons">keyboard_arrow_down</i>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="selectbox proof">
                        <label for="status">Pages</label>
                        <form action="<?php echo e(url('/proof-reading/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                      <input class="" type="text" name="pageName" id="SearchText" value="<?php echo e($pageName); ?>" placeholder="Search your page" aria-label="Search"><i class="material-icons">search</i>
       
                      <input type="submit" value="Search">

                    </form>
                        
                    
                      </div>
                    </div>
                  </div>
                   
                  <div class="col-md-6 text-right"> 
                  <!-- <a href="<?php echo e(url('/updated-project/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])); ?>">Execute Now </a> -->
                   <a class="btn btn-primary btn btn_add_proof" data-toggle="modal" data-target="#exampleModal2"><i class="material-icons">add</i>Add String Correction
                    </a>
                    <!-- <input type="hidden" name="select_project"  id="select_project">
                    <input type="hidden" name="select_language" id="select_language"> -->

                    <?php if($ProjectDetails['project_type'] == 1): ?>
                    
                    <form action="<?php echo e(url('/proof-reading/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])); ?>" method="POST">
                      <?php echo csrf_field(); ?>

                    

                      <input class="" type="text" name="search" id="SearchText" value="<?php echo e($search); ?>" placeholder="Search your text" aria-label="Search"><i class="material-icons">search</i>
       
                      <input type="submit" value="Search">

                    </form>
              
                    <!-- <a class="btn btn-primary" data-toggle="modal" data-target="#myModal">Show Script</a>  -->
                    <!-- <?php echo e(url('/website-execute/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])); ?> -->
                      <?php if($LanguagePairDetails['api'] == 'D'): ?>
                      
                        <input  type="button" class="btn btn-primary" onclick="Loader('<?php echo e($LanguagePairDetails['api']); ?>','<?php echo e($ProjectDetails['id']); ?>','<?php echo e($DestinationLanguage['id']); ?>')" value="Execute">
                      <?php elseif($LanguagePairDetails['api'] == 'G'): ?>

                        <input type="button" class="btn btn-primary" onclick="Loader('<?php echo e($LanguagePairDetails['api']); ?>','<?php echo e($ProjectDetails['id']); ?>','<?php echo e($DestinationLanguage['id']); ?>')" value="Execute">
                      <?php elseif($LanguagePairDetails['api'] == 'A'): ?>
                     
                        <input  type="button" class="btn btn-primary" onclick="Loader('<?php echo e($LanguagePairDetails['api']); ?>','<?php echo e($ProjectDetails['id']); ?>','<?php echo e($DestinationLanguage['id']); ?>')" value="Execute">
                      <?php endif; ?>
                    <?php endif; ?>
                    <?php if($ProjectDetails['project_type'] == 2): ?>
                   
                      <?php if($ProjectDetails['extension'] === 'pdf'): ?>
                      <!-- <?php echo e(url('/download-updated-project/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])); ?> --> 
                        <!-- PDF -->
                        <a class="btn btn-primary" href="<?php echo e(url('/download-updated-project/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])); ?>">Download</a> 
                      <?php elseif($ProjectDetails['extension'] === 'docx'): ?> 
                        <!-- DOCX -->
                        <a class="btn btn-primary" href="<?php echo e(url('/download-updated-project/'.$ProjectDetails['id'].'/'.$DestinationLanguage['id'])); ?>">Download</a>
                      <?php endif; ?>
                    <?php endif; ?> 
                  </div> 
                </div> 
              </form>
            </div>
            <div class="replace_table">
              <div class="string_correction_1 ">
                <div class="string_correction">
                  <!--Table-->
                  <table class="table custom-data">
                    <thead>
                      <tr>
                        <th class="th-lg">Translated from <span style="color: #716FEA"><?php echo e($OriginLanguage['name']); ?></span>
                        </th>
                        <th class="th-lg">Translated into <span style="color: #716FEA"><?php echo e($DestinationLanguage['name']); ?></span>
                        </th>
                        <th class="th-lg">Action</th>
                      </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                      <?php if(!empty($ParagraphDetails)): ?>
                        <?php $__currentLoopData = $ParagraphDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(strip_tags($Data['original_data'])!=''): ?>
                      
                          <tr>
                           
                              <td class="remove-image"><?php echo $Data['original_data']; ?></td>
                           
                              <td id=""  class="common-width remove-image">

                             
                              <!-- content-editable -->
                                <p class="mb-0 content-editable" id="ph_<?php echo e($Data['id']); ?>" value="<?php echo e($Data['translated_data']); ?>" onkeyup="changeValuePro(this,'<?php echo $Data['id'];?>')"><?php echo $Data['translated_data']; ?></p>
                               
                             <div id="div_edit_<?php echo e($Data['id']); ?>" style="display:none">
                              
                               <input type="text" class="mb-0 content-editable" id="ph1_<?php echo e($Data['id']); ?>" value="<?php echo e($Data['translated_data']); ?>">
                              
                              </div>
                                
                               
                               
                                  <p class="approved_head" id="approve_<?php echo e($Data['id']); ?>"> <?php if($Data['status'] == 1): ?>Approved by<span>:  <?php echo e(Auth::user()->name); ?> at <?php echo e($Data['updated_at']); ?> </span> <?php elseif($Data['status'] == 0): ?>Pending <?php endif; ?>
                                      <!-- <button class="Version-btn" data-toggle="modal" href="#ignismyModal">Version</button> -->
                                      <button class="Version-btn" onclick=VersionData('<?php echo e($Data['id']); ?>')>Version</button>
                                  </p>
                               
                                
                        </td>
                        <td style="color: #716FEA">
                      
                          <input type="button" id="edit_button1" value="Edit" class="edit string-edit-btn" onclick="edit_data('<?php echo e($Data['id']); ?>')">
                       
                          <input type="button" id="save_button1" value="Save" class="save save_dis"   onclick="save_data('<?php echo e($Data['id']); ?>')">
                          <!-- onclick="save_data('<?php echo e($Data['id']); ?>')" -->

                          <?php if($Data['status'] != 1): ?>
                            <button class="approve-btn" id="approve_button_<?php echo e($Data['id']); ?>" onclick="Approved_Paragraph('<?php echo e($Data['id']); ?>')">Approve</button>
                          <?php endif; ?>
                        </td>
                      </tr>
                     <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      <?php endif; ?>
                    </tbody>
                    <!--Table body-->
                  </table>
                  <!--Table-->
                </div>
                <div class="col-md-12">
                  <div class="pagination_right">
                 
                  <?php echo $__env->make('vendor.pagination.custom', ['paginator' => $results->appends(['search'=>$search]), 'link_limit' => 3], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                 
                    <!-- <ul>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                      </li>
                      <li><span class="activea">1</span>
                      </li>
                      <li><a href="javascript:void(0)" class="bg-none">2</a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-right"
                            aria-hidden="true"></input></a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-double-right"
                            aria-hidden="true"></input></a>
                      </li>
                    </ul> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end my profile section your document section -->


   <!-- start version modal section -->
   <div id="ModalCLoseCheck">
   <div class="modal fade cust-modal" id="ignismyModal" >
 
    <!-- <div class="modal-dialog profedding-bx">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="version_title">Version</h3>
           
          <button type="button" class="close close_version" data-dismiss="modal" aria-label=""><i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body">
          <ul class="timeline">
            <li>
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Origin language</h4>
                </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>
            <li class="timeline-inverted">
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Destination language (machine translated)</h4>
                </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>
            <li>
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Edited version <span class="update_tags">current</span></h4>
                      </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>

              <li>
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Currnet version <span class="update_tags tag_different">Last updated 21th July</span></h4>
                      <div class="back_drop dropdown">
                      <button onclick="show_hide()" type="button" class="btn_previous btn_next">make it Actual</button>
                      <div style="display: none;" id="drop-content"> <a href="javascript:void(0)">Last Updated 26th September</a>
                        <a href="javascript:void(0)">Last Updated 21th July</a>
                        <a href="javascript:void(0)">Last Updated 10th Otober</a>
                      </div>
                    </div>
                      </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>

              <li>
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Currnet version <span class="update_tags tag_different">Last updated 21th July</span></h4>
                      <div class="back_drop dropdown">
                      <button onclick="show_hide()" type="button" class="btn_previous btn_next">make it Actual</button>
                      <div style="display: none;" id="drop-content"> <a href="javascript:void(0)">Last Updated 26th September</a>
                        <a href="javascript:void(0)">Last Updated 21th July</a>
                        <a href="javascript:void(0)">Last Updated 10th Otober</a>
                      </div>
                    </div>
                      </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>

              <li>
              <div class="timeline-badge warning"></div>
              <div class="timeline-panel">
                <div class="timeline-heading">
                  <h4 class="timeline-title">Currnet version <span class="update_tags tag_different">Last updated 21th July</span></h4>
                      <div class="back_drop dropdown">
                      <button onclick="show_hide()" type="button" class="btn_previous btn_next">make it Actual</button>
                      <div style="display: none;" id="drop-content"> <a href="javascript:void(0)">Last Updated 26th September</a>
                        <a href="javascript:void(0)">Last Updated 21th July</a>
                        <a href="javascript:void(0)">Last Updated 10th Otober</a>
                      </div>
                    </div>
                      </div>
                <div class="timeline-body">
                  <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>-->
    </div>
  </div>
  <!-- end version modal section -->

  <script>
    function show_hide(){
        var click = document.getElementById("drop-content");
        if(click.style.display == "none"){
          click.style.display = "block";
        }
        else{
          click.style.display = "none";
        }
      } 
  </script>
  <!-- start js section -->
  <script src="<?php echo e(asset('assets/js/jquery-1.9.1.min.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/bootstrap.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/custom.js')); ?>"></script>
  <script>


// $('body').on('click', '.string-edit-btn', function () {
//           var editelement = $(this).parents('tr').find('.content-editable');;
//           editelement.attr('contenteditable', 'true').focus();
//           editelement.parent("td").css('background', '#f2f2f2');
//           $(this).css('display', 'none');
//           $(this).next('.save').css('display', 'inline-block');
//         });
    
    $('body').on('click', '.string-edit-btn', function () {
          var editelement = $(this).parents('tr').find('.content-editable');;
          editelement.attr('contenteditable', 'true').focus();
          editelement.parent("td").css('background', '#f2f2f2');
          $(this).css('display', 'none');
          $(this).next('.save').css('display', 'inline-block');
        });
        $('body').on('click', '.save_dis', function () {
          var editelement = $(this).parents('tr').find('.content-editable');
        
          editelement.attr('contenteditable', 'false').blur();
          editelement.parent("td").css('background', '#ffff');
          $(this).css('display', 'none');
          $(this).prev('.string-edit-btn').css('display', 'inline-block');
        });
  </script>



<div class="modal fade proof-mod" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="add_heading">Add New String Corrections</h2>
          <button type="button" class="close close_add" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
      </div>
 <div class="modal-body">
       <div class="col-xs-12 ">
          <div class="string-form">
           
        <nav>
          <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
            <?php if($LanguagePairDetails['do_not_translate']==1): ?>
            <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Do not translate</a>
            <?php endif; ?>
            <?php if($LanguagePairDetails['always_translate_as']==1): ?>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Always translate as</a>
            <?php endif; ?>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Replace translated string</a>
          </div>
        </nav>
       
        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
          <div class="tab-pane fade show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
          <form method="post" action="<?php echo e(route('do_not_translate')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo e($ProjectDetails['id']); ?>">
            <input type="hidden" value="<?php echo e($OriginLanguage['id']); ?>" name="from_language">
            <input type="hidden" value="<?php echo e($DestinationLanguage['id']); ?>" name="to_language">
              <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">Do not Translate</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="string" required>
              </div>
              </div>
              </div>
                <button type="submit" class="btn btn-primary btn_str_save">Add</button>
                </form>
          </div>

     
          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
          <form method="post" action="<?php echo e(route('always_translate_as')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo e($ProjectDetails['id']); ?>">
                   <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String From (<?php echo e($OriginLanguage['name']); ?>)</label>
                <input type="hidden" value="<?php echo e($OriginLanguage['id']); ?>" name="from_language">
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="from_string" required>
              </div>
                <div class="form-group">
                <label for="formGroupExampleInput">String To (<?php echo e($DestinationLanguage['name']); ?>)</label>
                <input type="hidden" value="<?php echo e($DestinationLanguage['id']); ?>" name="to_language">
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="to_string" required>
              </div>
              </div>
              </div>
              <!-- <div class="form-group text-right">
                <button type="button" class="btn btn_add_increase"><i class="material-icons">add</i>Add</button>
              </div> -->

              <button type="submit" class="btn btn-primary btn_str_save">Save</button>
              </form>
          </div>

     
          <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
          <form method="post" action="">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id" value="<?php echo e($ProjectDetails['id']); ?>" id="id">
           <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String From (<?php echo e($OriginLanguage['name']); ?>)</label>
                <input type="text" class="form-control" id="from_string" placeholder="" name="from_string" required>
                <input type="hidden" value="<?php echo e($OriginLanguage['id']); ?>" name="from_language" id="from_language">
              </div>
                <div class="form-group">
                <label for="formGroupExampleInput">String To (<?php echo e($DestinationLanguage['name']); ?>)</label>
                <input type="text" class="form-control" id="to_string" placeholder="" name="to_string" required>
                <input type="hidden" value="<?php echo e($DestinationLanguage['id']); ?>" name="to_language" id="to_language">
                
              </div>
              </div>
              </div>
              <!-- <div class="form-group text-right">
                <button type="button" class="btn btn_add_increase"><i class="material-icons">add</i>Add</button>
              </div> -->

              <button type="button" onclick="ReplaceStringOnFly()" class="btn btn-primary btn_str_save">Save</button>
              </form>
          </div>

        </div>
       
      </div>
      </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        <div>
        <code>
        <span><</span>script type="text/javascript">
          var chidbx ="MzU=";
          window.onload=(function(d,s,u){
          var e=d.getElementsByTagName(s)[0],$=d.createElement(s);
          $.src=atob(u);
          e.parentNode.insertBefore($,e) 
          })(document,"script","aHR0cHM6Ly9kZXYudHJhbnNsdWMuaW8vcHVibGljL3RyYW5zbGF0b3IvcmVtb3RlLmpz");
          <span><</span>/script>
            </code>
        </div>
        </div>
        </div>
        </div>

<script>
$(document).ready(function () {

// Attach Button click event listener 
$("#myBtn").click(function(){

    // show Modal
    $('#myModal').modal('show');
});

$('.replace_table table tr').find('.remove-image').find('img').remove();
});
</script>
<script>
  $(".btn_add_proof").click(function(){
    $("#exampleModal2").modal({backdrop: true}); 
});

  //   function add_div()
  // {

  //   var html='<div class="str_increment"><div class="form-group"><label for="formGroupExampleInput">String Form</label><input type="text" class="form-control" id="formGroupExampleInput" placeholder=""></div><div class="form-group"><label for="formGroupExampleInput2">String To</label><input type="text" class="form-control" id="formGroupExampleInput2" placeholder=""></div></div>';

  //   $('.append_main').append(html);
  // }
</script>
  <!-- start add project popup -->
  <div class="modal fade add_new_modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body add_mid"> <a href="my-project.html" class="btn_new"><i class="material-icons">add</i>New Webproject</a>
          <a href="new-document.html" class="btn_new btn_diff"><i class="material-icons">add</i>New Document</a>
        </div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>


<?php $__env->stopSection(); ?>

<script>

function edit_data(id)
{
  // $('#'+id).css('background', '#f2f2f2');
  // document.getElementById('div_edit_'+id).style.display='block';

}
  function save_data(id){ 
     //alert('div_edit_'+id);
    // alert(language_id);
 
    data='';
    var data = data + document.getElementById('ph_'+id).innerHTML;
     
    // for(var i=0;i<=arr_count-1;i++)
    // {
    //   if(i==arr_count-1)
    //   var data = data + document.getElementById('ph_'+i+'_'+id).value;
    //   else
    // var data = data + document.getElementById('ph_'+i+'_'+id).value+'|##|';
    // }
    //alert(data);
    // var project_id = document.getElementById('select_project').value;
    // alert(project_id);
    //alert(data);
    // var r = confirm("Are you sure to Update");
    
    // if(r == true){ 
        $.ajax({
          type:"GET",
          headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
          dataType: "json",
          data: {'id' :  id, 'data' : data },
          url: "<?php echo e(route('update_website')); ?>", 
          success:function(message){
            console.log(message);
            document.getElementById('approve_button_'+id).style.display='none';
            document.getElementById('div_edit_'+id).style.display='none';
           
            $('#ph_'+id).css('background', '#ffff');
            document.getElementById('ph_'+id).innerHTML=message.success.data;
           
           // document.getElementById('pending_'+id).style.display='none';
            document.getElementById('approve_'+id).innerHTML=message.success.msg;
            var ver_btn='<button class="Version-btn" onclick=VersionData('+id+')>Version</button>';
            
            $('#approve_'+id).append(ver_btn);
            

            //location.reload();
          }
        });
    // }
    // else{
    //   txt = "You pressed Cancel!";
    // }
  }


function Approved_Paragraph(id){
  //alert(id);
  // var r = confirm("Are you sure to Approved!");
  
  
  // if (r == true) {
   
          $.ajax({
        type: "GET", 
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        dataType: "json",
        data: {'id' :  id },
        url: "<?php echo e(route('approved_paragraph')); ?>", 
        success:function(message){ 
          document.getElementById('approve_button_'+id).style.display='none';
          document.getElementById('approve_'+id).innerHTML=message.success.msg;

          document.getElementById('approve_'+id).innerHTML=message.success.msg;
            var ver_btn='<button class="Version-btn" onclick=VersionData('+id+')>Version</button>';
            
            $('#approve_'+id).append(ver_btn);
          // console.log(message);
          // console.log("H4ello");
          // location.reload();
        }
      }).done(function( msg ) {
      });
  // }
  // else{
  //   txt = "You pressed Cancel!";
  // }
}
</script>

<script>

function ReplaceStringOnFly(){

  var from_language = document.getElementById("from_language").value;
  var to_language = document.getElementById("to_language").value;
  var id = document.getElementById("id").value;
  var from_string = document.getElementById("from_string").value;
  var to_string = document.getElementById("to_string").value;
  // alert(from_language);
  // alert(to_language);
  // alert(id);
  // alert(from_string);
  // alert(to_string);
  // alert("under processing");
  


//   $(document).ajaxStart(function(){
//   // Show image container
//   $("#main").hide();
//   $("#exampleModal2").hide();
//    $("#loadingmessage").show();
//   //alert("OKAY");
// });
// $(document).ajaxComplete(function(){
//   // Hide image container
//   $("#loadingmessage").hide();
//   // $("#main").show();
//   // $("#exampleModal2").show(); 
 
// });


  $.ajax({
    type: "POST",
    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
    dataType: "json",
    data: {'project_id' : id , 'from_language' : from_language , 'to_language' : to_language , 'from_string' : from_string , 'to_string' : to_string},
    // url: "<?php echo e(url('/user/replace-translated-string')); ?>",
    url: "<?php echo e(route('replace_translated_string')); ?>",
    success:function(message){
      // console.log(message);
       location.reload(); 
    }
  });
  
}
  </script>

<script>

function FilterDataList(val,id,destination_language){
  // alert("Prpocessing");
  // alert(val);
  // alert(id);
  // alert(destination_language);
  
  window.location.href = "<?php echo e(url('/proof-reading')); ?>"+'/'+id+'/'+destination_language+'/'+val;

  // $.ajax({
  //     type: "GET", 
  //     //headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
  //     dataType: "json",
  //     //data: {'val' :  val , 'id' : id , 'language_id' : destination_language},
  //     url: "<?php echo e(url('/proof-reading')); ?>"+'/'+id+'/'+destination_language+'/'+val, 
  //     success:function(message){ 
  //       console.log(message);
  //       // location.reload();
  //     }
  //   });
}


</script>



<script>
function VersionData(paragraph_id){
  //  alert(paragraph_id);

 
       $.ajax({
        type: "POST", 
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
         data: {'paragraph_id' :  paragraph_id },
        url: "<?php echo e(route('version_data')); ?>", 
        success:function(response){ 
            // console.log(response.success['data_version']);
          
           
            
             document.getElementById("ignismyModal").innerHTML = response;
             $('body').addClass('modal-open');
             $('body').append('<div class="modal-backdrop fade show"></div>');
       $('.cust-modal').addClass('show fade').css({'display':'block'});
             $("#ignismyModal").modal('show');
    
        }
      });
       
     
      

}

function closeModal()
{
  $('.modal').removeClass('show fade').css({'padding':'0','display':'none'});  
  $('.modal-backdrop').remove();
  $('body').removeClass('modal-open').css({'padding':'0'});
  // location.reload();
  

    
  
}

// window.onclick = function(event) {
//   var modal = document.getElementById('ModalCLoseCheck').hide();
 
//   // $('#ignismyModal').modal('hide');  
//   // if (event.target == modal) {
//     // alert("OKAY");
//     // modal.style.display = "hide";
//   // }
// }

function MakeItActual(id){
  // alert(paragraph_id);
  $.ajax({
        type: "POST", 
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
         data: {'id' :  id },
        url: "<?php echo e(route('version_data_swap')); ?>", 
        success:function(response){ 
          location.reload();
          // if(response.success === 'true'){
           
          // }
          // else{
          //   alert("Some Error please try again later!");
          // }
        }
      });
}

function Loader(api,project,language){
  
  $(document).ajaxStart(function(){
  // Show image container
  $("#main").hide();
  $("#loadingmessage").show();
  });
  $(document).ajaxComplete(function(){
    // Hide image container
    $("#loadingmessage").hide();
    $("#main").show();
  });

  if(api === 'D'){
    $.ajax({
      type: "POST", 
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        data: {'project' :  project, 'language' : language },
        url: "<?php echo e(route('website_execute')); ?>", 
        success:function(response){ 
          location.reload();
        }
      });
  }
  if(api === 'G'){
    $.ajax({
      type: "POST", 
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        data: {'project' :  project, 'language' : language },
        url: "<?php echo e(route('website_execute_google_api')); ?>", 
        success:function(response){ 
          location.reload();
        }
      });
  }
  if(api === 'A'){
    $.ajax({
        type: "POST", 
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        data: {'project' :  project, 'language' : language },
        url: "<?php echo e(route('website_execute_amazon_api')); ?>", 
        success:function(response){ 
          location.reload();
        }
      });
  }
}
</script>
<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/user/myProject/proofreading_website.blade.php ENDPATH**/ ?>