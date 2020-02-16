@extends('layouts.home')
@section('title')
TRANSLICIO | Become A Proofreader
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


  <!-- start my profile section css your document section -->
  <div class="translate-document-main personal-main">
    <div class="container custom-con">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="translate-heading profile-heading">Proofreading</h2>
          <div class="credit_history">
            <div class="col-sm-12 col-md-12">
              <form method="" action="" class="frm_proof_sub">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <div class="selectbox proof">
                        <label for="content">Content Type</label>
                        <select class="form-control" id="">
                          <option>Visible text</option>
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
                        <select class="form-control" id="">
                          <option>All</option>
                          <option>Approved</option>
                          <option>Not approved</option>
                        </select> <i class="material-icons">keyboard_arrow_down</i>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 text-right">
                   <a class="btn btn-primary btn btn_add_proof" data-toggle="modal" data-target="#exampleModal2"><i class="material-icons">add</i>Add String Correction
                    </a>
                  </div>
                </div>
              </form>
            </div>
            <div class="replace_table">
              <div class="string_correction_1 ">
                <div class="string_correction">
                  <!--Table-->
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="th-lg">Translated from <span style="color: #716FEA">English</span>
                        </th>
                        <th class="th-lg">Translated into <span style="color: #716FEA">Spanish</span>
                        </th>
                        <th class="th-lg">Action</th>
                      </tr>
                    </thead>
                    <!--Table head-->
                    <!--Table body-->
                    <tbody>
                      <tr>
                        <td>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</td>
                        <td id="" class="common-width">
                          <p class="mb-0 content-editable">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</p>
                          <p class="approved_head">Approved <span>Approved by : John Doe at 29/09/2019 10:30 PM </span>
                              <button class="Version-btn" data-toggle="modal" href="#ignismyModal">Version</button>
                          </p>
                        </td>
                        <td style="color: #716FEA">
                          <input type="button" id="edit_button1" value="Edit" class="edit string-edit-btn">
                          <input type="button" id="save_button1" value="Save" class="save save_dis" onclick="save_row('1')">
                          <!--  <button class="approve-btn">Approve</button> -->
                        </td>
                      </tr>
                      <tr>
                        <td>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</td>
                        <td id="" class="common-width">
                          <p class="mb-0 content-editable">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</p>
                        <!--   <p class="reject_cnt">Rejected</p> -->
                        </td>
                        <td style="color: #716FEA">
                          <input type="button" id="edit_button1" value="Edit" class="edit string-edit-btn">
                          <input type="button" id="save_button1" value="Save" class="save save_dis" onclick="save_row('1')">
                          <button class="approve-btn">Approve</button>
                        </td>
                      </tr>
                      <tr>
                        <td>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</td>
                        <td id="" class="common-width">
                          <p class="mb-0 content-editable">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</p>
                        </td>
                        <td style="color: #716FEA">
                          <input type="button" id="edit_button1" value="Edit" class="edit string-edit-btn">
                          <input type="button" id="save_button1" value="Save" class="save save_dis" onclick="save_row('1')">
                          <button class="approve-btn">Approve</button>
                        </td>
                      </tr>
                      <tr>
                        <td>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</td>
                        <td id="" class="common-width">
                          <p class="mb-0 content-editable">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</p>
                          <p class="approved_head">Approved <span>Approved by : John Doe at 29/09/2019 10:30 PM </span>
                              <button class="Version-btn" data-toggle="modal" href="#ignismyModal">Version</button>
                          </p>
                        </td>
                        <td style="color: #716FEA">
                          <input type="button" id="edit_button1" value="Edit" class="edit string-edit-btn">
                          <input type="button" id="save_button1" value="Save" class="save save_dis" onclick="save_row('1')">
                          <!--    <button class="approve-btn">Approve</button> -->
                        </td>
                      </tr>
                      <tr>
                        <td>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</td>
                        <td id="" class="common-width">
                          <p class="mb-0 content-editable">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</p>
                         <!--  <p class="reject_cnt">Rejected</p> -->
                        </td>
                        <td style="color: #716FEA">
                          <input type="button" id="edit_button1" value="Edit" class="edit string-edit-btn">
                          <input type="button" id="save_button1" value="Save" class="save save_dis" onclick="save_row('1')">
                          <button class="approve-btn">Approve</button>
                        </td>
                      </tr>
                      <tr>
                        <td>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</td>
                        <td id="" class="common-width">
                          <p class="mb-0 content-editable">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</p>
                        </td>
                        <td style="color: #716FEA">
                          <input type="button" id="edit_button1" value="Edit" class="edit string-edit-btn">
                          <input type="button" id="save_button1" value="Save" class="save save_dis" onclick="save_row('1')">
                          <button class="approve-btn">Approve</button>
                        </td>
                      </tr>
                      <tr>
                        <td>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</td>
                        <td id="" class="common-width">
                          <p class="mb-0 content-editable">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a</p>
                          <p class="approved_head">Approved <span>Approved by : John Doe at 29/09/2019 10:30 PM </span>
                            <button class="Version-btn" data-toggle="modal" href="#ignismyModal">Version</button>
                          </p>
                        </td>
                        <td style="color: #716FEA">
                          <input type="button" id="edit_button1" value="Edit" class="edit string-edit-btn">
                          <input type="button" id="save_button1" value="Save" class="save save_dis" onclick="save_row('1')">
                          <!--       <button class="approve-btn">Approve</button> -->
                        </td>
                      </tr>
                    </tbody>
                    <!--Table body-->
                  </table>
                  <!--Table-->
                </div>
                <div class="col-md-12">
                  <div class="pagination_right">
                    <ul>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)"><i class="fa fa-angle-left" aria-hidden="true"></i></a>
                      </li>
                      <li><span class="activea">1</span>
                      </li>
                      <li><a href="javascript:void(0)" class="bg-none">2</a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-right"
                            aria-hidden="true"></i></a>
                      </li>
                      <li><a href="javascript:void(0)" class="bg_dark"><i class="fa fa-angle-double-right"
                            aria-hidden="true"></i></a>
                      </li>
                    </ul>
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
   <div class="modal fade" id="ignismyModal" role="dialog"  data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog profedding-bx">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="version_title">Version</h3>
          <!--  <div class="frm_headin">
                    <form method="" action="" class="frm_proof">
                        <div class="container">
                          <div class="row">
                            <div class="col-md-3">
                            <div class="profeding_search">
                           <div class="form-group">
                            <label>Search Your Text</label>
                            <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                            <span class="search_proff"><img src="assets/images/icons/search.png" class="img-responsive"></span>
                          </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="filter">Filter Language</label>
                            <select class="form-control" id="">
                              <option>English</option>
                              <option>French</option>
                              <option>Bengali</option>
                              <option>Hindi</option>
                            </select>
                          </div>
                         </div>
                           <div class="col-md-3">
                          <div class="form-group">
                            <label for="content">Content Type</label>
                            <select class="form-control" id="">
                              <option>Visible text</option>
                              <option>Metadata</option>
                              <option>Media</option>
                            </select>
                          </div>
                         </div>
                           <div class="col-md-3">
                          <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="">
                              <option>Approved</option>
                              <option>Not approved</option>
                            </select>
                          </div>
                         </div>
                        </div>
                        </div>
                      </form>
                      </div> -->
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
  <script src="assets/js/jquery-1.9.1.min.js"></script>
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/custom.js"></script>
  <script>
    $('body').on('click', '.string-edit-btn', function () {
          var editelement = $(this).parents('tr').find('.content-editable');;
          editelement.attr('contenteditable', 'true').focus();
          editelement.parent("td").css('background', '#f2f2f2');
          $(this).css('display', 'none');
          $(this).next('.save').css('display', 'inline-block');
        });
        $('body').on('click', '.save_dis', function () {
          var editelement = $(this).parents('tr').find('.content-editable');;
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
        <h2 class="add_heading">Add New  String Corrections</h2>
          <button type="button" class="close close_add" data-dismiss="modal" aria-label="Close"> <i class="material-icons">close</i>
          </button>
      </div>
 <div class="modal-body">
       <div class="col-xs-12 ">
          <div class="string-form">
            <form method="" action="">
        <nav>
          <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Do not translate</a>
            <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Always translate as</a>
            <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">Replace translated string</a>
          </div>
        </nav>
        <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
              <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">Do not Translate</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="">
              </div>
              </div>
              </div>
                <button type="submit" class="btn btn-primary btn_str_save">Add</button>
          </div>

          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                   <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String From (German)</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="">
              </div>
                <div class="form-group">
                <label for="formGroupExampleInput">String To (Spanish)</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="">
              </div>
              </div>
              </div>
              <div class="form-group text-right">
                <button type="button" class="btn btn_add_increase"><i class="material-icons">add</i>Add</button>
              </div>

              <button type="submit" class="btn btn-primary btn_str_save">Save</button>
          </div>

          <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
           <div class="append_main">
             <div class="str_increment">
              <div class="form-group">
                <label for="formGroupExampleInput">String From (German)</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="">
              </div>
                <div class="form-group">
                <label for="formGroupExampleInput">String To (German)</label>
                <input type="text" class="form-control" id="formGroupExampleInput" placeholder="">
              </div>
              </div>
              </div>
              <div class="form-group text-right">
                <button type="button" class="btn btn_add_increase"><i class="material-icons">add</i>Add</button>
              </div>

              <button type="submit" class="btn btn-primary btn_str_save">Save</button>
          </div>
        </div>
        </form>
      </div>
      </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(".btn_add_proof").click(function(){
    $("#exampleModal2").modal({backdrop: false});
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
  <!-- end add project popup -->

@endsection