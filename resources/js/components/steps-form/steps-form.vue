<template>
    <div class="container">
        <h2>{{name.replace('Filter:', '')}}</h2>
        <select :name="name" v-model="selectValue" v-on:change="selectOption">
            <option value="" selected></option>
        <option v-for="option in options" v-if="option !== ''"  v-bind:value="option" :key="option.id">{{option}}</option>
        </select>
    </div>
</template>

<script>
    export default {
        props: ['options','name', 'changeResult'],
        data() {
            return {
                selectValue: ''
            }
        },
        methods: {
            selectOption(){
                    this.changeResult(this.name.replace('Filter:', ''), this.selectValue);
                    this.selectValue = '';
            }
        }
    }
</script>
