<?php 

$flashMsgOk = Lib\FlashMsg::getMsgOk();
$flashMsgError = Lib\FlashMsg::getMsgError();

if (!empty($flashMsgOk)) { ?>
      <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Aviso</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                             <div  class="alert alert-success">AVISO: <?php echo $flashMsgOk ?></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
}

if (!empty($flashMsgError)) {
    ?>
          <div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Erro</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                             <div class="alert alert-danger"><?php echo $flashMsgError ?></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
} ?>

<script type="text/javascript">
         jQuery(document).ready( function (){
             jQuery("#alertModal").modal("show");      
         });
</script>
