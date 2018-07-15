<a class="btn btn-danger" id="bulk_delete_btn"><i class="voyager-trash"></i> <span>删除选中</span></a>

<div class="modal modal-danger fade" tabindex="-1" id="bulk_delete_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">
            <i class="voyager-trash"></i> 你确定要删除吗<span id="bulk_delete_count"></span> <span id="bulk_delete_display_name"></span>?
        </h4>
      </div>
      <div class="modal-body" id="bulk_delete_modal_body">
      </div>
      <div class="modal-footer">
        <form action="#" id="bulk_delete_form" method="POST">
          @csrf
          @method('DELETE')
          <input type="hidden" name="ids" id="bulk_delete_input" value="">
          <input type="submit" class="btn btn-danger pull-right delete-confirm" value="是的, 删除这些 {{ strtolower($dataType->display_name_plural) }}">
        </form>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">取消</button>
      </div>
    </div>
  </div>
</div>

<script>
window.onload = function () {
  // Bulk delete selectors
  var $bulkDeleteBtn = $('#bulk_delete_btn');
  var $bulkDeleteModal = $('#bulk_delete_modal');
  var $bulkDeleteCount = $('#bulk_delete_count');
  var $bulkDeleteDisplayName = $('#bulk_delete_display_name');
  var $bulkDeleteInput = $('#bulk_delete_input');
  // Reposition modal to prevent z-index issues
  $bulkDeleteModal.appendTo('body');
  // Bulk delete listener
  $bulkDeleteBtn.click(function () {
    var ids = [];
    var $checkedBoxes = $('#dataTable input[type=checkbox]:checked').not('.select_all');
    var count = $checkedBoxes.length;
    if (count) {
        // Reset input value
        $bulkDeleteInput.val('');
        // Deletion info
        var displayName = count > 1 ? '{{ $dataType->display_name_plural }}' : '{{ $dataType->display_name_singular }}';
        displayName = displayName.toLowerCase();
        $bulkDeleteCount.html(count);
        $bulkDeleteDisplayName.html(displayName);
        // Gather IDs
        $.each($checkedBoxes, function () {
            var value = $(this).val();
            ids.push(value);
        })
        // Set input value
        $bulkDeleteInput.val(ids);
        // Show modal
        $bulkDeleteModal.modal('show');
    } else {
        // No row selected
        toastr.warning('没有选择要删除的内容');
    }
  });
}
</script>
