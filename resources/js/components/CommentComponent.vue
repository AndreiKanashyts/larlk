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
                    @click.prevent="getComments"
                    type="submit"
                    value="Обновить"
                    class="btn btn-primary"
                />
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Вид замечания</th>
                    <th scope="col">Текст замечания</th>
                    <th scope="col">Стоимость</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="comment in paginatedComments" :key="comment.id">
                    <th>{{ comment.id }}</th>
                    <td>{{ comment.date.date | date }}</td>
                    <td>{{ comment.name }}</td>
                    <td>{{ comment.description }}</td>
                    <td>{{ comment.cost }}</td>
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
    </div>
</template>

<script>
export default {
    name: "CommentComponent",

    props: ["authoperator"],

    data() {
        return {
            comments: "",
            userPerPage: 5,
            pageNumber: 1,
            upMonth: 5,
            upYear: 2022,
        };
    },

    computed: {
        pages() {
            return Math.ceil(this.comments.length / 5);
        },
        paginatedComments() {
            let from = (this.pageNumber - 1) * this.userPerPage;
            let to = from + this.userPerPage;
            return this.comments.slice(from, to);
        },
    },

    mounted() {
        this.getComments();
    },

    methods: {
        getComments() {
            axios
                .get(
                    "https://sp-oktell-stat1.patio-minsk.by/SSA_Integration_External_System/integration/PA_GetMistake.php",
                    {
                        params: {
                            Month: `${this.upMonth}`,
                            Year: `${this.upYear}`,
                            IdOperator: `${this.authoperator.IdOperator}`,
                        },
                    }
                )
                .then((response) => {
                    this.comments = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
        pageClick(page) {
            this.pageNumber = page;
        },
    },
};
</script>
