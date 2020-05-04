<!--- Email threads -->
<div class="list-group-item active text-center" style="cursor: text;">
   Associated Email Communication
</div>
<div class="row">
        <?php if(is_array($emails)){ ?>
        <div class="col-xs-12" id="email_viewer">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 emm">
           <div class="list-group">
            <div class="col-xs-12" style="float:none; background:#ebeaea; padding:10px">
                                  <b>Select Emails</b>
                                </div>
                      <input type="hidden" value="<?php //print_r($emails); ?>" />
                      <!-- email list -->
							
                      <?php foreach($emails as $email){ ?>
                     
                              <div id="<?php echo $email['action_id']; ?>" class="list-group-item text-center">
                                
                                   <input type="hidden" value="<?php echo $email['filename']; ?>" class="filename">
                                    <input type="hidden" value="<?php echo $folderpath.'/'; ?>" class="folderpath">
                                   <span class="toEmail">
                                  <?php  $emailto = str_replace('ExclusiveLineBreak', "\n", $email['to_email']);?>
                                   <?php  $subj = str_replace('ExclusiveLineBreak', "\n", $email['subject']);?>
                                       <span><?php echo $emailto; ?></span><br/>
                                       <span> <?php echo $subj; ?></span>
                                   </span>

                              </div>
                      <?php } ?>
                     
                  </div>
            </div>
<style>
.col-xs-2.tb_close {
    padding-top: 5px;
    padding-left: 38px;
}
</style>            
            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
             <!-- email display-->
             <div class="col-xs-10" style=" background:#ebeaea; padding:10px">
                     <b>Email Body</b>
                   </div>
                    <div class="col-xs-2 tb_close" style="float:right;">
     <button type="button" class="btn btn-default" style="cursor: pointer;" onclick="javascript:window.top.close()">Close Tab and Return</button>
                 </div>
                 <div class="col-xs-12" id="emailDisplay">
                    <div class="alert alert-warning text-center">Please select email from sidebar to view</div>
                 </div>
            </div> 
        </div>
      
        <?php } else{ ?>
          <div class="col-xs-12">
        	<?php if($this->session->flashdata('no_mails')){
				echo $this->session->flashdata('no_mails');
			 } ?>
          </div>
        <?php } ?>
</div>

 <div class="col-xs-2 tb_close" style="float:right;">
     <button type="button" class="btn btn-default" style="cursor: pointer;" onclick="javascript:window.top.close()">Close Tab and Return</button>
                 </div>

<!-- attachment download/view modal -->
<div class="modal fade bs-example-modal-sm" id="attachment_modal" tabindex="-1" role="dialog" aria-labelledby="attachmentAction">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="attachmentAction">
        </h4>
      </div>
      <div class="modal-body">
        <center>
          <a target="_blank" class="btn btn-info" id="view_att" href="" data-value="">View</a>
          <div class="btn btn-success" id="download_att" data-value="">Download</div>
          <form method="post" action="<?php echo base_url('dashboard/downloadFile'); ?>" class="download-form">
            <input type="text" style="display:none" name="path" value="" />
            <input type="text" style="display:none" name="title" value="" />
          </form>
        </center>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">

$(function(){
  /* show email in the container */
  $('.toEmail').click(function(){
    var filename = $(this).parent().find('input.filename').val();
	var folder = $(this).parent().find('input.folderpath').val();
	
    $.get('<?php echo base_url() ?>' + folder + filename, function(data){
       data = data.replace(/ExclusiveLineBreak/g, "<br />");
       //ExclusiveLineBreak is a custom line break to use instead of \n to save in the file
				// It saves from ambiguity
				/* testcase using "\n" instead of ExclusiveLineBreak spoils the email in email_threads page
					  (for debugging purposes) SID : aoFnmXQkp8 UID : 40477 EmailID : scalesplus.cameron@gmail.com
					   */
     messs = $('#emailDisplay').html(data);
	 console.log(messs);
      $('#emailDisplay').find('.attachment-name').attr('data-target','#attachment_modal');
    });
  });

  $('#<?php echo $actionId; ?> .toEmail').click();


  /*  attachment actions  */
  $(document.body).on('click', '.attachment-name', function(){
        var imgpath = $(this).data('url');
        var name    = $(this).html();
        imgpath     = $.trim(imgpath);

        $('#attachment_modal .modal-title').html(name);
        $('#attachment_modal .download-form input[name="title"]').val(name);
        $('#attachment_modal #view_att').attr('href', imgpath);
        $('#attachment_modal .download-form input[name="path"]').val(imgpath);

        $('#download_att').on('click', function(){
          $('#attachment_modal .download-form').submit();
        });
  });


});
$(document).ready(function(){
	$('.emm').css('height', $(window).height());
});
$(window).resize(function(){
	$('.emm').css('height', $(window).height());
});
</script>
<script>

var classHighlight = 'highlight'; 
var $thumbs = $('.text-center').click(function(e) {
    e.preventDefault();
    $thumbs.removeClass(classHighlight);
    $(this).addClass(classHighlight);
});

</script>

<style>
.highlight {
    background-color: #337ab7;
	color:#FFF;
    font-weight: bold;
}
</style>