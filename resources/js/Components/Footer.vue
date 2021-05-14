<template>
    <footer class="main-footer">
        <strong>Copyright &copy; 2014-{{ currentDate.getFullYear() }} <inertia-link href="#">Admin-O-Matic</inertia-link>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0
        </div>
    </footer>
</template>

<script>
    export default {
        data() {
            return {
                currentDate: new Date,
            }
        }
    }
</script>