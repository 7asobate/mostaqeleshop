<template>
  <div
    class="div"
    tabindex="-1"
    id="operationConfirmModal"
    v-if="this.displayMode === 'showConfirmOperationModal'"
  >
    <div class="">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">تأكيدات الدفع .. عملية رقم #{{ operation.id }}</h5>
           <button
            type="button"
            class="btn btn-light"
            @click="getOperationConfirmation"
            >
            إعادة تعيين <i class="icon-refresh"></i>
          </button>
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
              <input name="description" type="text" class="form-control" placeholder="الوصف" v-model="row.description"/>
            </div>
            <div class="col-3"> 
              <input type="file" class="form-control" @change="onFileChange($event, row)" />
            </div>
            <div class="col-2">
              <a v-if="row.file" :href="row.file" target="_blank" download>إظهار الملف</a>
            </div>
            <div class="col-1">
              <button v-if="operationConfirmInputRows.length > 1 && operationConfirmInputRows[operationConfirmInputRows.length - 1].id == row.id" 
                  class="btn btn-danger" type="button" :fx="row.id" @click="removeOperationInputRow(row.id)"> <i  style="font-size: 18px;" class="icon-remove-circle"></i></button>
              <button v-else class="btn btn-success" type="button" :fx="index" @click="addOperationInputRow(operationConfirmInputRows[operationConfirmInputRows.length - 1].id + 1)">
                <i style="font-size: 18px;" class="icon-plus-sign"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="saveOperationConfirm">تأكيد <i v-if="loadingConfirm" class="icon icon-repeat icon-spin"></i></button>
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
       if(val == "showConfirmOperationModal"){
         debugger;
         this.operationConfirmInputRows = [];
         this.confirmLoaded = false;
         this.getOperationConfirmation();
       }
    },
    operationConfirmInputRows: {
      handler: function(){
        var operationConfirmInputRows = this.operationConfirmInputRows.filter(function(val){
          return val.description;
        });
 
        var formData = new FormData();
        operationConfirmInputRows.map(function(val, index){
          var uploadFile = val.uploadFile != undefined ? val.uploadFile : '';
          var file = val.file != undefined ? val.file : '';
          formData.append('rows[id][' + index + ']', val.id);
          formData.append('rows[description][' + index + ']', val.description);
          formData.append('rows[uploadFile][' + index + ']', uploadFile);
          formData.append('rows[file][' + index + ']',file);
        })
  
        this.operationConfirmInputRowsFormData = formData;
      },
      deep: true
    }
  },


  methods: {
    

      onFileChange(e, row) {
        var createdFile = '';

        var files = e.target.files || e.dataTransfer.files;
        if (files.length){

          row['uploadFile'] = files[0];
          row['file'] = '';

          this.operationConfirmInputRows = this.operationConfirmInputRows.filter(function(operation){
              if(row && operation.id == row.id){
                return row;
              }
              return operation;
            })

          //this.createFile(files[0], row);
        }
      },

      createFile : function(file, row) {
          var image = new Image();
          var reader = new FileReader();
          var vm = this;

          reader.onload = (e) => {
            
              var createdFile = reader.result;
              if(createdFile){
              row['uploadFile'] = createdFile;

              this.operationConfirmInputRows = this.operationConfirmInputRows.filter(function(operation){
                if(row && operation.id == row.id){
                  return row;
                }
                return operation;
              })
            }
          };
          reader.readAsDataURL(file);            

     },

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
      saveOperationConfirm:  async function(){
        this.loadingConfirm = true;
        
        fetch('/operations/operationConfirm/' + String(this.operation.id) + '/save', {
            headers: {
              "X-CSRF-Token": $('[name="csrf-token"]').attr('content'),
            },
            method: 'post',
            body: this.operationConfirmInputRowsFormData,
  
        })
        .then(res => res.json())
        .then(res => {
          console.log(res);
   
          var fetchedOperationConfirm = [];
          res.map(function(row){
           fetchedOperationConfirm.push({ 
              id: row.id,
              description: row.description,
              file: row.fileUrl,
              uploadFile: ''
          });
          });
          this.operationConfirmInputRows = fetchedOperationConfirm;

          this.loadingConfirm = false;
        
        })
        .catch(e => console.log(e));
      },

      removeOperationInputRow: function(key){
          this.operationConfirmInputRows = this.operationConfirmInputRows.filter(
            row => row.id != key
          )
      },
      addOperationInputRow: function(key){
           this.operationConfirmInputRows = this.operationConfirmInputRows.concat(
              {
                  id: key,
                  'description': '',
                  'file': '',
                  'uploadFile': ''
              }
          )
      }
  },
  props: ["operation", "displayMode"],
};
</script>