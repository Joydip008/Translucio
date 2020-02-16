
<div class="modal-dialog profedding-bx">
            <div class="modal-content">
              <div class="modal-header">
                <h3 class="version_title">Version</h3>
                 
                <button type="button" class="close close_version" onclick="closeModal()"><i class="material-icons">close</i>
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
                        <p>{!! $OriginalData !!}</p>
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
                        <p>{!! $MachineTranslateData !!}</p>
                      </div>
                    </div>
                  </li>
                  <!-- <li>
                    <div class="timeline-badge warning"></div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title">Edited version <span class="update_tags">current</span></h4>
                            </div>
                      <div class="timeline-body">
                        <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                      </div>
                    </div>
                  </li> -->
      
      @if(!empty($ChangesDataList))
        @foreach($ChangesDataList as $Data)
                    <li>
                    <div class="timeline-badge warning"></div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                      
                        <h4 class="timeline-title">Edited version <span class="update_tags tag_different">Last updated <?php echo date("jS F , y ",strtotime($Data['created_at'])) ?></span></h4>
                            <div class="back_drop dropdown">
                            @if($Data['status'] == 1)
                                <h4 class="timeline-title"><span class="update_tags">current</span></h4>
                            @else
                                <button onclick="MakeItActual('{{$Data['id']}}')" type="button" class="btn_previous btn_next">Make It Actual</button>
                            @endif
                          </div>
                            </div>
                      <div class="timeline-body">
                        <p>{!! $Data['change_data'] !!}</p>
                      </div>
                    </div>
                  </li>
        @endforeach
      @endif
                    <!-- <li>
                    <div class="timeline-badge warning"></div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title">Currnet version <span class="update_tags tag_different">Last updated 21th July</span></h4>
                            <div class="back_drop dropdown">
                            <button onclick="show_hide()" type="button" class="btn_previous btn_next">make it Actual</button>
                           
                          </div>
                            </div>
                      <div class="timeline-body">
                        <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                      </div>
                    </div>
                  </li> -->
<!--       
                    <li>
                    <div class="timeline-badge warning"></div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title">Currnet version <span class="update_tags tag_different">Last updated 21th July</span></h4>
                            <div class="back_drop dropdown">
                            <button onclick="show_hide()" type="button" class="btn_previous btn_next">make it Actual</button>
                           
                          </div>
                            </div>
                      <div class="timeline-body">
                        <p>Mussum ipsum cacilds, vidis litro abertis. Consetis adipiscings elitis. Pra lá , depois divoltis porris, paradis. Paisis, filhis, espiritis santis.</p>
                      </div>
                    </div>
                  </li> -->
                </ul>
              </div>
            </div>
          </div>

          
          