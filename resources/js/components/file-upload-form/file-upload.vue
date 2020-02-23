<template>
    <div class="row">
        <div class="col-md-6 offset-2">
            <input type="file" v-on:change="onFileChange" class="custom-file-input" id="input-file">
            <label class="custom-file-label" for="input-file">Choose file...</label>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-block" @click="uploadFile">Upload File</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['uploadFile', 'onFileChange'],
    }
</script>
