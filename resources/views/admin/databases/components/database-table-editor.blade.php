@section('database-table-editor-template')
  <div class="panel panel-bordered">
    <div class="panel-body">
      <div class="row">
        @if($db->action === 'update')
          <div class="col-md-12">
            @else
              <div class="col-md-6">
                @endif
                <label for="name">{{ __('表名') }}</label><br>
                <input v-model.trim="table.name" type="text" class="form-control"
                       placeholder="{{ __('表名') }}" required
                       pattern="{{ $db->identifierRegex }}">
              </div>

              @if($db->action === 'create')
                <div class="col-md-3 col-sm-4 col-xs-6">
                  <label for="create_model">{{ __('创建模型表') }}</label><br>
                  <input type="checkbox" name="create_model" data-toggle="toggle"
                         data-on="{{ __('是的，就这样做') }}"
                         data-off="{{ __('不，谢谢') }}"
                  >
                </div>
              @endif
          </div>

          <div v-if="compositeIndexes.length" v-once class="alert alert-danger">
            <p>{{ __('此表有复合索引。请注意，他们目前不受支持。在尝试添加 / 删除索引时要小心。') }}</p>
          </div>

          <div id="alertsContainer"></div>

          <template v-if="tableHasColumns">
            <p>{{ __('表列') }}</p>
            <table class="table table-bordered" style="width:100%;">
              <thead>
              <tr>
                <th>{{ __('名称') }}</th>
                <th>{{ __('类型') }}</th>
                <th>{{ __('长度') }}</th>
                <th>{{ __('不为空') }}</th>
                <th>{{ __('无符号') }}</th>
                <th>{{ __('自增') }}</th>
                <th>{{ __('索引') }}</th>
                <th>{{ __('默认') }}</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
              <database-column
                v-for="(column, index) in table.columns"
                :column="column"
                :index="getColumnsIndex(column.name)"
                :key="index"
                @columnNameUpdated="renameColumn"
                @columnDeleted="deleteColumn"
                @indexAdded="addIndex"
                @indexDeleted="deleteIndex"
                @indexUpdated="updateIndex"
                @indexChanged="onIndexChange"
              ></database-column>
              </tbody>
            </table>
          </template>
          <div v-else>
            <p>{{ __('表没有列') }}</p>
          </div>
          <div style="text-align:center">
            <database-table-helper-buttons @columnAdded="addColumn"></database-table-helper-buttons>
          </div>
      </div>

      <div class="panel-footer">
        <input type="submit" class="btn btn-primary pull-right"
               value="@if($db->action === 'update'){{ __('更新表') }}@else{{ __('创建表') }}@endif"
               :disabled="!tableHasColumns">
        <div style="clear:both"></div>
      </div>
    </div>
  </div>
@endsection

@include('admin.databases.components.database-column')
@include('admin.databases.components.database-table-helper-buttons')

<script>
  Vue.component('database-table-editor', {
    props: {
      table: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        emptyIndex: {
          type: '',
          name: ''
        },
        compositeIndexes: []
      };
    },
    template: `@yield('database-table-editor-template')`,
    mounted() {
      // Add warning to columns that are part of a composite index
      this.compositeIndexes = this.getCompositeIndexes();
      let compositeColumns = this.getIndexesColumns(this.compositeIndexes);

      for (col in compositeColumns) {
        this.getColumn(compositeColumns[col]).composite = true;
      }

      // Display errors
      @if (\Illuminate\Support\Facades\Session::has('alerts'))
      helpers.displayAlerts(alerts, helpers.bootstrapAlerter({dismissible: true}), 'error');
      @endif
    },
    computed: {
      tableHasColumns() {
        return this.table.columns.length;
      }
    },
    methods: {
      addColumn(column) {
        column.name = column.name.trim();

        if (column.name && this.hasColumn(column.name)) {
          return toastr.error("{{ __('列') }} " + column.name + " {{ __('已经存在') }}");
        }

        this.table.columns.push(
          JSON.parse(JSON.stringify(column))
        );
      },
      getColumn(name) {
        name = name.toLowerCase().trim();

        return this.table.columns.find(function (column) {
          return name === column.name.toLowerCase();
        });
      },
      hasColumn(name) {
        return !!this.getColumn(name);
      },
      renameColumn(column) {
        let newName = column.newName.trim();
        column = column.column;

        let existingColumn;
        if ((existingColumn = this.getColumn(newName)) && (existingColumn !== column)) {
          return toastr.error("{{ __('列') }} " + newName + " {{ __('已经存在') }}");
        }

        let index = this.getColumnsIndex(column.name);
        if (index !== this.emptyIndex) {
          index.columns = [newName];
        }

        column.name = newName;
      },
      deleteColumn(column) {
        let columnPos = this.table.columns.indexOf(column);

        if (columnPos !== -1) {
          this.table.columns.splice(columnPos, 1);

          // Delete associated index
          this.deleteIndex(this.getColumnsIndex(column.name));
        }
      },
      getColumnsIndex(columns) {
        // todo: detect if a column has a composite index
        //  if so, maybe disable its Index input, and tell the user to go to special Index form (advanced view)?
        if (!Array.isArray(columns)) {
          columns = [columns];
        }

        let index = null;

        for (let i in this.table.indexes) {
          // if there is no difference between columns
          if (!($(this.table.indexes[i].columns).not(columns).get().length)) {
            index = this.table.indexes[i];
            break;
          }
        }

        if (!index) {
          index = this.emptyIndex;
        }

        index.table = this.table.name;
        return index;
      },
      onIndexChange(index) {
        if (index.old === this.emptyIndex) {
          return this.addIndex({
            columns: index.columns,
            type: index.newType
          });
        }

        if (index.newType === '') {
          return this.deleteIndex(index.old);
        }

        return this.updateIndex(index.old, index.newType);
      },
      addIndex(index) {
        if (index.type === 'PRIMARY') {
          if (this.table.primaryKeyName) {
            return toastr.error("{{ __('表已经有索引') }}");
          }

          this.table.primaryKeyName = 'primary';
        }

        this.setIndexName(index);
        this.table.indexes.push(index);
      },
      deleteIndex(index) {
        var indexPos = this.table.indexes.indexOf(index);

        if (indexPos !== -1) {
          if (index.type === 'PRIMARY') {
            this.table.primaryKeyName = false;
          }

          this.table.indexes.splice(indexPos, 1);
        }
      },
      updateIndex(index, newType) {
        if (index.type === 'PRIMARY') {
          this.table.primaryKeyName = false;
        } else if (newType === 'PRIMARY') {
          if (this.table.primaryKeyName) {
            return toastr.error("{{ __('表已经有索引') }}");
          }

          this.table.primaryKeyName = 'primary';
        }

        index.type = newType;
        this.setIndexName(index);
      },
      setIndexName(index) {
        if (index.type === 'PRIMARY') {
          index.name = 'primary';
        } else {
          // the name will be set on the server by PHP
          index.name = '';
        }
      },
      getCompositeIndexes() {
        let composite = [];

        for (let i in this.table.indexes) {
          if (this.table.indexes[i].isComposite) {
            composite.push(this.table.indexes[i]);
          }
        }

        return composite;
      },
      getIndexesColumns(indexes) {
        let columns = [];

        for (let i in indexes) {
          for (col in indexes[i].columns) {
            columns.push(indexes[i].columns[col]);
          }
        }

        return [...new Set(columns)];
      }
    }
  });
</script>
