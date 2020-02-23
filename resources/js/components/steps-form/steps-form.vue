<template>
    <div class="row">
        <div class="col-md-12" style="text-align: center">
            <h2>{{title.replace('Filter:', '')}}</h2>
            <select :name="title" v-model="selectValue" v-on:change="selectOption" class="col col-8">
                <option value="" selected></option>
                <option v-for="option in options" v-if="option !== ''" v-bind:value="option" :key="option.id">
                    {{option}}
                </option>
            </select>
        </div>
    </div>


</template>

<script>
    export default {
        props: ['options', 'title', 'changeResult'],
        data() {
            return {
                selectValue: ''
            }
        },
        methods: {
            selectOption() {
                this.changeResult(this.title.replace('Filter:', ''), this.selectValue);
                this.selectValue = '';
            }
        }
    }
</script>
