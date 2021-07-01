<!-- messageComponent -->
<template>

  
  <div>
    
    <input type="text" v-model="message" />
    <p>Messege is: "{{ message }}"</p>
    <p v-if="showSecretMessage">Secret message shown on {{ this.date }}</p>
    <button type="button" class="btn btn-primary" v-on:click="toggleSecretMessage">show/hide message</button>
    <button type="button" class="btn btn-primary" @click="changeList">Change List</button>
    <button type="button" class="btn btn-primary" @click="deleteMiddleItem">Delete Middle Item</button>
      <button type="button" class="btn btn-primary" @click="resetList">Reset List</button>


    <!-- <ol>
      <li v-for="listItem in list" :key="listItem">{{listItem }}</li>
    </ol> -->
    Transition List:
    <transition-group name="list"  mode="out-in" tag="p">
      <p v-for="item in list" v-bind:key="item" class="list-item">{{item}}</p>
    </transition-group>
    <div id="list-demo">
  <button v-on:click="add">Add</button>
  <button v-on:click="remove">Remove</button>
  <transition-group name="list" tag="p">
    <span v-for="item in items" v-bind:key="item" class="list-item">
      {{ item }}
    </span>
  </transition-group>
</div>
    <!-- Reversed list:
    <ol>
      <li v-for="reversedListItem in reversedList" :key="reversedListItem">{{ reversedListItem }}</li>
    </ol> -->
  </div>
</template>
<script>
 export default {
    data(){
      return {
        message: 'My Message',
        showSecretMessage: true,
        date: new Date().toLocaleString(),
        list: [
          'listItem1', 'listItem2', 'listItem3'
        ],
         items: [1,2,3,4,5,6,7,8,9],
         nextNum: 10
      }
    },
    computed: {
      reversedList: function(){
        return [].concat(this.list).reverse();
      }
    },
    methods: {
      completeMessage: function() {
        return this.message + ' is complete again.'; 
      },
      toggleSecretMessage: function() {
        this.showSecretMessage = !this.showSecretMessage;
      },
      changeList: function() {
        var changedList = ['changedListItem1', 'changedListItem2', 'changesListItem3'];
        
        if(this.list.length === changedList.length && this.list.every(function(element, index) {
            return element === changedList[index]; 
        })){
          this.list = ['listItem1', 'listItem2', 'listItem3'];
        } else{
          this.list = changedList;
        }
      },
      deleteMiddleItem: function(){
        this.list.splice((this.list.length - 1) / 2, 1);
      },
      resetList: function(){
        this.list = ['listItem1', 'listItem2', 'listItem3'];
      },
       randomIndex: function () {
        return Math.floor(Math.random() * this.items.length)
      },
      add: function () {
        this.items.splice(this.randomIndex(), 0, this.nextNum++)
      },
      remove: function () {
        this.items.splice(this.randomIndex(), 1)
      },
    },
    
  }
</script>