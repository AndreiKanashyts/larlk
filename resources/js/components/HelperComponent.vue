<template>
    <div>
        <iframe
            src="https://sp-oktell-stat1.patio-minsk.by/patioHelperUPA/"
        ></iframe>
    </div>
</template>

<script>
export default {
    name: "HelperComponent",

    props: {
        authoperator: {
            type: Object,
            default: "",
        },
    },
};
</script>

<style scoped>
body {
    margin: 0;
}
iframe {
    display: block;
    background: #000;
    border: none;
    height: 87vh;
    width: 100%;
}
</style>
