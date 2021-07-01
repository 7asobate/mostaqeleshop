<template>
    <nav class="navbar navbar-dark bg-main navbar-expand-lg">
            <a href="#" class="navbar-brand">متجر تجريبي</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                   
                </ul>

                <!-- <ul class="navbar-nav mr-auto">
                    <li>
                        <a :href="myprop" class="nav-link">Logout</a>
                    </li>
                </ul> -->
            </div>
            
    </nav>
    
</template>

<script>
    export default {
       props: ['myprop'],
       mounted() {
           console.log(this.myprop)
       }
    }

</script>


