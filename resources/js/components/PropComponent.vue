<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">{{ this.authoperator }}</div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["authoperator"],
    mounted() {
        this.update();
    },
    methods: {
        update: function () {
            console.log(this.authoperator);
        },
    },
};
</script>
