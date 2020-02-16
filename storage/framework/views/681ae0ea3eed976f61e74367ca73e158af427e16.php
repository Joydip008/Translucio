<?php $__env->startSection('title'); ?>
TRANSLICIO | My Page
<?php $__env->stopSection(); ?>
<?php $__env->startSection('add-meta'); ?>
<style type="text/css">
  .t-red{
     font-size: 80%;
     color: #dc3545;
  }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

 <!-- start my profile section css your document section -->
 <div class="translate-document-main personal-main">
    <div class="container custom-con">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="translate-heading profile-heading"> String Corrections</h2>
        </div>
            <div class="credit_history string_correction_top">
            <div class="col-12">
              <div class="row">
                <div class="col-md-8">
                    <!-- start tab section -->
                    <ul class="nav nav-tabs custom_string_tab" role="tablist">
                    <!-- onclick="DNTPagination('<?php echo e($ProjectDetail['id']); ?>','<?php echo e($ToLanguage['id']); ?>')" -->
                    <?php if($LanguagePairDetails['do_not_translate']==1): ?>
                      <li class="nav-item" >
                        <!-- <a class="nav-link toggle_value_0 active" href="#profile" role="tab" data-toggle="tab" >Do not translate</a> -->
                        <!-- <a href="<?php echo e(url('/string-correction/'.$ProjectDetail['id'].'/'.$ToLanguage['id'].'/1')); ?>" class="nav-link toggle_value_1 " >Do not Translate as</a> -->
                        <a class="nav-link toggle_value_0 <?php echo $tabId==1?'active':''; ?>" href="<?php echo e(url('/string-correction/'.$ProjectDetail['id'].'/'.$ToLanguage['id'].'/1')); ?>"  >Do not translate</a>
                        
                      
                      </li>
                    <?php endif; ?>
                    <?php if($LanguagePairDetails['always_translate_as']==1): ?>
                      <li class="nav-item">
                        <!-- <a class="nav-link toggle_value_1" href="#buzz" role="tab" data-toggle="tab">Always translate as</a> -->
                        <a href="<?php echo e(url('/string-correction/'.$ProjectDetail['id'].'/'.$ToLanguage['id'].'/2')); ?>" class="nav-link toggle_value_1 <?php echo $tabId==2?'active':''; ?>" >Always translate as</a>
                      </li>
                    <?php endif; ?>
                      <!-- <li class="nav-item" >
                        <a class="nav-link toggle_value_1"  href="<?php echo e(url('/updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])); ?>">Execute Now </a>
                      </li> -->
                      <?php if($ProjectDetail['project_type'] == 1): ?>
                      <li class="nav-item">
                      <!-- <?php echo e(url('/update-web-html/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])); ?> -->
                      <a class="btn btn-primary" href="<?php echo e(url('/website-execute/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])); ?>">Execute & Show Web Site</a>
                      </li>
                      <?php endif; ?>
                      <?php if($ProjectDetail['project_type'] == 2): ?>
                        <?php if($ProjectDetail['extension'] === 'pdf'): ?>
                        <!-- <?php echo e(url('/download-updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])); ?> -->
                          <li class="nav-item">
                            <a class="nav-link toggle_value_1"  href="<?php echo e(url('/download-updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])); ?>">Execute & Download PDF</a>
                          </li>
                        <?php elseif($ProjectDetail['extension'] === 'docx'): ?> 
                          <li class="nav-item">
                            <a class="nav-link toggle_value_1"  href="<?php echo e(url('/download-updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])); ?>">Execute & Download DOCX</a>
                          </li>
                        <?php endif; ?>
                      <?php endif; ?>



                       <!-- <a href="<?php echo e(url('/updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])); ?>">Execute Now </a>
                <a href="<?php echo e(url('/download-updated-project/'.$ProjectDetail['id'].'/'.$ToLanguage['id'])); ?>">Download Now </a> -->
                    </ul>
              </div>
              <div class="col-md-4">
                 <div class="string_tabs_text text_none">
                  
                 </div>

                <div class="string_tabs_text" id="str_1">
                  <span><?php echo e($FromLanguage['name']); ?> <i class="fa fa-arrow-right" aria-hidden="true"></i><?php echo e($ToLanguage['name']); ?></span>
                </div>

                 <div class="string_tabs_text" id="str_2">
                 <span>German <i class="fa fa-arrow-right" aria-hidden="true"></i>German</span>
                </div>

              </div>
            </div>
            </div>
            <div class="col-md-12 p-0">
<!-- Tab panes -->
<div class="tab-content">
<?php if($tabId==1): ?>
  <div role="tabpanel" class="tab-pane <?php echo $tabId==1?'active':'fade'; ?>" id="profile">
            <div class="row p-15">
            <?php if($LanguagePairDetails['do_not_translate']==1 && $LanguagePairDetails['always_translate_as']==1): ?>
                <div class="col-sm-4 col-md-4">
                  <form action="<?php echo e(url('string-correction',[$ProjectDetail['id'], $ToLanguage['id'],$tabId])); ?>" method="get">
                    <div class="text-search">
                      <div class="form-group">
                        <input class="form-control" type="text" name="search" id="DoNotStringSearch" placeholder="Search your translated text" aria-label="Search"> <span class="search_span"> <i class="material-icons">search</i></span>
                     <!-- <input type="submit"> -->
                     <button class="filter-search-btn" > <i class="fa fa-search" aria-hidden="true"></i> &nbsp; Search</button>
                      </div>
                    </div>
                  </form>
                </div>

                <div class="col-sm-3 col-md-2">
                <!-- onclick="DoNotSearch('<?php echo e($ProjectDetail['id']); ?>','<?php echo e($ToLanguage['id']); ?>')" -->
                  <!-- <button class="filter-search-btn" onclick="DoNotSearch('<?php echo e($ProjectDetail['id']); ?>','<?php echo e($ToLanguage['id']); ?>')"> <i class="fa fa-search" aria-hidden="true" ></i> &nbsp; Search</button> -->
                </div>
               <?php endif; ?>
                <div class="col-md-4 col-sm-4">
               
                  <a href="javascript:void(0)" class="btn btn-primary str_from_other" data-toggle="modal" data-target="#string_other">Use string corrections from other projects</a>
                </div>
                <div class="col-md-2 text-right">
                  <a class="btn btn-primary btn_add_proof btn_string_proof" data-toggle="modal" data-target="#exampleModal3"><i class="material-icons">add</i>Add
                    </a>
                </div>
              </div>

              <?php if($LanguagePairDetails['do_not_translate']==1): ?>
              
 
                 <div class="replace_table">
              <div class="string_correction_1 ">
                <div class="string_correction str_first">
                  <!--Table-->
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="th-lg">String that needs no translation
                          <!-- <span style="color: #716FEA">
          <select class="custom-select cus_1">
        <option value="1">English</option>
        <option value="2">Spanish</option>
        <option value="3">Hindi</option>
        <option value="4">Bengali</option>
      </select></span> -->
                        </th>
                        <th class="th-lg">Action</th>
                      </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                      <?php $__currentLoopData = $DoNotTranslatedList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $DNTList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <td id="name_row<?php echo e($DNTList['id']); ?>" class="common-width" id="<?php echo e($DNTList['id']); ?>" value="<?php echo e($DNTList['do_not_translate_string']); ?>" ><?php echo e($DNTList->do_not_translate_string); ?></td>
                        <input type="hidden" class="mb-0 content-editable" id="ph_<?php echo e($DNTList['id']); ?>" value="<?php echo e($DNTList['do_not_translate_string']); ?>">
                        <td style="color: #716FEA">
                          <input type="button" id="edit_button<?php echo e($DNTList['id']); ?>" value="Edit" class="edit" onclick="edit_row_custom(<?php echo e($DNTList['id']); ?>)">
                         
                          <input type="button" id="save_button<?php echo e($DNTList['id']); ?>" value="Save" class="save save_dis" onclick="editDoNotTranslate('<?php echo e($DNTList['id']); ?>')">
                          <!-- <input type="button" name="Delete" value="Delete" class="btn-delete" data-toggle="modal" data-target=".delete_modal"> -->
                          <input type="button" name="Delete" value="Delete" class="btn-delete" onclick="deleteDoNotTranslate('<?php echo e($DNTList['id']); ?>')">
                        </td>
                      </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <!--Table body-->
                  </table>
                  <!--Table-->
                </div>
                <div class="col-md-12">
                  <div class="pagination_right">
                 <?php if(!empty($DoNotTranslatedList)): ?>
                	<?php if(!empty($search)): ?>
                  
						<?php echo e($DoNotTranslatedList->appends(['search'=>$search])->links()); ?>

            
					<?php else: ?>
          
						<?php echo e($DoNotTranslatedList->links()); ?>

            
					<?php endif; ?>
          
                 
                    <!-- <ul>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                      </li>
                      <li><span class="activea">1</span>
                      </li>
                      <li><a href="javascript:void(0)" class="bg-none">2</a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-right" aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                      </li>
                    </ul> -->
                  </div>
                </div>
              </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
            <?php endif; ?>
  </div>
  
  <?php if($tabId==2): ?>
  
  <div role="tabpanel" class="tab-pane<?php echo $tabId==2?'active':'fade'; ?>" >
    <div class="row p-15">
                <div class="col-sm-5 col-md-4">
                  <form action="<?php echo e(url('string-correction',[$ProjectDetail['id'], $ToLanguage['id'],$tabId])); ?>" method="get">
                    <div class="text-search">
                      <div class="form-group">
                        <input class="form-control" type="text"  name="search" id="AlwaysSearch" placeholder="Search your translated text" aria-label="Search"> <span class="search_span"> <i class="material-icons">search</i></span>
                        <button class="filter-search-btn" > <i class="fa fa-search" aria-hidden="true"></i> &nbsp; Search</button>
                      </div>
                    </div>
                  </form>
                </div>

                <div class="col-sm-3 col-md-2">
                <!-- onclick="AlwaysSearch('<?php echo e($ProjectDetail['id']); ?>','<?php echo e($ToLanguage['id']); ?>')" -->
                  <!-- <button class="filter-search-btn" onclick="AlwaysSearch('<?php echo e($ProjectDetail['id']); ?>','<?php echo e($ToLanguage['id']); ?>')"> <i class="fa fa-search" aria-hidden="true"></i> &nbsp; Search</button> -->
                </div>
                <div class="col-md-4"></div>
                     <div class="col-md-2 text-right">
                  <!--     <button class="btn_add" data-toggle="modal" data-target="#add_modal"> <i class="material-icons">add</i> Add</button> -->
                  <a class="btn btn-primary btn btn_add_proof btn_string_proof" data-toggle="modal" data-target="#exampleModal3"><i class="material-icons">add</i>Add
                    </a>
                    </div>
              </div>
                 <div class="replace_table">
              <div class="string_correction_1 ">
                <div class="string_correction">
                  <!--Table-->
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="th-lg">String From
                        
                        </th>
                        <th class="th-lg">String To
                         
                        </th>
                        <th class="th-lg">Action</th>
                      </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                      <?php $__currentLoopData = $AlwaysTranslatedList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ATList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <td><?php echo e($ATList['do_not_translate_string']); ?></td>
                        <td id="name_row<?php echo e($ATList['id']); ?>" id="<?php echo e($ATList['id']); ?>" value="<?php echo e($ATList['always_translate_as_string']); ?>" class="common-width"><?php echo e($ATList['always_translate_as_string']); ?></td>
                        <input type="hidden" class="mb-0 content-editable" id="ph_<?php echo e($ATList['id']); ?>" value="<?php echo e($ATList['always_translate_as_string']); ?>">
                        <td style="color: #716FEA">
                        
                          <input type="button" id="edit_button<?php echo e($ATList['id']); ?>" value="Edit" class="edit" onclick="edit_row_custom(<?php echo e($ATList['id']); ?>)">
                          <input type="button" id="save_button<?php echo e($ATList['id']); ?>" value="Save" class="save save_dis" onclick="editAlwaysTranslate('<?php echo e($ATList['id']); ?>')">
                          <!-- <input type="button" name="Delete" value="Delete" class="btn-delete" data-toggle="modal" data-target=".delete_modal"> -->
                         
                          <input type="button" name="Delete" value="Delete" class="btn-delete"  onclick="deleteDoNotTranslate('<?php echo e($ATList['id']); ?>')">
                        </td>
                      </tr>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <!--Table body-->
                  </table>
                  <!--Table-->
                </div>
                <div class="col-md-12">
                  <div class="pagination_right">
                <?php if(!empty($AlwaysTranslatedList)): ?>
                	<?php if(!empty($search)): ?>
                  
						<?php echo e($AlwaysTranslatedList->appends(['search'=>$search])->links()); ?>

            
					<?php else: ?>
          
						<?php echo e($AlwaysTranslatedList->links()); ?>

            
					<?php endif; ?>

                  </div>
                </div>
              </div>
            </div>
  </div>
  <?php endif; ?>
 <?php endif; ?>
 
</div>
</div>


<!-- end tab section -->
          </div>














      </div>
    </div>
  </div>
  <!-- end my profile section your document section -->
  <!-- start kstring lcorrection popup -->
  <!-- Modal -->
  <div class="modal fade str_modal" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="add_heading">Add New String Corrections</h2>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body">
          <div class="string-form">
            <form method="" action="">
              <div class="form-group">
                <label for="formGroupExampleInput">String Form</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="">
              </div>
              <div class="form-group">
                <label for="formGroupExampleInput2">String To</label>
                <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="">
              </div>
              <button type="submit" class="btn btn-primary btn_str_save">Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end string correction popup -->
  <!-- start delete modal -->
  <div class="modal fade delete_modal" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
        </div>
        <div class="modal-body">
          <div class="delete-account">
            <h2 class="add_heading">Delete Your Account</h2>
            <p>If you want to delete your account, along with all associated information, your materials and you preferences, please click the button below.</p> <span>This action cannot be reversed.</span>
            <button type="button" class="btn_modal_delete">Delete my account</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- end delete modal -->
  <!-- start js section -->
  <script src="<?php echo e(asset('assets/js/jquery-1.9.1.min.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/bootstrap.js')); ?>"></script>
  <script src="<?php echo e(asset('assets/js/custom.js')); ?>"></script>


  <div class="modal fade proof-mod" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="true">
  <div class="modal-dialog modal-dialog-slideout" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="add_heading">Add New  String Corrections</h2>
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
            <input type="hidden" name="id" value="<?php echo e($ProjectDetail['id']); ?>">
            <input type="hidden" value="<?php echo e($FromLanguage['id']); ?>" name="from_language">
            <input type="hidden" value="<?php echo e($ToLanguage['id']); ?>" name="to_language">
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
            <input type="hidden" name="id" value="<?php echo e($ProjectDetail['id']); ?>">
                   <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String From (<?php echo e($FromLanguage['name']); ?>)</label>
                <input type="hidden" value="<?php echo e($FromLanguage['id']); ?>" name="from_language">
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="" name="from_string" required>
              </div>
                <div class="form-group">
                <label for="formGroupExampleInput">String To (<?php echo e($ToLanguage['name']); ?>)</label>
                <input type="hidden" value="<?php echo e($ToLanguage['id']); ?>" name="to_language">
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
            <input type="hidden" name="id" value="<?php echo e($ProjectDetail['id']); ?>" id="id">
           <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String From (<?php echo e($FromLanguage['name']); ?>)</label>
                <input type="text" class="form-control" id="from_string" placeholder="" name="from_string" required>
                <input type="hidden" value="<?php echo e($FromLanguage['id']); ?>" name="from_language" id="from_language">
              </div>
                <div class="form-group">
                <label for="formGroupExampleInput">String To (<?php echo e($ToLanguage['name']); ?>)</label>
                <input type="text" class="form-control" id="to_string" placeholder="" name="to_string" required>
                <input type="hidden" value="<?php echo e($ToLanguage['id']); ?>" name="to_language" id="to_language">
                
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
      <!--  <div class="string-form">
            <form method="" action="">
              <div class="check_left pro_check_left">
             <label class="form_radio">
              <input type="radio" name="status">
               <span class="checkmark">Do not translate</span>
             </label>
             <label class="form_radio">
              <input type="radio" name="status">
               <span class="checkmark">Always translate</span>
             </label>
              <label class="form_radio">
              <input type="radio" name="status">
               <span class="checkmark">Replace translated string</span>
             </label>
             </div>


             <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String Form</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="">
              </div>
              <div class="form-group">
                <label for="formGroupExampleInput2">String To</label>
                <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="">
              </div>
              </div>
              
              </div>
              <div class="form-group text-right">
                <button type="button" class="btn btn_add_increase" onclick="add_div()"><i class="material-icons">add</i>Add</button>
              </div>

              <button type="submit" class="btn btn-primary btn_str_save">Save</button>
            </form>
          </div> -->
      </div>
    </div>
  </div>
</div>
<script>
  $(".btn_string_proof").click(function(){
    $("#exampleModal3").modal({backdrop: true});
});
    
  // function add_div()
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
  <!-- end add project popup -->




  <!-- string other modal popup -->
 <!-- Modal -->
<div class="modal fade string_other_popup" id="string_other" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> Select Projects for  String Corrections</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
         <i class="material-icons">
          close
          </i>
        </button>
      </div>
      <div class="modal-body">
          <div class="my-project-main string-correction">
              <div class="my-project-frm">

              <div class="row">
                <div class="col-md-12 col-sm-12">
                  <div class="str_project_main">
                <form action="<?php echo e(route('select_string_correction_project')); ?>" method="POST">
                
                  <?php echo csrf_field(); ?>

                  <input type="hidden" value="<?php echo e($ProjectDetail['id']); ?>" name="project_id">
                  <input type="hidden" value="<?php echo e($FromLanguage['id']); ?>" name="from_language">
                  <input type="hidden" value="<?php echo e($ToLanguage['id']); ?>" name="to_language">
                  <div class="form-group">
                    <div class="all-select-all-section">
                          <div class="mutliSelect">
                            <ul>
                              <?php if(!empty($ProjectLists)): ?>
                                <?php $__currentLoopData = $ProjectLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                          <div>
                                              <input id="checkbox-<?php echo e($pro['id']); ?>" class="checkbox-custom" name="project_select[]" type="checkbox" value="<?php echo e($pro['id']); ?>" data-name="<?php echo e($pro['project_name']); ?>">
                                              <label for="checkbox-<?php echo e($pro['id']); ?>" class="checkbox-custom-label"><?php echo e($pro['project_name']); ?></label>
                                        
                                            </div>
                                        </li>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              <?php else: ?>
                                  
                                <li>
                                    <div>
                                  
                                    <label for="checkbox-1" class="checkbox-custom-label">No Project</label>
                                  </div>
                                  </li>

                              <?php endif; ?>
                            </ul>
                            <!-- <div class="sve_middle"><a href="javascript:void(0)" class="str_sve">Update</a></div> -->
                            <!-- <div class="sve_middle str_sve" onclick="SelectProject()">Update</a></div> -->
                            <input type="submit"> Update 
                          </div>
                    </div>
                  </div> 

</form>
                </div>
                </div>
              </div>

            </div>
            </div>
      </div>
    </div>
  </div>
</div>
  <!-- End string other modal popup -->

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
      $(document).ready(function() {
        $(".toggle_value_0 ").click(function(){
		 $(".string_tabs_text").hide();
        
        });  
});
    $(document).ready(function() {
        $(".toggle_value_1 ").click(function(){
		     $(".string_tabs_text").hide();
           $("#str_1").show();
        });  
});

    $(document).ready(function() {
        $(".toggle_value_2").click(function(){
            $(".string_tabs_text").hide();
             $("#str_2").show();
        });  
});
  </script>

<?php $__env->stopSection(); ?>

<script>


function changeValue(val,id)
    {
      // alert(val);
      // alert('#ph_'+id);
      $('#ph_'+id).val(val);
    }


function editDoNotTranslate(id){
 
   var data = document.getElementById('ph_'+id).value;

  $.ajax({
    type: "POST",
    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
    dataType: "json",
    data: {'existingId' : id , 'string' : data }, 

    url: "<?php echo e(route('do_not_translate')); ?>",
    success:function(message){
      // console.log(message);
      location.reload(); 
    }
  });
  
}




function deleteDoNotTranslate(id){
  var r = confirm("Are you sure to delete!");
  
  if (r == true) {
    //alert(id);
        $.ajax({
        type: "GET", 
        headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
        dataType: "json",
        data: {'id' :  id },
        url: "<?php echo e(route('delete_translate_string')); ?>", 
        success:function(message){ 
          // alert("successfully deleted");
          location.reload();
        }
      }).done(function( msg ) {
      });
  }
  else{
    txt = "You pressed Cancel!";
  }
}





function editAlwaysTranslate(id){
 
 var data = document.getElementById('ph_'+id).value;
//  alert(data);

 $.ajax({
    type: "POST",
    headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
    dataType: "json",
    data: {'existingId' : id , 'to_string' : data },

    url: "<?php echo e(route('always_translate_as')); ?>",
    success:function(message){
       console.log(message);
      location.reload(); 
    }
  });

}


</script>


<script>

  function DoNotSearch(project_id,language_id){
    // alert(project_id);
    // alert(language_id);
    var type=1;
    var search = document.getElementById("DoNotStringSearch").value;
    // var length = search.length;
    // alert(length);
    // alert(search);

    if(search.length === 0){
      alert("Please Enter e text!");
      return false;
    } 
    else{
      window.location.href = "<?php echo e(url('/string-correction')); ?>"+'/'+project_id+'/'+language_id+'/'+search+'/'+type;

    }

    
  //   $.ajax({
  //   type: "GET",
  //   // headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
  //   dataType: "json",
  //   // data: {'id' : id , 'to_string' : data },

  //   url: "<?php echo e(url('/string-correction')); ?>"+'/'+project_id+'/'+language_id+'/'+search,
   
  //   success:function(message){
  //      console.log(message);
  //      window.location.href = "<?php echo e(url('/string-correction')); ?>"+'/'+project_id+'/'+language_id+'/'+search;
  //     // location.reload(); 
  //   }
  // });
  }

  function AlwaysSearch(project_id,language_id){ 

    var search = document.getElementById("AlwaysSearch").value;
    var type=2;
    if(search.length === 0){
      alert("Please Enter e text!");
      return false;
    }
    else{
      window.location.href = "<?php echo e(url('/string-correction')); ?>"+'/'+project_id+'/'+language_id+'/'+search+'/'+type;
    }
    

  }

</script>


<script>

function SelectProject(){
  var data = document.getElementById("").val();
  alert("OKAY");
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



$(document).ajaxStart(function(){
// Show image container
$("#main").hide();
$("#exampleModal2").hide();
 $("#loadingmessage").show();
//alert("OKAY");
});
$(document).ajaxComplete(function(){
// Hide image container
$("#loadingmessage").hide();
// $("#main").show();
// $("#exampleModal2").show();

});


$.ajax({
  type: "POST",
  headers: {'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')},
  dataType: "json",
  data: {'project_id' : id , 'from_language' : from_language , 'to_language' : to_language , 'from_string' : from_string , 'to_string' : to_string},
  // url: "<?php echo e(url('/user/replace-translated-string')); ?>",
  url: "<?php echo e(route('replace_translated_string')); ?>",
  success:function(message){
    console.log(message);
    location.reload(); 
  }
});

}

  </script>
<?php echo $__env->make('layouts.home', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\XAMPP1\htdocs\translucio\translucio\resources\views/user/myProject/stringcorrection.blade.php ENDPATH**/ ?>