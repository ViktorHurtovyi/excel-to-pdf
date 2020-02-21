<template v-if="hide===false">
    <div class="container">
        <h2>{{name.replace('Filter:', '')}}</h2>
        <select :name="name" id="" v-model="selected" v-on:change="selectOption">
            <option value=""></option>
        <option v-for="option in options" v-if="option !== ''" :value="option" :key="option.id">{{option}}</option>
        </select>
    </div>
</template>

<script>
    export default {
        props: ['options','name', 'changeResult'],
        data(){
            return {
                selected:'',
                hide:false
            }
        },
        methods: {
            selectOption(){
                this.hide = true;
                this.changeResult(this.name.replace('Filter:', ''), this.selected);
            }
        }
    }
</script>
