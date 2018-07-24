<a class="btn btn-danger" id="bulk_delete_btn"><i class="voyager-trash"></i> <span>删除选中</span></a>

<div class="modal modal-danger fade" tabindex="-1" id="bulk_delete_modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">
          <i class="voyager-trash"></i>
          你确定要删除<span id="bulk_delete_count"></span>个<span id="bulk_delete_display_name"></span>吗?
        </h4>
      </div>
      <div class="modal-body" id="bulk_delete_modal_body"></div>
      <div class="modal-footer">
        <form action="{{ route('admin.'.$dataType->slug.'.index') }}/0" id="bulk_delete_form" method="POST">
          @csrf
          @method('DELETE')
          <input type="hidden" name="ids" id="bulk_delete_input">
          <input type="submit" class="btn btn-danger pull-right delete-confirm"
                 value="是的，删除选中的{{ $dataType->display_name_singular }}">
        </form>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">取消</button>
      </div>
    </div>
  </div>
</div>

<script>
  window.onload = function () {
    // Bulk delete selectors.
    let $bulkDeleteBtn = $('#bulk_delete_btn');
    let $bulkDeleteModal = $('#bulk_delete_modal');
    let $bulkDeleteCount = $('#bulk_delete_count');
    let $bulkDeleteDisplayName = $('#bulk_delete_display_name');
    let $bulkDeleteInput = $('#bulk_delete_input');

    // Reposition modal to prevent z-index issues.
    $bulkDeleteModal.appendTo('body');

    // Bulk delete listener.
    $bulkDeleteBtn.click(function () {
      let ids = [];
      let $checkedBoxes = $('#dataTable input[type=checkbox]:checked').not('.select_all');
      let count = $checkedBoxes.length;
      if (count) {
        // Reset input value.
        $bulkDeleteInput.val('');

        // Deletion info.
        let displayName = count > 1 ? '{{ $dataType->display_name_plural }}' : '{{ $dataType->display_name_singular }}';
        $bulkDeleteCount.html(count);
        $bulkDeleteDisplayName.html(displayName);

        // Gather IDs.
        $.each($checkedBoxes, function () {
          let value = $(this).val();
          ids.push(value);
        });

        // Set input value.
        $bulkDeleteInput.val(ids);

        // Show modal.
        $bulkDeleteModal.modal('show');
      } else {
        // No row selected.
        toastr.warning('没有选择要删除的内容');
      }
    });
  }
</script>
