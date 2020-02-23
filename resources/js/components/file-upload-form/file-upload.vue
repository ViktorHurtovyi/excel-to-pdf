<template>
    <div class="col-md-8">
        <div class="card card-default">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <input type="file" v-on:change="onFileChange" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-success btn-block" @click="uploadFile">Upload File</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['uploadFile','onFileChange'],
    }
</script>
