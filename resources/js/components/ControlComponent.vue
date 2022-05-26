<template>
    <div>
        <div class="w-25">
            <div class="mb-3">
                <select
                    v-model="upMonth"
                    class="form-select"
                    aria-label="Default select example"
                >
                    <option value="1">январь</option>
                    <option value="2">февраль</option>
                    <option value="3">март</option>
                    <option value="4">апрель</option>
                    <option value="5">май</option>
                    <option value="6">июнь</option>
                    <option value="7">июль</option>
                    <option value="8">август</option>
                    <option value="9">сентябрь</option>
                    <option value="10">октябрь</option>
                    <option value="11">ноябрь</option>
                    <option value="12">декабрь</option>
                </select>
            </div>
            <div class="mb-3">
                <select
                    v-model="upYear"
                    class="form-select"
                    aria-label="Default select example"
                >
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </div>
            <div class="mb-3">
                <input
                    @click.prevent="getControls"
                    type="submit"
                    value="Обновить"
                    class="btn btn-primary"
                />
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">№</th>
                    <th scope="col">Дата и время звонка</th>
                    <th scope="col">ФИО сотрудника</th>
                    <th scope="col">Номер/ID</th>
                    <th scope="col">Оценка</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Чек-лист</th>
                    <th scope="col">Запись</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="control in controls" :key="control.IdCall">
                    <th>{{ control.number }}</th>
                    <td>{{ control.dateCall.date | date("datetime") }}</td>
                    <td>{{ control.fullName }}</td>
                    <td>{{ control.phoneClient }}</td>
                    <td>{{ control.score }}</td>
                    <td>{{ control.nameStatus }}</td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-outline-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#exampleModal"
                            @click.prevent="
                                takeIdCall(control.IdCall, control.phoneClient),
                                    getControlCheck(control.IdCall),
                                    getPhoneComments(control.IdCall)
                            "
                        >
                            Подробнее
                        </button>
                    </td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-outline-info"
                            data-bs-toggle="modal"
                            data-bs-target="#exampleModal"
                        >
                            Прослушать
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <PopupControl>
            <template v-slot:header>
                Информация по номеру: {{ editPhone }}
            </template>
            <template v-slot:body>
                <table class="table table-bordered" v-if="checkVisible">
                    <thead>
                        <tr>
                            <th scope="col">Вопрос</th>
                            <th scope="col">Ответ</th>
                            <th scope="col">Стоимость</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="controlCheck in controlChecks"
                            :key="controlCheck.number"
                        >
                            <th>{{ controlCheck.nameQuestion }}</th>
                            <td>{{ controlCheck.nameOption }}</td>
                            <td>{{ controlCheck.currentWeight }}</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered" v-else>
                    <thead>
                        <tr>
                            <th scope="col">Тип</th>
                            <th scope="col">Дата</th>
                            <th scope="col">Автор</th>
                            <th scope="col">Временная метка</th>
                            <th scope="col">Комментарий</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="phoneComment in phoneComments"
                            :key="phoneComment.id"
                        >
                            <td>{{ phoneComment.markup }}</td>
                            <td>
                                {{
                                    phoneComment.dateTime.date
                                        | date("datetime")
                                }}
                            </td>
                            <td>{{ phoneComment.fullName }}</td>
                            <td>{{ phoneComment.timeMark }}</td>
                            <td>{{ phoneComment.comment }}</td>
                        </tr>
                    </tbody>
                </table>
            </template>
            <template v-slot:footer>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="swapTablesCheck"
                >
                    Чек-лист
                </button>
                <button
                    type="button"
                    class="btn btn-success"
                    @click="swapTablesComment"
                >
                    Комментарии
                </button>
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Закрыть
                </button>
            </template>
        </PopupControl>
    </div>
</template>

<script>
import PopupControl from "./popup/PopupControl.vue";

export default {
    name: "ControlComponent",

    components: {
        PopupControl,
    },

    props: {
        authoperator: {
            type: Object,
            default: "",
        },
    },

    data() {
        return {
            controls: [],
            upMonth: 5,
            upYear: 2022,
            editIdCall: "",
            editPhone: "",
            controlChecks: [],
            phoneComments: [],
            checkVisible: true,
        };
    },

    mounted() {
        this.getControls();
    },

    methods: {
        getControls() {
            axios
                .get(
                    "https://sp-oktell-stat1.patio-minsk.by/SSA_Integration_External_System/integration/PA_GetAssessment.php",
                    {
                        params: {
                            Month: `${this.upMonth}`,
                            Year: `${this.upYear}`,
                            IdOperator: `${this.authoperator.IdOperator}`,
                        },
                    }
                )
                .then((response) => {
                    this.controls = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },

        takeIdCall(IdCall, phoneClient) {
            this.checkVisible = true;
            this.editIdCall = IdCall;
            this.editPhone = phoneClient;
        },

        isTake(IdCall) {
            return this.editIdCall === IdCall;
        },

        getControlCheck() {
            setTimeout(() => {
                axios
                    .get(
                        "https://sp-oktell-stat1.patio-minsk.by/SSA_Integration_External_System/integration/PA_GetAssessmentCheckList.php",
                        {
                            params: {
                                IdCall: `${this.editIdCall}`,
                            },
                        }
                    )
                    .then((response) => {
                        this.controlChecks = response.data;
                    })
                    .catch((error) => {
                        console.log(error.response);
                    });
            }, 50);
        },

        getPhoneComments() {
            setTimeout(() => {
                axios
                    .get(
                        "https://sp-oktell-stat1.patio-minsk.by/SSA_Integration_External_System/integration/PA_GetComment.php",
                        {
                            params: {
                                IdCall: `${this.editIdCall}`,
                            },
                        }
                    )
                    .then((response) => {
                        this.phoneComments = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 200);
        },

        swapTablesCheck() {
            this.checkVisible = true;
        },

        swapTablesComment() {
            this.checkVisible = false;
        },
    },
};
</script>
