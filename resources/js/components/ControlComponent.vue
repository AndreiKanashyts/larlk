<template>
    <div>
        <table>
            <col width="250" valign="top" />
            <col width="250" valign="top" />
            <tr>
                <td>
                    <div>
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
                </td>
                <td>
                    <audio
                        :src="phoneRecords"
                        :class="this.phoneRecords.length > 30 ? '' : 'd-none'"
                        autoplay="autoplay"
                        controls
                        controlsList="nodownload"
                        oncontextmenu="return false"
                        preload="metadata"
                    ></audio>
                </td>
            </tr>
        </table>

        <p class="center" v-if="!controls.length">Оценок в этом месяце нет!</p>

        <table class="table table-bordered" v-else>
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
                <tr v-for="control in paginatedControls" :key="control.IdCall">
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
                            data-bs-target="#popupControlModal"
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
                            :class="{ 'd-none': control.source === 1 }"
                            class="btn btn-outline-info"
                            type="button"
                            @click.prevent="
                                takeIdCall(control.IdCall, control.phoneClient),
                                    getPhoneRecords(control.IdCall)
                            "
                        >
                            Прослушать
                        </button>
                        <div
                            :class="{ 'd-none': control.source === 0 }"
                            class="btn-group"
                        >
                            <a
                                role="button"
                                class="btn btn-outline-info"
                                :href="`https://sp-oktell-stat1.patio-minsk.by/ChatCard/?id=&quot;${control.IdCall}&quot;`"
                                target="_blank"
                            >
                                Прочитать
                            </a>
                            <button
                                type="button"
                                class="btn btn-outline-info dropdown-toggle dropdown-toggle-split"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                <span class="visually-hidden"
                                    >Переключатель выпадающего списка</span
                                >
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a
                                        class="dropdown-item"
                                        :href="`https://sp-oktell-stat1.patio-minsk.by/ChatCard/?id=&quot;${control.IdCall}&quot;`"
                                        target="_blank"
                                    >
                                        Открыть в браузере
                                    </a>
                                </li>
                                <li>
                                    <a
                                        class="dropdown-item"
                                        :href="`https://app.jivo.ru/chat/archive/${control.phoneClient}_chat-490834-${control.phoneClient}`"
                                        target="_blank"
                                    >
                                        Открыть в Jivo
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li
                    class="page-item"
                    v-for="page in pages"
                    :key="page"
                    :class="{ 'page-item active': page === pageNumber }"
                    @click.prevent="pageClick(page)"
                >
                    <ul class="page-link">
                        {{
                            page
                        }}
                    </ul>
                </li>
            </ul>
        </nav>

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
                            :class="{
                                'table-danger':
                                    controlCheck.currentWeight === 0,
                            }"
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
                            :class="[phoneComment.classMarcup]"
                        >
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
            upMonth: 6,
            upYear: 2022,
            editIdCall: "",
            editPhone: "",
            controlChecks: [],
            phoneComments: [],
            phoneRecords: "",
            checkVisible: true,
            userPerPage: 10,
            pageNumber: 1,
        };
    },

    computed: {
        //Пагинация 1 этап
        pages() {
            return Math.ceil(this.controls.length / 10);
        },
        //Пагинация 2 этап
        paginatedControls() {
            let from = (this.pageNumber - 1) * this.userPerPage;
            let to = from + this.userPerPage;
            return this.controls.slice(from, to);
        },
    },

    mounted() {
        this.getControls();
    },

    methods: {
        //Данные из API контроль-качества
        getControls() {
            this.pageNumber = 1;
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
        //Получаем ID звонка
        takeIdCall(IdCall, phoneClient) {
            this.checkVisible = true;
            this.editIdCall = IdCall;
            this.editPhone = phoneClient;
        },

        isTakeIdCall(IdCall) {
            return this.editIdCall === IdCall;
        },

        //Данные из API чек-лист
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
        //Данные из API комментарии
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
                        const phoneComments = response.data;
                        this.phoneComments = phoneComments.map(
                            (phoneComment) => {
                                return {
                                    ...phoneComment,
                                    classMarcup:
                                        phoneComment.markup === 1
                                            ? "table-danger"
                                            : phoneComment.markup === 2
                                            ? "table-warning"
                                            : phoneComment.markup === 3
                                            ? "table-info"
                                            : "",
                                };
                            }
                        );
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }, 200);
        },
        //Получаем запись разговоров
        getPhoneRecords() {
            setTimeout(() => {
                this.phoneRecords = "";
                axios
                    .get(
                        "https://sp-oktell-stat1.patio-minsk.by/SSA_Integration_External_System/integration/PA_GetAudio.php",
                        {
                            params: {
                                IdCall: `${this.editIdCall}`,
                                login: `${this.authoperator.LoginOperator}`,
                            },
                        }
                    )
                    .then((response) => {
                        let link = "data:audio/ogg;base64," + response.data;
                        this.phoneRecords = link;
                    })
                    .catch((error) => {
                        console.log(error.response);
                    });
            }, 50);
        },
        //Включаем чек-лист в модалке
        swapTablesCheck() {
            this.checkVisible = true;
        },
        //Включаем комментарии в модалке
        swapTablesComment() {
            this.checkVisible = false;
        },
        //Выбор страницы
        pageClick(page) {
            this.pageNumber = page;
        },
    },
};
</script>
