<template>
  <div>
    <table class="table table-bordered" v-if="displayMode === 'default'">
      <thead>
        <tr style="vertical-align: top">
          <th style="width: 150px !important; height:70px !important;">
            <textarea
              class="form-control"
              size="1"
              style="height=50px; margin:0 !important; overflow:hidden; resize:none"
              placeholder="ابحث برقم العملية هنا"
              v-model="filterId"
            ></textarea>
          </th>
          <th scope="col" ></th>
          <th scope="col"></th>
          <th scope="col"></th>
          <th scope="col"></th>
          <th scope="col" style="vertical-align: middle">تأكيدات الدفع</th>
          <th scope="col" style="vertical-align: middle">حالة العملية</th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="operation in filteredOperations" v-bind:key="operation.id">
          <th>{{ operation.id }}</th>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><button class="btn btn-primary" @click="showConfirmOperationModal(operation)">تأكيدات الدفع</button></td>
          <td><button class="btn btn-success" @click="showConfirmStatusModal(operation)">حالة العملية</button></td>
          <td></td>
        </tr>
        
      </tbody>
    </table>
    <div v-if="!operationsLoaded" style="text-align: center; font-size: 40px;"><i class="icon icon-repeat icon-spin"></i></div>
    <operationconfirm :displayMode="displayMode" :operation="activeOperation" v-on:showDefaultOperationTable="displayMode = 'default'"></operationconfirm>
     <operationstatusmodal :displayMode="displayMode" :operation="activeOperation" v-on:showDefaultOperationTable="displayMode = 'default'"></operationstatusmodal>

  
  </div>
</template>

<script>
import operationconfirm from '../views/operationconfirm.vue'
import operationstatusmodal from '../views/operationStatus.vue'

 export default {
    components: {operationconfirm, operationstatusmodal},
    data(){
      return {
        operations:[],
        filteredOperations: [],
        filterId: '',
        displayMode: 'default',
        operationsLoaded: false,
        activeOperation: null
      }
    },
    created: function(){
      this.getOperations();
    },
    watch: {
        filterId: function(val){
            if(this.filterId){
                    this.filteredOperations = this.operations.filter(
                        operation => operation.id == this.filterId
                    )
            } else{
                this.filteredOperations = this.operations;
            }
            
        },
        operations: function(){
          this.operationsLoaded = true;
        }
    },
    methods: {
      showConfirmOperationModal: function(operation){
        this.displayMode = 'showConfirmOperationModal';
        this.activeOperation = operation;
      },
      showConfirmStatusModal: function(operation){
         this.displayMode = 'showConfirmStatusModal';
         this.activeOperation = operation;
      },
      getOperations: async function(){
        debugger;
        fetch('/operations/get')
        .then(res => res.json())
        .then(res => {
         var fetchedOperations = res;
         this.operations = fetchedOperations;
         this.filteredOperations = fetchedOperations;
        })
        .catch(e => console.log(e));
      }
    
    }
 }
 </script>