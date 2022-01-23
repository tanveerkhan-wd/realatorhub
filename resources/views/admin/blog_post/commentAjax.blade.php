<!-- 
View File for  Comment View AJAX
@package    Laravel
@subpackage View
@since      1.0
 -->
<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
      <h4 class="modal-title">Blog Title : {{ $data['title'] }}</h4>
    </div>
    <div class="modal-body" style="max-height: 400px;overflow-y: auto;">
     <!--  <p><strong>User Name : </strong>{{ $data['name'] }}</p>  

      <p><strong>Comment :</strong><br> {{ $data['comment'] }}</p> -->

      <div class="form-group">
        <label>User Name :</label>
        <input type="text" name="" value="{{ $data['name'] }}" readonly class="form-control">
      </div>
      <div class="form-group">
        <label>Comment :</label>
        <textarea value="" readonly class="form-control">{{ $data['comment'] }}</textarea>
      </div>
    </div>
    <div class="modal-footer">
      @if($data['status'] == 'Disapproved')
        <button type="button" class="btn btn-primary pull-center changeStatus" data-url="{{ url('admin/blog-comment/activeInactive/'.$data['id']) }}" data-dismiss="modal">Approved</button>
      @else
        <button type="button" class="btn btn-danger pull-center changeStatus" data-url="{{ url('admin/blog-comment/activeInactive/'.$data['id']) }}" data-dismiss="modal">Disapproved</button>
      @endif
      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
    </div>
  </div>
  <!-- /.modal-content -->
</div>