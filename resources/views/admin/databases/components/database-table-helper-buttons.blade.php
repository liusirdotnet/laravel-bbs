@section('database-table-helper-buttons-template')
  <div>
    <div class="btn btn-success" @click="addNewColumn">+ {{ __('添加新列') }}</div>
    <div class="btn btn-success" @click="addTimestamps">+ {{ __('添加时间戳') }}</div>
    <div class="btn btn-success" @click="addSoftDeletes">+ {{ __('添加软删除') }}</div>
  </div>
@endsection

<script>
  Vue.component('database-table-helper-buttons', {
    template: `@yield('database-table-helper-buttons-template')`,
    methods: {
      addColumn(column) {
        this.$emit('columnAdded', column);
      },
      makeColumn(options) {
        return $.extend({
          name: '',
          oldName: '',
          type: getDbType('integer'),
          length: null,
          fixed: false,
          unsigned: false,
          autoincrement: false,
          notnull: false,
          default: null
        }, options);
      },
      addNewColumn() {
        this.addColumn(this.makeColumn());
      },
      addTimestamps() {
        this.addColumn(this.makeColumn({
          name: 'created_at',
          type: getDbType('timestamp')
        }));

        this.addColumn(this.makeColumn({
          name: 'updated_at',
          type: getDbType('timestamp')
        }));
      },
      addSoftDeletes() {
        this.addColumn(this.makeColumn({
          name: 'deleted_at',
          type: getDbType('timestamp')
        }));
      }
    }
  });
</script>
