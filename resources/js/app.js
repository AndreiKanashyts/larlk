import Vue from "vue";
import dateFilter from "./filters/date.filter";
import CommentComponent from "./components/CommentComponent";
import ControlComponent from "./components/ControlComponent";
import CheckComponent from "./components/CheckComponent";

require("./bootstrap");

Vue.filter("date", dateFilter);

const app = new Vue({
    el: "#kav",

    components: {
        CommentComponent,
        ControlComponent,
        CheckComponent,
    },
});
