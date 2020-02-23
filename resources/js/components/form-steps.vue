<template>
    <div class="container">
        <h2>{{name.replace('Filter:', '')}}</h2>
        <select :name="name" v-on:change="selectOption">
            <option value="" id="option-default" selected></option>
        <option v-for="option in options" v-if="option !== ''" :value="option" :key="option.id">{{option}}</option>
        </select>
    </div>
</template>

<script>
    export default {
        props: ['options','name', 'changeResult'],
        methods: {
            selectOption(e){
                this.changeResult(this.name.replace('Filter:', ''), e.target.value);
                document.getElementById('option-default').selected = true;
            }
        }
    }
</script>
