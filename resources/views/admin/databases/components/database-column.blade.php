@section('database-column-template')

  <tr class="newTableRow">
    <td>
      <input :value="column.name" @input="onColumnNameInput" type="text" class="form-control" required
             pattern="{{ $db->identifierRegex }}">
    </td>
    <td>
      <database-types
        :column="column"
        @typeChanged="onColumnTypeChange">
      </database-types>
    </td>
    <td>
      <input v-model.number="column.length" :type="lengthInputType" min="0">
    </td>
    <td>
      <input v-model="column.notnull" type="checkbox">
    </td>
    <td>
      <input v-model="column.unsigned" type="checkbox">
    </td>
    <td>
      <input v-model="column.autoincrement" type="checkbox">
    </td>
    <td>
      <select :value="index.type" @change="onIndexTypeChange"
              :disabled="column.type.notSupportIndex"
              class="form-control"
      >
        <option value=""></option>
        <option value="INDEX">{{ __('普通索引') }}</option>
        <option value="UNIQUE">{{ __('唯一索引') }}</option>
        <option value="PRIMARY">{{ __('主键索引') }}</option>
      </select>
      <small v-if="column.composite" v-once>{{ __('警告：此列是复合索引的一部分') }}</small>
    </td>
    <td>
      <database-column-default :column="column"></database-column-default>
    </td>
    <td>
      <div class="btn btn-danger delete-row" @click="deleteColumn"><i class="voyager-trash"></i></div>
    </td>
  </tr>

@endsection

@include('admin.databases.components.database-type')
@include('admin.databases.components.database-column-default')

<script>
  Vue.component('database-column', {
    data: function () {
      return {
        lengthInputType: 'number'
      }
    },
    props: {
      column: {
        type: Object,
        required: true
      },
      index: {
        type: Object,
        required: true
      }
    },
    template: `@yield('database-column-template')`,
    methods: {
      deleteColumn() {
        this.$emit('columnDeleted', this.column);
        // todo: add an UNDO button or something in case the user mistakenly deletes the column
      },
      onColumnNameInput(event) {
        let newName = event.target.value;

        this.$emit('columnNameUpdated', {
          column: this.column,
          newName: newName
        });
      },
      onColumnTypeChange(type) {
        if (type.notSupportIndex && this.index.type) {
          this.$emit('indexDeleted', this.index);
        }

        // Reset default value
        this.column.default = null;
        this.column.type = type;
        this.setLengthInputType();
      },
      onIndexTypeChange(event) {
        if (this.column.name === '') {
          return toastr.error("{{ __('请在添加索引之前给列命名') }}");
        }

        return this.$emit('indexChanged', {
          columns: [this.column.name],
          old: this.index,
          newType: event.target.value
        });
      },
      setLengthInputType() {
        let name = this.column.type.name;
        if (name === 'double' || name === 'float' || name === 'decimal') {
          this.lengthInputType = 'text';
        } else {
          this.lengthInputType = 'number';
        }
      }
    },
    mounted() {
      this.setLengthInputType();
    },
  });
</script>
