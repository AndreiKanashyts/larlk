import Vue from "vue";
import CommentComponent from "./components/CommentComponent";
import PropComponent from "./components/PropComponent";

require("./bootstrap");

const app = new Vue({
    el: "#kav",

    components: {
        CommentComponent,
        PropComponent,
    },
});
