<template>
  <div
    class="div"
    tabindex="-1"
    id="operationConfirmModal"
    v-if="this.displayMode === 'showConfirmStatusModal'"
  >
    <div class="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">حالة العملية #{{ operation.id }}</h5>
          <button
            type="button"
            class="btn btn-light"
            @click="$emit('showDefaultOperationTable')"
          >
            رجوع <i class="icon-circle-arrow-left"></i>
          </button>
        </div>
        <div class="modal-body">
          <div v-if="!confirmLoaded" style="text-align: center; font-size: 40px;"><i class="icon icon-repeat icon-spin"></i></div>
          <div class="row mb-2" v-for="(row, index) in operationConfirmInputRows" :key="row.id">
            <div class="col-6">
                <h6>{{ row.description }}</h6>
            </div>
            <div class="col-4">
              <a v-if="row.file" :href="row.file" target="_blank" download>إظهار الملف</a>
            </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
        operationConfirmInputRows: [],
        operationConfirmInputRowsFormData: [],
        operationConfirm : [],
        confirmLoaded: false,
        loadingConfirm: false
    };
  },

  watch: {
    displayMode: function(val){
       if(val == "showConfirmStatusModal"){
         debugger;
         this.operationConfirmInputRows = [];
         this.confirmLoaded = false;
         this.getOperationConfirmation();
       }
    },
  },

  methods: {
    
      getOperationConfirmation: async function(){
        
        this.confirmLoaded = false;
        this.operationConfirmInputRows = [];
        fetch('/operations/operationConfirm/' + String(this.operation.id) + '/get')
        .then(res => res.json())
        .then(res => {
         this.confirmLoaded = true;
        var fetchedOperationConfirm = [];
         res.map(function(row){
           fetchedOperationConfirm.push({ 
              id: row.id,
              description: row.description,
              file: row.fileUrl,
              uploadFile: ''
          })
         });
          

         this.operationConfirmInputRows = fetchedOperationConfirm;

          var placeholderInputRowIndex = this.operationConfirmInputRows.length ?
          this.operationConfirmInputRows[this.operationConfirmInputRows.length].id + 1 : 1;
          this.addOperationInputRow(placeholderInputRowIndex);

        })
        .catch(e => console.log(e));

       
      },
    
  },
  props: ["operation", "displayMode"],
};
</script>