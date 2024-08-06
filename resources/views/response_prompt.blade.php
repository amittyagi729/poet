<div class="modal fade" id="exampleModal<?php echo $promptinfo->id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">{{ $promptinfo->title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true" style="font-size: 31px;color: #25282B;">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <span class="pr_title"><strong>Deadline date: </span></strong><span id="promptname1"> <?php  echo $newDate = date("m/d/Y", strtotime($promptinfo->deadline_date));  ?> </span>
        </br>
        <span class="pr_title"><strong>Word limit: </span></strong><span id="promptname1"> {{ $promptinfo->word_limit }} </span>
        </br>
        <span class="pr_title"><strong>Description: </span></strong><span id="promptname1"> {!! $promptinfo->description !!} </span>
        
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
      </div>
    </div>
  </div>
</div>