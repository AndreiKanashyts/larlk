<template>
    <div>
        <div
            class="modal fade"
            id="exampleModal"
            tabindex="-1"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content" v-if="editIdCall">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Чек-лист:
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Вопрос</th>
                                    <th scope="col">Ответ</th>
                                    <th scope="col">Стоимость</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="check in checks" :key="check.id">
                                    <th>{{ check.nameQuestion }}</th>
                                    <td>{{ check.nameOption }}</td>
                                    <td>{{ check.currentWeight }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>
                        <!-- <button
                            type="button"
                            class="btn btn-primary"
                            @click.prevent="getChecks()"
                        >
                            Отобразить чек-лист
                        </button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CheckComponent",

    props: {
        editIdCall: {
            type: String,
            default() {
                return {};
            },
        },
    },

    data() {
        return {
            checks: [],
        };
    },

    maunted() {
        this.getChecks();
    },

    updated() {
        this.getChecks();
    },

    methods: {
        getChecks() {
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
                    this.checks = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
};
</script>
