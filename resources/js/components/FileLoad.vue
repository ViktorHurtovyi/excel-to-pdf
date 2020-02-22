<template>
    <div class="container">
        <div class="row justify-content-center">
            <FileUpload  v-if="form !== false"
                :onFileChange="onFileChange"
                :uploadFile="uploadFile"
            >
            </FileUpload>
            <h4 v-if="error===true">Something went wrong! Please try later!</h4>
            <FormSteps v-if="form !== true && end === false && error===false"
                       :options=optionValue
                       :name=nameValue
                       :changeResult=changeResult
            ></FormSteps>
            <button class="btn btn-success" v-if="formEnd === true" v-on:click="()=>savePdf()">download PDF</button>
        </div>
    </div>
</template>

<script>
    import FormSteps from "./form-steps";
    import FileUpload from "./file-upload";

    export default {
        components: {FormSteps, FileUpload},
        data() {
            return {
                file: '',
                data: '',
                result: [],
                count: '',
                form: true,
                formEnd: false,
                currentStep: 0,
                nameValue: '',
                optionValue: [],
                end: false,
                error: false,
            }
        },
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.file = e.target.files[0];
            },
            uploadFile() {
                const config = {
                    headers: {'content-type': 'multipart/form-data'}
                };
                let formData = new FormData();
                formData.append('file', this.file);

                axios.post('/api/file', formData, config).then(response => {
                    this.data = response.data;
                    this.form = false;
                    this.count = Object.keys(this.data).length;
                    this.nextStep();
                }).catch(err => {
                    this.error = true;
                    this.form = false;
                });
            },
            changeResult: function (key, value) {
                this.result.push({key, value});
                this.nextStep();
                if (Object.keys(this.result).length === this.count)
                    this.formEnd = true;
            },
            savePdf: function () {
                axios.post('/api/get-pdf', {result: this.result}, {responseType: 'arraybuffer'})
                    .then(response => {
                        let blob = new Blob([response.data], {type: 'application/pdf'});
                        let link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = 'test.pdf';
                        link.click()
                    })
            },
            nextStep: function () {
                if (this.count !== this.currentStep) {
                    this.optionValue = Object.values(this.data)[this.currentStep];
                    this.nameValue = Object.keys(this.data)[this.currentStep];
                    this.currentStep++;
                }else{
                    this.end = true;
                }
            }
        }
    }
</script>
