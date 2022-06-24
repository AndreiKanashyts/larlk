import Vue from "vue";
import dateFilter from "./filters/date.filter";
import FirstComponent from "./components/FirstComponent";
import CommentComponent from "./components/CommentComponent";
import ControlComponent from "./components/ControlComponent";
import JournalComponent from "./components/JournalComponent";
import MotivationComponent from "./components/MotivationComponent";
import HelperComponent from "./components/HelperComponent";
import PopupControl from "./components/popup/PopupControl";
import PopupDispute from "./components/popup/PopupDispute";
import PopupMotivation from "./components/popup/PopupMotivation";
import AlertStatus from "./components/popup/AlertStatus";

require("./bootstrap");

Vue.filter("date", dateFilter);

const app = new Vue({
    el: "#kav",

    components: {
        FirstComponent,
        CommentComponent,
        ControlComponent,
        JournalComponent,
        MotivationComponent,
        HelperComponent,
        PopupControl,
        PopupDispute,
        PopupMotivation,
        AlertStatus,
    },
});
