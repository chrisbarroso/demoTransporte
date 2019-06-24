<div id="message">
  <div id="inner-message" class="alert alert-{{ $AlertType }} fade in">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    @if($AlertType == "success")
    <strong>Éxito:</strong>
    @endif @if($AlertType == "danger")
    <strong>Error:</strong>
    @endif
    <br /> {{ $Msj }}
  </div>
</div>