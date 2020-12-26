<template>
    <div class="card my-4 pb-3">
        <h5 class="card-header">Languages</h5>
        <div class="card-body">
            <div >{{ todos }}</div>
            <ul class="list-unstyled mb-0">

                <li v-bind:todos="todos" :key="componentKey">
                    10
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'language-component',
        props: ['repository', 'organization', 'lang'],
        data() {
            return {
                languages: [],
                todos: [],
                componentKey: 0,
            }
        },
        created() {
            /* var url = `/api/repository/${this.organization}/${this.repository}/languages`;

             axios.get(url)
                 .then(resp => {
                     this.language = {"HTML": 8647, "Python": 4803}//resp.data.list;
                     console.log(this.languages);

                 })
                 .catch(err => console.log(err));
                 */
            axios.get('https://jsonplaceholder.typicode.com/todos?_limit=5')
                .then(res => {
                    this.todos = res.data
                    console.log(this.todos);
                    this.componentKey += 1;

                })
                .catch(err => console.log(err));
        },
        methods: {

        }
    }
</script>
