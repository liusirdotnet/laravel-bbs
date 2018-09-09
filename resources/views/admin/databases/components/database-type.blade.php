@section('database-type-template')
  <div>
    <select :value="column.type.name" @change="onTypeChange" class="form-control">
      <optgroup v-for="(types, category) in dbTypes" :label="category">
        <option v-for="type in types" :value="type.name" :disabled="type.notSupported">
          @{{ type.name.toUpperCase() }}
        </option>
      </optgroup>
    </select>
    <div v-if="column.type.notSupported">
      <small>{{ __('不支持这种类型') }}</small>
    </div>
  </div>
@endsection

<script>
  let databaseTypes = {!! json_encode($db->types) !!};

  function getDbType(name) {
    let type;
    name = name.toLowerCase().trim();

    for (category in databaseTypes) {
      type = databaseTypes[category].find(function (type) {
        return name === type.name.toLowerCase();
      });

      if (type) {
        return type;
      }
    }
    toastr.error("{{ __('未知类型') }}: " + name);

    // fallback to a default type
    return databaseTypes.Numbers[0];
  }

  Vue.component('database-type', {
    props: {
      column: {
        type: Object,
        required: true
      }
    },
    data() {
      return {
        dbTypes: databaseTypes
      };
    },
    template: `@yield('database-type-template')`,
    methods: {
      onTypeChange(event) {
        this.$emit('typeChanged', this.getType(event.target.value));
      },
      getType(name) {
        return getDbType(name);
      }
    }
  });
</script>
