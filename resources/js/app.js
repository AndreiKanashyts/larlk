import Vue from "vue";
import dateFilter from "./filters/date.filter";
import CommentComponent from "./components/CommentComponent";
import ControlComponent from "./components/ControlComponent";
import PopupControl from "./components/popup/PopupControl";
import PopupComment from "./components/popup/PopupComment";

require("./bootstrap");

Vue.filter("date", dateFilter);

const app = new Vue({
    el: "#kav",

    components: {
        CommentComponent,
        ControlComponent,
        PopupControl,
        PopupComment,
    },
});
