<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8" v-if="form !== false">
                <div class="card card-default">
                    <div class="card-header">File Upload Component</div>
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
            <FormSteps v-for="(item, name) in data"
                       :options=item
                       :name=name
                       :key=Math.random()
                       :changeResult=changeResult
            ></FormSteps>
        </div>

    </div>
</template>

<script>
    import FormSteps from "./form-steps";

    export default {
        components: {FormSteps},
        data() {
            return {
                file: '',
                data: '',

                count: '',
                steps: {},
                form: true,
                endCount: 0
            }
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createFile(files[0]);
            },
            createFile(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.file = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            uploadFile() {
                axios.post('/api/file', {file: this.file}).then(response => {
                    this.data = response.data;
                    console.log(this.data);
                    this.form = false;
                    this.count = Object.keys(this.data).length;
                });
            },
            changeResult: function (key, value) {
                this.result.push({key, value});
                this.endCount++;
                if (Object.keys(this.result).length === this.count) {
                    axios.post('/api/get-pdf', {result:this.result}, {responseType: 'arraybuffer'})
                .then(response => {
                    let blob = new Blob([response.data], { type: 'application/pdf'})
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'test.pdf'
                    link.click()
                })
                }
            }
        }
    }
</script>
